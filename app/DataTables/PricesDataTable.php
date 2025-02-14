<?php

namespace App\DataTables;

use App\Models\Price;
use App\Models\Venue;
use App\Models\VenueArea;
use App\Models\Option;
use App\Models\Tenant;
use App\Models\Staffs;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

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
                } elseif ($price->option_id != 0) {
                    return Option::find($price->option_id)->name ?? 'N/A';
                } elseif ($price->staff_id != 0) {
                    return Staffs::find($price->staff_id)->name ?? 'N/A';
                } else {
                    return 'N/A';
                }
            })
            ->editColumn('price', function ($price) {
                return $price->price;
            })
            ->editColumn('multiplier', function ($price) {
                return $this->getMultiLabel($price->multiplier, $price->x);
            })
            ->rawColumns(['action']);
    }

    public function query(Price $model)
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
                'id', 'name', 'type',
                'venue_id', 'area_id', 'option_id', 'price', 'multiplier', 'x', 'tenant_id', 'staff_id'
            ]);
    }

    public function getTypeLabel($type)
    {
        $labels = [
            'area' => 'Area',
            'venue' => 'Venue',
            'option' => 'Option',
            'staff' => 'Staff',
        ];

        return $labels[$type] ?? $type;
    }

    public function getMultiLabel($Multi, $X)
    {
        $labels = [
            'event' => 'Per Event',
            'event_pp' => 'Per Person',
            'daily' => 'Per Day',
            'daily_pp' => 'Per Day / PP',
            'hourly' => 'Per Hour',
            'hourly_pp' => 'Per Hour / PP',
            'hourly_pp' => 'Per Hour / PP',
            'every_x_p' => 'Every ' . $X . ' people',
            'every_x_d' => 'Every ' . $X . ' days',
            'every_x_h' => 'Every ' . $X . ' hours',
        ];

        return $labels[$Multi] ?? $Multi;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('prices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/prices/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title(trans('prices.name'))->addClass('text-nowrap'),
            Column::make('type')->title(trans('prices.type')),
            Column::make('tenant.name')->title(trans('prices.tenantname')),
            Column::computed('property_id')->title(trans('prices.applypriceto')),
            Column::make('price')->title(trans('prices.amount')),
            Column::make('multiplier')->title(trans('prices.multiplier')),
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
