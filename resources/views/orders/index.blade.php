@extends('layouts.app')

@section('title', 'سفارشات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-shopping-cart"></i>
                سفارشات
            </h2>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                افزودن سفارش جدید
            </a>
        </div>
    </div>
</div>

<!-- فرم جستجو و فیلتر پیشرفته -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">شماره سفارش</label>
                    <input type="text" name="order_number" value="{{ request('order_number') }}" class="form-control" placeholder="شماره...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">نام مشتری</label>
                    <input type="text" name="customer_name" value="{{ request('customer_name') }}" class="form-control" placeholder="نام مشتری...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">وضعیت</label>
                    <select name="status" class="form-select">
                        <option value="">همه</option>
                        <option value="pending" @selected(request('status') === 'pending')>در انتظار</option>
                        <option value="processing" @selected(request('status') === 'processing')>در حال پردازش</option>
                        <option value="completed" @selected(request('status') === 'completed')>تکمیل شده</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>لغو شده</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">از تاریخ</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">تا تاریخ</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label">حداقل مبلغ</label>
                    <input type="number" name="min_total" value="{{ request('min_total') }}" class="form-control" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label">حداکثر مبلغ</label>
                    <input type="number" name="max_total" value="{{ request('max_total') }}" class="form-control" placeholder="...">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-search"></i>
                        جستجو
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">لیست سفارشات</h5>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>شماره سفارش</th>
                            <th>مشتری</th>
                            <th>مبلغ کل</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
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
                            <td>{{ $order->created_at->format('Y/m/d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این سفارش اطمینان دارید؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">هیچ سفارشی یافت نشد</h5>
                <p class="text-muted">برای شروع، یک سفارش جدید ایجاد کنید.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    افزودن سفارش جدید
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 