<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $userId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($userId);

        Rating::create([
            'user_id' => $user->id,
            'rated_by' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return $this->sendResponse([], "Rating submitted successfully");
    }

    public function show($userId)
    {
        $user = User::with('ratings.rater')->findOrFail($userId);

        $data = [
            'user' => $user->name,
            'average_rating' => $user->ratings->avg('rating'),
            'ratings' => $user->ratings,
        ];

        return $this->sendResponse($data);
    }
}
