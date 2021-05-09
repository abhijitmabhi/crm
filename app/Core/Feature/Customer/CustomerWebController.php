<?php

namespace LocalheroPortal\Core\Feature\Customer;

use LocalheroPortal\Core\Http\Controllers\Controller;

class CustomerWebController extends Controller
{

    public function checkCustomerView()
    {
        return view('lli.customer.CheckCustomerView');
    }
    
}
