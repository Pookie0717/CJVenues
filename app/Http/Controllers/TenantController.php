<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\DataTables\TenantsDataTable;

class TenantController extends Controller
{
    public function setTenant(Request $request)
    {
        $selectedTenantId = $request->input('tenant');
        Session::put('current_tenant_id', $selectedTenantId);
        return redirect()->back()->with('success', 'Organisation updated successfully');
    }

    public function index(TenantsDataTable $dataTable)
    {
        $user = auth()->user();
        $tenants = $user->tenants;

        return $dataTable->render('pages.tenants.list');
    }

}