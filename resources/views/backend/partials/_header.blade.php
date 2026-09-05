<header class="topbar">
    <div class="topbar-left">
        <button class="icon-btn topbar-sidebar-toggle" id="topbarSidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
            <i class="bi bi-list"></i>
        </button>
        <div class="breadcrumb">
            <span>Home</span>
            <span class="separator">></span>
            <span class="current">Dashboard</span>
        </div>
    </div>

    <div class="topbar-brand-mobile">
        <img src="images/logo.png" alt="ST TYRE" onerror="this.style.display='none'">
    </div>

    <div class="topbar-center">
        <div class="dropdown">
            <div class="date-range dropdown-toggle" id="dateRangeBtn" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                <i class="bi bi-calendar"></i>
                <span id="dateRangeText">Today</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="dropdown-menu date-range-menu" aria-labelledby="dateRangeBtn">
                <ul class="date-preset-list">
                    <li><a href="#" class="date-preset-option active" data-preset="Today">Today</a>
                    </li>
                    <li><a href="#" class="date-preset-option" data-preset="Yesterday">Yesterday</a>
                    </li>
                    <li><a href="#" class="date-preset-option" data-preset="This Week">This Week</a>
                    </li>
                    <li><a href="#" class="date-preset-option" data-preset="This Month">This Month</a>
                    </li>
                    <li><a href="#" class="date-preset-option" data-preset="This Quarter">This
                            Quarter</a></li>
                    <li><a href="#" class="date-preset-option" data-preset="This Year">This Year</a>
                    </li>
                </ul>
                <div class="date-preset-divider"></div>
                <a href="#" class="date-preset-option" id="customRangeToggle">Custom Range</a>
                <div class="custom-range-inputs p-3" id="customRangeInputs">
                    <div class="mb-2">
                        <label class="form-label small mb-1">From</label>
                        <input type="date" class="form-control form-control-sm" id="dateFrom" value="2026-05-01">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">To</label>
                        <input type="date" class="form-control form-control-sm" id="dateTo" value="2026-05-31">
                    </div>
                    <button type="button" class="btn btn-primary btn-sm w-100" id="applyDateRange">Apply</button>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <div class="product-select dropdown-toggle" id="productBtn" data-bs-toggle="dropdown"
                data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                <i class="bi bi-box-seam"></i>
                <span id="productText">All Products</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <ul class="dropdown-menu" aria-labelledby="productBtn">
                <li><a class="dropdown-item product-option" href="#" data-product="All Products">All
                        Products</a>
                </li>
                <li><a class="dropdown-item product-option" href="#" data-product="Tyre">Tyre</a></li>
                <li><a class="dropdown-item product-option" href="#" data-product="Bearing">Bearing</a>
                </li>
                <li><a class="dropdown-item product-option" href="#" data-product="Lubrication">Lubricationh</a>
                </li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="branch-select dropdown-toggle" id="branchBtn" data-bs-toggle="dropdown"
                data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                <i class="bi bi-building"></i>
                <span id="branchText">All Branch</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <ul class="dropdown-menu" aria-labelledby="branchBtn">
                <li><a class="dropdown-item branch-option" href="#" data-branch="All Branch">All
                        Branch</a></li>
                <li><a class="dropdown-item branch-option" href="#" data-branch="Head Office">Head
                        Office</a>
                </li>
                <li><a class="dropdown-item branch-option" href="#" data-branch="Chattogram Branch">Chattogram
                        Branch</a></li>
                <li><a class="dropdown-item branch-option" href="#" data-branch="Sylhet Branch">Sylhet
                        Branch</a></li>
            </ul>
        </div>
    </div>
    @php
        $user = auth()->user();
    @endphp

    <div class="topbar-right">
        <button class="icon-btn" id="topbarSearchBtn" aria-label="Search">
            <i class="bi bi-search"></i>
        </button>
        <button class="icon-btn" id="fullscreenBtn" aria-label="Toggle Fullscreen">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>
        <button class="icon-btn" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            <span class="notification-badge">12</span>
        </button>
        <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ $user->profile_picture ? asset('image/' . $user->profile_picture) : asset('user.avif') }}"
                alt="User">
            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                {{-- <div class="user-role">{{ $user->role }}</div> --}}
            </div>
            <i class="bi bi-chevron-down"></i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger logout-btn" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
    </div>
</header>
