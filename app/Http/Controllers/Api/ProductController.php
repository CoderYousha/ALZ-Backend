<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\DTO\Product\ProductData;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\Product\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct(
        private ProductService $productService,
    ){}

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        $request->validate([
            'search' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'per_page' => 'nullable|numeric',
        ]);

        $products = Product::with(['category', 'images'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name_en', 'like', '%' . $request->search . '%')
                        ->orWhere('name_ar', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->paginate($request->per_page ?? 10);

        return ApiResponseClass::successResponse(ProductResource::collection($products));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        
        return ApiResponseClass::successResponse(new ProductResource($product));
    }

    public function store(StoreProductRequest $request){
        $productData = ProductData::fromRequest($request);
        $product = $this->productService->store($productData);

        return ApiResponseClass::successResponse(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product){
        $productData = ProductData::forUpdate($request, $product);
        $product = $this->productService->update($productData, $product);

        return ApiResponseClass::successResponse(new ProductResource($product));
    }

    public function delete(Product $product){
        $product->delete();
        return ApiResponseClass::successMsgResponse();
    }
}
