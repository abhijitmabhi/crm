<?php

namespace LocalheroPortal\Core\Feature\ImportCustomers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;

class ImportCustomersController extends Controller
{

    public function getImportCustomersForm()
    {
        return view('lli.company.ImportCustomerView');
    }

    public function postImportCustomers(Request $request)
    {
        $parser = new CustomerImportSheetParser();
        $parser->parse($request->file('spreadsheet'));
        $importer = new CustomerDataImporter(Auth::user());
        $importer->import($parser->parsedData);
        $errors = array_merge($parser->errors, $importer->errors);
        if ($request->ajax()) {
            return Response::json(['errors' => $errors, 'success' => count($importer->companyIds)]);
        }

        if (!empty($parser->errors)) {
            return back()->with('duplicates', $errors)->with('success', count($importer->companyIds));
        }

        return redirect('companies')->with('success', count($parser->parsedData));
    }
}
