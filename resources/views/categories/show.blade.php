@extends('layouts.app')

@section('title', 'جزئیات دسته‌بندی')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-tag"></i>
                جزئیات دسته‌بندی: {{ $category->name }}
            </h2>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    ویرایش
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">اطلاعات دسته‌بندی</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>نام:</strong> {{ $category->name }}</p>
                        <p><strong>توضیحات:</strong> {{ $category->description ?: 'بدون توضیحات' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>وضعیت:</strong> 
                            @if($category->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-danger">غیرفعال</span>
                            @endif
                        </p>
                        <p><strong>تاریخ ایجاد:</strong> {{ $category->created_at->format('Y/m/d H:i') }}</p>
                        <p><strong>آخرین بروزرسانی:</strong> {{ $category->updated_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- محصولات این دسته‌بندی -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">محصولات این دسته‌بندی</h5>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>نام محصول</th>
                                    <th>کد</th>
                                    <th>قیمت</th>
                                    <th>موجودی</th>
                                    <th>وضعیت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->code }}</td>
                                    <td>{{ number_format($product->price) }} تومان</td>
                                    <td>
                                        @if($product->stock_quantity < 10)
                                            <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">فعال</span>
                                        @else
                                            <span class="badge bg-danger">غیرفعال</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">هیچ محصولی در این دسته‌بندی یافت نشد</h5>
                        <p class="text-muted">برای افزودن محصول به این دسته‌بندی، به بخش محصولات مراجعه کنید.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            افزودن محصول جدید
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">آمار</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-box fa-3x text-primary mb-2"></i>
                    <h4>{{ $category->products->count() }}</h4>
                    <p class="text-muted">تعداد محصولات</p>
                </div>
                
                <div class="text-center mb-3">
                    <i class="fas fa-dollar-sign fa-3x text-success mb-2"></i>
                    <h4>{{ number_format($category->products->sum('price')) }} تومان</h4>
                    <p class="text-muted">مجموع قیمت محصولات</p>
                </div>

                <div class="text-center">
                    <i class="fas fa-cubes fa-3x text-info mb-2"></i>
                    <h4>{{ $category->products->sum('stock_quantity') }}</h4>
                    <p class="text-muted">مجموع موجودی</p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">عملیات سریع</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        افزودن محصول جدید
                    </a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        ویرایش دسته‌بندی
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">
                            <i class="fas fa-trash"></i>
                            حذف دسته‌بندی
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 