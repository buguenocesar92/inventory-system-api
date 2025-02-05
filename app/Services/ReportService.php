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

    public function getSalesHistory(array $filters)
    {
        return $this->reportRepository->getSales($filters);
    }

    public function getDailySalesReport(string $date)
    {
        return $this->reportRepository->getSales(['date' => $date]);
    }

    public function getMonthlySalesReport(string $month)
    {
        return $this->reportRepository->getSales(['month' => $month]);
    }

    public function getSalesByUser(int $userId)
    {
        return $this->reportRepository->getSales(['user_id' => $userId]);
    }
}
