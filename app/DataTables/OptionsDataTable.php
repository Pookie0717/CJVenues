<?php

namespace App\DataTables;

use App\Models\Option;
use App\Models\Tenant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

class OptionsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($option) {
                return view('pages.options.columns._actions', compact('option'));
            })
            ->editColumn('tenant_id', function ($option) {
                return $option->tenant->name;
            })
            ->editColumn('name', function ($option) {
                return $option->name;
            })
            ->editColumn('position', function ($option) {
                return $option->position;
            })
           ->editColumn('type', function ($option) {
                $typeLabels = [
                    'yes_no' => 'Yes / No',
                    'check' => 'Check',
                    'radio' => 'Radio',
                    'number' => 'Number',
                    'dropdown' => 'Dropdown',
                    'logic' => 'Logic',
                ];

                return $typeLabels[$option->type] ?? $option->type;
            })
            ->editColumn('values', function ($option) {
                return $option->values;
            })
            ->rawColumns(['action']);
    }

    public function query(Option $model)
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        // Query the VenueArea records, filter by tenant_id, and select specific columns
        return $model->newQuery()
            ->whereIn('tenant_id', $tenantIds)
            ->select(['id', 'name', 'position', 'type', 'values', 'tenant_id']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('options-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/options/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title(trans('options.id'))->addClass('text-nowrap'),
            Column::make('name')->title(trans('options.name'))->addClass('text-nowrap'),
            Column::make('tenant_id')->title(trans('fields.tenant')),
            Column::make('position')->title(trans('fields.position')),
            Column::make('type')->title(trans('options.type')),
            Column::make('values')->title(trans('general.values')),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    protected function filename(): string
    {
        return 'Options_' . date('YmdHis');
    }
}
