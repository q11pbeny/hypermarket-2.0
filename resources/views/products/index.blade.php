@extends('layouts.app')

@section('title', 'محصولات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-box"></i>
                محصولات
            </h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                افزودن محصول جدید
            </a>
        </div>
    </div>
</div>

<!-- فرم جستجو و فیلتر پیشرفته -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">نام محصول</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="جستجو...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">بارکد</label>
                    <input type="text" name="barcode" value="{{ request('barcode') }}" class="form-control" placeholder="بارکد...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">برند</label>
                    <select name="brand" class="form-select">
                        <option value="">همه برندها</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') == $brand)> {{ $brand }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">دسته‌بندی</label>
                    <select name="category_id" class="form-select">
                        <option value="">همه دسته‌ها</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)> {{ $category->name }} </option>
                        @endforeach
                    </select>
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
                    <label class="form-label">موجودی</label>
                    <select name="stock_status" class="form-select">
                        <option value="">همه</option>
                        <option value="low" @selected(request('stock_status') === 'low')>کم</option>
                        <option value="normal" @selected(request('stock_status') === 'normal')>عادی</option>
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
        <h5 class="mb-0">لیست محصولات</h5>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>نام محصول</th>
                            <th>کد</th>
                            <th>دسته‌بندی</th>
                            <th>تامین‌کننده</th>
                            <th>قیمت</th>
                            <th>موجودی</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ number_format($product->price) }} تومان</td>
                            <td>
                                @php
                                    $isLow = $product->is_low_stock;
                                    $isExpiring = $product->is_expiring_soon;
                                @endphp
                                <span class="badge {{ $isLow ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock_quantity }}
                                    @if($isLow)
                                        <i class="fas fa-exclamation-triangle ms-1" title="موجودی کم"></i>
                                    @endif
                                    @if($isExpiring)
                                        <i class="fas fa-hourglass-end ms-1 text-warning" title="نزدیک به انقضا"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این محصول اطمینان دارید؟')">
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
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">هیچ محصولی یافت نشد</h5>
                <p class="text-muted">برای شروع، یک محصول جدید ایجاد کنید.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    افزودن محصول جدید
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 