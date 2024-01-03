<?php

namespace App\DataTables;

use App\Models\VenueArea; // Import the VenueArea model
use App\Models\Tenant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Session;

class AreasDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param \Yajra\DataTables\Services\DataTable $dataTable
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable($query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function (VenueArea $area) {
                return view('pages.areas.columns._actions', compact('area'));
            })
            ->editColumn('tenant_id', function (VenueArea $area) {
                return $area->tenant->name;
            })
            ->editColumn('venue_id', function (VenueArea $area) {
                return $area->venue->name;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VenueArea $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VenueArea $model)
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        // Query the VenueArea records with the associated Venue and filter by tenant_id
        return $model->newQuery()
            ->whereHas('venue', function ($query) use ($tenantIds) {
                $query->whereIn('tenant_id', $tenantIds);
            })
            ->with('venue');
    }

    /**
     * Optional method if you want to use the html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('areas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0) // Order by the first column (venue_id)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//areas/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get columns for the DataTable.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('venue_id')->title(trans('areas.venue')),
            Column::make('tenant_id')->title(trans('areas.tenant')),
            Column::make('name')->title(trans('areas.name')),
            Column::make('capacity_noseating')->title(trans('areas.capacity')." (".trans('areas.capacity_noseating').")"),
            Column::make('capacity_seatingrows')->title(trans('areas.capacity')." (".trans('areas.capacity_inrows').")"),
            Column::make('capacity_seatingtables')->title(trans('areas.capacity')." (".trans('areas.capacity_tables').")"),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Areas_' . date('YmdHis');
    }
}
