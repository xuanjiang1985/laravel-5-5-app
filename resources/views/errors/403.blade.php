@extends('admin.main')

@section('title')
<title>403 page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
    <div class="container">
        <br>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">x</button>
            无权限访问！
        </div>
        <!-- <a href="javascript:;" id="404-back">返回</a> -->
    </div>
    <!-- <script src="{{ asset('/js/404.js') }}"></script> -->
</div>
@endsection