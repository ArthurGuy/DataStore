<?php namespace Data\Triggers;

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
    }
} 