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

<div id="kt_app_sidebar"
    class="app-sidebar flex-column"
    data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="225px"
    data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6"
        id="kt_app_sidebar_logo"
        style="justify-content: start!important">

        @php
            $company = \App\Models\Application::first();
            $companyLogo = $company ? $company->logo : '';
            $companyName = $company ? $company->company_name : '';
        @endphp

        <a href="{{ route('dashboard') }}">
            <img alt="Logo"
                src="{{ asset('image/' . $companyLogo) }}"
                class="h-55px app-sidebar-logo-default" />

            <img alt="Logo"
                src="{{ asset('image/' . $companyLogo) }}"
                class="h-20px app-sidebar-logo-minimize" />
        </a>

        {{-- <h2 style="color: white; margin-left:10px">
            {{ $companyName }}
        </h2> --}}

        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
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
        <div id="kt_app_sidebar_menu_wrapper"
            class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true"
            data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu"
            data-kt-scroll-offset="5px"
            data-kt-scroll-save-state="true">

            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3"
                id="kt_app_sidebar_menu"
                data-kt-menu="true"
                data-kt-menu-expand="false">


                <!--begin::Dashboard-->
                <div class="menu-item">

                    <a class="menu-link
                        {{ request()->routeIs('dashboard')
                            ? 'customer-button-background-color active'
                            : '' }}"
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
                <!--end::Dashboard-->


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
                {{-- USER MANAGEMENT --}}
                {{-- ===================================================== --}}

                @if (
                    auth()->user()->can('user.view') ||
                    auth()->user()->can('role.view')
                )

                    @php
                        $userManagementActive =
                            request()->routeIs('user.index') ||
                            request()->routeIs('user.create') ||
                            request()->routeIs('user.edit') ||
                            request()->routeIs('role.index') ||
                            request()->routeIs('role.create') ||
                            request()->routeIs('role.edit');

                        $userManagementClass = $userManagementActive
                            ? 'custom-show'
                            : '';
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
                            @if (auth()->user()->can('user.list'))

                                <div class="menu-item">

                                    <a class="menu-link
                                        {{ request()->routeIs('user.index')
                                            ? 'active button-active'
                                            : '' }}"
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
                                        {{ request()->routeIs('role.index')
                                            ? 'active button-active'
                                            : '' }}"
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

                @if (
                    auth()->user()->can('system_settings.view') ||
                    auth()->user()->can('wing.list')
                )

                    @php
                        $settingsActive =
                            request()->routeIs('applications.index') ||
                            request()->routeIs('social.icon.index') ||
                            request()->routeIs('banner.index') ||
                            request()->routeIs('wing.index') ||
                            request()->routeIs('wing.create') ||
                            request()->routeIs('wing.edit');

                        $settingsClass = $settingsActive
                            ? 'custom-show'
                            : '';
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
                                        {{ request()->routeIs('applications.index')
                                            ? 'active button-active'
                                            : '' }}"
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
                                        {{ request()->routeIs('wing.index')
                                            ? 'active button-active'
                                            : '' }}"
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
