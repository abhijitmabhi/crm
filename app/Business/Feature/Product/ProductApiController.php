<?php

namespace LocalheroPortal\Business\Feature\Product;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Sales\Product;

class ProductApiController extends Controller
{

    public function index(Request $request): AnonymousResourceCollection
    {
        $pagination = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'name';
        $products = Product::query()->with('paymentOptions');
        return ProductResource::collection($products->orderBy($sortBy)->paginate($pagination));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'string|required',
            'description'         => 'string|nullable',
            'min_price'           => 'numeric|required',
            'payment_progression' => 'json|required',
        ]);

        $product = new Product($request->all());
        try {
            $product->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json(new ProductResource($product), 201);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'                => 'string|required',
            'description'         => 'string|nullable',
            'min_price'           => 'numeric|required',
            'payment_progression' => 'json|required',
        ]);

        $product->fill($request->all());
        try {
            $product->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json(new ProductResource($product), 200);
    }
}
