<?php namespace Data\RealTime;

class PushoverMessage {

    function __construct()
    {

    }

    public function sendMessage($subject, $message)
    {
        \Pushover::push($subject, $message);
        \Pushover::send();
    }
} 