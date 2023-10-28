<?php

namespace App\DataTables;

use App\Models\Venue;
use App\Models\VenueArea;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class VenuesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @return void
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($venue) {
                return view('pages.venues.columns._actions', compact('venue'));
            })
            ->editColumn('created_at', function ($venue) {
            return $venue->created_at->format('d-m-Y H:i:s'); // Change the format as needed
            })
            ->editColumn('updated_at', function ($venue) {
                return $venue->updated_at->format('d-m-Y H:i:s'); // Change the format as needed
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Venue $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Venue $model)
    {
        $currentTenantId = Session::get('current_tenant_id');

        return $model->newQuery()
            ->where('tenant_id', $currentTenantId) // Filter by tenant_id
            ->select(['id', 'name', 'type', 'address', 'created_at', 'updated_at']);
    }

    /**
     * Add additional html attributes to the table.
     *
     * @return array
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('venues-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//venues/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('type')->title('Type'),
            Column::make('address')->title('Address'),
            Column::make('created_at')->title('Created At'),
            Column::make('updated_at')->title('Updated At'),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
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
        return 'venues_' . date('YmdHis');
    }
}