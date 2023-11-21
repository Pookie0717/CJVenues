<?php

namespace App\DataTables;

use App\Models\EventType;
use App\Models\Season;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

class EventTypesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($event_type) {
                return view('pages.events.columns.types._actions', compact('event_type'));
            })
            ->editColumn('name', function ($event_type) {
                $labels = [
                    'wedding' => 'Wedding',
                    'birthday' => 'Birthday Party',
                    'summer' => 'Summer Party',
                    'corporate' => 'Corporate Event',
                ];
                return $labels[$event_type->name] ?? $event_type->name;
            })
            ->editColumn('event_name', function ($event_type) {
                return $event_type->event_name;
            })
            ->editColumn('description', function ($event_type) {
                return $event_type->description;
            })
            ->editColumn('typical_seating', function ($event_type) {
                $seatingLabels = [
                    'noseating' => trans('fields.name'),
                    'seatingrows' => trans('fields.name'),
                    'seatingtables' => trans('fields.name'),
                ];
                return $seatingLabels[$event_type->typical_seating] ?? $event_type->typical_seating;
            })
            ->rawColumns(['action']);
    }

    public function query(EventType $model): QueryBuilder
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');

        // Query the VenueArea records, filter by tenant_id, and select specific columns
        return $model->newQuery()
            ->where('tenant_id', $currentTenantId)
            ->select(['id', 'name', 'event_name', 'description', 'typical_seating', 'created_at', 'updated_at']);    
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('event-types-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/events/columns/types/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('event_name')->title(trans('events.name'))->addClass('text-nowrap'),
            Column::make('name')->title(trans('events.categories'))->addClass('text-nowrap'),
            Column::make('description')->title(trans('events.description'))->addClass('text-nowrap'),
            Column::make('typical_seating')->title(trans('events.typicalseating'))->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    protected function filename(): string
    {
        return 'EventTypes_' . date('YmdHis');
    }
}
