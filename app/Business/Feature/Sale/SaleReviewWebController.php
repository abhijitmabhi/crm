<?php


namespace LocalheroPortal\Business\Feature\Sale;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\Sales\SaleStatus;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;
use LocalheroPortal\Models\Sales\Sale;

class SaleReviewWebController extends Controller
{
    private $related = ['customer.company', 'expert', 'product.paymentOptions', 'paymentOption'];
    public function __invoke(Request $request)
    {
        $experts = User::byRole(RoleType::EXPERT)->get();
        $openSales = Sale::whereStatus(SaleStatus::OPEN)->with($this->related)->get();
        $oldSales = Sale::whereStatus(SaleStatus::ACCEPTED)->with($this->related);
        $salesTotal = Sale::whereStatus(SaleStatus::ACCEPTED);

        if ($request->has('expert') && !empty($request->expert)) {
            $oldSales->whereHas('expert', function ($q) use ($request) {
                return $q->where('id', $request->expert);
            });
            $salesTotal->whereHas('expert', function ($q) use ($request) {
                return $q->where('id', $request->expert);
            });
        }

        if ($request->has('date') && !empty($request->date)) {
            $oldSales->whereMonth('updated_at', Carbon::parse($request->date));
            $salesTotal->whereMonth('updated_at', Carbon::parse($request->date));
        }
        $oldSales = $oldSales->get();
        $salesTotal = 0; // TODO: get new total
        return view('business.sales.SalesListView', compact('experts', 'openSales', 'oldSales', 'salesTotal'));
    }
}
