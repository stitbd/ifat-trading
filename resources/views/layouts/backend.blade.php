<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') || </title>
    @php
        $company = \App\Models\Application::first();
        $companyLogo = $company ? $company->fav_icon : '';
    @endphp
    <link rel="icon" href="{{ asset('image/application/' . $companyLogo) }}"type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('new-assets/css/style.css') }}" />


    {{-- new link  --}}

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js@1.11.1/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.11.1/src/toastify.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /*
|--------------------------------------------------------------------------
| Action buttons wrapper
|--------------------------------------------------------------------------
*/

        .forword-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            height: 32px;
            border-radius: 6px;
            border: 1px solid #e2e4e9;
            background: #fff;
            color: #555;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .forword-icon-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        /* View */
        .action-view {
            color: #4361ee;
            border-color: #d7ddfb;
        }

        .action-view:hover {
            background: #eef2ff;
        }

        /* Print */
        .action-print {
            color: #6c757d;
            border-color: #e2e4e9;
        }

        .action-print:hover {
            background: #f1f3f5;
        }

        /* Export */
        .action-export {
            color: #198754;
            border-color: #cdebd9;
        }

        .action-export:hover {
            background: #eafaf1;
        }

        /* Edit */
        .action-edit {
            color: #ff9f1c;
            border-color: #ffe6c2;
        }

        .action-edit:hover {
            background: #fff6e9;
        }

        /* Delete */
        .action-delete {
            color: #e53e3e;
            border-color: #fbd5d5;
        }

        .action-delete:hover {
            background: #fdecea;
        }


        /*
|--------------------------------------------------------------------------
| Workflow buttons (Forward / Approve / Reject / Generate CS)
| - icon + text pashapashi, alada alada color
|--------------------------------------------------------------------------
*/

        .action-workflow-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 32px;
            padding: 0 12px;
            border-radius: 6px;
            border: 1px solid transparent;
            font-size: 12.5px;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .action-workflow-btn i {
            font-size: 14px;
        }

        .action-workflow-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* Forward button (GU -> SCI) - blue */
        .forward-btn {
            background: #eef2ff;
            color: #4361ee;
            border-color: #d7ddfb;
        }

        .forward-btn:hover {
            background: #4361ee;
            color: #fff;
        }

        /* Approve buttons (SCI / OM / MD) - green */
        .sci-approve-btn,
        .om-approve-btn,
        .md-approve-btn {
            background: #eafaf1;
            color: #198754;
            border-color: #cdebd9;
        }

        .sci-approve-btn:hover,
        .om-approve-btn:hover,
        .md-approve-btn:hover {
            background: #198754;
            color: #fff !important;
        }

        /* Reject buttons (SCI / OM / MD) - red */
        .sci-reject-btn,
        .om-reject-btn,
        .md-reject-btn {
            background: #fdecea;
            color: #e53e3e;
            border-color: #fbd5d5;
        }

        .sci-reject-btn:hover,
        .om-reject-btn:hover,
        .md-reject-btn:hover {
            background: #e53e3e;
            color: #fff !important;
        }

        /* Generate CS button - purple/violet, alada standout color */
        .generate-cs-btn {
            background: #f3ecfd;
            color: #7c3aed;
            border-color: #e2d2fb;
        }

        .generate-cs-btn:hover {
            background: #7c3aed;
            color: #fff;
        }


        /*
|--------------------------------------------------------------------------
| Status pill (Status column)
|--------------------------------------------------------------------------
*/

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill i {
            font-size: 8px;
        }

        .status-active {
            background: #eafaf1;
            color: #198754;
        }

        .status-inactive {
            background: #f1f3f5;
            color: #6c757d;
        }

        .status-rejected {
            background: #fdecea;
            color: #e53e3e;
        }


        /*
|--------------------------------------------------------------------------
| History column
|--------------------------------------------------------------------------
*/

        .requisition-history-cell {
            max-width: 220px;
            font-size: 12px;
            line-height: 1.6;
            color: #555;
            white-space: normal;
        }

        /* DataTables length & search controls */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 16px;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #4a4a5a;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dfe2e8 !important;
            border-radius: 8px !important;
            padding: 6px 28px 6px 12px !important;
            font-size: 13px !important;
            font-weight: 600;
            color: #1e1e2d;
            background-color: #fff !important;
            cursor: pointer;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #4361ee !important;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dfe2e8 !important;
            border-radius: 8px !important;
            padding: 8px 14px !important;
            font-size: 13px !important;
            color: #1e1e2d;
            margin-left: 8px !important;
            min-width: 220px;
            box-shadow: none !important;
            transition: border-color 0.15s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4361ee !important;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input::placeholder {
            color: #9aa0ac;
        }

        /* row that holds length + search side by side */
        .dataTables_wrapper .row:first-child {
            align-items: center;
            margin-bottom: 4px;
        }

        .dataTables_wrapper .dataTables_filter label {
            position: relative;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding-left: 34px !important;
        }

        .dataTables_wrapper .dataTables_filter::before {
            content: "\f52a";
            /* bootstrap-icons search glyph */
            font-family: "bootstrap-icons";
            position: absolute;
            margin-left: 20px;
            margin-top: 9px;
            color: #9aa0ac;
            font-size: 13px;
            pointer-events: none;
        }

        /* Export/Print buttons */
        .dt-buttons {
            display: flex;
            gap: 8px;
        }

        .dt-buttons .dt-button {
            border: 1px solid #dfe2e8 !important;
            background: #fff !important;
            color: #4a4a5a !important;
            border-radius: 8px !important;
            padding: 8px 14px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            box-shadow: none !important;
        }

        .dt-buttons .dt-button:hover {
            background: #f5f6fa !important;
            color: #1e1e2d !important;
        }

        .dt-buttons .buttons-excel {
            color: #17c653 !important;
            border-color: #d1fadf !important;
        }

        .dt-buttons .buttons-print {
            color: #1b84ff !important;
            border-color: #d6e0ff !important;
        }

        /* ==== Reusable Admin UI Components (shared across all pages) ==== */
        i.bi,
        i[class*=" fa-"],
        i[class*=" fonticon-"],
        i[class*=" la-"],
        i[class^=fa-],
        i[class^=fonticon-],
        i[class^=la-] {
            line-height: 1;
            font-size: 1rem;
            color: inherit !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #f1f3f6;
        }

        /* Page Header */
        .admin-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .admin-page-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-page-header-title .icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #eaf0ff;
            color: #4361ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .admin-page-header-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e1e2d;
            margin: 0;
        }

        /* Buttons */
        .btn-admin-primary {
            background: #4361ee;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-admin-primary:hover {
            background: #3651d4;
            color: #fff;

        }

        /* Card wrapper for tables/content */
        .admin-card {
            background: #fff;
            border: 1px solid #eef0f2;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .admin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .admin-card-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: #1e1e2d;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* DataTable styling */
        /* DataTable styling */
        .admin-card table.dataTable thead th {
            background: #f5f6fa !important;
            color: #7e8299 !important;
            font-size: 12px !important;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 600 !important;
            border-bottom: 1px solid #eef0f2 !important;
            padding: 14px !important;
        }

        .admin-card table.dataTable tbody td {
            padding: 14px !important;
            font-size: 14px !important;
            color: #2a2a3c !important;
            border-bottom: 1px solid #f2f3f5 !important;
            vertical-align: middle;
            background: #fff !important;
        }

        .admin-card table.dataTable tbody tr:hover td {
            background: #fbfbfd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4361ee !important;
            border-color: #4361ee !important;
            color: #fff !important;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
        }

        /* Serial number badge */
        .serial-badge {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #eaf0ff;
            color: #4361ee;
            font-weight: 700;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill i {
            font-size: 6px;
        }

        .status-active {
            background: #e7f9ee;
            color: #12b76a;
        }

        .status-inactive {
            background: #fdeceb;
            color: #f04438;
        }

        /* Action icon buttons — outline style only, no fill background */
        .action-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1.5px solid;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            padding: 0;
            box-shadow: none;
            transition: all 0.15s ease;
        }

        .action-view {
            color: #1b84ff;
            border-color: #1b84ff;
        }

        .action-edit {
            color: #198754;
            border-color: #198754;
            text-decoration: none;
        }

        .action-export {
            color: #198754;
            border-color: #198754;
        }

        .action-delete {
            color: #f1416c;
            border-color: #f1416c;
        }

        .action-status-on {
            color: #17c653;
            border-color: #17c653;
        }

        .action-status-off {
            color: #98a2b3;
            border-color: #98a2b3;
        }

        .action-icon-btn:hover {
            opacity: 0.8;
        }

        /* Modal */
        .admin-modal-content {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .admin-modal-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eef0f2;
            padding: 20px 24px;
            align-items: flex-start;
        }

        .admin-modal-header h5 {
            color: #1e1e2d;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 4px;
        }

        .admin-modal-header p {
            color: #8a8a9a;
            font-size: 13px;
            margin-bottom: 0;
        }

        .admin-modal-body {
            padding: 24px;
            background-color: #fbfbfd;
        }

        .admin-modal-section {
            background: #fff;
            border: 1px solid #eef0f2;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 12px;
        }

        .admin-modal-section .section-badge {
            background: #eaf0ff;
            color: #4361ee;
            font-weight: 600;
            font-size: 12px;
            padding: 5px 10px;
        }

        .admin-modal-section label {
            color: #1e1e2d;
            font-weight: 700;
            font-size: 13px;
        }

        .admin-modal-section .form-control,
        .admin-modal-section .form-select {
            border: 1px solid #dfe2e8;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
        }

        .admin-modal-footer {
            background-color: #ffffff;
            border-top: 1px solid #eef0f2;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-modal-cancel {
            border: 1px solid #dfe2e8;
            color: #4a4a5a;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 14px;
        }

        .btn-modal-submit {
            background-color: #4361ee;
            color: #fff;
            border-radius: 8px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
        }

        /* ==== Layout essentials (kept, still used by JS) ==== */
        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        #backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }

        .spinner-border {
            color: white;
        }

        @media (max-width: 768px) {

            table.dataTable th,
            table.dataTable td {
                padding: 8px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    @include('backend.partials._sidebar')
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="main-wrapper">
        @include('backend.partials._header')

        <main class="main-content">
            <div class="">
                <div id="loading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="backdrop"></div>
                @yield('content')
            </div>
            <footer class="footer">
                <div class="footer-left">© 2026 ST TYRE ERP. All rights reserved.</div>
                <div class="footer-center">Version 1.0.0</div>
                <div class="footer-right">Developed by <a target="blank" href="www.stitbd.com">STIT BD</a></div>
            </footer>
        </main>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav d-lg-none" id="bottomNav">
        <a href="#" class="bottom-nav-item active">
            <i class="bi bi-house-door-fill"></i>
            <span>Home</span>
        </a>
        <a href="purchase-add.html" class="bottom-nav-item">
            <i class="bi bi-cart3"></i>
            <span>Purchase</span>
        </a>
        <a href="invoice-add.html" class="bottom-nav-fab" aria-label="New Sales Invoice">
            <i class="bi bi-plus-lg"></i>
        </a>
        <a href="stock-management.html" class="bottom-nav-item">
            <i class="bi bi-box-seam"></i>
            <span>Inventory</span>
        </a>
        <div class="dropup">
            <a href="#" class="bottom-nav-item dropdown-toggle no-caret" id="bottomNavProfileBtn"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img class="bottom-nav-avatar" src="images/profile.jpg" alt="Profile"
                    onerror="this.src='https://ui-avatars.com/api/?name=MD+Shaokat&background=2563eb&color=fff'">
                <span>Profile</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="bottomNavProfileBtn">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger logout-btn" href="#"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content logout-modal-content">
                <div class="modal-body text-center pt-4">
                    <div class="logout-modal-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <h5 class="mt-3 mb-2">Log out of your account?</h5>
                    <p class="text-muted mb-0">You'll need to sign in again to access the dashboard.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmLogoutBtn">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden logout form -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        $(document).on('click', '#confirmLogoutBtn', function() {
            document.getElementById('logoutForm').submit();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Check if success flag is set in localStorage
            if (localStorage.getItem('formSuccess') === 'true') {
                Toastify({
                    text: "Data added successfully",
                    duration: 5000, // 5 seconds
                    backgroundColor: "green",
                    close: true, // Show close button
                    gravity: "top", // Position: top or bottom
                    position: "center", // Position: left, right, top, bottom
                }).showToast();

                // Remove success flag from localStorage after displaying the toast
                localStorage.removeItem('formSuccess');
            }
            if (localStorage.getItem('UpdateformSuccess') === 'true') {
                Toastify({
                    text: "Data updated successfully",
                    duration: 5000, // 5 seconds
                    backgroundColor: "green",
                    close: true, // Show close button
                    gravity: "top", // Position: top or bottom
                    position: "center", // Position: left, right, top, bottom
                }).showToast();

                // Remove success flag from localStorage after displaying the toast
                localStorage.removeItem('UpdateformSuccess');
            }
        });
        @if (session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000, // 3 seconds
                backgroundColor: "red",
                close: true, // Show close button
                gravity: "top", // Position: top or bottom
                position: "left", // Position: left, right, top, bottom
            }).showToast();
        @endif
        @if (session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000, // 3 seconds
                backgroundColor: "green",
                close: true, // Show close button
                gravity: "top", // Position: top or bottom
                position: "center", // Position: left, right, top, bottom
            }).showToast();
        @endif
    </script>
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-download-btn');
            if (btn) {
                // ২ সেকেন্ড পর জোর করে loading বন্ধ করে দাও
                setTimeout(function() {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('backdrop').style.display = 'none';
                }, 500);
            }
        });
    </script>
    @if (session('permission_error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: "{{ session('permission_error') }}",
                    confirmButtonColor: '#FF4C29'
                });
            });
        </script>
    @endif
    <script>
        // Show the loading spinner when navigating to the page
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Handle page load from the back-forward cache
                document.getElementById('loading').style.display = 'none';
                document.getElementById('backdrop').style.display = 'none';
            }
        });

        // Hide the loading spinner when the page finishes loading
        window.addEventListener('load', function() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('backdrop').style.display = 'none';
        });

        // Show the loading spinner
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('backdrop').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('backdrop').style.display = 'none';
        }

        // Event listener for page navigation (including back button)
        window.addEventListener('beforeunload', function() {
            showLoading();
        });

        // Optional: Attach the loading spinner to form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                showLoading();
            });
        }
    </script>
    <script>
        $(document).on('submit', 'form', function(e) {
            e.stopPropagation();
        });
    </script>
    @include('sweetalert::alert')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script> --}}

    <!--end::Vendors Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('new-assets/js/script.js') }}"></script>
</body>

</html>
