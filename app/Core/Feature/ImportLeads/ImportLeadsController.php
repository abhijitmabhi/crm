<?php


namespace LocalheroPortal\Core\Feature\ImportLeads;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Feature\ImportCustomers\CustomerImportSheetParser;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class ImportLeadsController extends Controller
{

    public function getImportLeadsView(Request $request)
    {
        $expert = $request->expert ? User::find($request->expert) : null;
        return view('experts.admin.ImportLeadView', ['expert' => $expert]);
    }

    // TODO: Create Lead while Iterating over input File to reduce memory usage
    public function importLeads(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|mimes:ods',
            'expert' => 'required'
        ]);
        $importer = new LeadImporter(Auth::user());
        $importer->batchImport($request->file('spreadsheet'));
        if ($request->ajax()) {
            return Response::json([
                'errors' => $importer->getErrors(),
                'errorFile' => $importer->getErrorFileUrl(),
                'success' => $importer->getSuccesses()
            ]);
        }

        if ($importer->hasErrors()) {
            return back()->with('duplicates', $importer->getErrors())->with('success', $importer->getSuccesses());
        }

        return redirect('admin/experts')->with('success', $importer->getSuccesses());
    }

    public function getImportBlacklistLeadsView()
    {
        return view('core.import.ImportBlacklistLeadsView');
    }

    public function importBlacklistLeads(Request $request)
    {
        $parser = new CustomerImportSheetParser();
        $parser->parse($request->file('spreadsheet'));
        $importer = new BlacklistLeadsImporter(Auth::user());
        $importer->import($parser->parsedData);
        $errors = array_merge($parser->errors, $importer->errors);
        if ($request->ajax()) {
            return Response::json(['errors' => $errors, 'success' => count($importer->leadIds)]);
        }

        if (!empty($parser->errors)) {
            return back()->with('duplicates', $errors)->with('success', count($importer->leadIds));
        }

        return redirect('leads')->with('success', count($parser->parsedData));
    }
}