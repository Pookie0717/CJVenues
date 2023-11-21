<?php

namespace App\DataTables;

use App\Models\Quote;
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
        ->addColumn('id_version', function ($quote) {
            return $quote->quote_number . '.' . $quote->version;
        })
         ->addColumn('event_type', function ( $quote) {
            $eventType = $quote->eventType;
            return $eventType ? $eventType->event_name : 'N/A';
        })
        ->addColumn('contact_id', function ($quote) {
            $eventContact = $quote->eventContact;
            return $eventContact ? $eventContact->first_name.' '.$eventContact->last_name : 'N/A';
        })
        ->addColumn('area_id', function ($quote) {
            $area = $quote->eventArea;
            return $area ? $area->name : 'N/A';
        })
        ->addColumn('updated_at', function ( $quote) {
            return max($quote->updated_at, $quote->created_at)->format('d-m-Y H:i:s');
        })
        ->addColumn('status', function ( $quote) {
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




public function query(Quote $model)
{
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');

        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');

        // Query the VenueArea records, filter by tenant_id, and select specific columns
        return $model->newQuery()
            ->where('tenant_id', $currentTenantId)
            ->where('status', '<>', 'Archived')
            ->select([
                'id', 'quote_number', 'version', 'status', 'contact_id', 'event_type', 'area_id', 'created_at', 'updated_at'
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
            Column::make('id_version')->title(trans('quotes.quote').' #')->addClass('text-nowrap'),
            Column::make('contact_id')->title(trans('quotes.contact'))->addClass('text-nowrap'),
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