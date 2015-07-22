<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{
    protected $table = 'client_notification';

    protected $fillable = ['user_id', 'building_id', 'endpoint_url', 'subscription_id'];

    public function sendNotification()
    {
        $client = new \GuzzleHttp\Client();
        $client->post($this->endpoint_url, [
            'headers' => [
                'Authorization' => 'key=' . env('GOOGLE_CLOUD_NOTIFICATION_API_KEY')
            ],
            'json' => ['registration_ids' => [$this->subscription_id]]
        ]);
    }
}
