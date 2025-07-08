<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * Get random profiles for discovery
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandomProfiles(Request $request)
    {
        try {
            $user = auth()->user();
            $perPage = $request->get('per_page', 10);
            $perPage = min($perPage, 100); // Limit to 50 per page

            // Get random profiles excluding the authenticated user
            $profiles = User::where('id', '!=', $user->id)
                ->inRandomOrder()
                ->paginate($perPage);

            // Transform the data to include relevant profile information
            $profilesData = $profiles->getCollection()->map(function ($profile) use ($user) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'avatar' => $profile->avatar,
                    'location' => $profile->location,
                    'identity' => $profile->identity,
                    'is_favorite' => $user->hasFavorited($profile->id),

                ];
            });

            return $this->success([
                'profiles' => $profilesData,
                'pagination' => [
                    'current_page' => $profiles->currentPage(),
                    'last_page' => $profiles->lastPage(),
                    'per_page' => $profiles->perPage(),
                    'total' => $profiles->total(),
                    'from' => $profiles->firstItem(),
                    'to' => $profiles->lastItem(),
                ]
            ], 'Random profiles retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get matching profiles based on user preferences
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatchingProfiles(Request $request)
    {
        try {
            $user = auth()->user();
            $perPage = min($request->get('per_page', 10), 100); // Limit to max 100
            $page = (int) $request->get('page', 1);

            // Build initial query for potential matches
            $query = User::where('id', '!=', $user->id);

            // Filter by age range
            if ($user->preferred_age_min && $user->preferred_age_max) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN ? AND ?', [
                    $user->preferred_age_min,
                    $user->preferred_age_max
                ]);
            }

            // Filter by relationship goal
            if ($user->relationship_goal) {
                $query->where('relationship_goal', $user->relationship_goal);
            }

            // Filter by location
            if ($user->location) {
                $query->where('location', 'LIKE', '%' . $user->location . '%');
            }

            // Filter by property type and identity
            if ($user->preferred_property_type && $user->identity) {
                $query->where(function ($q) use ($user) {
                    $q->where('preferred_property_type', $user->preferred_property_type)
                        ->orWhere('identity', $user->identity);
                });
            }

            // Get all potential matches
            $allProfiles = $query->orderBy('created_at', 'desc')->get();

            // Filter mutual favorites
            $mutualFavorites = $allProfiles->filter(function ($profile) use ($user) {
                return $user->hasFavorited($profile->id) && $profile->hasFavorited($user->id);
            });

            // Sort mutual favorites by match score
            $sortedProfiles = $mutualFavorites->map(function ($profile) use ($user) {
                $matchScore = $this->calculateMatchScore($user, $profile);
                $profile->match_score = $matchScore; // Add temp match score
                return $profile;
            })->sortByDesc('match_score')->values();

            // Get total after filtering
            $total = $sortedProfiles->count();

            // Manually paginate the sorted profiles
            $paginated = $sortedProfiles->forPage($page, $perPage)->values();

            // Format profiles for the response
            $profilesData = $paginated->map(function ($profile) use ($user) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'avatar' => $profile->avatar,
                    'location' => $profile->location,
                    'identity' => $profile->identity,
                    'match_score' => $profile->match_score,
                    'is_favorite' => $user->hasFavorited($profile->id),
                    'is_both_favorite' => true,

                ];
            });

            // Return response with accurate pagination info
            return $this->success([
                'profiles' => $profilesData,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'location' => $user->location,
                    'identity' => $user->identity,
                ],
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => (int) ceil($total / $perPage),
                    'per_page' => (int) $perPage,
                    'total' => $total,
                    'from' => $total > 0 ? (($page - 1) * $perPage) + 1 : null,
                    'to' => $total > 0 ? min($page * $perPage, $total) : null,
                ]
            ], 'Matching profiles retrieved successfully', 200);

        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Calculate match score between two users
     *
     * @param User $user1
     * @param User $user2
     * @return int
     */
    private function calculateMatchScore($user1, $user2)
    {
        $score = 0;

        // Age compatibility (both ways)
        if ($user1->age && $user2->preferred_age_min && $user2->preferred_age_max) {
            if ($user1->age >= $user2->preferred_age_min && $user1->age <= $user2->preferred_age_max) {
                $score += 20;
            }
        }

        if ($user2->age && $user1->preferred_age_min && $user1->preferred_age_max) {
            if ($user2->age >= $user1->preferred_age_min && $user2->age <= $user1->preferred_age_max) {
                $score += 20;
            }
        }

        // Relationship goal match
        if ($user1->relationship_goal && $user2->relationship_goal && $user1->relationship_goal === $user2->relationship_goal) {
            $score += 25;
        }

        // Location match
        if ($user1->location && $user2->location && $user1->location === $user2->location) {
            $score += 15;
        }

        // Real estate preferences match
        if ($user1->preferred_property_type && $user2->preferred_property_type && $user1->preferred_property_type === $user2->preferred_property_type) {
            $score += 10;
        }

        if ($user1->identity && $user2->identity && $user1->identity === $user2->identity) {
            $score += 10;
        }

        // Budget compatibility
        if ($user1->budget_min && $user1->budget_max && $user2->budget_min && $user2->budget_max) {
            $overlap = min($user1->budget_max, $user2->budget_max) - max($user1->budget_min, $user2->budget_min);
            if ($overlap > 0) {
                $score += 20;
            }
        }

        return $score;
    }

    /**
     * Get a single profile's details by user id
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfileDetails(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $profile = User::findOrFail($id);

            $data = [
                'id' => $profile->id,
                'name' => $profile->name,
                'avatar' => $profile->avatar,
                'location' => $profile->location,
                'identity' => $profile->identity,
                'age' => $profile->age,
                'about_me' => $profile->about_me,
                'relationship_goal' => $profile->relationship_goal,
                'date_of_birth' => $profile->date_of_birth,
                'preferred_age_min' => $profile->preferred_age_min,
                'preferred_age_max' => $profile->preferred_age_max,
                'preferred_property_type' => $profile->preferred_property_type,
                'budget_min' => $profile->budget_min,
                'budget_max' => $profile->budget_max,
                'preferred_location' => $profile->preferred_location,
                'perfect_weekend' => $profile->perfect_weekend,
                'cant_live_without' => $profile->cant_live_without,
                'quirky_fact' => $profile->quirky_fact,
                'tags' => $profile->tags,
                'is_profile_complete' => $profile->is_profile_complete,
                'is_real_estate_complete' => $profile->is_real_estate_complete,
                'is_favorite' => $user ? $user->hasFavorited($profile->id) : false,
                'is_favorited_by' => $user ? $profile->hasFavorited($user->id) : false,
            ];

            return $this->success($data, 'Profile details retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 404);
        }
    }
}
