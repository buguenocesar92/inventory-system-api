<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    /**
     * Obtener ventas con filtros dinámicos (fecha, mes, usuario).
     */
    public function getSales(array $filters = [])
    {
        $query = Sale::query()->with(['user', 'product']);

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        if (!empty($filters['month'])) {
            $query->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $filters['month']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }
}
