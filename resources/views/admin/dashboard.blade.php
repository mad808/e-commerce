@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- CUSTOM DASHBOARD STYLES -->
<style>
    :root {
        --primary-gold: #D68C09;
        --light-gold-bg: #FFF8E6;
        --soft-blue-bg: #e3f2fd;
        --soft-green-bg: #e8f5e9;
        --soft-purple-bg: #f3e5f5;
        --text-dark: #2c3e50;
    }

    .text-dark { color: var(--text-dark) !important; }
    
    .card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
    }
    
    .icon-circle {
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #6c757d;
        letter-spacing: 0.5px;
    }
</style>

<!-- 1. WELCOME SECTION -->
<div class="mb-4">
    <h3 class="fw-bold text-dark">Hoş geldiňiz, {{ Auth::user()->name }}!</h3>
    <p class="text-muted">Online Market dolandyryş paneli.</p>
</div>

<!-- 2. STAT CARDS ROW -->
<div class="row g-4 mb-4">
    <!-- Revenue -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Jemi Girdeji</p>
                    <h2 class="mb-0 fw-bold text-success">{{ number_format($totalRevenue, 2) }} m</h2>
                </div>
                <div class="icon-circle" style="background-color: var(--soft-green-bg); color: #198754;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Orders -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Sargytlar</p>
                    <h2 class="mb-0 fw-bold text-primary">{{ $totalOrders }}</h2>
                </div>
                <div class="icon-circle" style="background-color: var(--soft-blue-bg); color: #0d6efd;">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Products -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Harytlar</p>
                    <h2 class="mb-0 fw-bold text-warning">{{ $totalProducts }}</h2>
                </div>
                <div class="icon-circle" style="background-color: var(--light-gold-bg); color: var(--primary-gold);">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Clients -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Müşderiler</p>
                    <h2 class="mb-0 fw-bold" style="color: #9c27b0;">{{ $totalClients }}</h2>
                </div>
                <div class="icon-circle" style="background-color: var(--soft-purple-bg); color: #9c27b0;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. MAIN CHARTS ROW -->
<div class="row g-4 mb-4">
    <!-- Sales Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Söwda Statistikasy (Soňky 7 gün)</h5>
            </div>
            <div class="card-body px-2">
                <div id="salesChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    <!-- Category Chart -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Kategoriýalar</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div id="categoryChart" style="width: 100%; min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- 4. TOP USERS & ORDER PIPELINE -->
