
let activeFlyoutSection = null;
function closeSidebarFlyout() {
    const flyout = document.getElementById('sidebarFlyout');
    if (flyout) flyout.classList.remove('show');
    activeFlyoutSection = null;
}

function toggleSidebarFlyout(headerEl) {
    if (activeFlyoutSection === headerEl) {
        closeSidebarFlyout();
        return;
    }
    activeFlyoutSection = headerEl;
    let flyout = document.getElementById('sidebarFlyout');
    if (!flyout) {
        flyout = document.createElement('div');
        flyout.id = 'sidebarFlyout';
        flyout.className = 'sidebar-flyout';
        document.body.appendChild(flyout);
    }
    const title = headerEl.querySelector('.nav-section-title').textContent;
    const itemsContainer = headerEl.nextElementSibling;
    flyout.innerHTML = `
        <div class="sidebar-flyout-title">${title}</div>
        ${itemsContainer ? itemsContainer.innerHTML : ''}
    `;
    const r = headerEl.getBoundingClientRect();
    flyout.style.left = `${r.right + 10}px`;
    flyout.style.top = `${r.top}px`;
    flyout.classList.add('show');
    flyout.querySelectorAll('a').forEach((link) => {
        link.classList.add('sidebar-flyout-item');
        link.addEventListener('click', () => closeSidebarFlyout());
    });
}

function initStaticSidebar() {
    document.querySelectorAll('.nav-section-header').forEach((header) => {
        const itemsContainer = header.nextElementSibling;
        const isGroup = itemsContainer && itemsContainer.classList.contains('nav-items-container');
        if (!isGroup) return;

        header.addEventListener('click', (e) => {
            e.stopPropagation();
            const sidebarEl = document.getElementById('sidebar');
            if (sidebarEl && sidebarEl.classList.contains('collapsed')) {
                toggleSidebarFlyout(header);
                return;
            }
            const isOpen = itemsContainer.style.display === 'block';
            document.querySelectorAll('.nav-items-container').forEach((c) => c.style.display = 'none');
            document.querySelectorAll('.nav-chevron').forEach((chev) => chev.style.transform = 'rotate(0deg)');
            if (!isOpen) {
                itemsContainer.style.display = 'block';
                header.querySelector('.nav-chevron').style.transform = 'rotate(180deg)';
            }
        });
    });

    document.querySelectorAll('.nav-item-link').forEach((link) => {
        link.addEventListener('click', (e) => {
            if (!link.getAttribute('href') || link.getAttribute('href') === '#') {
                e.preventDefault();
            }
            document.querySelectorAll('.nav-item-link, .nav-section-header').forEach(el => el.classList.remove('active'));
            link.classList.add('active');
            if (window.innerWidth <= 767) {
                document.getElementById('sidebar').classList.remove('show');
                document.getElementById('mobileOverlay').classList.remove('show');
            }
        });
    });
}

document.addEventListener('click', (e) => {
    if (!activeFlyoutSection) return;
    if (e.target.closest('.sidebar-flyout') || e.target.closest('.nav-section-header')) return;
    closeSidebarFlyout();
});

window.addEventListener('scroll', closeSidebarFlyout, true);

