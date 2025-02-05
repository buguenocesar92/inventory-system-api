<?php

namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    private ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getSalesHistory(int $page, int $itemsPerPage, array $sortBy, string $search)
    {
        return $this->reportRepository->getSales($page, $itemsPerPage, $sortBy, $search);
    }

    public function getDailySalesReport(int $page, int $itemsPerPage, array $sortBy, string $search, string $date)
    {
        return $this->reportRepository->getSales($page, $itemsPerPage, $sortBy, $search, ['date' => $date]);
    }

    public function getMonthlySalesReport($page, $itemsPerPage, $sortBy, $search, string $month)
    {
        return $this->reportRepository->getSales($page, $itemsPerPage, $sortBy, $search, ['month' => $month]);
    }

    public function getSalesByUser($page, $itemsPerPage, $sortBy, $search, int $userId)
    {
        return $this->reportRepository->getSales($page, $itemsPerPage, $sortBy, $search, ['user_id' => $userId]);
    }
}
