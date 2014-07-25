<?php

Event::listen('stream.data.store', function($eventData) {
    (\App::environment() != 'production')
        ? $pusherChannelName = \App::environment().'-stream'
        : $pusherChannelName = 'stream';
    Pusherer::trigger($pusherChannelName, $eventData['streamId'], [ 'data' => json_encode($eventData['data']) ]);
});