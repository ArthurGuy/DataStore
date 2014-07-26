<?php namespace Data\Triggers;

use Data\RealTime\PushoverMessage;

class NewDataHandler {

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


        foreach ($triggers as $i=>$trigger)
        {
            if ($trigger->check_field)
            {
                //If the trigger needs a field and that feel isn't there skip the trigger
                if (!isset($data[$trigger->check_field]))
                {
                    unset($triggers[$i]);
                    continue;
                }
                else
                {
                    switch($trigger->check_operator)
                    {
                        case '=':
                            if ($data[$trigger->check_field] != $trigger->check_value)
                            {
                                unset($triggers[$i]);
                                continue;
                            }
                            break;
                        case '>':
                            if ($data[$trigger->check_field] < $trigger->check_value)
                            {
                                unset($triggers[$i]);
                                continue;
                            }
                            break;
                        case '<':
                            if ($data[$trigger->check_field] > $trigger->check_value)
                            {
                                unset($triggers[$i]);
                                continue;
                            }
                            break;
                        case '!=':
                            if ($data[$trigger->check_field] == $trigger->check_value)
                            {
                                unset($triggers[$i]);
                                continue;
                            }
                            break;
                    }
                }
            }
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
        }



        //$triggers should now only contain triggers we need to act upon
        foreach ($triggers as $trigger)
        {
            if ($trigger->action == 'push_message')
            {
                $pushover = new PushoverMessage();
                $pushover->sendMessage($trigger->push_subject, $trigger->push_message);
            }
        }
    }
} 