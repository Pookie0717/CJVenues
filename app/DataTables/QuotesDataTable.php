<?php

namespace App\DataTables;

use App\Models\Quote;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\HtmlString; // Add this import statement

class QuotesDataTable extends DataTable
{
public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action', function (Quote $quote) {
            return view('pages.quotes.columns._actions', compact('quote'));
        })
        ->addColumn('id_version', function (Quote $quote) {
            return $quote->quote_number . '.' . $quote->version;
        })
         ->addColumn('event_type', function (Quote $quote) {
            $eventType = $quote->eventType;
            return $eventType ? $eventType->name : 'N/A';
        })
        ->addColumn('contact_id', function (Quote $quote) {
            $eventContact = $quote->eventContact;
            return $eventContact ? $eventContact->first_name.' '.$eventContact->last_name : 'N/A';
        })
        ->addColumn('area_id', function (Quote $quote) {
            $area = $quote->eventArea;
            return $area ? $area->name : 'N/A';
        })
        ->addColumn('updated_at', function (Quote $quote) {
            return max($quote->updated_at, $quote->created_at)->format('d-m-Y H:i:s');
        })
        ->addColumn('status', function (Quote $quote) {
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
        return $model->newQuery()->select(['id', 'quote_number', 'version', 'status', 'contact_id', 'event_type', 'area_id', 'created_at', 'updated_at']);

}



    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('quotes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/quotes/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        return [
            Column::make('id_version')->title('Quote #')->addClass('text-nowrap'),
            Column::make('contact_id')->title('Contact')->addClass('text-nowrap'),
            Column::make('status')->title('Status')->addClass('text-nowrap'),
            Column::make('event_type')->title('Event')->addClass('text-nowrap'),
            Column::make('area_id')->title('Area')->addClass('text-nowrap'),
            Column::make('updated_at')->title('Created / Updated')->addClass('text-nowrap'),
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