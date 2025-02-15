<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use App\Helpers\Cryptor;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Requests\Shop\ProductRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

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

    public function products(ProductRequest $request)
    {
        $limit = $request->limit ?? 50;

        $sort = $request->sort ?? 'id';

        $order = $request->order ?? 'asc';

        $products = Product::with([
            'category:id,name,slug', 
            'collection:id,name'
        ])
        ->select(
            'id',
            'collection_id',
            'name',
            'description',
            'price',
            'is_discounted',
            'discounted_price',
            'markup',
            'markdown',
            'final_price',
            'quantity',
            'category_id',
            'status',
            'thumbnail_url',
            'created_at'
        )
        ->where('status', 1);

        if(isset($request->status)) {
            $products->where('status', $request->status);
        }

        if(isset($request->collection_id)) {
            $products->where('collection_id', $request->collection_id);
        }

        if(isset($request->search)) {
            $search = $request->search;

            $products->where(function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        if($products->doesntExist()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $products->orderBy($sort, $order)->paginate($limit);

        $data->getCollection()->transform(function ($product) {
            $product->_id = Cryptor::encrypt($product->id);
            $product->category_name = $product->category->name;
            $product->collection_name = $product->collection->name;

            unset($product->id, $product->category, $product->collection, $product->category_id, $product->collection_id);

            return $product;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $data
        ]);
    }
}
