<?php

namespace LocalheroPortal\LLI\Feature\CompanyManagement;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Core\Repository\CompanyRepository;
use LocalheroPortal\Core\Repository\UserRepository;
use LocalheroPortal\LLI\Http\Resources\CompanyResource;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\CommentReason;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\User;

class CompanyApiController extends Controller
{

    public function destroy(Company $company)
    {
        $company->delete();
        return Response::make('Company deleted');
    }

    public function index(Request $request)
    {
        $per_page = $request->size ?? 20;
        $repo = new CompanyRepository();
        $companies = $repo->get($request->searchInput, $per_page);

        return CompanyResource::collection($companies);
    }

    public function show($id)
    {
        if ($company = Company::find($id)) {
            return new CompanyResource($company);
        }
        return Response::json('No resource with id '.$id.' exists', 404);
    }

    public function store(CompanyFormRequest $request)
    {
        $company = new Company($request->all());
        DB::beginTransaction();
        $userRepo = new UserRepository();
        $user = $userRepo->getUserByEmail($company->email);
        if (!$user) {
            $user = User::create([
                'name' => $company->name,
                'email' => $company->email,
                'password' => Hash::make(now('Europe/Berlin')->toDateString().'_'.$company->name),
            ]);
        }
        $company->user_id = $user->id;
        try {
            $company->save();
            $company->comments()->save(Comment::make([
                'reason' => CommentReason::CREATED,
                'body' => 'Wurde von '.Auth::user().' angelegt.',
                'user_id' => Auth::id(),
            ]));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json(['message' => $e], 500);
        }
        return Response::json($company, 201);
    }

    public function update(Company $company, CompanyFormRequest $request)
    {
        $data = $request->validated();
        try {
            $company->fill($data);
            $company->save();
        } catch (Exception $e) {
            return Response::json(['message' => $e->__toString()], 500);
        }

        return Response::json([
            'company' => new CompanyResource($company),
            'message' => 'updated company'
        ]);
    }

}
