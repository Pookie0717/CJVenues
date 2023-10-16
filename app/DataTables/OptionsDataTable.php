<?php

namespace App\DataTables;

use App\Models\Option;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class OptionsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($option) {
                return view('pages.options.columns._actions', compact('option'));
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
        return $model->newQuery()->select(['id', 'name', 'position', 'type', 'values']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('options-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/options/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->addClass('text-nowrap'),
            Column::make('name')->title('Name')->addClass('text-nowrap'),
            Column::make('position')->title('Position'),
            Column::make('type')->title('Type'),
            Column::make('values')->title('Values'),
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
