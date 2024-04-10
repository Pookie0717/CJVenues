<?php

namespace App\DataTables;

use App\Models\Contact;
use App\Models\Tenant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;

class ContactsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Contact $contact) {
                return view('pages.contacts.columns._actions', compact('contact'));
            })
            ->editColumn('tenant_id', function (Contact $contact) {
                return $contact->tenant->name;
            })
            ->editColumn('created_at', function (Contact $contact) {
                return $contact->created_at->format('d-m-Y H:i:s');
            })
            ->setRowId('id');
    }

    public function query(Contact $model): QueryBuilder
    {
        // Get the current tenant_id from the session
        $currentTenantId = Session::get('current_tenant_id');
        $tenantIds = [];
        $tenantIds = Tenant::where('parent_id', $currentTenantId)->pluck('id')->toArray();
        $tenantIds[] = $currentTenantId;

        // Query the VenueArea records and filter by tenant_id
        return $model->newQuery()->whereIn('tenant_id', $tenantIds);
    }

public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contacts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rti' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages//contacts/columns/_draw-scripts.js')) . "}");
    }


    public function getColumns(): array
    {
        return [
            Column::make('id')->title(trans('fields.id')),
            Column::make('name')->title(trans('fields.name')),
            Column::make('email')->title(trans('fields.email')),
            Column::make('tenant_id')->title(trans('fields.tenant')),
            Column::make('phone')->title(trans('fields.phone')),
            Column::make('notes')->title(trans('fields.notes')),
            Column::make('created_at')->title(trans('general.createdat'))->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    protected function filename(): string
    {
        return 'Contacts_' . date('YmdHis');
    }
}
