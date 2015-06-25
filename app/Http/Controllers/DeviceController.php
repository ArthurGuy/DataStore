<?php

namespace App\Http\Controllers;

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

        $state = $request->get('state');

        $device->state = $state;
        $device->save();

        return $device;
	}



}
