<!--begin::Sidebar-->
<style>
    [data-kt-app-layout=dark-sidebar] .app-sidebar .menu .menu-item .menu-link.button-active {
        background: rgba(255, 255, 255, .9) !important;
    }

    [data-kt-app-layout=dark-sidebar] .app-sidebar .menu .menu-item .menu-link.button-active .menu-title {
        color: #343a40 !important;
    }

    [data-kt-app-layout=dark-sidebar] .app-sidebar .menu .menu-item.custom-show>.menu-link {
        background: #FF4C29 !important;
    }

    .customer-button-background-color {
        background: #FF4C29 !important;
    }

    .menu-sub-indention .menu-sub:not([data-popper-placement]) {
        margin-left: 0px !important;
    }

    .menu-sub-indention .menu-item .menu-item .menu-link.active {
        margin-right: 0px !important;
    }
</style>

@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo" style="justify-content: start!important">

        @php
            $company = \App\Models\Application::first();
            $companyLogo = $company ? $company->logo : '';
            $companyName = $company ? $company->company_name : '';
        @endphp

        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="{{ asset('image/application/' . $companyLogo) }}"
                class="h-55px app-sidebar-logo-default" />

            <img alt="Logo" src="{{ asset('image/application/' . $companyLogo) }}"
                class="h-20px app-sidebar-logo-minimize" />
        </a>

        {{-- <h2 style="color: white; margin-left:10px">
            {{ $companyName }}
        </h2> --}}

        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">

            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>

        </div>

    </div>
    <!--end::Logo-->


    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">

        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">

            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">


                <!--begin::Dashboard-->
                <div class="menu-item">

                    <a class="menu-link
                        {{ request()->routeIs('dashboard') ? 'customer-button-background-color active' : '' }}"
                        href="{{ route('dashboard') }}">

                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>

                        <span class="menu-title">
                            Dashboard
                        </span>

                    </a>

                </div>


                <!--begin::Pages-->
                <div class="menu-item pt-5">

                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">
                            Pages
                        </span>
                    </div>

                </div>
                <!--end::Pages-->
                {{-- ===================================================== --}}
                {{-- PRODUCT INVENTORY --}}
                {{-- ===================================================== --}}

                @if (auth()->user()->can('category.view') ||
                        auth()->user()->can('subcategory.view') ||
                        auth()->user()->can('brand.view') ||
                        auth()->user()->can('product.view') ||
                        auth()->user()->can('product_type.view') ||
                        auth()->user()->can('product_size.view'))

                    @php
                        $productInventoryActive =
                            request()->routeIs('category.index') ||
                            request()->routeIs('subcategory.index') ||
                            request()->routeIs('brand.index') ||
                            request()->routeIs('product.index') ||
                            request()->routeIs('product-type.index') ||
                            request()->routeIs('product-type.create') ||
                            request()->routeIs('product-type.edit') ||
                            request()->routeIs('product-size.index') ||
                            request()->routeIs('product-size.create') ||
                            request()->routeIs('product-size.edit');

                        $productInventoryClass = $productInventoryActive ? 'custom-show' : '';
                    @endphp

                    <div data-kt-menu-trigger="click"
                        class="menu-item
                        {{ $productInventoryActive ? 'show' : '' }}
                        {{ $productInventoryClass }}
                        menu-accordion">

                        <!--begin::Product Inventory Link-->
                        <span class="menu-link">

                            <span class="menu-icon">
                                <i class="fa-solid fa-boxes-stacked fs-2"></i>
                            </span>

                            <span class="menu-title">
                                Product Inventory
                            </span>

                            <span class="menu-arrow"></span>

                        </span>
                        <!--end::Product Inventory Link-->

                        <!--begin::Product Inventory Sub-->
                        <div class="menu-sub menu-sub-accordion">

                            {{-- Category --}}
                            @if (auth()->user()->can('category.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('category.index') ? 'active button-active' : '' }}"
                                        href="{{ route('category.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Category
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- SubCategory --}}
                            @if (auth()->user()->can('subcategory.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('subcategory.index') ? 'active button-active' : '' }}"
                                        href="{{ route('subcategory.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            SubCategory
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- Brand --}}
                            @if (auth()->user()->can('brand.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('brand.index') ? 'active button-active' : '' }}"
                                        href="{{ route('brand.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Brand
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- Product --}}
                            @if (auth()->user()->can('product.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('product.index') ? 'active button-active' : '' }}"
                                        href="{{ route('product.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Product
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- Product Type --}}
                            @if (auth()->user()->can('product_type.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('product-type.index') ? 'active button-active' : '' }}"
                                        href="{{ route('product-type.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Product Type
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- Product Size --}}
                            @if (auth()->user()->can('product_size.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                        {{ request()->routeIs('product-size.index') ? 'active button-active' : '' }}"
                                        href="{{ route('product-size.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Product Size
                                        </span>
                                    </a>
                                </div>
                            @endif

                        </div>
                        <!--end::Product Inventory Sub-->

                    </div>

                @endif



                @if (auth()->user()->can('requisition.view') || auth()->user()->can('requisition.create'))

                    @php
                        $productInventoryActive =
                            request()->routeIs('requisition.index') ||
                            request()->routeIs('requisition.create') ||
                            request()->routeIs('requisition.edit');

                        $productInventoryClass = $productInventoryActive ? 'custom-show' : '';
                    @endphp

                    <div data-kt-menu-trigger="click"
                        class="menu-item
                            {{ $productInventoryActive ? 'show' : '' }}
                            {{ $productInventoryClass }}
                            menu-accordion">

                        <!--begin::Product Inventory Link-->
                        <span class="menu-link">

                            <span class="menu-icon">
                                <i class="fa-solid fa-boxes-stacked fs-2"></i>
                            </span>

                            <span class="menu-title">
                                Requisition
                            </span>

                            <span class="menu-arrow"></span>

                        </span>
                        <!--end::Product Inventory Link-->

                        <!--begin::Product Inventory Sub-->
                        <div class="menu-sub menu-sub-accordion">

                            {{-- requisition --}}
                            @if (auth()->user()->can('requisition.create'))
                                <div class="menu-item">
                                    <a class="menu-link
                                        {{ request()->routeIs('requisition.create') ? 'active button-active' : '' }}"
                                        href="{{ route('requisition.create') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Add Requisition
                                        </span>
                                    </a>
                                </div>
                            @endif
                            @if (auth()->user()->can('requisition.view'))
                                <div class="menu-item">
                                    <a class="menu-link
                                        {{ request()->routeIs('requisition.index') ? 'active button-active' : '' }}"
                                        href="{{ route('requisition.index') }}">
                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>
                                        <span class="menu-title">
                                            Requisition List
                                        </span>
                                    </a>
                                </div>
                            @endif





                        </div>
                        <!--end::Product Inventory Sub-->

                    </div>

                @endif

                {{-- ===================================================== --}}
                {{-- USER MANAGEMENT --}}
                {{-- ===================================================== --}}

                @if (auth()->user()->can('user.view') || auth()->user()->can('role.view'))

                    @php
                        $userManagementActive =
                            request()->routeIs('user.index') ||
                            request()->routeIs('user.create') ||
                            request()->routeIs('user.edit') ||
                            request()->routeIs('role.index') ||
                            request()->routeIs('role.create') ||
                            request()->routeIs('role.edit');

                        $userManagementClass = $userManagementActive ? 'custom-show' : '';
                    @endphp


                    <div data-kt-menu-trigger="click"
                        class="menu-item
                        {{ $userManagementActive ? 'show' : '' }}
                        {{ $userManagementClass }}
                        menu-accordion">


                        <!--begin::User Management Link-->
                        <span class="menu-link">

                            <span class="menu-icon">
                                <i class="fa-solid fa-users-gear fs-2"></i>
                            </span>

                            <span class="menu-title">
                                User Management
                            </span>

                            <span class="menu-arrow"></span>

                        </span>
                        <!--end::User Management Link-->


                        <!--begin::User Management Sub-->
                        <div class="menu-sub menu-sub-accordion">


                            {{-- User --}}
                            @if (auth()->user()->can('user.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('user.index') ? 'active button-active' : '' }}"
                                        href="{{ route('user.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            User
                                        </span>

                                    </a>

                                </div>
                            @endif


                            {{-- Roles --}}
                            @if (auth()->user()->can('role.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('role.index') ? 'active button-active' : '' }}"
                                        href="{{ route('role.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Roles
                                        </span>

                                    </a>

                                </div>
                            @endif


                        </div>
                        <!--end::User Management Sub-->

                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- SETTINGS --}}
                {{-- ===================================================== --}}

                @if (auth()->user()->can('system_settings.view') || auth()->user()->can('wing.list'))

                    @php
                        $settingsActive =
                            request()->routeIs('applications.index') ||
                            request()->routeIs('social.icon.index') ||
                            request()->routeIs('banner.index') ||
                            request()->routeIs('wing.index') ||
                            request()->routeIs('wing.create') ||
                            request()->routeIs('wing.edit') ||
                            request()->routeIs('vehicle-type.index') ||
                            request()->routeIs('vehicle-type.create') ||
                            request()->routeIs('vehicle-type.edit') ||
                            request()->routeIs('vat-percentage.index') ||
                            request()->routeIs('vat-percentage.create') ||
                            request()->routeIs('vat-percentage.edit') ||
                            request()->routeIs('warranty-period.index') ||
                            request()->routeIs('warranty-period.create') ||
                            request()->routeIs('warranty-period.edit') ||
                            request()->routeIs('manufacturer.index') ||
                            request()->routeIs('manufacturer.create') ||
                            request()->routeIs('manufacturer.edit') ||
                            request()->routeIs('warehouse.index') ||
                            request()->routeIs('warehouse.create') ||
                            request()->routeIs('warehouse.edit') ||
                            request()->routeIs('country-of-origin.index') ||
                            request()->routeIs('country-of-origin.create') ||
                            request()->routeIs('country-of-origin.edit');

                        $settingsClass = $settingsActive ? 'custom-show' : '';
                    @endphp


                    <div data-kt-menu-trigger="click"
                        class="menu-item
                        {{ $settingsActive ? 'show' : '' }}
                        {{ $settingsClass }}
                        menu-accordion">


                        <!--begin::Settings Link-->
                        <span class="menu-link">

                            <span class="menu-icon">
                                <i class="fa-solid fa-gear fs-2"></i>
                            </span>

                            <span class="menu-title">
                                Settings
                            </span>

                            <span class="menu-arrow"></span>

                        </span>
                        <!--end::Settings Link-->


                        <!--begin::Settings Sub-->
                        <div class="menu-sub menu-sub-accordion">


                            {{-- System Settings --}}
                            @if (auth()->user()->can('system_settings.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('applications.index') ? 'active button-active' : '' }}"
                                        href="{{ route('applications.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            System-Settings
                                        </span>

                                    </a>

                                </div>
                            @endif


                            {{-- Wings --}}
                            @if (auth()->user()->can('wing.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('wing.index') ? 'active button-active' : '' }}"
                                        href="{{ route('wing.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Wings
                                        </span>

                                    </a>

                                </div>
                            @endif

                            {{-- code from pranto --}}


                            @if (auth()->user()->can('manufacturer.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('manufacturer.index') ? 'active button-active' : '' }}"
                                        href="{{ route('manufacturer.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Manufacturer
                                        </span>

                                    </a>

                                </div>
                            @endif
                            @if (auth()->user()->can('country_of_origin.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('country-of-origin.index') ? 'active button-active' : '' }}"
                                        href="{{ route('country-of-origin.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Country-of-origin
                                        </span>

                                    </a>

                                </div>
                            @endif
                            @if (auth()->user()->can('vehicle_type.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('vehicle-type.index') ? 'active button-active' : '' }}"
                                        href="{{ route('vehicle-type.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Vehicle Type
                                        </span>

                                    </a>

                                </div>
                            @endif

                            @if (auth()->user()->can('vat_percentage.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('vat-percentage.index') ? 'active button-active' : '' }}"
                                        href="{{ route('vat-percentage.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Vat-Percentage
                                        </span>

                                    </a>

                                </div>
                            @endif
                            @if (auth()->user()->can('warranty_period.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('warranty-period.index') ? 'active button-active' : '' }}"
                                        href="{{ route('warranty-period.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Warranty Period
                                        </span>

                                    </a>

                                </div>
                            @endif
                            @if (auth()->user()->can('warehouse.view'))
                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('warehouse.index') ? 'active button-active' : '' }}"
                                        href="{{ route('warehouse.index') }}">

                                        <span class="menu-bullet">
                                            <i class="far fa-circle nav-icon"></i>
                                        </span>

                                        <span class="menu-title">
                                            Warehouse
                                        </span>

                                    </a>

                                </div>
                            @endif
                        </div>
                        <!--end::Settings Sub-->

                    </div>

                @endif


            </div>
            <!--end::Menu-->

        </div>
        <!--end::Menu wrapper-->

    </div>
    <!--end::sidebar menu-->

</div>
<!--end::Sidebar-->
