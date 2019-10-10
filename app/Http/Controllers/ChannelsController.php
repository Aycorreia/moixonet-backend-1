<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\ChannelDestroyRequest;
use App\Http\Requests\ChannelIndexRequest;
use App\Http\Requests\ChannelShowRequest;
use App\Http\Requests\ChannelStoreRequest;
use Tests\Feature\ChannelsControllerTest;

class ChannelsController extends Controller
{
    public static function testedBy()
    {
        return ChannelsControllerTest::class;
    }

    public function index(ChannelIndexRequest $request)
    {
        return Channel::all();
    }

    public function show(ChannelShowRequest $request, Channel $channel)
    {
        return $channel;
    }

    public function store(ChannelStoreRequest $request)
    {
//        $validatedData = $request->validate([
//            'name' => 'required|unique:channels|max:255|email|',
//            'body' => 'required',
//        ]);
        // Comprovar que m'han passat un name

        // THIN CONTROLLER
        // FAT CONTROLLER
        return Channel::create([
            'name' => $request->name
        ]);

    }

    // SENSE ROUTE MODEL BINDING
//    public function destroy(ChannelDestroyRequest $request)
//    {
//        Channel::destroy($request->id);
//    }

    // AMB ROUTE MODEL BINDING
    public function destroy(ChannelDestroyRequest $request, Channel $channel)
    {
        $channel->delete();
    }

    public function update()
    {
        // Comprovar que m'han passat un id
        // name

    }
}
