<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Helpers\Cryptor;
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
}
