<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\ActiveChannelsDestroyRequest;
use App\Http\Requests\ActiveChannelsStoreRequest;
use Illuminate\Http\Request;

class ActiveChannelsController extends Controller
{
    public function store(ActiveChannelsStoreRequest $request, Channel $channel)
    {
        $channel->active = true;
        $channel->save();
    }

    public function destroy(ActiveChannelsDestroyRequest $request, Channel $channel)
    {
        $channel->active = false;
        $channel->save();
    }
}
