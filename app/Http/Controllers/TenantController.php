<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class TenantController extends Controller
{
    public function setTenant(Request $request)
    {
        $selectedTenantId = $request->input('tenant');
        Session::put('current_tenant_id', $selectedTenantId);
        return redirect()->back()->with('success', 'Organisation updated successfully');
    }
}