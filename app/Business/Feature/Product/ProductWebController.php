<?php

namespace LocalheroPortal\Business\Feature\Product;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Sales\Product;

class ProductWebController extends Controller
{
    private $rules = [
        'name'                => 'string|required',
        'description'         => 'string|nullable',
        'min_price'           => 'integer|required',
    ];

    public function create(): View
    {
        return view('business.products.CreateProductView');
    }

    public function edit(Product $product): View
    {
        return view('business.products.EditProductView', ['product' => $product]);
    }

    public function index(Request $request): View
    {
        $pagination = $request->per_page ?? 15;
        $products = Product::paginate($pagination);
        if ($request->sort_by) {
            $products->orderBy($request->sort_by);
        }
        return view('business.products.ProductListView', ['products' => $products]);
    }

    public function show(Product $product): View
    {
        $product->load('paymentOptions:payment_option_id,name');
        return view('business.products.EditProductView', ['product' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $product = new Product($request->only('name', 'min_price', 'description'));
        try {
            $product->save();
            $product->paymentOptions()->sync(explode(',', $request->selected));
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate($this->rules);

        $product->fill($request->only('name', 'min_price', 'description'));
        try {
            $product->save();
            $product->paymentOptions()->sync(explode(',', $request->selected));
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json($product, 200);
    }
}
