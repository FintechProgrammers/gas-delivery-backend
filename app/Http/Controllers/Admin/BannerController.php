<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.banner.index');
    }

    function filter(Request $request)
    {
        $data['banners'] = Banner::latest()->paginate(10);

        return view('admin.banner._table', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            $image = uploadFile($request->file('image'), "uploads/banner", "do_spaces");

            Banner::create([
                'file_url' => $image
            ]);

            return response()->json(['success' => false, 'message' => 'Successfully uploaded.']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        $data['banner'] = $banner;

        return view('admin.banner.edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $data['banner'] = $banner;

        return view('admin.banner.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            if ($request->hasFile('image')) {
                deleteFile($banner->file_url);
                $image = uploadFile($request->file('image'), "uploads/banner", "do_spaces");
            } else {
                $image = $banner->file_url;
            }

            $banner->update([
                'file_url' => $image
            ]);

            return response()->json(['success' => false, 'message' => 'Successfully uploaded.']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function publish(Banner $banner)
    {
        $banner->update([
            'is_active' => true,
        ]);

        return $this->sendResponse([], "Published successfully");
    }

    function unpublish(Banner $banner)
    {
        $banner->update([
            'is_active' => false,
        ]);

        return $this->sendResponse([], "UnPublished successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        deleteFile($banner->file_url);

        $banner->delete();

        return $this->sendResponse([], "Deleted successfully.");
    }
}
