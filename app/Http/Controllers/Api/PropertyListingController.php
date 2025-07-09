<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyListing;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyListingController extends Controller
{
    use ApiResponse;

    /**
     * List all property listings (paginated)
     */
    public function index(Request $request)
    {
        $perPage = min($request->get('per_page', 10), 50);
        $listings = PropertyListing::with('user')->latest()->paginate($perPage);
        return $this->success($listings, 'Property listings fetched successfully', 200);
    }

    /**
     * Show a single property listing
     */
    public function show($id)
    {
        $listing = PropertyListing::with('user')->find($id);
        if (!$listing) {
            return $this->error([], 'Property listing not found', 404);
        }
        return $this->success($listing, 'Property listing details', 200);
    }

    /**
     * Create a new property listing
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,house,condo,townhouse,studio,land,other',
            'ownership_type' => 'required|in:owner,agent,developer,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'external_link' => 'nullable|url',
            'property_tags' => 'nullable|array',
            'property_tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();
        $image = null;
        if ($request->hasFile('image')) {
            $image = uploadImage($request->file('image'), 'Property/Images');
        }

        $listing = PropertyListing::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'current_value' => $request->current_value,
            'location' => $request->location,
            'description' => $request->description,
            'property_type' => $request->property_type,
            'ownership_type' => $request->ownership_type,
            'images' => $image,
            'external_link' => $request->external_link,
            'property_tags' => $request->property_tags,
        ]);

        return $this->success($listing, 'Property listing created successfully', 201);
    }

    /**
     * Update a property listing (only owner)
     */
    public function update(Request $request, $id)
    {
        $listing = PropertyListing::find($id);
        if (!$listing) {
            return $this->error([], 'Property listing not found', 404);
        }
        $user = auth()->user();
        if ($listing->user_id !== $user->id) {
            return $this->error([], 'Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'sometimes|required|in:apartment,house,condo,townhouse,studio,land,other',
            'ownership_type' => 'sometimes|required|in:owner,agent,developer,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'external_link' => 'nullable|url',
            'property_tags' => 'nullable|array',
            'property_tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

    $image = $listing->images ?? null;
    if ($request->hasFile('image')) {
        // Remove old image if exists
        if (!empty($image)) {
            $previousImagePath = public_path($image);
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        $image = uploadImage($request->file('image'), 'Property/Images');
    }

        $listing->update([
            'title' => $request->title ?? $listing->title,
            'current_value' => $request->current_value ?? $listing->current_value,
            'location' => $request->location ?? $listing->location,
            'description' => $request->description ?? $listing->description,
            'property_type' => $request->property_type ?? $listing->property_type,
            'ownership_type' => $request->ownership_type ?? $listing->ownership_type,
            'images' => $image,
            'external_link' => $request->external_link ?? $listing->external_link,
            'property_tags' => $request->property_tags ?? $listing->property_tags,
        ]);

        return $this->success($listing, 'Property listing updated successfully', 200);
    }

    /**
     * Delete a property listing (only owner)
     */
    public function destroy($id)
    {
        $listing = PropertyListing::find($id);
        if (!$listing) {
            return $this->error([], 'Property listing not found', 404);
        }
        $user = auth()->user();
        if ($listing->user_id !== $user->id) {
            return $this->error([], 'Unauthorized', 403);
        }
        $listing->delete();
        return $this->success([], 'Property listing deleted successfully', 200);
    }

    /**
     * Get all property listings belonging to the authenticated user
     */
    public function myProperties(Request $request)
    {
        $user = auth()->user();
        $perPage = min($request->get('per_page', 10), 50);
        $listings = PropertyListing::where('user_id', $user->id)
                                  ->latest()
                                  ->paginate($perPage);

        return $this->success($listings, 'My property listings fetched successfully', 200);
    }
}
