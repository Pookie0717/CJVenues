<?php

namespace App\DataTables;

use App\Models\Quote;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class QuotesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Quote $quote) {
                return view('pages.quotes.columns._actions', compact('quote'));
            })
            ->addColumn('contact_name', function (Quote $quote) {
                return $quote->contact ? $quote->contact->name : 'N/A';
            })
            ->editColumn('created_at', function (Quote $quote) {
                return $quote->created_at->format('d M Y, h:i a');
            })
            ->setRowId('id');
    }

    public function query(Quote $model): QueryBuilder
    {
        return $model->newQuery();
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('quotes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Quote #'),
            Column::make('contact_name')->title('Contact Name'),  // New column for contact name
            Column::make('content')->title('Content'),
            Column::make('created_at')->title('Created At')->addClass('text-nowrap'),
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
