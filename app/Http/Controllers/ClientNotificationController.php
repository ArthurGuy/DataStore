<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientNotification as NotificationStore;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Response;

class ClientNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $buildingId = $request->json('building_id');
        $endpoint = $request->json('endpoint');
        $endpointParts = explode('/', $endpoint);
        $subscriptionId = array_pop($endpointParts);
        $endpointUrl = implode('/', $endpointParts);

        $notification = NotificationStore::where('user_id', Auth::id())->where('building_id', $buildingId)->where('subscription_id', $subscriptionId)->first();
        if ($notification) {
            $notification->touch();
        } else {
            $notification = NotificationStore::create([
                'user_id'         => Auth::id(),
                'building_id'     => $request->json('building_id'),
                'endpoint_url'    => $endpointUrl,
                'subscription_id' => $subscriptionId,
            ]);
        }

        return Response::make($notification, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $buildingId = $request->json('building_id');
        $endpoint = $request->json('endpoint');
        $endpointParts = explode('/', $endpoint);
        $subscriptionId = array_pop($endpointParts);
        $endpointUrl = implode('/', $endpointParts);

        NotificationStore::where('user_id', Auth::id())->where('building_id', $buildingId)->where('subscription_id', $subscriptionId)->delete();
    }
}
