@extends('layouts.backend')

@section('content')
    <style>
        .my-button {
            color: white;
            border: 1px solid white !important;
            padding: 4px 15px !important;
        }

        .my-button:hover {
            color: black;
            border-color: black;
        }

        .card-icon {
            color: white !important;
            font-size: 30px !important;
        }

        .card-background-icon {
            font-size: 90px !important;
            color: white !important;
        }
    </style>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10 mt-4">
                {{--
                <div class="col-md-4 col-xl-3">
                    <div class="card shadow-sm border-0 position-relative overflow-hidden"
                        style="background: #ff3b3b; color: white; padding: 25px; border-radius: 6px;">

                        <!-- Background Icon -->
                        <div class="position-absolute" style="top: -5px; right: -5px; opacity: 0.1;">
                            <i class="fas fa-images fa-8x"></i>
                        </div>

                        <!-- Card Content -->
                        <div class="card-body" style="padding: 5px!important">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-uppercase fw-bold mb-3 text-white"
                                    style="letter-spacing: 0.5px; font-size: 14px;">
                                    Total Services
                                </h6>

                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="fw-bold mb-3 text-white" style="font-size: 36px;">
                                    {{ optional($services)->count() ?? 0 }}
                                </h2>
                                <i class="fas fa-tools fa-2x card-icon"></i>
                            </div>

                            <!-- View Button -->
                            <a href="{{ route('services.index') }}" class="btn btn-outline-light  my-button">
                                View
                            </a>
                        </div>
                        <div class="position-absolute" style="bottom: -15px; right: -15px; opacity: 0.1;">
                            <i class="fas fa-wrench card-background-icon"></i>
                        </div>
                    </div>
                </div> --}}
                <h2>Comming Soon...</h2>
            </div>
        </div>
        <!--end::Content container-->
    </div>
@endsection
