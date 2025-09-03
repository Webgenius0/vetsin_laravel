<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FunPrompt;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class FunPromptApiController extends Controller
{
    use ApiResponse;
    public function all($type)
    {
        $prompts = FunPrompt::where('type', $type)
            ->where('status', 'active')
            ->get();

        if ($prompts->isEmpty()) {
            return $this->error([], 'Fun Prompts not found', 200);
        }

        return $this->success($prompts, 'Fun Prompts fetched successfully!', 200);
    }
}
