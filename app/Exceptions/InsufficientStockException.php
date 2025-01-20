<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $message = 'Insufficient stock';

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message);
    }

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 409);
    }
}