// --- Functional filter mock data (Sales/Purchase Overview period select) ---
const OVERVIEW_DATA = {
    sales: {
        today: { currentLabel: 'Today (BDT)', prevLabel: 'Yesterday (BDT)', current: 4850200, prev: 4210500, labels: ['12 AM', '4 AM', '8 AM', '12 PM', '4 PM', '8 PM', '11 PM'], data: [2, 4, 3, 6, 5, 8, 7] },
        yesterday: { currentLabel: 'Yesterday (BDT)', prevLabel: 'Day Before (BDT)', current: 4210500, prev: 3980200, labels: ['12 AM', '4 AM', '8 AM', '12 PM', '4 PM', '8 PM', '11 PM'], data: [2, 3, 3, 5, 4, 7, 6] },
        thisWeek: { currentLabel: 'This Week (BDT)', prevLabel: 'Last Week (BDT)', current: 28640300, prev: 26150800, labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'], data: [3, 4, 5, 6, 5, 7, 8] },
        thisMonth: { currentLabel: 'This Month (BDT)', prevLabel: 'Last Month (BDT)', current: 125845750, prev: 111903200, labels: ['01 May', '06 May', '11 May', '16 May', '21 May', '26 May', '31 May'], data: [12, 19, 15, 25, 22, 30, 28] },
        last3Months: { currentLabel: 'This Quarter (BDT)', prevLabel: 'Last Quarter (BDT)', current: 341969050, prev: 298540200, labels: ['Mar', 'Apr', 'May'], data: [104, 111, 126] },
        thisYear: { currentLabel: 'This Year (BDT)', prevLabel: 'Last Year (BDT)', current: 1285420600, prev: 1102760400, labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], data: [220, 235, 260, 111, 126] },
    },
    purchase: {
        today: { currentLabel: 'Today (BDT)', prevLabel: 'Yesterday (BDT)', current: 3620400, prev: 3050100, labels: ['12 AM', '4 AM', '8 AM', '12 PM', '4 PM', '8 PM', '11 PM'], data: [2, 3, 2, 5, 4, 6, 5] },
        yesterday: { currentLabel: 'Yesterday (BDT)', prevLabel: 'Day Before (BDT)', current: 3050100, prev: 2880700, labels: ['12 AM', '4 AM', '8 AM', '12 PM', '4 PM', '8 PM', '11 PM'], data: [1, 2, 2, 4, 3, 5, 4] },
        thisWeek: { currentLabel: 'This Week (BDT)', prevLabel: 'Last Week (BDT)', current: 21480700, prev: 19860200, labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'], data: [2, 3, 4, 5, 4, 6, 7] },
        thisMonth: { currentLabel: 'This Month (BDT)', prevLabel: 'Last Month (BDT)', current: 98260500, prev: 90820400, labels: ['01 May', '06 May', '11 May', '16 May', '21 May', '26 May', '31 May'], data: [10, 15, 12, 20, 18, 24, 22] },
        last3Months: { currentLabel: 'This Quarter (BDT)', prevLabel: 'Last Quarter (BDT)', current: 274221200, prev: 241880900, labels: ['Mar', 'Apr', 'May'], data: [85, 91, 98] },
        thisYear: { currentLabel: 'This Year (BDT)', prevLabel: 'Last Year (BDT)', current: 982450300, prev: 861220700, labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], data: [175, 188, 205, 91, 98] },
    },
};

const PRESET_KEY_MAP = {
    'Today': 'today',
    'Yesterday': 'yesterday',
    'This Week': 'thisWeek',
    'This Month': 'thisMonth',
    'This Quarter': 'last3Months',
    'This Year': 'thisYear',
};