<div class="row g-4 mb-4">
    <!-- Top 10 Users -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0 text-dark">Iň Gowy 10 Müşderi</h5>
            </div>
            <div class="table-responsive px-2">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>User</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topUsers as $stat)
                        <tr>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light text-center me-2 fw-bold text-secondary" style="width:32px; height:32px; line-height:32px;">
                                        {{ substr($stat->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $stat->user->name ?? 'Deleted' }}</span>
                                </div>
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $stat->order_count }}</span></td>
                            <td class="text-end fw-bold text-success">{{ number_format($stat->total_spent, 2) }} <small>TMT</small> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Pipeline (WITH CHART) -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0">Sargyt Ýagdaýy</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                
                <!-- NEW: Order Status Donut Chart -->
                <div id="orderStatusChart" class="mb-3 d-flex justify-content-center" style="min-height: 250px;"></div>

                <!-- Status Badges -->
                <div class="d-flex justify-content-around text-center mb-4 border-top pt-3">
                    <div>
                        <h4 class="fw-bold text-warning mb-0">{{ $orderStats['pending'] ?? 0 }}</h4>
                        <small class="text-muted fw-bold" style="font-size:10px">PENDING</small>
                    </div>
                    <div>
                        <h4 class="fw-bold text-info mb-0">{{ $orderStats['processing'] ?? 0 }}</h4>
                        <small class="text-muted fw-bold" style="font-size:10px">PROCESS</small>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary mb-0">{{ $orderStats['shipped'] ?? 0 }}</h4>
                        <small class="text-muted fw-bold" style="font-size:10px">SHIPPED</small>
                    </div>
                    <div>
                        <h4 class="fw-bold text-success mb-0">{{ $orderStats['delivered'] ?? 0 }}</h4>
                        <small class="text-muted fw-bold" style="font-size:10px">DELIVERED</small>
                    </div>
                    <div>
                        <h4 class="fw-bold text-danger mb-0">{{ $orderStats['cancelled'] ?? 0 }}</h4>
                        <small class="text-muted fw-bold" style="font-size:10px">CANCEL</small>
                    </div>
                </div>

                <!-- Monthly Target -->
                <div class="bg-light p-3 rounded border mt-auto">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="fw-bold text-dark small">Aýlyk Meýilnama</span>
                        <span class="fw-bold text-success">{{ number_format($targetPercentage, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: {{ $targetPercentage }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 small text-muted">
                        <span>Häzirki: <strong> {{ number_format($currentMonthRevenue) }} TMT</strong></span>
                        <span>Maksat: <strong> 50,000 TMT</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 5. TOP SELLING PRODUCTS -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0 text-dark">Iň Köp Satylan 20 Haryt</h5>
            </div>
            <div class="table-responsive px-3 pb-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th width="60">Surat</th>
                            <th>Haryt Ady</th>
                            <th>Kategoriýa</th>
                            <th>Baha</th>
                            <th>Satylan Sany</th>
                            <th class="text-end">Hereket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="bg-light rounded text-center text-muted" style="width:40px; height:40px; line-height:40px;"><i class="bi bi-box"></i></div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->product->name ?? 'Pozulan Haryt' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->product->category->name ?? '-' }}</span></td>
                            <td>{{ number_format($item->product->price ?? 0, 2) }} TMT</td>
                            <td><span class="badge bg-warning text-dark">{{ $item->total_sold }}</span></td>
                            <td class="text-end">
                                @if($item->product)
                                <a href="{{ route('admin.products.edit', $item->product->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 6. TOP FAVORITES -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0 text-dark">Iň Köp Halanan 20 Haryt (Favorites)</h5>
            </div>
            <div class="table-responsive px-3 pb-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th width="60">Surat</th>
                            <th>Haryt Ady</th>
                            <th>Kategoriýa</th>
                            <th>Baha</th>
                            <th class="text-center">Jemi Like</th>
                            <th class="text-end">Hereket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topFavorites as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                @else
                                <div class="bg-light rounded text-center text-muted" style="width:40px; height:40px; line-height:40px;"><i class="bi bi-box"></i></div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->category_name }}</span></td>
                            <td>{{ number_format($item->price, 2) }} TMT</td>
                            <td class="text-center">
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3">
                                    <i class="bi bi-heart-fill me-1"></i> {{ $item->likes_count }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- JAVASCRIPT FOR CHARTS -->
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- DEBUG: Check your Console if charts don't appear ---
        const salesDates = @json($chartDates);
        const salesTotals = @json($chartTotals);
        const catLabels = @json($catNames);
        const catData = @json($catCounts);
        
        console.log("Sales Data:", salesDates, salesTotals);
        console.log("Category Data:", catLabels, catData);

        // --- 1. SALES CHART ---
        var salesOptions = {
            series: [{
                name: 'Girdeji',
                data: salesTotals // Uses the array directly
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            colors: ['#405FA5'], 
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: salesDates // Uses the array directly
            },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.6, opacityTo: 0.1, stops: [0, 90, 100] }
            },
            dataLabels: { enabled: false },
            yaxis: {
                labels: { formatter: function(value) { return value + " m"; } }
            },
            grid: { strokeDashArray: 4, borderColor: '#f1f1f1' },
            noData: {
                text: 'Loading...'
            }
        };
        
        if(document.querySelector("#salesChart")) {
            new ApexCharts(document.querySelector("#salesChart"), salesOptions).render();
        }

        // --- 2. CATEGORY CHART ---
        var catOptions = {
            series: catData,
            labels: catLabels,
            chart: { type: 'donut', height: 350, fontFamily: 'inherit' },
            noData: {
                text: 'Maglumat ýok (No Data)',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#6c757d',
                    fontSize: '16px'
                }
            },
            colors: ['#405FA5', '#0d6efd', '#198754', '#6c757d', '#6610f2', '#dc3545', '#0dcaf0', '#fd7e14', '#20c997', '#d63384'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '14px', offsetY: -5 },
                            value: { show: true, fontSize: '20px', fontWeight: 600, offsetY: 5 },
                            total: { show: true, label: 'Jemi', fontSize: '15px', fontWeight: 600, color: '#373d3f' }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            stroke: { show: false }
        };
        if(document.querySelector("#categoryChart")) {
            new ApexCharts(document.querySelector("#categoryChart"), catOptions).render();
        }

        // --- 3. ORDER STATUS CHART ---
        // Manually constructing array to ensure no syntax errors
        const pending = {{ $orderStats['pending'] ?? 0 }};
        const processing = {{ $orderStats['processing'] ?? 0 }};
        const shipped = {{ $orderStats['shipped'] ?? 0 }};
        const delivered = {{ $orderStats['delivered'] ?? 0 }};
        const cancelled = {{ $orderStats['cancelled'] ?? 0 }};

        var orderOptions = {
            series: [pending, processing, shipped, delivered, cancelled],
            labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
            noData: {
                text: 'Sargyt ýok',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#6c757d',
                    fontSize: '14px'
                }
            },
            colors: ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#dc3545'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Orders',
                                fontSize: '16px',
                                fontWeight: 600,
                                color: '#373d3f'
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { show: false }
        };
        if(document.querySelector("#orderStatusChart")) {
            new ApexCharts(document.querySelector("#orderStatusChart"), orderOptions).render();
        }
    });
</script>