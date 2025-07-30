<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    use ApiResponse;

    /**
     * Add a user to favorites
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToFavorites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favorite_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $favoriteUserId = $request->favorite_user_id;

            // Check if user is trying to favorite themselves
            if ($user->id == $favoriteUserId) {
                return $this->error([], 'You cannot favorite yourself', 400);
            }

            // Check if already favorited
            if ($user->hasFavorited($favoriteUserId)) {
                return $this->error([], 'User is already in your favorites', 400);
            }

            // Create favorite
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'favorite_user_id' => $favoriteUserId,
            ]);

            // Load the favorited user data
            $favorite->load('favoriteUser');

            // Send push notification to the favorited user
            $favoritedUser = User::find($favoriteUserId);
            if ($favoritedUser) {
                NotificationService::sendFavoriteNotification($user, $favoritedUser);
            }

            return $this->success($favorite, 'User added to favorites successfully', 201);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Remove a user from favorites
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromFavorites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favorite_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $favoriteUserId = $request->favorite_user_id;

            // Find and delete the favorite
            $favorite = Favorite::where('user_id', $user->id)
                ->where('favorite_user_id', $favoriteUserId)
                ->first();

            if (!$favorite) {
                return $this->error([], 'User is not in your favorites', 404);
            }

            $favorite->delete();

            return $this->success([], 'User removed from favorites successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get all favorites of the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyFavorites()
    {
        try {
            $user = auth()->user();

            $favorites = Favorite::where('user_id', $user->id)
                ->with('favoriteUser')
                ->latest()
                ->get();

            // Transform the data to include user details
            $favoritesData = $favorites->map(function ($favorite) {
                $favoriteUser = $favorite->favoriteUser;
                return [
                    'id' => $favorite->id,
                    'favorite_user' => [
                        'id' => $favoriteUser->id,
                        'name' => $favoriteUser->name,
                        'email' => $favoriteUser->email,
                        'avatar' => $favoriteUser->avatar,
                        'location' => $favoriteUser->location,
                        'relationship_goal' => $favoriteUser->relationship_goal,
                        'age' => $favoriteUser->age,
                        'about_me' => $favoriteUser->about_me,
                        'identity' => $favoriteUser->identity,
                        'tags' => $favoriteUser->tags,
                    ],
                    'created_at' => $favorite->created_at,
                ];
            });

            return $this->success($favoritesData, 'Favorites retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get users who favorited the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavoritedByMe()
    {
        try {
            $user = auth()->user();

            $favoritedBy = Favorite::where('favorite_user_id', $user->id)
                ->with('user')
                ->latest()
                ->get();

            // Transform the data to include user details
            $favoritedByData = $favoritedBy->map(function ($favorite) {
                $favoritingUser = $favorite->user;
                return [
                    'id' => $favorite->id,
                    'user' => [
                        'id' => $favoritingUser->id,
                        'name' => $favoritingUser->name,
                        'email' => $favoritingUser->email,
                        'avatar' => $favoritingUser->avatar,
                        'location' => $favoritingUser->location,
                        'relationship_goal' => $favoritingUser->relationship_goal,
                        'age' => $favoritingUser->age,
                        'about_me' => $favoritingUser->about_me,
                        'tags' => $favoritingUser->tags,
                    ],
                    'created_at' => $favorite->created_at,
                ];
            });

            return $this->success($favoritedByData, 'Users who favorited you retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Check if a user is favorited by the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfFavorited(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $targetUserId = $request->user_id;

            $isFavorited = $user->hasFavorited($targetUserId);

            return $this->success([
                'is_favorited' => $isFavorited
            ], 'Favorite status checked successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get favorite count for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavoriteCount()
    {
        try {
            $user = auth()->user();

            $favoritesCount = $user->favorites()->count();
            $favoritedByCount = $user->favoritedBy()->count();

            return $this->success([
                'favorites_count' => $favoritesCount,
                'favorited_by_count' => $favoritedByCount,
            ], 'Favorite counts retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Clear all favorites for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearAllFavorites()
    {
        try {
            $user = auth()->user();

            $deletedCount = $user->favorites()->delete();

            return $this->success([
                'deleted_count' => $deletedCount
            ], 'All favorites cleared successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
