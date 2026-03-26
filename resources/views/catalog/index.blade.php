@extends('layouts.app') {{-- или ваш основной шаблон --}}

@section('content')
    <h1>Каталог продукции</h1>
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($category->image)
                        <img src="{{ asset('imgproc/mikronproduction/' . $category->image . '/') }}" class="card-img-top" alt="{{ $category->title }}">
                    @else
                        <img src="{{ asset('img/logo.png') }}" class="card-img-top" alt="Нет фото">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('catalog.show', $category->id) }}">{{ $category->title }}</a>
                        </h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection