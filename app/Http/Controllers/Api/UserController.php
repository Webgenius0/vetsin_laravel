<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProfileOption;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Fetch Login User Data
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userData()
    {

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 404);
        }

        // Add computed attributes to the response
        $userData = $user->toArray();
        $userData['age'] = $user->age;
        $userData['is_profile_complete'] = $user->is_profile_complete;
        $userData['is_real_estate_complete'] = $user->is_real_estate_complete;

        return $this->success($userData, 'User data fetched successfully', 200);
    }

    /**
     * Update User Information
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userUpdate(Request $request)
    {

        // fetch allowed keys from DB
        $idealValues = ProfileOption::where('group', 'ideal_connection')->pluck('label')->toArray();
        $relocateValues = ProfileOption::where('group', 'willing_to_relocate')->pluck('label')->toArray();
     

        $validator = Validator::make($request->all(), [
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'name'    => 'required|string|max:255',

            // accept MM/DD/YYYY input
            'date_of_birth' => 'nullable|date_format:m/d/Y|before:today',

            'location' => 'nullable|string|max:255',

            // backward compat: relationship_goal (old) - keep allowed list
            'relationship_goal' => 'nullable|in:casual,serious,friendship,marriage',

            'ideal_connection'    => ['nullable', Rule::in($idealValues)],
            'willing_to_relocate' => ['nullable', Rule::in($relocateValues)],

            'preferred_age_min' => 'nullable|integer|min:18|max:120',
            'preferred_age_max' => 'nullable|integer|min:18|max:120',

            'preferred_property_type' => 'nullable|in:apartment,house,condo,townhouse,studio,any',
            'identity' => 'nullable|in:buyer,seller,renter,investor',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'preferred_location' => 'nullable|string|max:255',
            'perfect_weekend' => 'nullable|string|max:1000',
            'cant_live_without' => 'nullable|string|max:1000',
            'quirky_fact' => 'nullable|string|max:1000',
            'about_me' => 'nullable|string|max:2000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);


        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Find the user by ID
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    $previousImagePath = public_path($user->avatar);
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }
                $image     = $request->file('avatar');
                $imageName = uploadImage($image, 'User/Avatar');
            } else {
                $imageName = $user->avatar;
            }

            $user->name    = $request->name;
            $user->avatar  = $imageName;

            // Update dating profile fields if present
            $fields = [
                'date_of_birth',
                'location',
                'relationship_goal',   // legacy (kept)
                'ideal_connection',    // new
                'willing_to_relocate', // new
                'preferred_age_min',
                'preferred_age_max',
                'preferred_property_type',
                'identity',
                'budget_min',
                'budget_max',
                'preferred_location',
                'perfect_weekend',
                'cant_live_without',
                'quirky_fact',
                'about_me',
                'tags',
            ];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->input($field);
                }
            }

            $user->save();

            return $this->success($user, 'User updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Change Login User Password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function passwordChange(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // If user is not found, return an error response
        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        // Validate request inputs
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error([], "Current password is incorrect", 400);
        }

        // Update the password securely
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success($user->fresh(), "Password changed successfully", 200);
    }

    /**
     * Logout the authenticated user's account
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function logoutUser()
    {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success([], 'Successfully logged out', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Delete the authenticated user's account
     *
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function deleteUser()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            // Delete the user's avatar if it exists
            if ($user->avatar) {
                $previousImagePath = public_path($user->avatar);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            // Delete the user
            $user->delete();

            return $this->success([], 'User deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
