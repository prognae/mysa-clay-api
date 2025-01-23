<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Helpers\Cryptor;
use App\Models\Collection;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function banners(Request $request)
    {
        $banners = Banner::all();

        if ($banners->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No banners found.'
            ], 404);
        }

        $data = $banners->transform(function ($banner) {
            $banner->_id = Cryptor::encrypt($banner->id);

            unset($banner->id);

            return $banner;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Banners retrieved successfully',
            'data' => $data
        ]);
    }

    public function collections(Request $request)
    {
        $collections = Collection::all()->where('status', 1);
        $featured = null;

        if ($collections->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No collections found.'
            ], 404);
        }

        $data = $collections->transform(function ($banner) {
            $banner->_id = Cryptor::encrypt($banner->id);

            unset($banner->id);

            return $banner;
        });

        $featured = $data->firstWhere('is_featured', true);

        $data = $data->where('is_featured', false)->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Collections retrieved successfully',
            'featured' => $featured,
            'data' => $data
        ]);
    }
}
