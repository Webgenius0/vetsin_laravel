<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HashTag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HashTagsController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function HashTags()
    {
        $tags = HashTag::where('status', 'active')->get();

        if ($tags->isEmpty()) {
            return $this->error([], 'No hashtags found', 200);
        }

        return $this->success($tags, 'Hashtags retrieved successfully', 200);
    }
}
