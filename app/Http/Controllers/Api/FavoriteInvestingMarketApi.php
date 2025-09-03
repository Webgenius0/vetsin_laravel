<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteInvestingMarket;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class FavoriteInvestingMarketApi extends Controller
{
    use ApiResponse;
    public function all()
    {
        $favorite = FavoriteInvestingMarket::where('status', 'active')->get();

        if ($favorite->isEmpty()) {
            return $this->error([], 'Favorite Investing markets not found', 200);
        }

        return $this->success($favorite, 'Favorite Investing markets fetch Successful!', 200);
    }
}

