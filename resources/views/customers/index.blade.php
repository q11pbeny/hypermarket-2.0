@extends('layouts.app')

@section('title', 'مشتریان')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-users"></i>
                مشتریان
            </h2>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                افزودن مشتری جدید
            </a>
        </div>
    </div>
</div>

<!-- فرم جستجو و فیلتر پیشرفته -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">نام</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="نام...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">ایمیل</label>
                    <input type="text" name="email" value="{{ request('email') }}" class="form-control" placeholder="ایمیل...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">تلفن</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" class="form-control" placeholder="تلفن...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">کدملی</label>
                    <input type="text" name="national_code" value="{{ request('national_code') }}" class="form-control" placeholder="کدملی...">
                </div>
                <div class="col-md-1">
                    <label class="form-label">وضعیت</label>
                    <select name="is_active" class="form-select">
                        <option value="">همه</option>
                        <option value="1" @selected(request('is_active') === '1')>فعال</option>
                        <option value="0" @selected(request('is_active') === '0')>غیرفعال</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">وفاداری</label>
                    <select name="loyal" class="form-select">
                        <option value="">همه</option>
                        <option value="1" @selected(request('loyal') === '1')>وفادار</option>
                        <option value="0" @selected(request('loyal') === '0')>غیروفادار</option>
                    </select>
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
        <h5 class="mb-0">لیست مشتریان</h5>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>تلفن</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                @if($customer->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این مشتری اطمینان دارید؟')">
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
                {{ $customers->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">هیچ مشتری یافت نشد</h5>
                <p class="text-muted">برای شروع، یک مشتری جدید ایجاد کنید.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    افزودن مشتری جدید
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 