const KPI_DATA = {
    today: {
        sales: { value: 4850200, trend: 'up', pct: '+3.15% vs Yesterday' },
        purchase: { value: 3620400, trend: 'up', pct: '+2.40% vs Yesterday' },
        inventory: { value: 215478300, trend: 'up', pct: '+0.18% vs Yesterday' },
        profit: { value: 685200, trend: 'up', pct: '+2.05% vs Yesterday', currency: true },
        receivable: { value: 2150600, trend: 'down', pct: '-1.10% vs Yesterday' },
        payable: { value: 1180300, trend: 'down', pct: '-0.85% vs Yesterday' },
    },
    yesterday: {
        sales: { value: 4210500, trend: 'up', pct: '+1.80% vs Day Before' },
        purchase: { value: 3050100, trend: 'up', pct: '+1.25% vs Day Before' },
        inventory: { value: 215120800, trend: 'up', pct: '+0.10% vs Day Before' },
        profit: { value: 612400, trend: 'up', pct: '+1.40% vs Day Before', currency: true },
        receivable: { value: 2210900, trend: 'down', pct: '-0.95% vs Day Before' },
        payable: { value: 1205600, trend: 'down', pct: '-0.60% vs Day Before' },
    },
    thisWeek: {
        sales: { value: 28640300, trend: 'up', pct: '+6.80% vs Last Week' },
        purchase: { value: 21480700, trend: 'up', pct: '+5.10% vs Last Week' },
        inventory: { value: 216050400, trend: 'up', pct: '+0.85% vs Last Week' },
        profit: { value: 3845200, trend: 'up', pct: '+5.60% vs Last Week', currency: true },
        receivable: { value: 30120400, trend: 'down', pct: '-2.40% vs Last Week' },
        payable: { value: 17980500, trend: 'down', pct: '-3.10% vs Last Week' },
    },
    thisMonth: {
        sales: { value: 125845750, trend: 'up', pct: '+12.45% vs Last Month' },
        purchase: { value: 98260500, trend: 'up', pct: '+8.21% vs Last Month' },
        inventory: { value: 215478300, trend: 'up', pct: '+6.32% vs Last Month' },
        profit: { value: 1845000, trend: 'up', pct: '+8.21% vs Last Month', currency: true },
        receivable: { value: 32745600, trend: 'down', pct: '-4.32% vs Last Month' },
        payable: { value: 18654200, trend: 'down', pct: '-6.15% vs Last Month' },
    },
    last3Months: {
        sales: { value: 341969050, trend: 'up', pct: '+14.55% vs Last Quarter' },
        purchase: { value: 274221200, trend: 'up', pct: '+13.38% vs Last Quarter' },
        inventory: { value: 218760200, trend: 'up', pct: '+7.90% vs Last Quarter' },
        profit: { value: 5120400, trend: 'up', pct: '+11.20% vs Last Quarter', currency: true },
        receivable: { value: 35480900, trend: 'down', pct: '-3.10% vs Last Quarter' },
        payable: { value: 20140700, trend: 'down', pct: '-5.40% vs Last Quarter' },
    },
    thisYear: {
        sales: { value: 1285420600, trend: 'up', pct: '+16.56% vs Last Year' },
        purchase: { value: 982450300, trend: 'up', pct: '+14.06% vs Last Year' },
        inventory: { value: 225300600, trend: 'up', pct: '+9.45% vs Last Year' },
        profit: { value: 18650200, trend: 'up', pct: '+13.80% vs Last Year', currency: true },
        receivable: { value: 42180300, trend: 'down', pct: '-2.85% vs Last Year' },
        payable: { value: 24560900, trend: 'down', pct: '-4.90% vs Last Year' },
    },
};

const PRODUCT_FACTORS = {
    'All Products': 1,
    'Tyre': 0.72,
    'Bearing': 0.18,
    'Lubrication': 0.10,
};

const BRANCH_FACTORS = {
    'All Branch': 1,
    'Head Office': 0.52,
    'Chattogram Branch': 0.28,
    'Sylhet Branch': 0.20,
};

let currentPresetLabel = 'Today';
let currentProduct = 'All Products';
let currentBranch = 'All Branch';

function getCombinedFactor() {
    return (PRODUCT_FACTORS[currentProduct] ?? 1) * (BRANCH_FACTORS[currentBranch] ?? 1);
}

function formatBDT(num) {
    return Math.round(num).toLocaleString('en-US');
}

function updateOverviewCard(type, period, factor) {
    const base = OVERVIEW_DATA[type] && OVERVIEW_DATA[type][period];
    if (!base) return;
    document.getElementById(`${type}CurrentLabel`).textContent = base.currentLabel;
    document.getElementById(`${type}PrevLabel`).textContent = base.prevLabel;
    document.getElementById(`${type}CurrentValue`).textContent = formatBDT(base.current * factor);
    document.getElementById(`${type}PrevValue`).textContent = formatBDT(base.prev * factor);
    const chart = type === 'sales' ? salesChartInstance : purchaseChartInstance;
    if (chart) {
        chart.data.labels = base.labels;
        chart.data.datasets[0].data = base.data.map((v) => +(v * factor).toFixed(1));
        chart.update();
    }
}

