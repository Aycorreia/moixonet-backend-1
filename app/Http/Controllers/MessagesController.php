<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageIndexRequest;
use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(MessageIndexRequest $request)
    {
        return Message::all();
    }
}
