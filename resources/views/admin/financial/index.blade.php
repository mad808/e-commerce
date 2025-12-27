@extends('admin.layouts.app')

@section('title', 'Financial Report')

@section('content')
<style>
    :root {
        --brand-blue: #285179;
        --brand-green: #198754;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-left: 5px solid transparent;
    }

    .border-investment {
        border-left-color: #6c757d;
    }

    .border-revenue {
        border-left-color: #0d6efd;
    }

    .border-profit {
        border-left-color: var(--brand-green);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
        font-weight: 700;
    }
</style>

<div class="container-fluid px-3 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Maliýe Hasabaty</h4>
        <div class="badge bg-white text-dark border px-3 py-2">{{ now()->format('d.m.Y') }}</div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-investment">
                <div class="card-body">
                    <div class="stat-label">Ammar Gymmaty (Cost)</div>
                    <div class="stat-value text-secondary">${{ number_format($totalInvestment, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-revenue">
                <div class="card-body">
                    <div class="stat-label text-primary">Potensial Girdeji</div>
                    <div class="stat-value text-primary">${{ number_format($totalExpectedRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-profit">
                <div class="card-body">
                    <div class="stat-label text-success">Potensial Peýda</div>
                    <div class="stat-value text-success">${{ number_format($totalExpectedProfit, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Söwda we Peýda Seljermesi</h6>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary filter-btn active" data-filter="day">Gün</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="week">Hepde</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="month">Aý</button>
            </div>
        </div>
        <div class="card-body">
            <div style="height: 350px;"><canvas id="financialChart"></canvas></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Haryt</th>
                        <th>Stok</th>
                        <th>Özi düşýän</th>
                        <th>Satuw bahasy</th>
                        <th class="text-end">Potensial Peýda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->stock }}</td>
                        <td>${{ number_format($product->cost_price, 2) }}</td>
                        <td class="text-primary">${{ number_format($product->price, 2) }}</td>
                        <td class="text-end text-success fw-bold">
                            ${{ number_format(($product->price - $product->cost_price) * $product->stock, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $products->links() }}</div>
    </div>
</div>


<script src="{{ asset('assets/js/chart.js') }}"></script>
<script>
    let myChart;

    function loadChart(filter) {
        fetch(`{{ route('admin.financial.chart') }}?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('financialChart').getContext('2d');
                if (myChart) myChart.destroy();
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                                label: 'Söwda',
                                data: data.revenues,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13,110,253,0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Arassa Peýda',
                                data: data.profits,
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25,135,84,0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadChart(this.dataset.filter);
        });
    });

    loadChart('day');
</script>
@endsection