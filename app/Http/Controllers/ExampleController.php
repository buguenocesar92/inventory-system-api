<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the project",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Development Server"
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
