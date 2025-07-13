@extends('layouts.app')

@section('title', 'جزئیات سفارش')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-shopping-cart"></i>
                جزئیات سفارش: {{ $order->order_number }}
            </h2>
            <div>
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    ویرایش
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">اطلاعات سفارش</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>شماره سفارش:</strong> {{ $order->order_number }}</p>
                        <p><strong>مشتری:</strong> {{ $order->customer->name }}</p>
                        <p><strong>ایمیل مشتری:</strong> {{ $order->customer->email }}</p>
                        <p><strong>تلفن مشتری:</strong> {{ $order->customer->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>مبلغ کل:</strong> {{ number_format($order->total_amount) }} تومان</p>
                        <p><strong>وضعیت:</strong> 
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
                        </p>
                        <p><strong>تاریخ ایجاد:</strong> {{ $order->created_at->format('Y/m/d H:i') }}</p>
                        <p><strong>آخرین بروزرسانی:</strong> {{ $order->updated_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
                @if($order->notes)
                <div class="row">
                    <div class="col-12">
                        <p><strong>یادداشت:</strong></p>
                        <p>{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 