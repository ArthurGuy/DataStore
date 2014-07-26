<?php namespace Data\Triggers;

use Carbon\Carbon;
use Data\RealTime\PushoverMessage;

class NewDataTriggerHandler {

    private $pusher;

    public function __construct(\Data\RealTime\Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * When a new piece of data comes in sent it to the relivent services
     * @param $streamId
     * @param $data
     */
    public function handle($streamId, $data)
    {
        //Send the data out over pusher
        $this->pusher->trigger($streamId, ['data' => json_encode($data)]);

        $stream = \Stream::findOrFail($streamId);
        $triggers = \Trigger::where('streamId', $streamId)->get();

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
                    }
                }
            }

        }



        foreach ($matchedTriggers as $trigger)
        {
            //Only fire the actions if this trigger hasn't been fired yet - update to be an option we might want to fire veery time
            if ($trigger->trigger_matched == false)
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
                    $variable = \Variable::findOrFail($trigger->variable_name);
                    $variable->value = $trigger->variable_value;
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