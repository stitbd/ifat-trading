@extends('layouts.backend')

@section('content')
    <div class="mobile-filter-bar" id="mobileFilterBar"></div>
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="bi bi-cart"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">TOTAL SALES (BDT)</div>
                <div class="kpi-value" id="totalSalesValue">125,845,750</div>
                <div class="kpi-trend up" id="totalSalesTrend"><i class="bi bi-arrow-up"></i><span>+12.45% vs
                        Last Month</span></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green"><i class="bi bi-bag"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">TOTAL PURCHASE (BDT)</div>
                <div class="kpi-value" id="totalPurchaseValue">98,260,500</div>
                <div class="kpi-trend up" id="totalPurchaseTrend"><i class="bi bi-arrow-up"></i><span>+8.21% vs
                        Last Month</span></div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon purple"><i class="bi bi-box"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">TOTAL INVENTORY VALUE</div>
                <div class="kpi-value" id="inventoryValueValue">215,478,300</div>
                <div class="kpi-trend up" id="inventoryValueTrend"><i class="bi bi-arrow-up"></i><span>+6.32% vs
                        Last Month</span>
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon orange"><i class="bi bi-people"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">TOTAL RECEIVABLE (BDT)</div>
                <div class="kpi-value" id="receivableValue">32,745,600</div>
                <div class="kpi-trend down" id="receivableTrend"><i class="bi bi-arrow-down"></i><span>-4.32% vs
                        Last
                        Month</span>
                </div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon red"><i class="bi bi-credit-card"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">TOTAL PAYABLE (BDT)</div>
                <div class="kpi-value" id="payableValue">18,654,200</div>
                <div class="kpi-trend down" id="payableTrend"><i class="bi bi-arrow-down"></i><span>-6.15% vs
                        Last
                        Month</span>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon blue"><i class="bi bi-graph-up"></i></span>
                    <h3 class="card-title">Sales Overview</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="overview-stats">
                    <div class="overview-stat">
                        <div class="overview-stat-label" id="salesCurrentLabel">This Month (BDT)</div>
                        <div class="overview-stat-value" id="salesCurrentValue">125,845,750</div>
                    </div>
                    <div class="overview-stat">
                        <div class="overview-stat-label" id="salesPrevLabel">Last Month (BDT)</div>
                        <div class="overview-stat-value" id="salesPrevValue">111,903,200</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon green"><i class="bi-bag"></i></span>
                    <h3 class="card-title">Purchase Overview</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="overview-stats">
                    <div class="overview-stat">
                        <div class="overview-stat-label" id="purchaseCurrentLabel">This Month (BDT)</div>
                        <div class="overview-stat-value" id="purchaseCurrentValue">98,260,500</div>
                    </div>
                    <div class="overview-stat">
                        <div class="overview-stat-label" id="purchasePrevLabel">Last Month (BDT)</div>
                        <div class="overview-stat-value" id="purchasePrevValue">90,820,400</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="purchaseChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon purple"><i class="bi-award"></i></span>
                    <h3 class="card-title">Top Selling Products</h3>
                </div>
                <a href="#" class="view-all-link">View All</a>
            </div>
            <div class="card-body">
                <div class="product-list">
                    <div class="product-item">
                        <div class="product-img-placeholder"><img src="images/products/01.jpg" alt="GOODRIDE 205/55R16"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="product-info">
                            <div class="product-name">GOODRIDE 205/55R16</div>
                            <div class="product-bar">
                                <div class="product-bar-fill" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="product-qty">2,850 Pcs</div>
                    </div>
                    <div class="product-item">
                        <div class="product-img-placeholder"><img src="images/products/02.jpg" alt="LINGLONG 195/65R15"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="product-info">
                            <div class="product-name">LINGLONG 195/65R15</div>
                            <div class="product-bar">
                                <div class="product-bar-fill" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="product-qty">2,320 Pcs</div>
                    </div>
                    <div class="product-item">
                        <div class="product-img-placeholder"><img src="images/products/03.jpg" alt="MAXXIS 185/65R14"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="product-info">
                            <div class="product-name">MAXXIS 185/65R14</div>
                            <div class="product-bar">
                                <div class="product-bar-fill" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="product-qty">1,980 Pcs</div>
                    </div>
                    <div class="product-item">
                        <div class="product-img-placeholder"><img src="images/products/04.jpg" alt="CEAT 215/60R16"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="product-info">
                            <div class="product-name">CEAT 215/60R16</div>
                            <div class="product-bar">
                                <div class="product-bar-fill" style="width: 55%"></div>
                            </div>
                        </div>
                        <div class="product-qty">1,650 Pcs</div>
                    </div>
                    <div class="product-item">
                        <div class="product-img-placeholder"><img src="images/products/05.jpg" alt="APOLLO 7.50R16 LT"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="product-info">
                            <div class="product-name">APOLLO 7.50R16 LT</div>
                            <div class="product-bar">
                                <div class="product-bar-fill" style="width: 48%"></div>
                            </div>
                        </div>
                        <div class="product-qty">1,420 Pcs</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon teal"><i class="bi-box-seam"></i></span>
                    <h3 class="card-title">Inventory Summary</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="inventory-grid">
                    <div class="donut-chart">
                        <canvas id="inventoryChart"></canvas>
                        <div class="donut-center">
                            <div class="donut-center-label">Total Qty</div>
                            <div class="donut-center-value">48,256</div>
                            <div class="donut-center-sub">Pcs</div>
                        </div>
                    </div>
                    <div class="inventory-stats">
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #10b981"></div>
                            <div class="stat-label">Available Stock</div>
                            <div class="stat-value">34,256 Pcs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #3b82f6"></div>
                            <div class="stat-label">Reserved Stock</div>
                            <div class="stat-value">5,120 Pcs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #f59e0b"></div>
                            <div class="stat-label">In-Transit Stock</div>
                            <div class="stat-value">3,450 Pcs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #ef4444"></div>
                            <div class="stat-label">Damage Stock</div>
                            <div class="stat-value">1,250 Pcs</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #8b5cf6"></div>
                            <div class="stat-label">Warranty Stock</div>
                            <div class="stat-value">2,180 Pcs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon orange"><i class="bi-hourglass-split"></i></span>
                    <h3 class="card-title">Stock Aging (DOT Wise)</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="inventory-grid">
                    <div class="donut-chart">
                        <canvas id="agingChart"></canvas>
                        <div class="donut-center">
                            <div class="donut-center-label">Total Qty</div>
                            <div class="donut-center-value">48,256</div>
                            <div class="donut-center-sub">Pcs</div>
                        </div>
                    </div>
                    <div class="inventory-stats">
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #10b981"></div>
                            <div class="stat-label">0 - 90 Days</div>
                            <div class="stat-value">18,450 Pcs (38%)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #3b82f6"></div>
                            <div class="stat-label">91 - 180 Days</div>
                            <div class="stat-value">12,750 Pcs (26%)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #f59e0b"></div>
                            <div class="stat-label">181 - 365 Days</div>
                            <div class="stat-value">8,420 Pcs (17%)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #ef4444"></div>
                            <div class="stat-label">1 - 2 Years</div>
                            <div class="stat-value">5,230 Pcs (11%)</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-dot" style="background: #8b5cf6"></div>
                            <div class="stat-label">2+ Years</div>
                            <div class="stat-value">3,406 Pcs (8%)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-4">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon red"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <h3 class="card-title">Low Stock Alerts</h3>
                </div>
                <a href="#" class="view-all-link">View All</a>
            </div>
            <div class="card-body">
                <div class="alert-list">
                    <div class="alert-item">
                        <div class="alert-img-placeholder"><img src="images/products/06.jpg" alt="GOODRIDE 175/70R13"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="alert-info">
                            <div class="alert-name">GOODRIDE 175/70R13</div>
                            <div class="alert-detail">Reorder Level: 200 Pcs</div>
                        </div>
                        <div class="alert-stock">
                            <div class="alert-current">45 Pcs</div>
                            <div class="alert-label">Current Stock</div>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="alert-img-placeholder"><img src="images/products/07.jpg" alt="LINGLONG 205/55R16"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="alert-info">
                            <div class="alert-name">LINGLONG 205/55R16</div>
                            <div class="alert-detail">Reorder Level: 150 Pcs</div>
                        </div>
                        <div class="alert-stock">
                            <div class="alert-current">60 Pcs</div>
                            <div class="alert-label">Current Stock</div>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="alert-img-placeholder"><img src="images/products/08.jpg" alt="CEAT 215/60R16"
                                onerror="this.outerHTML='<i class=\'bi bi-disc-fill\'></i>'"></div>
                        <div class="alert-info">
                            <div class="alert-name">CEAT 215/60R16</div>
                            <div class="alert-detail">Reorder Level: 100 Pcs</div>
                        </div>
                        <div class="alert-stock">
                            <div class="alert-current">25 Pcs</div>
                            <div class="alert-label">Current Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-8">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon blue"><i class="bi-activity"></i></span>
                    <h3 class="card-title">Recent Activities</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon green"><i class="bi bi-cart"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Purchase Order Created</div>
                            <div class="activity-desc">PO# PO-2605-0058 has been created to Rubber World
                                Ltd.
                            </div>
                            <div class="activity-meta">Today, 11:30 AM by Al Amin</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon purple"><i class="bi bi-arrow-left-right"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Stock Transfer</div>
                            <div class="activity-desc">Transfer# TR-2605-0021 from Head Office to Chattogram
                                Branch.</div>
                            <div class="activity-meta">Yesterday, 04:20 PM by Karim Uddin</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon blue"><i class="bi bi-truck"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Goods Received</div>
                            <div class="activity-desc">GRN# GRN-2605-0123 received from China Tyre Co. Ltd.
                            </div>
                            <div class="activity-meta">Today, 10:15 AM by Rasel Ahmed</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Damage Entry</div>
                            <div class="activity-desc">Damage entry# DMG-2605-0012 for 25 Pcs tyres.</div>
                            <div class="activity-meta">Yesterday, 03:05 PM by Sohel Rana</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon orange"><i class="bi bi-file-text"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Sales Invoice</div>
                            <div class="activity-desc">Invoice# SI-2605-0456 has been created for Rahman
                                Motors.
                            </div>
                            <div class="activity-meta">Today, 09:40 AM by Jannatul</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon blue"><i class="bi bi-sliders"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Stock Adjustment</div>
                            <div class="activity-desc">Adjustment# ADJ-2605-0015 has been completed.</div>
                            <div class="activity-meta">Yesterday, 02:30 PM by Al Amin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-span-4 quick-links-card">
            <div class="card-header">
                <div class="card-title-wrap">
                    <span class="card-title-icon purple"><i class="bi-lightning-charge"></i></span>
                    <h3 class="card-title">Quick Links</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="quick-links">
                    <a href="purchase-add.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #dbeafe; color: #3b82f6;"><i
                                class="bi bi-cart-plus"></i></div>
                        <div class="quick-link-label">New Purchase</div>
                    </a>
                    <a href="invoice-add.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #d1fae5; color: #10b981;"><i
                                class="bi bi-bag"></i></div>
                        <div class="quick-link-label">New Sales</div>
                    </a>
                    <a href="stock-management.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #e9d5ff; color: #8b5cf6;"><i
                                class="bi bi-box"></i></div>
                        <div class="quick-link-label">Stock In</div>
                    </a>
                    <a href="stock-management.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #ffedd5; color: #f97316;"><i
                                class="bi bi-box-arrow-up"></i></div>
                        <div class="quick-link-label">Stock Out</div>
                    </a>
                    <a href="stock-transfer.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #dbeafe; color: #3b82f6;"><i
                                class="bi bi-arrow-left-right"></i></div>
                        <div class="quick-link-label">Stock Transfer</div>
                    </a>
                    <a href="grn.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #d1fae5; color: #10b981;"><i
                                class="bi bi-clipboard"></i></div>
                        <div class="quick-link-label">GRN</div>
                    </a>
                    <a href="invoice-list.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #ffedd5; color: #f97316;"><i
                                class="bi bi-file-text"></i></div>
                        <div class="quick-link-label">Invoice</div>
                    </a>
                    <a href="#" class="quick-link">
                        <div class="quick-link-icon" style="background: #e9d5ff; color: #8b5cf6;"><i
                                class="bi bi-credit-card"></i></div>
                        <div class="quick-link-label">Payment</div>
                    </a>
                    <a href="receipt.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #dbeafe; color: #3b82f6;"><i
                                class="bi bi-receipt"></i></div>
                        <div class="quick-link-label">Receipt</div>
                    </a>
                    <a href="sales-report.html" class="quick-link">
                        <div class="quick-link-icon" style="background: #f3e8ff; color: #a855f7;"><i
                                class="bi bi-file-earmark-bar-graph"></i></div>
                        <div class="quick-link-label">Reports</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
