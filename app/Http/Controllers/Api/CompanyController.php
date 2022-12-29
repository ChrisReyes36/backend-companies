<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    private function validations($companyId = '')
    {
        $id = $companyId ? ',' . $companyId : '';
        return [
            'name' => ['required', 'max:191'],
            'email' => ['required', 'email', 'unique:companies,email' . $id],
            'address' => ['string'],
            'website' => ['url'],
        ];
    }

    public function index()
    {
        return CompanyResource::collection(Company::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validations());
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $company = Company::create($request->all());
        return new CompanyResource($company);
    }

    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), $this->validations($company->id));
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        $company->update($request->all());
        return new CompanyResource($company);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json([
            'success' => true,
        ], 200);
    }
}
