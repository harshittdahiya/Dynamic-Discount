@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="bi bi-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Top KPI Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-rupee text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalOrders) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-bag-check text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Products (Categories)
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalProducts }} ({{ $totalCategories }})</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Coupons Used</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCouponsUsed) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-tags text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Sales Report ({{ $currentYear }})</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart / Best Performers -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Highlights</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="small text-muted text-uppercase fw-bold mb-1">Best Selling Product</div>
                        @if($bestSellingProduct)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    @if($bestSellingProduct->image_url)
                                        <img src="{{ $bestSellingProduct->image_url }}" alt="{{ $bestSellingProduct->product_name }}" class="rounded me-2 object-fit-cover" style="width: 36px; height: 36px;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 36px; height: 36px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <span class="fw-bold">{{ $bestSellingProduct->product_name }}</span>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ $bestSellingProduct->order_items_sum_quantity }} sold</span>
                            </div>
                        @else
                            <span class="text-muted">No data available</span>
                        @endif
                    </div>
                    
                    <hr>

                    <div class="mb-4">
                        <div class="small text-muted text-uppercase fw-bold mb-1">Most Used Coupon</div>
                        @if($mostUsedCoupon)
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-primary">{{ $mostUsedCoupon->coupon_code }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $mostUsedCoupon->usages_count }} uses</span>
                            </div>
                        @else
                            <span class="text-muted">No data available</span>
                        @endif
                    </div>

                    <hr>

                    <div>
                        <div class="small text-muted text-uppercase fw-bold mb-1 d-flex justify-content-between">
                            <span>Failed Coupon Attempts</span>
                            <span class="badge bg-danger rounded-pill">{{ $failedAttemptsCount }}</span>
                        </div>
                        <ul class="list-group list-group-flush mt-2">
                            @forelse($recentFailedAttempts as $attempt)
                                <li class="list-group-item px-0 py-1 border-0 text-sm">
                                    <span class="text-danger fw-bold">{{ $attempt->attempted_code }}</span> 
                                    <small class="text-muted">- {{ $attempt->reason }}</small>
                                </li>
                            @empty
                                <li class="list-group-item px-0 border-0 text-muted small">No recent failures.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offers Performance Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Offers Performance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Offer Title</th>
                                    <th>Type</th>
                                    <th>Items Sold (During Offer)</th>
                                    <th>Revenue Generated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offerPerformance as $op)
                                    <tr>
                                        <td class="fw-bold">{{ $op['title'] }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($op['type']) }}</span></td>
                                        <td>{{ $op['sold_qty'] }}</td>
                                        <td class="text-success fw-bold">₹{{ number_format($op['revenue'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No active offers data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('monthlySalesChart');
        if (ctx) {
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: {{ json_encode(array_values($salesData)) }},
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return '₹' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
