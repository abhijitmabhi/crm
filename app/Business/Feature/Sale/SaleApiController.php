<?php


namespace LocalheroPortal\Business\Feature\Sale;


use LocalheroPortal\Models\Sales\Product;
use LocalheroPortal\Models\Sales\Sale;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{Auth, DB, Hash};
use LocalheroPortal\Models\Sales\SaleStatus;
use LocalheroPortal\Models\LeadState;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;
use LocalheroPortal\Models\LLI\Company;

class SaleApiController extends Controller
{
    public function update(Sale $sale, Request $request)
    {
        if ($request->action === "denied") {
            $sale->status = SaleStatus::DENIED;
            $sale->save();
            return Response::create('scuess');
        }

        $sale->status = SaleStatus::ACCEPTED;
        $sale->payment_option_id = $request->payed_with;
        $sale->save();
    }

    public function store(SaleFormRequest $request)
    {
        $sale = null;
        $customer = self::findCustomer($request);
        if (!$customer) {
            return false;
        }
        $company = self::findCompany($request, $customer);
        if (!$company) {
            return false;
        }
        DB::transaction(function () use ($request, &$sale, $customer, $company) {
            $sale = Sale::create([
                'customer_id' => $customer->id,
                'expert_id'   => Auth::id(),
                'price'       => $request->price,
                'product_id'  => $request->product_id,
                'payment_option_id' => $request->payment_option_id,
            ]);
            $company->products()->save(Product::find($request->product_id));
        });
        return $sale;
    }

    public static function findCustomer(SaleFormRequest $request): User
    {
        if ($request->customer_id) {
            return User::find($request->customer_id);
        }
        $company = Company::whereLeadId($request->lead_id)->first();
        if ($company) {
            $customer = User::whereEmail($company->email)->first();
            if ($customer) {
                return $customer;
            }
        }
        return self::createCustomer(Lead::find($request->lead_id));
    }

    /**
     * @return User|boolean
     */
    public static function createCustomer(Lead $lead, string $password = "password")
    {
        $customer = false;
        DB::transaction(function () use ($lead, $password, &$customer) {
            $customer = User::create([
                'name'     => $lead->company_name,
                'email'    => $lead->email,
                'password' => Hash::make($password),
            ]);
            $lead->status = LeadState::CLOSED;
            $lead->save();
        });
        return $customer;
    }

    public static function findCompany(SaleFormRequest $request, User $user): Company
    {
        if ($company = Company::whereEmail($user->email)->first()) {
            return $company;
        }
        if ($request->lead_id) {
            $lead = Lead::find($request->lead_id);
            return self::createCompany($lead, $user);
        }
        return false;
    }

    public static function createCompany(Lead $lead, User $user)
    {
        $company = null;
        DB::transaction(function () use ($lead, $user, &$company) {
            $args = $lead->only(['street', 'zip', 'city']);
            $args['name']  = $lead->company_name;
            $args['phone'] = $lead->phone1;
            $args['email'] = $user->email;
            if ($lead->website) {
                $args['url'] = $lead->website;
            }
            $company = Company::create($args);
            $user->company()->save($company);
            $lead->company()->save($company);
        });
        return $company;
    }
}
