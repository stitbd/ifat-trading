@extends('layouts.backend')
@section('content')
@section('title')
    Role Create
@endsection

<div class="app-toolbar py-3 py-lg-6">
    <div class="app-container container-fluid">
        <div class="admin-page-header">
            <div class="admin-page-header-title">
                <span class="icon-box"><i class="bi bi-shield-plus"></i></span>
                <h1>Role Create</h1>
            </div>
            <a href="{{ route('role.index') }}" class="btn-admin-primary">
                <i class="bi bi-arrow-left"></i> Back to Roles
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="bi bi-shield-lock" style="color:#4361ee;"></i> Create Role</h5>
            </div>

            <div style="padding: 24px;">
                <form method="POST" action="{{ route('role.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Role Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Enter Role Name" required
                            style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                            Select Permissions:
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                            <label class="form-check-label" for="checkAll">Select All</label>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        @foreach ($permissions as $group => $items)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm"
                                    style="border-radius:10px; overflow:hidden; border:1px solid #eef0f2;">
                                    <div class="card-header d-flex align-items-center"
                                        style="background-color:#4361ee; border:none;">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input group-check" type="checkbox"
                                                id="group_{{ $group }}">
                                            <label class="form-check-label fw-bold text-white text-capitalize"
                                                for="group_{{ $group }}">
                                                {{ str_replace('_', ' ', $group) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" style="background-color:#fbfbfd;">
                                        @foreach ($items as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input child-check-{{ $group }}"
                                                    type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                    id="perm_{{ $permission->id }}">
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

                    <button type="submit" class="btn submit-btn"
                        style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Submit
                    </button>
                    <a href="{{ route('role.index') }}" class="btn me-2"
                        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
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
