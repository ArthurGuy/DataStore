<?php

namespace App\Http\Controllers;

use App\Events\DeviceStateChanged;
use App\Models\Device;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use View;
use Input;
use Auth;
use Redirect;

class DeviceController extends BaseController {

    public function __construct()
    {

    }


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        $device = Device::findOrFail($id);

        $state = $request->get('state', NULL);
        $on = $request->get('on', NULL);

        if ($state !== null) {
            $device->state = $state;
        }
        if ($on !== null) {
            $device->on = $on;
        }
        $device->save();

        //We need to fire an event to get the device to update now rather than in 60 seconds

        event(new DeviceStateChanged($device));

        return $device;
	}



}
