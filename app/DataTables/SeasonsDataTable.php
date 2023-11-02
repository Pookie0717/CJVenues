<?php

namespace App\DataTables;

use App\Models\Season;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

class SeasonsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($season) {
                return view('pages.seasons.columns._actions', compact('season'));
            })
            ->editColumn('name', function ($season) {
                return $season->name;
            })
            ->editColumn('priority', function ($season) {
                return $season->priority;
            })
            ->editColumn('date_from', function ($season) {
                return $season->date_from;
            })
            ->editColumn('date_to', function ($season) {
                return $season->date_to;
            })
            ->editColumn('weekdays', function ($season) {
                return $season->weekdays;
            })
            ->rawColumns(['action']);
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Venue $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Season $model)
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');

        // Query the VenueArea records, filter by tenant_id, and select specific columns
        return $model->newQuery()
            ->where('tenant_id', $currentTenantId)
            ->select([
                'id', 'name', 'priority', 'date_from', 'date_to', 'weekdays'
            ]);
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('seasons-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//seasons/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name')->addClass('text-nowrap'),
            Column::make('date_from')->title('Date From'),
            Column::make('date_to')->title('Date To'),
            Column::make('priority')->title('Priority'),
            Column::make('weekdays')->title('Weekdays'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    protected function filename(): string
    {
        return 'Seasons_' . date('YmdHis');
    }
}