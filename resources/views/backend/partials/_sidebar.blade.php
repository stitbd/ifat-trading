@php
    $company = \App\Models\Application::first();
    $companyLogo = $company ? $company->fav_icon : '';

    // ---- Route groups for each collapsible sidebar section ----
    // Add/remove patterns here if you add new routes under a section.
    $inventoryRoutePatterns = [
        'category.*',
        'subcategory.*',
        'brand.*',
        'product.*',
        'product-type.*',
        'product-size.*',
    ];
    $requisitionRoutePatterns = ['requisition.*'];
    $userMgmtRoutePatterns = ['user.*', 'role.*'];
    $systemSettingsRoutePatterns = [
        'applications.*',
        'wing.*',
        'manufacturer.*',
        'country-of-origin.*',
        'vehicle-type.*',
        'vat-percentage.*',
        'warranty-period.*',
        'warehouse.*',
    ];

    $isDashboardActive = request()->routeIs('dashboard');
    $isInventoryActive = request()->routeIs($inventoryRoutePatterns);
    $isRequisitionActive = request()->routeIs($requisitionRoutePatterns);
    $isUserMgmtActive = request()->routeIs($userMgmtRoutePatterns);
    $isSystemSettingsActive = request()->routeIs($systemSettingsRoutePatterns);