function updateKpiCard(prefix, entry, factor) {
    if (!entry) return;
    const valueEl = document.getElementById(`${prefix}Value`);
    const trendEl = document.getElementById(`${prefix}Trend`);
    if (valueEl) valueEl.textContent = (entry.currency ? '৳' : '') + formatBDT(entry.value * factor);
    if (trendEl) {
        trendEl.classList.remove('up', 'down');
        trendEl.classList.add(entry.trend);
        trendEl.innerHTML = `<i class="bi bi-arrow-${entry.trend}"></i><span>${entry.pct}</span>`;
    }
}

function applyFilters() {
    const key = PRESET_KEY_MAP[currentPresetLabel] || 'today';
    const factor = getCombinedFactor();
    updateOverviewCard('sales', key, factor);
    updateOverviewCard('purchase', key, factor);
    const kpi = KPI_DATA[key];
    if (!kpi) return;
    updateKpiCard('totalSales', kpi.sales, factor);
    updateKpiCard('totalPurchase', kpi.purchase, factor);
    updateKpiCard('inventoryValue', kpi.inventory, factor);
    updateKpiCard('grossProfit', kpi.profit, factor);
    updateKpiCard('receivable', kpi.receivable, factor);
    updateKpiCard('payable', kpi.payable, factor);
}

function updateDashboardForPreset(presetLabel) {
    currentPresetLabel = presetLabel;
    applyFilters();
}

let salesChartInstance, purchaseChartInstance;

function buildLineConfig(entry, borderColor, backgroundColor) {
    return {
        type: 'line',
        data: {
            labels: entry.labels,
            datasets: [{
                label: 'Value',
                data: entry.data,
                borderColor,
                backgroundColor,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: borderColor,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => v + 'M' }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    };
}

function initCharts() {
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) salesChartInstance = new Chart(salesCtx, buildLineConfig(OVERVIEW_DATA.sales.thisMonth, '#3b82f6', 'rgba(59, 130, 246, 0.1)'));
    const purchaseCtx = document.getElementById('purchaseChart');
    if (purchaseCtx) purchaseChartInstance = new Chart(purchaseCtx, buildLineConfig(OVERVIEW_DATA.purchase.thisMonth, '#10b981', 'rgba(16, 185, 129, 0.1)'));
    const inventoryCtx = document.getElementById('inventoryChart');
    if (inventoryCtx) {
        new Chart(inventoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Reserved', 'In-Transit', 'Damage', 'Warranty'],
                datasets: [{ data: [34256, 5120, 3450, 1250, 2180], backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
        });
    }
    const agingCtx = document.getElementById('agingChart');
    if (agingCtx) {
        new Chart(agingCtx, {
            type: 'doughnut',
            data: {
                labels: ['0-90 Days', '91-180 Days', '181-365 Days', '1-2 Years', '2+ Years'],
                datasets: [{ data: [38, 26, 17, 11, 8], backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
        });
    }
}

function formatDate(isoDate) {
    const d = new Date(isoDate);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${String(d.getDate()).padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

function initTopbarFilters() {
    const applyBtn = document.getElementById('applyDateRange');
    if (applyBtn) {
        applyBtn.addEventListener('click', () => {
            const from = document.getElementById('dateFrom').value;
            const to = document.getElementById('dateTo').value;
            if (from && to) {
                document.getElementById('dateRangeText').textContent = `${formatDate(from)} - ${formatDate(to)}`;
                document.querySelectorAll('.date-preset-option').forEach(o => o.classList.remove('active'));
                document.getElementById('customRangeToggle').classList.add('active');
                applyFilters();
            }
            const dropdownEl = document.getElementById('dateRangeBtn');
            bootstrap.Dropdown.getOrCreateInstance(dropdownEl).hide();
        });
    }
    document.querySelectorAll('.date-preset-option').forEach((opt) => {
        opt.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.date-preset-option').forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
            if (opt.id === 'customRangeToggle') {
                document.getElementById('customRangeInputs').classList.toggle('show');
                return;
            }
            document.getElementById('customRangeInputs').classList.remove('show');
            document.getElementById('dateRangeText').textContent = opt.dataset.preset;
            updateDashboardForPreset(opt.dataset.preset);
            const dropdownEl = document.getElementById('dateRangeBtn');
            bootstrap.Dropdown.getOrCreateInstance(dropdownEl).hide();
        });
    });
    document.querySelectorAll('.product-option').forEach((opt) => {
        opt.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('productText').textContent = opt.dataset.product;
            currentProduct = opt.dataset.product;
            applyFilters();
        });
    });
    document.querySelectorAll('.branch-option').forEach((opt) => {
        opt.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('branchText').textContent = opt.dataset.branch;
            currentBranch = opt.dataset.branch;
            applyFilters();
        });
    });
}

function initSidebarTooltips() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    const tip = document.createElement('div');
    tip.className = 'sidebar-tooltip';
    document.body.appendChild(tip);
    sidebar.addEventListener('mouseover', (e) => {
        const target = e.target.closest('[data-tip]');
        if (!target || !sidebar.classList.contains('collapsed')) return;
        const role = target.getAttribute('data-tip-role');
        if (role) {
            tip.innerHTML = `<strong>${target.getAttribute('data-tip')}</strong><span>${role}</span>`;
        } else {
            tip.textContent = target.getAttribute('data-tip');
        }
        const r = target.getBoundingClientRect();
        tip.style.left = `${r.right + 12}px`;
        tip.style.top = `${r.top + r.height / 2}px`;
        tip.classList.add('show');
    });
    sidebar.addEventListener('mouseout', (e) => {
        const target = e.target.closest('[data-tip]');
        const toEl = e.relatedTarget;
        if (target && (!toEl || !target.contains(toEl))) {
            tip.classList.remove('show');
        }
    });
    window.addEventListener('scroll', () => tip.classList.remove('show'), true);
}

