<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = ['user_id', 'building_id', 'title', 'message', 'icon', 'tag'];

    public function createNotification($userId, $buildingId, $title, $message, $icon = null, $tag = null)
    {
        return self::create([
            'user_id'     => $userId,
            'building_id' => $buildingId,
            'title'       => $title,
            'message'     => $message,
            'icon'        => $icon,
            'tag'         => $tag,
        ]);
    }

    public function broadcast()
    {
        $clientNotification = new ClientNotification();

        $notificationEndpoints = $clientNotification->where('user_id', $this->user_id)->where('building_id', $this->building_id)->get();
        foreach ($notificationEndpoints as $endpoint) {
            $endpoint->sendNotification();
        }
        $this->sent = true;
        $this->save();
    }
}
