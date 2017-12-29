@extends('zhuan.layout.base')

{{--@section('baidutongji')--}}
    {{--@include('frontend.partials.baidutongji')--}}
{{--@endsection--}}

{{--@section('googletongji')--}}
    {{--@include('frontend.partials.googletongji')--}}
{{--@endsection--}}

@section('base-css')
    @include('zhuan.partials.base_css')
@endsection

@section('header')
    @include('zhuan.partials.header')
@endsection

@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9">
            <div class="main_left">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </div>
        </div>
    </main>
@endsection

@section('content-css')

@endsection

@section('goTop')
    @include('frontend.partials.goTop')
@endsection

@section('base-js')
    @include('frontend.partials.base_js')
@endsection