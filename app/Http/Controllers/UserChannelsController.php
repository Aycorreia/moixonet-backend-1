<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChannelsIndex;

class UserChannelsController extends Controller
{
    public function index(UserChannelsIndex $request)
    {
//        return $request->user()->channels(); AVOID THIS!!!!!
        return $request->user()->channels;
//        return Auth::user()->channels;
    }
}
