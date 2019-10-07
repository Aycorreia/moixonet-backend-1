<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;
use Tests\Feature\ChannelsControllerTest;

class ChannelsController extends Controller
{
    public static function testedBy()
    {
        return ChannelsControllerTest::class;
    }

    public function index()
    {
        return Channel::all();
    }

    public function store()
    {

    }
}
