@extends('layouts.app')

@section('title', 'جزئیات محصول')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-box"></i>
                جزئیات محصول: {{ $product->name }}
            </h2>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    ویرایش
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات محصول</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>نام محصول:</strong> {{ $product->name }}</p>
                        <p><strong>کد محصول:</strong> {{ $product->code }}</p>
                        @if($product->barcode)
                            <p><strong>بارکد:</strong> {{ $product->barcode }}</p>
                        @endif
                        @if($product->brand)
                            <p><strong>برند:</strong> {{ $product->brand }}</p>
                        @endif
                        <p><strong>دسته‌بندی:</strong> {{ $product->category->name }}</p>
                        <p><strong>تامین‌کننده:</strong> {{ $product->supplier->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>قیمت فروش:</strong> {{ number_format($product->price) }} تومان</p>
                        @if($product->cost_price)
                            <p><strong>قیمت خرید:</strong> {{ number_format($product->cost_price) }} تومان</p>
                            <p><strong>سود:</strong> {{ number_format($product->profit) }} تومان</p>
                        @endif
                        <p><strong>موجودی:</strong> 
                            @php
                                $isLow = $product->is_low_stock;
                                $isExpiring = $product->is_expiring_soon;
                            @endphp
                            <span class="badge {{ $isLow ? 'bg-danger' : 'bg-success' }}">
                                {{ $product->stock_quantity }} {{ $product->unit ?? 'عدد' }}
                                @if($isLow)
                                    <i class="fas fa-exclamation-triangle ms-1" title="موجودی کم"></i>
                                @endif
                                @if($isExpiring)
                                    <i class="fas fa-hourglass-end ms-1 text-warning" title="نزدیک به انقضا"></i>
                                @endif
                            </span>
                        </p>
                        @if($product->min_stock_level)
                            <p><strong>سطح هشدار موجودی:</strong> {{ $product->min_stock_level }}</p>
                        @endif
                        @if($product->expiry_date)
                            <p><strong>تاریخ انقضا:</strong> 
                                <span class="{{ $product->is_expiring_soon ? 'text-warning' : '' }}">
                                    {{ $product->expiry_date->format('Y/m/d') }}
                                </span>
                            </p>
                        @endif
                        <p><strong>وضعیت:</strong> 
                            @if($product->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-danger">غیرفعال</span>
                            @endif
                        </p>
                        <p><strong>تاریخ ایجاد:</strong> {{ $product->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
                @if($product->description)
                <div class="row">
                    <div class="col-12">
                        <p><strong>توضیحات:</strong></p>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- تاریخچه تغییرات موجودی -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i>
                    تاریخچه تغییرات موجودی
                </h5>
            </div>
            <div class="card-body">
                @if($product->inventoryLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>تاریخ</th>
                                    <th>نوع عملیات</th>
                                    <th>تعداد</th>
                                    <th>موجودی قبلی</th>
                                    <th>موجودی جدید</th>
                                    <th>دلیل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->inventoryLogs->take(10) as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        @switch($log->action_type)
                                            @case('in')
                                                <span class="badge bg-success">ورود</span>
                                                @break
                                            @case('out')
                                                <span class="badge bg-danger">خروج</span>
                                                @break
                                            @case('adjustment')
                                                <span class="badge bg-warning">تنظیم</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-secondary">انقضا</span>
                                                @break
                                            @case('damaged')
                                                <span class="badge bg-dark">خرابی</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ $log->previous_stock }}</td>
                                    <td>{{ $log->new_stock }}</td>
                                    <td>{{ $log->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">هیچ تغییری در موجودی ثبت نشده است.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- آمار سریع -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i>
                    آمار سریع
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $product->orderItems->sum('quantity') }}</h4>
                        <small class="text-muted">کل فروش</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success">{{ number_format($product->orderItems->sum('total_price')) }}</h4>
                        <small class="text-muted">درآمد (تومان)</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-info">{{ $product->inventoryLogs->count() }}</h4>
                        <small class="text-muted">تغییرات موجودی</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-warning">{{ $product->sales->count() }}</h4>
                        <small class="text-muted">تعداد فروش</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- هشدارها -->
        @if($product->is_low_stock || $product->is_expiring_soon)
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    هشدارها
                </h5>
            </div>
            <div class="card-body">
                @if($product->is_low_stock)
                    <div class="alert alert-danger mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        موجودی کم! سطح موجودی زیر حد هشدار است.
                    </div>
                @endif
                @if($product->is_expiring_soon)
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-hourglass-end"></i>
                        محصول نزدیک به انقضا است.
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 