function initSidebarToggle() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('topbarSidebarToggle');
    const menuToggle = document.getElementById('menuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            closeSidebarFlyout();
        });
    }
    if (menuToggle && mobileOverlay) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            mobileOverlay.classList.toggle('show');
        });
        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            mobileOverlay.classList.remove('show');
        });
    }
}

function initMobileFilterPlacement() {
    const topbarCenter = document.querySelector('.topbar-center');
    const topbarLeft = document.querySelector('.topbar-left');
    const mobileFilterBar = document.getElementById('mobileFilterBar');
    if (!topbarCenter || !topbarLeft || !mobileFilterBar) return;
    const mq = window.matchMedia('(max-width: 767px)');
    function placeFilters(e) {
        if (e.matches) {
            mobileFilterBar.appendChild(topbarCenter);
        } else {
            topbarLeft.insertAdjacentElement('afterend', topbarCenter);
        }
    }
    placeFilters(mq);
    mq.addEventListener('change', placeFilters);
}

function initFullscreen() {
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    if (!fullscreenBtn) return;
    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => console.log(`Error attempting to enable fullscreen: ${err.message}`));
            fullscreenBtn.innerHTML = '<i class="bi bi-arrows-angle-contract"></i>';
        } else {
            document.exitFullscreen();
            fullscreenBtn.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
        }
    });
    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) fullscreenBtn.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
    });
}

function initLogout() {
    const logoutModalEl = document.getElementById('logoutModal');
    if (!logoutModalEl) return;
    const logoutModal = new bootstrap.Modal(logoutModalEl);
    document.querySelectorAll('.logout-btn').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.show();
        });
    });
    document.getElementById('confirmLogoutBtn').addEventListener('click', () => {
        window.location.href = 'login.html';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initStaticSidebar();
    initCharts();
    initSidebarToggle();
    initSidebarTooltips();
    initFullscreen();
    initTopbarFilters();
    initMobileFilterPlacement();
    initLogout();
    updateDashboardForPreset('Today');
});
