<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NombreDelEvento;

class RealTimeController extends Controller
{
    public function sendMessage()
    {
        event(new NombreDelEvento('Hola desde Laravel', 1));

        return response()->json(['status' => 'Evento enviado']);
    }

}
