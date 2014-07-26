<?php namespace Data\RealTime;

class Pusher {

    private static $pusherChannelName;

    public function __construct()
    {
        (\App::environment() != 'production')
            ? self::$pusherChannelName = \App::environment().'-stream'
            : self::$pusherChannelName = 'stream';
    }

    public function trigger($stream, $data)
    {

        \Pusherer::trigger(self::$pusherChannelName, $stream, $data);
    }

    public static function getChannelName()
    {
        return self::$pusherChannelName;
    }
} 