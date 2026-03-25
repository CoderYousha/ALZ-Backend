<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\File\FileManagementServicesClass;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $Categories = Category::when($request->search, function($query) use($request){
            return $query->where('name_en', 'like', '%' . $request->search . '%')
                ->orWhere('name_ar', 'like', '%' . $request->search . '%');
        })
        ->paginate($request->per_page ?? 10);

        return ApiResponseClass::successResponse(CategoryResource::collection($Categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            'name_en' => $request->input('name_en'),
            'name_ar' => $request->input('name_ar'),
            'image' => FileManagementServicesClass::storeFiles($request->image, 'category-img'),
            'description_en' => $request->input('description_en'),
            'description_ar' => $request->input('description_ar'),
        ]);

        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function show(Category $category)
    {
        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name_en' => $request->input('name_en', $category->name_en),
            'name_ar' => $request->input('name_ar', $category->name_ar),
            'image' => $request->image ? FileManagementServicesClass::storeFiles($request->image, 'category-img') : $category->image,
            'description_en' => $request->input('description_en', $category->description_en),
            'description_ar' => $request->input('description_ar', $category->description_ar),
        ]);

        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function delete(Category $category)
    {
        $category->delete();

        return ApiResponseClass::successMsgResponse();
    }

}
