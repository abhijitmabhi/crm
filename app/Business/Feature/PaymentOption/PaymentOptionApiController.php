<?php

namespace LocalheroPortal\Business\Feature\PaymentOption;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Business\Feature\Product\ProductResource;
use LocalheroPortal\Models\Sales\PaymentOption;
use LocalheroPortal\Core\Http\Controllers\Controller;

class PaymentOptionApiController extends Controller
{
    public function destroy(PaymentOption $option)
    {
        $option->delete();
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $pagination = $request->per_page ?? 15;
        $sortBy = $request->sort_by ?? 'created_at';
        //TODO: why ProductResource?
        return ProductResource::collection(PaymentOption::orderBy($sortBy)->paginate($pagination));
    }


    public function show(PaymentOption $option): ProductResource
    {
        return new ProductResource($option);
    }

    function store(Request $request)
    {
        $request->validate([
            'name'                => 'string|required',
            'description'         => 'string|nullable',
            'min_price'           => 'numeric|required',
            'payment_progression' => 'json|required',
        ]);

        $option = new PaymentOption($request->all());
        try {
            $option->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json(new ProductResource($option), 201);
    }

    public function update(Request $request, PaymentOption $option)
    {
        $request->validate([
            'name'                => 'string|required',
            'description'         => 'string|nullable',
            'min_price'           => 'numeric|required',
            'payment_progression' => 'json|required',
        ]);

        $option->fill($request->all());
        try {
            $option->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json(new ProductResource($option), 200);
    }
}
