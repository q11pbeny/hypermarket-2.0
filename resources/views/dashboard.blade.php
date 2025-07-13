@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt"></i>
            داشبورد مدیریتی
        </h2>
    </div>
</div>

<!-- آمار کلی -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-box fa-2x mb-2"></i>
            <h4>{{ $stats['total_products'] }}</h4>
            <p>کل محصولات</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
            <h4>{{ $stats['total_orders'] }}</h4>
            <p>کل سفارشات</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-users fa-2x mb-2"></i>
            <h4>{{ $stats['total_customers'] }}</h4>
            <p>کل مشتریان</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card text-center">
            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
            <h4>{{ number_format($stats['total_revenue']) }} تومان</h4>
            <p>درآمد کل</p>
        </div>
    </div>
</div>

<!-- نمودارها -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line"></i>
                    نمودار فروش ماهانه
                </h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie"></i>
                    توزیع دسته‌بندی‌ها
                </h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- سفارشات اخیر -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock"></i>
                    سفارشات اخیر
                </h5>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>شماره سفارش</th>
                                    <th>مشتری</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ number_format($order->total_amount) }} تومان</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">در انتظار</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">در حال پردازش</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">تکمیل شده</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">لغو شده</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">هیچ سفارشی یافت نشد.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- محصولات کم موجودی -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    محصولات کم موجودی
                </h5>
            </div>
            <div class="card-body">
                @if($low_stock_products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>نام محصول</th>
                                    <th>دسته‌بندی</th>
                                    <th>موجودی</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($low_stock_products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $product->stock_quantity }}
                                            <i class="fas fa-exclamation-triangle ms-1" title="موجودی کم"></i>
                                            @if($product->is_expiring_soon)
                                                <i class="fas fa-hourglass-end ms-1 text-warning" title="نزدیک به انقضا"></i>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">هیچ محصولی با موجودی کم یافت نشد.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- محصولات پرفروش و مشتریان برتر -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star"></i>
                    محصولات پرفروش
                </h5>
            </div>
            <div class="card-body">
                @if($top_products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>نام محصول</th>
                                    <th>تعداد فروش</th>
                                    <th>رتبه</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_products as $index => $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_sales ?? 0 }}</td>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning"><i class="fas fa-trophy"></i> اول</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary"><i class="fas fa-medal"></i> دوم</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-warning"><i class="fas fa-award"></i> سوم</span>
                                        @else
                                            <span class="badge bg-info">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">هیچ محصولی یافت نشد.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-crown"></i>
                    مشتریان برتر
                </h5>
            </div>
            <div class="card-body">
                @if($top_customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>نام مشتری</th>
                                    <th>کل خرید</th>
                                    <th>رتبه</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_customers as $index => $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ number_format($customer->total_purchases) }} تومان</td>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning"><i class="fas fa-crown"></i> اول</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary"><i class="fas fa-medal"></i> دوم</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-warning"><i class="fas fa-award"></i> سوم</span>
                                        @else
                                            <span class="badge bg-info">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">هیچ مشتری یافت نشد.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- آمار اضافی -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                <h5>{{ $stats['total_categories'] }}</h5>
                <p class="text-muted">دسته‌بندی‌ها</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-truck fa-2x text-info mb-2"></i>
                <h5>{{ $stats['total_suppliers'] }}</h5>
                <p class="text-muted">تامین‌کنندگان</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                <h5>{{ $stats['pending_orders'] }}</h5>
                <p class="text-muted">سفارشات در انتظار</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// نمودار فروش ماهانه
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthly_sales->keys()) !!},
        datasets: [{
            label: 'فروش ماهانه (تومان)',
            data: {!! json_encode($monthly_sales->values()) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// نمودار توزیع دسته‌بندی‌ها
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($category_stats->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($category_stats->pluck('products_count')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endpush 