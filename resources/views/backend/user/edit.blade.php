@extends('layouts.backend')
@section('content')

@section('title')
    Edit User
@endsection

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-1" style="color:#1e1e2d; font-weight:700;">
                <i class="bi bi-pencil-square me-2" style="color:#4361ee;"></i>
                Edit User
            </h4>
            <p class="mb-0 text-muted" style="font-size:13px;">Update the details for this user</p>
        </div>
        <a href="{{ route('user.index') }}" class="btn"
            style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card" style="border-radius:12px; border:none;">
        <div class="card-body" style="padding:24px; background-color:#fbfbfd; border-radius:12px;">
            <form id="userEditForm" method="POST" enctype="multipart/form-data"
                action="{{ route('user.update', $data->id) }}">
                @csrf
                @method('put')

                <div class="p-4 mb-3" style="background:#fff; border:1px solid #eef0f2; border-radius:10px;">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Full Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter full name" value="{{ $data->name }}"
                                style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <div class="invalid-feedback name-error"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email" value="{{ $data->email }}"
                                style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <div class="invalid-feedback email-error"></div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Leave blank to keep current password"
                                style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <div class="invalid-feedback password-error"></div>
                        </div>

                        <!-- User Type -->
                        <div class="col-md-6 mb-3">
                            <label for="user_type" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                User Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="user_type" name="user_type"
                                style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                                <option value="">Select type</option>
                                @foreach ($userTypes as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ $data->user_type == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback user_type-error"></div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="role" name="role[]" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $data->roles->pluck('name')->contains($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Ctrl/Cmd chepe multiple select korun</small>
                            <div class="invalid-feedback role-error"></div>
                        </div>

                        <!-- Wing assignment -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Assign Wing(s)
                            </label>
                            <small class="text-muted d-block mb-2">Supply Chain Incharge der jonno prasongik</small>

                            @php
                                $assignedWingIds = $data->wings->pluck('id')->toArray();
                            @endphp

                            <div class="p-3 mb-2" style="background:#fff; border:1px solid #eef0f2; border-radius:8px;">
                                <div class="form-check form-switch mb-2 pb-2" style="border-bottom:1px solid #eef0f2;">
                                    <input class="form-check-input" type="checkbox" role="switch" id="wing_all"
                                        style="width:2.5em; height:1.3em; cursor:pointer;">
                                    <label class="form-check-label fw-bold" for="wing_all"
                                        style="margin-left: 20px; cursor:pointer;">All
                                        Wings</label>
                                </div>

                                <div class="form-check form-switch mb-2 pb-2">
                                    @forelse ($wings as $wing)
                                        <div class="wing-item form-check form-switch mb-2">
                                            <input class="form-check-input wing-switch" type="checkbox" role="switch"
                                                name="wing[]" value="{{ $wing->id }}"
                                                id="wing_{{ $wing->id }}"
                                                {{ in_array($wing->id, $assignedWingIds) ? 'checked' : '' }}
                                                style="width:2.5em; height:1.3em; cursor:pointer;">
                                            <label class="form-check-label" for="wing_{{ $wing->id }}"
                                                style="cursor:pointer; margin-left: 20px;">{{ $wing->name }}</label>
                                        </div>
                                    @empty
                                        <span class="text-muted" style="font-size:13px;">No wings found.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="invalid-feedback wing-error"></div>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label fw-bold" style="color:#1e1e2d; font-size:13px;">
                                Profile Picture
                            </label>
                            <input type="file" class="form-control" id="image" name="image"
                                accept="image/*"
                                style="border:1px solid #dfe2e8; border-radius:8px; padding:11px 14px; font-size:14px;">
                            <div class="invalid-feedback image-error"></div>

                            @if ($data->image)
                                <div class="mt-2">
                                    <img src="{{ asset('image/' . $data->image) }}" width="90" height="90"
                                        style="object-fit:cover; border-radius:8px; border:1px solid #eef0f2;"
                                        alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('user.index') }}" class="btn me-2"
                        style="border:1px solid #dfe2e8; color:#4a4a5a; border-radius:8px; padding:8px 18px; font-size:14px;">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn submit-btn"
                        style="background-color:#4361ee; color:#fff; border-radius:8px; padding:8px 20px; font-size:14px; font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .wing-item {
        border-radius: 8px;
        padding: 6px 10px;
        transition: background-color .15s ease, box-shadow .15s ease;
    }


    .wing-item.wing-item-active .form-check-label {
        color: #4361ee;
        font-weight: 600;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#role').select2({
            placeholder: "Select role(s)",
            width: '100%'
        });

        // "All Wings" switch <-> individual wing switches
        function syncWingAllSwitch() {
            let total = $('.wing-switch').length;
            let checked = $('.wing-switch:checked').length;
            $('#wing_all').prop('checked', total > 0 && total === checked);
        }

        function applyWingItemActiveState() {
            $('.wing-switch').each(function() {
                $(this).closest('.wing-item').toggleClass('wing-item-active', $(this).is(':checked'));
            });
        }

        $('#wing_all').on('change', function() {
            $('.wing-switch').prop('checked', $(this).is(':checked'));
            applyWingItemActiveState();
        });

        $(document).on('change', '.wing-switch', function() {
            syncWingAllSwitch();
            applyWingItemActiveState();
        });

        syncWingAllSwitch();
        applyWingItemActiveState();

        $("#userEditForm").on("submit", function(e) {
            e.preventDefault();
            showLoading();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".invalid-feedback").text("").hide();
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        window.location.href =
                            "{{ route('user.index') }}?added-successfully=" +
                            encodeURIComponent(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(field) {
                            $("." + field + "-error").text(errors[field][0]).show();
                        });
                        setTimeout(function() {
                            $(".invalid-feedback").fadeOut();
                        }, 3000);
                    } else if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Access Denied',
                            text: xhr.responseJSON?.message ||
                                'You do not have permission to perform this action!',
                            confirmButtonColor: '#FF4C29'
                        });
                    } else if (xhr.status === 404) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: xhr.responseJSON?.message || 'User not found!',
                            confirmButtonColor: '#FF4C29'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Something went wrong! Please try again.',
                            confirmButtonColor: '#FF4C29'
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
