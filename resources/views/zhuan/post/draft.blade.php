@extends('zhuan.layout.zhuanlan')

@section('content-css')

@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-12 col-xs-12">

            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection