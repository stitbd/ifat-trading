<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic
Product Version: 8.1.8
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="" />
    <title> @yield('title') || </title>
    <meta charset="utf-8" />
    <meta name="description"
        content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar,  flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="stylesheet" href="{{ asset('font/kalpurush ANSI.ttf') }}">
    @php
        $company = \App\Models\Application::first();
        $companyLogo = $company ? $company->fav_icon : '';
    @endphp
    <link rel="shortcut icon" href="{{ asset('image/' . $companyLogo) }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />


    {{-- <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" /> --}}
    <!--end::Vendor Stylesheets-->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->



    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js@1.11.1/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.11.1/src/toastify.min.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <style>
        /* body {
            font-family: 'Kalpurush', sans-serif !important;
        } */

        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            /* Make sure it appears on top */
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
            /* Change the color to white */
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
            background: #1E1E2D;
            /* Dark background for admin panel */
            color: #2A2A3C;
            /* Light text for contrast */
            /* border-radius: 8px; */
            overflow: hidden;
        }

        /* Header Styling */
        table.dataTable thead th {
            background: #2A2A3C;
            /* Slightly lighter dark shade */
            color: #ffffff;
            font-weight: bold;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #3A3A4D;
        }

        /* Table Body Styling */
        table.dataTable tbody tr {
            transition: all 0.3s ease-in-out;
            border-bottom: 1px solid #3A3A4D;
            /* Dark border */
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #fffff;
            /* Slightly lighter dark row */
        }

        table.dataTable tbody tr:hover {
            background: #33334D;
            /* Hover effect */
        }

        /* Table Cells */
        table.dataTable th,
        table.dataTable td {
            padding: 10px;
            font-size: 14px;
        }

        /* Image Styling */


        /* Responsive Design */
        @media (max-width: 768px) {
            table.dataTable {
                font-size: 12px;
            }

            table.dataTable th,
            table.dataTable td {
                padding: 8px;
            }
        }

        .btn-success {
            background: #00802b !important;
        }

        .btn-check:active+.btn.btn-success,
        .btn-check:checked+.btn.btn-success,
        .btn.btn-success.active,
        .btn.btn-success.show,
        .btn.btn-success:active:not(.btn-active),
        .btn.btn-success:focus:not(.btn-active),
        .btn.btn-success:hover:not(.btn-active),
        .show>.btn.btn-success {
            background: #00802b !important;
        }

        .btn-danger {
            background: #e60000 !important;
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
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
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('backend.partials._header')
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('backend.partials._sidebar')
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">

                        <!--begin::Content-->
                        <div class="">
                            <div id="loading">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="backdrop"></div>
                            @yield('content')
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->

                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->


    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
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


    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->



    <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
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

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/new-target.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('sweetalert::alert')
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
