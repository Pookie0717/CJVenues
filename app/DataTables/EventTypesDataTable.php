<?php

namespace App\DataTables;

use App\Models\EventType;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

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
            ->editColumn('typical_seating', function ($event_type) {
                $seatingLabels = [
                    'noseating' => 'No Seating',
                    'seatingrows' => 'In Rows',
                    'seatingtables' => 'Tables',
                ];
                return $seatingLabels[$event_type->typical_seating] ?? $event_type->typical_seating;
            })
            ->editColumn('duration', function ($event_type) {
                $duration = $event_type->duration;
                $duration_type = $event_type->duration_type;
                return "$duration ($duration_type)";
            })
            ->editColumn('time_setup', function ($event_type) {
                $time_setup = $event_type->time_setup;
                $duration_type = $event_type->duration_type;
                return "$time_setup ($duration_type)";
            })
            ->editColumn('time_cleaningup', function ($event_type) {
                $time_cleaningup = $event_type->time_cleaningup;
                $duration_type = $event_type->duration_type;
                return "$time_cleaningup ($duration_type)";
            })
            ->editColumn('season_id', function ($event_type) {
                if ($event_type->season_id === 0) {
                    return 'All';
                } else {
                    // Replace 'SeasonModel' with your actual Season model
                    $season = SeasonModel::find($event_type->season_id);
                    return $season ? $season->name : '';
                }
            })
            ->editColumn('availability', function ($event_type) {
                $availability = $event_type->availability;
                if ($availability === '0') {
                    return 'All';
                } else {
                    return $availability;
                }
            })
            ->rawColumns(['action']);
    }

    public function query(EventType $model): QueryBuilder
    {
        return $model->newQuery()->select(['id', 'name', 'typical_seating', 'duration_type', 'duration', 'min_duration', 'time_setup', 'time_cleaningup', 'season_id', 'availability', 'created_at', 'updated_at']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('event-types-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/events/columns/types/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->addClass('text-nowrap'),
            Column::make('name')->title('Name')->addClass('text-nowrap'),
            Column::make('typical_seating')->title('Typical Seating')->addClass('text-nowrap'),
            Column::make('duration')->title('Duration')->addClass('text-nowrap'),
            Column::make('time_setup')->title('Setup')->addClass('text-nowrap'),
            Column::make('time_cleaningup')->title('Cleaning')->addClass('text-nowrap'),
            Column::make('season_id')->title('Season')->addClass('text-nowrap'),
            Column::make('availability')->title('Availability')->addClass('text-nowrap'),
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
