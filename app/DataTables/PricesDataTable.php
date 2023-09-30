<?php

namespace App\DataTables;

use App\Models\Price;
use App\Models\Venue;
use App\Models\VenueArea;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PricesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($price) {
                return view('pages.prices.columns._actions', compact('price'));
            })
            ->editColumn('name', function ($price) {
                return $price->name;
            })
            ->editColumn('type', function ($price) {
                return $this->getTypeLabel($price->type);
            })
           ->addColumn('property_id', function ($price) {
                if ($price->venue_id != 0) {
                    return Venue::find($price->venue_id)->name ?? 'N/A';
                } elseif ($price->area_id != 0) {
                    return VenueArea::find($price->area_id)->name ?? 'N/A';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('price', function ($price) {
                return $price->price;
            })
            ->editColumn('tier_type', function ($price) {
                return $price->tier_type;
            })
            ->editColumn('tier_value', function ($price) {
                return $price->tier_value;
            })
            ->rawColumns(['action']);
    }

    public function query(Price $model)
    {
        return $model->newQuery()->select([
            'id', 'name', 'type',
            'venue_id', 'area_id', 'option_id', 'price', 'tier_type', 'tier_value'
        ]);
    }

    public function getTypeLabel($type)
    {
        $labels = [
            'area' => 'Area',
            'venue' => 'Venue',
        ];

        return $labels[$type] ?? $type;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('prices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/prices/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->addClass('text-nowrap'),
            Column::make('name')->title('Price Rule Name')->addClass('text-nowrap'),
            Column::make('type')->title('Type'),
            Column::computed('property_id')->title('Property'),
            Column::make('price')->title('Amount'),
            //Column::make('tier_type')->title('Tier Type'),
            //Column::make('tier_value')->title('Tier Value'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60),
        ];
    }

    protected function filename(): string
    {
        return 'Prices_' . date('YmdHis');
    }
}
