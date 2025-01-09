<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints relacionados con la autenticación de usuarios."
 * )
 */
class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Iniciar sesión con credenciales.
     *
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="Iniciar sesión",
     *     description="Obtén un token JWT con credenciales válidas.",
     *     operationId="loginUser",
     *     @OA\Parameter(
     *         name="Host",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Host del tenant al que pertenece la solicitud."
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", description="Correo electrónico del usuario."),
     *             @OA\Property(property="password", type="string", format="password", description="Contraseña del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", description="Token JWT generado."),
     *             @OA\Property(property="token_type", type="string", description="Tipo de token."),
     *             @OA\Property(property="expires_in", type="integer", description="Tiempo de expiración del token en segundos.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", description="Mensaje de error.")
     *         )
     *     )
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->authService->respondWithToken($token);
    }

    /**
     * Registrar un usuario.
     *
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication"},
     *     summary="Registrar un usuario",
     *     description="Crea un nuevo usuario. Requiere autenticación JWT.",
     *     operationId="registerUser",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Host",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Host del tenant al que pertenece la solicitud."
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", description="Nombre del usuario."),
     *             @OA\Property(property="email", type="string", format="email", description="Correo electrónico del usuario."),
     *             @OA\Property(property="password", type="string", format="password", description="Contraseña del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="ID del usuario registrado."),
     *             @OA\Property(property="name", type="string", description="Nombre del usuario."),
     *             @OA\Property(property="email", type="string", description="Correo electrónico del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido o expirado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", description="Mensaje de error.")
     *         )
     *     )
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return response()->json($user, 201);
    }

    /**
     * Obtener el usuario autenticado.
     *
     * @OA\Post(
     *     path="/auth/me",
     *     tags={"Authentication"},
     *     summary="Obtener el usuario autenticado",
     *     description="Devuelve la información del usuario autenticado.",
     *     operationId="getAuthenticatedUser",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Host",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Host del tenant al que pertenece la solicitud."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario autenticado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="ID del usuario."),
     *             @OA\Property(property="name", type="string", description="Nombre del usuario."),
     *             @OA\Property(property="email", type="string", description="Correo electrónico del usuario.")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Cerrar sesión.
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Authentication"},
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario actual.",
     *     operationId="logoutUser",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Host",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Host del tenant al que pertenece la solicitud."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Mensaje de éxito.")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refrescar un token.
     *
     * @OA\Post(
     *     path="/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refrescar token",
     *     description="Obtén un nuevo token JWT.",
     *     operationId="refreshToken",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Host",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Host del tenant al que pertenece la solicitud."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token refrescado con éxito.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", description="Nuevo token JWT."),
     *             @OA\Property(property="token_type", type="string", description="Tipo de token."),
     *             @OA\Property(property="expires_in", type="integer", description="Tiempo de expiración del token.")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->authService->respondWithToken(auth()->refresh());
    }
}
