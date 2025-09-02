<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProfileOption;
use App\Traits\ApiResponse;

class UserOptionsController extends Controller
{

    use ApiResponse;
    /**
     * GET /api/users/options
     * Returns grouped options used by the mobile app (labels + info tooltip text)
     */
    public function index()
    {
        // groups we want to return
        $groups = ['willing_to_relocate', 'ideal_connection', 'age_preferences'];

        $options = ProfileOption::whereIn('group', $groups)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();

        $grouped = [];
        foreach ($options as $opt) {
            $grouped[$opt->group][] = [
                // 'key'   => $opt->key,
                'label' => $opt->label,
                'info'  => $opt->info,
            ];
        }
        return $this->success($grouped, 'User options fetched successfully', 200);
    }
}
