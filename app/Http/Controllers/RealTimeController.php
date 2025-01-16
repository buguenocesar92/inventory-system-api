<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NombreDelEvento;

class RealTimeController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userId = $request->user()->id;
        $message = $request->input('message', 'Hola desde Laravel');

        event(new NombreDelEvento($message, $userId));

        return response()->json(['status' => 'Evento enviado']);
    }

}
