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

@endsection

@section('content-css')

@endsection

@section('goTop')
    @include('frontend.partials.goTop')
@endsection

@section('base-js')
    @include('frontend.partials.base_js')
@endsection