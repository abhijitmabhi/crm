<?php


namespace LocalheroPortal\Business\Feature\PaymentOption;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use LocalheroPortal\Models\Sales\PaymentType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\Sales\PaymentOption;

class PaymentOptionWebController extends Controller
{
    public $rules =  [];

    public function __construct()
    {
        $this->rules = [
            'name'                => 'string|required',
            'description'         => 'string|nullable',
            'rates' => 'integer|required',
            'type' => [
                'string',
                'required',
                Rule::in(PaymentType::asArray())
            ]
        ];
    }

    public function create(): View
    {
        return view('business.paymentOptions.CreatePaymentOptionView');
    }

    public function destroy(PaymentOption $payment_option)
    {
        $payment_option->delete();
    }

    public function edit(PaymentOption $payment_option): View
    {
        return view('business.paymentOptions.EditPaymentOptionView', ['payment_option' => $payment_option]);
    }

    public function index(Request $request): View
    {
        $pagination = $request->per_page ?? 15;
        $options = PaymentOption::paginate($pagination);
        if ($request->sort_by) {
            $options->orderBy($request->sort_by);
        }
        return view('business.paymentOptions.PaymentOptionListView', ['options' => $options]);
    }

    public function show(PaymentOption $payment_option): View
    {
        return view('business.paymentOptions.EditPaymentOptionView', ['option' => $payment_option]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $product = new PaymentOption($request->all());
        try {
            $product->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json($product, 201);
    }

    public function update(Request $request, PaymentOption $payment_option)
    {
        $request->validate($this->rules);

        $payment_option->fill($request->all());
        try {
            $payment_option->save();
        } catch (Exception $e) {
            return Response::make($e, 500);
        }
        return Response::json($payment_option, 200);
    }
}