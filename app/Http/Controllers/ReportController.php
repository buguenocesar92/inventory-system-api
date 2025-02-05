<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Obtener el historial completo de ventas con filtros opcionales.
     */
    public function salesHistory(Request $request): JsonResponse
    {
        $sales = $this->reportService->getSalesHistory(
            $request->query('page', 1), // Página actual
            $request->query('itemsPerPage', 10), // Número de elementos por página
            $request->query('sortBy', []), // Ordenamiento
            $request->query('search', '') // Búsqueda
        );

        return response()->json($sales);
    }

    /**
     * Obtener las ventas del día especificado.
     */
    public function dailySales(Request $request): JsonResponse
    {
        $date = $request->get('date', now()->toDateString());
        $sales = $this->reportService->getSalesHistory(
            $request->query('page', 1), // Página actual
            $request->query('itemsPerPage', 10), // Número de elementos por página
            $request->query('sortBy', []), // Ordenamiento
            $request->query('search', ''), // Búsqueda
            $date
        );

        return response()->json([
            'message' => 'Daily sales retrieved successfully.',
            'data' => $sales,
        ]);
    }

    /**
     * Obtener las ventas del mes especificado.
     */
    public function monthlySales(Request $request): JsonResponse
    {
        $month = $request->get('month', now()->format('Y-m'));
        $sales = $this->reportService->getSalesHistory(
            $request->query('page', 1), // Página actual
            $request->query('itemsPerPage', 10), // Número de elementos por página
            $request->query('sortBy', []), // Ordenamiento
            $request->query('search', ''), // Búsqueda
            $month
        );
        return response()->json([
            'message' => 'Monthly sales retrieved successfully.',
            'data' => $sales,
        ]);
    }

    /**
     * Obtener ventas por usuario.
     */
    public function salesByUser(Request $request, int $userId): JsonResponse
    {

        $sales = $this->reportService->getSalesHistory(
            $request->query('page', 1), // Página actual
            $request->query('itemsPerPage', 10), // Número de elementos por página
            $request->query('sortBy', []), // Ordenamiento
            $request->query('search', ''), // Búsqueda
            $userId
        );
        return response()->json([
            'message' => 'Sales history for user retrieved successfully.',
            'data' => $sales,
        ]);
    }
}
