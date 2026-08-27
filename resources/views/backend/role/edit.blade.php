@extends('layouts.backend')
@section('content')
@section('title')
    Role Edit
@endsection

<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Role Edit</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('role.index') }}"
                        class="text-muted text-hover-primary">Roles</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Role Edit</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div class="app-container container-fluid">
        <div class="card">
            <div class="card-header" style="background-color:#0d6efd;">
                <h3 class="card-title text-white py-3">Edit Role</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('role.update', $data->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}"
                            placeholder="Enter Role Name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Permissions:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                            <label class="form-check-label" for="checkAll">Select All</label>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        @foreach ($permissions as $group => $items)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header d-flex align-items-center"
                                        style="background-color:#28a745;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input group-check" type="checkbox"
                                                id="group_{{ $group }}">
                                            <label class="form-check-label fw-bold text-white text-capitalize"
                                                for="group_{{ $group }}">
                                                {{ str_replace('_', ' ', $group) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" style="background-color:#f1f1f2;">
                                        @foreach ($items as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input child-check-{{ $group }}"
                                                    type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                    id="perm_{{ $permission->id }}"
                                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('role.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        function syncCheckboxStates() {
            let allChecked = true;

            $('.group-check').each(function() {
                let group = $(this).attr('id').replace('group_', '');
                let total = $('.child-check-' + group).length;
                let checked = $('.child-check-' + group + ':checked').length;

                if (checked === total && total > 0) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                    allChecked = false;
                }
            });

            $('#checkAll').prop('checked', allChecked);
        }

        // page load e existing checked value onujayi group/all checkbox thik kora
        syncCheckboxStates();

        $('#checkAll').on('change', function() {
            $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
        });

        $('.group-check').on('change', function() {
            let group = $(this).attr('id').replace('group_', '');
            $('.child-check-' + group).prop('checked', $(this).is(':checked'));
            syncCheckboxStates();
        });

        $('[class^="child-check-"]').on('change', function() {
            syncCheckboxStates();
        });
    });
</script>
@endsection
