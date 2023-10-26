<button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-tenant-id="{{ $tenant->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_update_tenant">
    {!! getIcon('setting-3','fs-3') !!}
</button>
<button class="btn btn-icon btn-active-light-primary w-30px h-30px" data-tenant-id="{{ $tenant->id }}" data-kt-action="delete_row">
    {!! getIcon('trash','fs-3') !!}
</button>
