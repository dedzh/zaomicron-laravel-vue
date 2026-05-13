@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Каталог</a></li>
        @foreach($breadcrumbs as $crumb)
        @if($loop->last)
        <li class="breadcrumb-item active" aria-current="page">{{ $crumb->title }}</li>
        @else
        <li class="breadcrumb-item"><a href="{{ route('catalog.show', $crumb->id) }}">{{ $crumb->title }}</a></li>
        @endif
        @endforeach
    </ol>
</nav>

<h1>{{ $category->title }}</h1>

@if($category->text)
<div class="category-description">
    {!! $category->text !!}
</div>
@endif

@if($children->count())
<h2>Подкатегории</h2>
<div class="row">
    @foreach($children as $child)
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            @if($child->image)
            <img src="{{ asset('imgproc/mikronproduction/' . $child->image) }}" class="card-img-top" alt="{{ $child->title }}">
            @else
            <img src="{{ asset('img/logo.png') }}" class="card-img-top" alt="Нет фото">
            @endif
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('catalog.show', $child->id) }}">{{ $child->title }}</a>
                </h5>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($products->count())
<h2>Товары</h2>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Код</th>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Наличие</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }} руб.</td>
                <td>{{ $product->quantity ?? 0 }}</td>
                <td>
                    <form action="{{ route('cart.add') }}" method="post">
                        @csrf
                        <input type="hidden" name="code" value="{{ $product->code }}">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}">
                        <button type="submit" class="btn btn-sm btn-primary">В корзину</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endif
@endsection