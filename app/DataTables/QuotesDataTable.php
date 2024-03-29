<?php

namespace App\DataTables;

use App\Models\Quote;
use App\Models\Tenant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Session;

class QuotesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
        ->addColumn('action', function ($quote) {
            return view('pages.quotes.columns._actions', compact('quote'));
        })
        ->editColumn('quote_number', function ($quote) {
            return $quote->quote_number . '.' . $quote->version;
        })
        ->editColumn('event_type', function ( $quote) {
            $eventType = $quote->eventType;
            return $eventType ? $eventType->event_name : 'N/A';
        })
        ->editColumn('contact_id', function ($quote) {
            $eventContact = $quote->eventContact;
            return $eventContact ? $eventContact->first_name.' '.$eventContact->last_name : 'N/A';
        })
        ->editColumn('area_id', function ($quote) {
            $area = $quote->eventArea;
            return $area ? $area->name : 'N/A';
        })
        ->editColumn('updated_at', function ( $quote) {
            return max($quote->updated_at, $quote->created_at)->format('d-m-Y H:i:s');
        })
        ->editColumn('status', function ( $quote) {
            $status = $quote->status;
            $badgeClass = '';

                switch ($status) {
                    case 'Sent':
                        $badgeClass = 'badge-primary';
                        break;
                    case 'Approved':
                        $badgeClass = 'badge-success';
                        break;
                    case 'Rejected':
                        $badgeClass = 'badge-danger';
                        break;
                    default:
                        $badgeClass = 'badge-secondary';
                        break;
                }

                return new HtmlString('<span class="badge ' . $badgeClass . '">' . $status . '</span>');
            });
    }

    // public function query(Quote $model)
    // {
    //         // Get the current tenant_id from the session
    //         $currentTenantId = Session::get('current_tenant_id');
    //         $tenant = Tenant::find($currentTenantId);
    //         $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
    //         $tenantIds[] = $currentTenantId;

    //         // Query the VenueArea records, filter by tenant_id, and select specific columns
    //         return $model->newQuery()->with('tenant')
    //             ->whereIn('tenant_id', $tenantIds)
    //             ->where('status', '<>', 'Archived')
    //             ->select([
    //                 'id', 'quote_number', 'version', 'status', 'contact_id', 'event_type', 'area_id', 'created_at', 'updated_at', 'tenant_id'
    //             ]);
    // }


    public function query(Quote $model) {
        $currentTenantId = Session::get('current_tenant_id');
        $tenant = Tenant::find($currentTenantId);
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;
        
        // Subquery to get the latest id for each quote_number
        $latestQuotesSubquery = $model->newQuery()
            ->whereIn('tenant_id', $tenantIds)
            ->where('status', '<>', 'Archived')
            ->selectRaw('MAX(id) as latest_id')
            ->groupBy('quote_number');

        // Main query that joins the subquery to ensure only the latest quotes are selected
        return $model->newQuery()
            ->joinSub($latestQuotesSubquery, 'latest_quotes', function ($join) {
                $join->on('quotes.id', '=', 'latest_quotes.latest_id');
            })
            ->with('tenant')
            ->select([
                'quotes.id', 'quotes.quote_number', 'quotes.version', 'quotes.status',
                'quotes.contact_id', 'quotes.event_type', 'quotes.area_id',
                'quotes.created_at', 'quotes.updated_at', 'quotes.tenant_id'
            ]);
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('quotes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/quotes/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('quote_number')->title(trans('quotes.quote').' #')->addClass('text-nowrap'),
            Column::make('contact_id')->title(trans('quotes.contact'))->addClass('text-nowrap'),
            Column::make('tenant.name')->title(trans('quotes.tenantname'))->addClass('text-nowrap'),
            Column::make('status')->title(trans('quotes.status'))->addClass('text-nowrap'),
            Column::make('event_type')->title(trans('quotes.event'))->addClass('text-nowrap'),
            Column::make('area_id')->title(trans('quotes.area'))->addClass('text-nowrap'),
            Column::make('updated_at')->title(trans('quotes.createdupdated'))->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    protected function filename(): string
    {
        return 'Quotes_' . date('YmdHis');
    }
}