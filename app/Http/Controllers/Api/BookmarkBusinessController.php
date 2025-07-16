<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookMarkBusinessRequest;
use App\Http\Resources\BookmarkedBusinessResource;
use App\Models\BusinessBookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookmarkBusinessController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookmarks = BusinessBookmark::with('vendor')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return $this->sendResponse([
            'data' => BookmarkedBusinessResource::collection($bookmarks),
            'pagination' => [
                'current_page' => $bookmarks->currentPage(),
                'last_page' => $bookmarks->lastPage(),
                'per_page' => $bookmarks->perPage(),
                'total' => $bookmarks->total(),
                'from' => $bookmarks->firstItem(),
                'to' => $bookmarks->lastItem(),
            ]
        ], 'Bookmarked businesses retrieved successfully.', Response::HTTP_OK);
    }

    public function create(BookMarkBusinessRequest $request)
    {
        $user = $request->user();
        $businessUuid = $request->validated()['vendor'];

        // Try to find the vendor by UUID
        $vendor = User::where('uuid', $businessUuid)->first();

        if (!$vendor) {
            return $this->sendResponse([], 'Business not found.', Response::HTTP_NOT_FOUND);
        }

        // Check if this business is already bookmarked by the user
        $alreadyBookmarked = BusinessBookmark::where('user_id', $user->id)
            ->where('business_id', $vendor->id)
            ->exists();

        if ($alreadyBookmarked) {
            return $this->sendResponse([], 'Business already bookmarked.', Response::HTTP_OK);
        }

        // Create the bookmark
        BusinessBookmark::create([
            'user_id' => $user->id,
            'business_id' => $vendor->id,
        ]);

        return $this->sendResponse([], 'Business bookmarked successfully.', Response::HTTP_CREATED);
    }


    public function removeBusiness(BusinessBookmark $bookmark, Request $request)
    {
        if ($bookmark->user_id !== $request->user()->id) {
            return $this->sendResponse([], 'Unauthorized to delete this bookmark.', Response::HTTP_FORBIDDEN);
        }

        $bookmark->delete();

        return $this->sendResponse([], 'Business bookmark removed successfully.', Response::HTTP_OK);
    }
}
