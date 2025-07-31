<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationSettingsController extends Controller
{
    use ApiResponse;


    /**
     * Toggle notification settings for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notifications_enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $oldStatus = $user->notifications_enabled;
            $newStatus = $request->notifications_enabled;

            $user->notifications_enabled = $newStatus;
            $user->save();

            $statusText = $newStatus ? 'enabled' : 'disabled';
            $message = "Notifications {$statusText} successfully";

            return $this->success([
                'notifications_enabled' => $user->notifications_enabled,
                'previous_status' => $oldStatus,
                'status_changed' => $oldStatus !== $newStatus,
                'updated_at' => $user->updated_at,
            ], $message, 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
