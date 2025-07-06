<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileCompletionController extends Controller
{
    use ApiResponse;

    /**
     * Complete basic dating profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeBasicProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'required|date|before:today',
            'location' => 'required|string|max:255',
            'relationship_goal' => 'required|in:casual,serious,friendship,marriage',
            'preferred_age_min' => 'required|integer|min:18|max:100',
            'preferred_age_max' => 'required|integer|min:18|max:100|gte:preferred_age_min',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            
            $user->update([
                'date_of_birth' => $request->date_of_birth,
                'location' => $request->location,
                'relationship_goal' => $request->relationship_goal,
                'preferred_age_min' => $request->preferred_age_min,
                'preferred_age_max' => $request->preferred_age_max,
            ]);

            return $this->success($user->fresh(), 'Basic profile completed successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Complete real estate preferences
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeRealEstatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'preferred_property_type' => 'required|in:apartment,house,condo,townhouse,studio,any',
            'identity' => 'required|in:buyer,seller,renter,investor',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0|gte:budget_min',
            'preferred_location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            
            $user->update([
                'preferred_property_type' => $request->preferred_property_type,
                'identity' => $request->identity,
                'budget_min' => $request->budget_min,
                'budget_max' => $request->budget_max,
                'preferred_location' => $request->preferred_location,
            ]);

            return $this->success($user->fresh(), 'Real estate preferences completed successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Complete personal questions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completePersonalQuestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perfect_weekend' => 'required|string|max:1000',
            'cant_live_without' => 'required|string|max:1000',
            'quirky_fact' => 'required|string|max:1000',
            'about_me' => 'required|string|max:2000',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            
            $user->update([
                'perfect_weekend' => $request->perfect_weekend,
                'cant_live_without' => $request->cant_live_without,
                'quirky_fact' => $request->quirky_fact,
                'about_me' => $request->about_me,
                'tags' => $request->tags ?? [],
            ]);

            return $this->success($user->fresh(), 'Personal questions completed successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get profile completion status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfileStatus()
    {
        try {
            $user = auth()->user();
            
            $status = [
                'basic_profile_complete' => $user->is_profile_complete,
                'real_estate_complete' => $user->is_real_estate_complete,
                'personal_questions_complete' => !empty($user->perfect_weekend) && 
                                               !empty($user->cant_live_without) && 
                                               !empty($user->quirky_fact) && 
                                               !empty($user->about_me),
                'overall_complete' => $user->is_profile_complete && 
                                    $user->is_real_estate_complete && 
                                    !empty($user->perfect_weekend) && 
                                    !empty($user->cant_live_without) && 
                                    !empty($user->quirky_fact) && 
                                    !empty($user->about_me),
            ];

            return $this->success($status, 'Profile status retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Update tags
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTags(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tags' => 'required|array|min:1',
            'tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $user->update(['tags' => $request->tags]);

            return $this->success($user->fresh(), 'Tags updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
} 