@endphp
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-logo">
            <img src="{{ asset('image/application/' . $companyLogo) }}" alt="ST TYRE" class="brand-logo-full"
                onerror="this.style.display='none'">
            <img src="{{ asset('image/application/' . $companyLogo) }}" alt="ST TYRE" class="brand-logo-mini"
                onerror="this.style.display='none'">
        </div>
    </div>

    <nav class="sidebar-nav" id="sidebarNav">
        <div class="nav-section">
            <a class="nav-section-header {{ $isDashboardActive ? 'active' : '' }}" href="{{ route('dashboard') }}"
                data-tip="Dashboard">
                <span class="nav-icon"><i class="bi bi-house-door-fill"></i></span>
                <span class="nav-section-title">Dashboard</span>
            </a>
        </div>

        @if (auth()->user()->can('category.view') ||
                auth()->user()->can('subcategory.view') ||
                auth()->user()->can('brand.view') ||
                auth()->user()->can('product.view') ||
                auth()->user()->can('product_type.view') ||
                auth()->user()->can('product_size.view'))
            <div class="nav-section">
                <div class="nav-section-header {{ $isInventoryActive ? 'active' : '' }}" data-tip="Inventory"
                    onclick="toggleNavSection(this)">
                    <span class="nav-icon"><i class="bi bi-archive"></i></span>
                    <span class="nav-section-title">Inventory</span>
                    <i class="bi bi-chevron-down nav-chevron"
                        style="{{ $isInventoryActive ? 'transform: rotate(180deg);' : '' }}"></i>
                </div>
                <div class="nav-items-container" style="display: {{ $isInventoryActive ? 'block' : 'none' }};">
                    @if (auth()->user()->can('category.view'))
                        <a class="nav-item-link {{ request()->routeIs('category.*') ? 'active' : '' }}"
                            href="{{ route('category.index') }}">
                            <span class="nav-icon"><i class="bi bi-tags"></i></span>
                            <span class="nav-label">Categories</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('subcategory.view'))
                        <a class="nav-item-link {{ request()->routeIs('subcategory.*') ? 'active' : '' }}"
                            href="{{ route('subcategory.index') }}">
                            <span class="nav-icon"><i class="bi bi-tag"></i></span>
                            <span class="nav-label">Subcategories</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('brand.view'))
                        <a class="nav-item-link {{ request()->routeIs('brand.*') ? 'active' : '' }}"
                            href="{{ route('brand.index') }}">
                            <span class="nav-icon"><i class="bi bi-award"></i></span>
                            <span class="nav-label">Brand</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('product.view'))
                        <a class="nav-item-link {{ request()->routeIs('product.*') ? 'active' : '' }}"
                            href="{{ route('product.index') }}">
                            <span class="nav-icon"><i class="bi bi-box-seam"></i></span>
                            <span class="nav-label">Product</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('product_type.view'))
                        <a class="nav-item-link {{ request()->routeIs('product-type.*') ? 'active' : '' }}"
                            href="{{ route('product-type.index') }}">
                            <span class="nav-icon"><i class="bi bi-diagram-3"></i></span>
                            <span class="nav-label">Product Type</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('product_size.view'))
                        <a class="nav-item-link {{ request()->routeIs('product-size.*') ? 'active' : '' }}"
                            href="{{ route('product-size.index') }}">
                            <span class="nav-icon"><i class="bi bi-rulers"></i></span>
                            <span class="nav-label">Product Size</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (auth()->user()->can('requisition.view') || auth()->user()->can('requisition.create'))
            <div class="nav-section">
                <div class="nav-section-header {{ $isRequisitionActive ? 'active' : '' }}" data-tip="Requisition"
                    onclick="toggleNavSection(this)">
                    <span class="nav-icon"><i class="bi bi-clipboard-check"></i></span>
                    <span class="nav-section-title">Requisition</span>
                    <i class="bi bi-chevron-down nav-chevron"
                        style="{{ $isRequisitionActive ? 'transform: rotate(180deg);' : '' }}"></i>
                </div>
                <div class="nav-items-container" style="display: {{ $isRequisitionActive ? 'block' : 'none' }};">
                    @if (auth()->user()->can('requisition.create'))
                        <a class="nav-item-link {{ request()->routeIs('requisition.create') ? 'active' : '' }}"
                            href="{{ route('requisition.create') }}">
                            <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                            <span class="nav-label">Add Requisition</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('requisition.view'))
                        <a class="nav-item-link {{ request()->routeIs('requisition.index') || request()->routeIs('requisition.show') || request()->routeIs('requisition.edit') ? 'active' : '' }}"
                            href="{{ route('requisition.index') }}">
                            <span class="nav-icon"><i class="bi bi-list-check"></i></span>
                            <span class="nav-label">Requisition List</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (auth()->user()->can('user.view') || auth()->user()->can('role.view'))
            <div class="nav-section">
                <div class="nav-section-header {{ $isUserMgmtActive ? 'active' : '' }}" data-tip="User Management"
                    onclick="toggleNavSection(this)">
                    <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
                    <span class="nav-section-title">User Management</span>
                    <i class="bi bi-chevron-down nav-chevron"
                        style="{{ $isUserMgmtActive ? 'transform: rotate(180deg);' : '' }}"></i>
                </div>
                <div class="nav-items-container" style="display: {{ $isUserMgmtActive ? 'block' : 'none' }};">
                    @if (auth()->user()->can('user.view'))
                        <a class="nav-item-link {{ request()->routeIs('user.*') ? 'active' : '' }}"
                            href="{{ route('user.index') }}">
                            <span class="nav-icon"><i class="bi bi-people"></i></span>
                            <span class="nav-label">Users</span>
                        </a>
                    @endif
                    @if (auth()->user()->can('role.view'))
                        <a class="nav-item-link {{ request()->routeIs('role.*') ? 'active' : '' }}"
                            href="{{ route('role.index') }}">
                            <span class="nav-icon"><i class="bi bi-shield-lock"></i></span>
                            <span class="nav-label">Roles &amp; Permissions</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if (auth()->user()->can('system_settings.view') ||
                auth()->user()->can('wing.view') ||
                auth()->user()->can('manufacturer.view') ||
                auth()->user()->can('country_of_origin.view') ||
                auth()->user()->can('vehicle_type.view') ||
                auth()->user()->can('vat_percentage.view') ||
                auth()->user()->can('warranty_period.view') ||
                auth()->user()->can('warehouse.view'))

            <div class="nav-section">
                <div class="nav-section-header {{ $isSystemSettingsActive ? 'active' : '' }}"
                    data-tip="System Settings" onclick="toggleNavSection(this)">
                    <span class="nav-icon"><i class="bi bi-gear"></i></span>
                    <span class="nav-section-title">System Settings</span>
                    <i class="bi bi-chevron-down nav-chevron"
                        style="{{ $isSystemSettingsActive ? 'transform: rotate(180deg);' : '' }}"></i>
                </div>

                <div class="nav-items-container" style="display: {{ $isSystemSettingsActive ? 'block' : 'none' }};">

                    @if (auth()->user()->can('system_settings.view'))
                        <a class="nav-item-link {{ request()->routeIs('applications.*') ? 'active' : '' }}"
                            href="{{ route('applications.index') }}">
                            <span class="nav-icon"><i class="bi bi-sliders"></i></span>
                            <span class="nav-label">System Settings</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('wing.view'))
                        <a class="nav-item-link {{ request()->routeIs('wing.*') ? 'active' : '' }}"
                            href="{{ route('wing.index') }}">
                            <span class="nav-icon"><i class="bi bi-building"></i></span>
                            <span class="nav-label">Wings</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('manufacturer.view'))
                        <a class="nav-item-link {{ request()->routeIs('manufacturer.*') ? 'active' : '' }}"
                            href="{{ route('manufacturer.index') }}">
                            <span class="nav-icon"><i class="bi bi-tools"></i></span>
                            <span class="nav-label">Manufacturer</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('country_of_origin.view'))
                        <a class="nav-item-link {{ request()->routeIs('country-of-origin.*') ? 'active' : '' }}"
                            href="{{ route('country-of-origin.index') }}">
                            <span class="nav-icon"><i class="bi bi-globe"></i></span>
                            <span class="nav-label">Country of Origin</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('vehicle_type.view'))
                        <a class="nav-item-link {{ request()->routeIs('vehicle-type.*') ? 'active' : '' }}"
                            href="{{ route('vehicle-type.index') }}">
                            <span class="nav-icon"><i class="bi bi-truck"></i></span>
                            <span class="nav-label">Vehicle Type</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('vat_percentage.view'))
                        <a class="nav-item-link {{ request()->routeIs('vat-percentage.*') ? 'active' : '' }}"
                            href="{{ route('vat-percentage.index') }}">
                            <span class="nav-icon"><i class="bi bi-percent"></i></span>
                            <span class="nav-label">VAT Percentage</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('warranty_period.view'))
                        <a class="nav-item-link {{ request()->routeIs('warranty-period.*') ? 'active' : '' }}"
                            href="{{ route('warranty-period.index') }}">
                            <span class="nav-icon"><i class="bi bi-shield-check"></i></span>
                            <span class="nav-label">Warranty Period</span>
                        </a>
                    @endif

                    @if (auth()->user()->can('warehouse.view'))
                        <a class="nav-item-link {{ request()->routeIs('warehouse.*') ? 'active' : '' }}"
                            href="{{ route('warehouse.index') }}">
                            <span class="nav-icon"><i class="bi bi-house-gear"></i></span>
                            <span class="nav-label">Warehouse</span>
                        </a>
                    @endif

                </div>
            </div>
        @endif
    </nav>
    @php
        $user = auth()->user();
    @endphp
    <div class="sidebar-footer">
        <div class="sidebar-user" data-tip="MD. Shaokat Hossain" data-tip-role="Managing Director">
            <img src="{{ $user->profile_picture ? asset('image/' . $user->profile_picture) : asset('user.avif') }}"
                alt="User">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $user->name }}</div>
                {{-- <div class="online-status"><i class="bi bi-circle-fill"></i> Online</div> --}}
            </div>
        </div>
        <div class="sidebar-copyright">© 2014-2026 STITBD, All Rights Reserved</div>
    </div>
</aside>
