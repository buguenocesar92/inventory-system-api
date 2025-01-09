<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="SaaS Starter Kit API",
 *     version="1.0.0",
 *     description="Documentación de la API para el SaaS Starter Kit."
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Servidor de desarrollo local"
 * )
 */
class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/example",
     *     summary="Get example data",
     *     tags={"Example"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="string", example="Hello World")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['data' => 'Hello World']);
    }
}
