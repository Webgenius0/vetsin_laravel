<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IdealConnection;
use App\Models\WillingToRelocate;
use App\Traits\ApiResponse;

class UserOptionsController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/users/ideal-connections
     */
    public function idealConnections()
    {
        $options = IdealConnection::where('status', 'active')
            ->orderBy('id')
            ->get();

        return $this->success($options, 'Ideal connections fetched successfully', 200);
    }

    /**
     * GET /api/users/willing-to-relocate
     */
    public function willingToRelocate()
    {
        $options = WillingToRelocate::where('status', 'active')
            ->orderBy('id')
            ->get();

        return $this->success($options, 'Willing to relocate options fetched successfully', 200);
    }
}
