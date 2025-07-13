@extends('layouts.app')

@section('title', 'جزئیات مشتری')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-user"></i>
                جزئیات مشتری: {{ $customer->name }}
            </h2>
            <div>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    ویرایش
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">اطلاعات مشتری</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>نام:</strong> {{ $customer->name }}</p>
                        <p><strong>ایمیل:</strong> {{ $customer->email }}</p>
                        <p><strong>تلفن:</strong> {{ $customer->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>وضعیت:</strong> 
                            @if($customer->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-danger">غیرفعال</span>
                            @endif
                        </p>
                        <p><strong>تاریخ ایجاد:</strong> {{ $customer->created_at->format('Y/m/d H:i') }}</p>
                        <p><strong>آخرین بروزرسانی:</strong> {{ $customer->updated_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>آدرس:</strong></p>
                        <p>{{ $customer->address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 