<?php

namespace App\DataTables;

use App\Models\Staffs;
use App\Models\Price;
use App\Models\Venue;
use App\Models\VenueArea;
use App\Models\Option;
use App\Models\Tenant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

class StaffDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($staff) {
                return view('pages.staff.columns._actions', compact('staff'));
            })
            ->editColumn('name', function ($staff) {
                return $staff->name;
            })
            ->editColumn('type', function ($staff) {
                return $staff->type;
            })
            ->editColumn('area_ids', function ($staff) {
                $areaIds = explode(',', $staff->area_ids);
                $staffAreas = VenueArea::whereIn('id', $areaIds)->pluck('name');
                if($staffAreas->isNotEmpty()) {
                    return implode(',', $staffAreas->toArray());
                } else {
                    return '';
                }
            })
            ->editColumn('value', function ($staff) {
                return $staff->value;
            })
            ->editColumn('tenant_id', function ($staff) {
                $staffTenant = Tenant::where('id', $staff->tenant_id)->pluck('name')->first();
                return $staffTenant;
            })
            ->rawColumns(['action']);
    }

    public function query(Staffs $model)
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        // Query the VenueArea records, filter by tenant_id, and select specific columns
        return $model->newQuery()->with('tenant')
            ->whereIn('tenant_id', $tenantIds)
            ->select([
                'id', 'name', 'type', 'area_ids', 'value', 'tenant_id'
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('staff-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/staff/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title(trans('staff.id')),
            Column::make('name')->title(trans('staff.name'))->addClass('text-nowrap'),
            Column::make('type')->title(trans('staff.type')),
            Column::make('area_ids')->title(trans('venue.area')),
            Column::make('tenant_id')->title(trans('staff.tenant')),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    protected function filename(): string
    {
        return 'Staff_' . date('YmdHis');
    }
}
