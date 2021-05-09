<?php

namespace LocalheroPortal\Business\Feature\Sale;

use Illuminate\Support\Facades\Auth;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Callcenter\Http\Resources\LeadSingleResource;
use LocalheroPortal\Core\Http\Controllers\Controller;

class SaleWebController extends Controller
{
    public function index()
    {
        $sales = [];
        for ($i = 0; $i <= 5; $i++) {
            $sales[] = [
                'customer_id' => $i,
                'expert_id' => $i + 100,
                'product_id' => $i,
            ];
        }
        $sales = collect($sales);
        return view('business.sales.SalesListView', ['sales' => $sales]);
    }

    public function makeSales()
    {
        $user = Auth::user();
        $leads = $user->leads()
            ->where('closed_until', '<', now('Europe/Berlin'))
            ->where('status', '=', LeadState::APPOINTMENT)->get();
        return view('business.sales.SalesAppointmentView', ['leads' => LeadSingleResource::collection($leads)]);
    }
}
