<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function getSales(int $page, int $itemsPerPage, array $sortBy = [], string $search = '', array $filters = []): array
    {
        $query = Sale::query()->with(['user', 'product']); // Incluir relaciones necesarias

        // Aplicar filtros dinámicos
        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        if (!empty($filters['month'])) {
            $query->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $filters['month']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Aplicar búsqueda
        if (!empty($search)) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Aplicar ordenamiento dinámico
        if (!empty($sortBy)) {
            foreach ($sortBy as $sort) {
                $query->orderBy($sort['key'], $sort['order'] ?? 'asc');
            }
        } else {
            // Orden por defecto
            $query->orderByDesc('created_at')->orderByDesc('id');
        }

        // Paginación
        $total = $query->count();
        $items = $query->skip(($page - 1) * $itemsPerPage)
                       ->take($itemsPerPage)
                       ->get();

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
