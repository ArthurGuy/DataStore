<?php namespace App\Data\Triggers;

use App\Events\DataReceived;
use App\Events\LocationHomeStateChanged;
use App\Models\Location;
use App\Models\Trigger;
use App\Models\Variable;
use Carbon\Carbon;

class NewDataTriggerHandler {


    /**
     * When a new piece of data comes in sent it to the relivent services
     * @param $streamId
     * @param $data
     */
    public function handle($streamId, $data)
    {
        event(new DataReceived($streamId, $data));

        //$stream = \Stream::findOrFail($streamId);
        $triggers = Trigger::where('streamId', $streamId)->get();

        $matchedTriggers = [];
        $unmatchedTriggers = [];
        foreach ($triggers as $i=>$trigger)
        {
            //If a filter is set remove non matching data
            if (!empty($trigger->filter_field) && !isset($data[$trigger->filter_field]))
            {
                unset($triggers[$i]);
                continue;
            }
            elseif (!empty($trigger->filter_field) && ($data[$trigger->filter_field] != $trigger->filter_value))
            {
                unset($triggers[$i]);
                continue;
            }
            if ($trigger->check_field)
            {
                //If the trigger needs a field and that feel isn't there skip the trigger
                if (!isset($data[$trigger->check_field]))
                {
                    //Data packet isn't relevant/valid
                    unset($triggers[$i]);
                    continue;
                }
                else
                {
                    switch($trigger->check_operator)
                    {
                        case '=':
                            if (floatval($data[$trigger->check_field]) == floatval($trigger->check_value))
                            {
                                $matchedTriggers[] = $trigger;
                            }
                            else
                            {
                                $unmatchedTriggers[] = $trigger;
                            }
                            break;
                        case '>':
                            if (floatval($data[$trigger->check_field]) > floatval($trigger->check_value))
                            {
                                $matchedTriggers[] = $trigger;
                            }
                            else
                            {
                                $unmatchedTriggers[] = $trigger;
                            }
                            break;
                        case '<':
                            if (floatval($data[$trigger->check_field]) < floatval($trigger->check_value))
                            {
                                $matchedTriggers[] = $trigger;
                            }
                            else
                            {
                                $unmatchedTriggers[] = $trigger;
                            }
                            break;
                        case '!=':
                            if (floatval($data[$trigger->check_field]) != floatval($trigger->check_value))
                            {
                                $matchedTriggers[] = $trigger;
                            }
                            else
                            {
                                $unmatchedTriggers[] = $trigger;
                            }
                            break;
                        case '-':
                            $matchedTriggers[] = $trigger;
                            break;
                    }
                }
            }

        }



        foreach ($matchedTriggers as $trigger)
        {
            $run = false;
            //Only fire the actions if this trigger hasn't been fired yet - update to be an option we might want to fire every time
            if (($trigger->push_when == 'once') && ($trigger->trigger_matched == false))
            {
                $run = true;
            }
            else if (($trigger->push_when == 'daily') && (Carbon::parse($trigger->last_trigger)->lt(Carbon::now()->subDay())))
            {
                $run = true;
            }
            else if (($trigger->push_when == 'weekly') && (Carbon::parse($trigger->last_trigger)->lt(Carbon::now()->subWeek())))
            {
                $run = true;
            }
            else if (($trigger->push_when == 'hourly') && (Carbon::parse($trigger->last_trigger)->lt(Carbon::now()->subHour())))
            {
                $run = true;
            }
            else if (($trigger->push_when == '5minute') && (Carbon::parse($trigger->last_trigger)->lt(Carbon::now()->subMinutes(5))))
            {
                $run = true;
            }
            else if ($trigger->push_when == 'all')
            {
                $run = true;
            }
            if ($run)
            {
                $trigger->trigger_matched = true;
                $trigger->last_trigger = Carbon::now();
                $trigger->save();

                if ($trigger->action == 'push_message')
                {
                    $pushover = new PushoverMessage();
                    $pushover->sendMessage($trigger->push_subject, $trigger->push_message);
                }
                elseif ($trigger->action == 'variable')
                {
                    $variable = Variable::findOrFail($trigger->variable_name);
                    $variable->value = $trigger->variable_value;
                    $variable->save();
                }
                elseif ($trigger->action == 'nest')
                {
                    // create a new cURL resource
                    $ch = curl_init();

                    $postData = json_encode([$trigger->nest_property => $trigger->nest_value]);

                    curl_setopt($ch, CURLOPT_URL, "https://developer-api.nest.com/structures/" . $trigger->nest_structure . "?auth=".$trigger->nest_api_key);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($postData) ]);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                    $response = curl_exec($ch);
                    //\Log::debug($response);

                    if (curl_errno($ch)) {
                        //\Log::debug("NEST Update Error: ".json_encode(curl_getinfo($ch)));
                    }

                    curl_close($ch);
                }
                elseif ($trigger->action == 'location')
                {
                    /** @var Location $location */
                    $location = Location::findOrFail($trigger->location_id);

                    $fireEvent = false;

                    if (isset($data['temp']) && !empty($data['temp'])) {
                        $location->temperature = $data['temp'];
                    }

                    if (isset($data['humidity']) && !empty($data['humidity'])) {
                        $location->humidity = $data['humidity'];
                    }

                    if (isset($data['at_home'])) {
                        $location->home = $data['at_home'];
                    }

                    if (isset($data['movement'])) {
                        if ($data['movement'] == 1) {
                            $location->home = true;
                            $location->last_movement = Carbon::now();
                        } else {
                            //if no movement for 30 minutes or no existing last movement record set to away
                            if (!$location->last_movement || $location->last_movement->lt(Carbon::now()->subMinutes(30))) {
                                $location->home = false;
                            }
                        }
                        //If the state has changed broadcast an event so the parent location can check itself
                        if ($location->isDirty('name')) {
                            $fireEvent = true;
                        }
                    }

                    $location->last_updated = Carbon::now();

                    $location->save();

                    if ($fireEvent) {
                        event(new LocationHomeStateChanged($location));
                    }
                }
            }
        }

        foreach ($unmatchedTriggers as $trigger)
        {
            //The trigger has gone from matching to not matching
            if ($trigger->trigger_matched == true)
            {
                $trigger->trigger_matched = false;
                $trigger->save();
            }
        }
    }
} 