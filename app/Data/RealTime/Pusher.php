<?php namespace Data\RealTime;

class Pusher {

    public function __construct()
    {
        (\App::environment() != 'production')
            ? $this->pusherChannelName = \App::environment().'-stream'
            : $this->pusherChannelName = 'stream';
    }

    public function trigger($stream, $data)
    {

        \Pusherer::trigger($this->pusherChannelName, $stream, $data);
    }

} 