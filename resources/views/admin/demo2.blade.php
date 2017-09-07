@extends('admin.main')

@section('title')
<title>demo1 page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line" data-treeview="2" data-treeview-menu="2">
		<i class="fa fa-map-marker"></i>
		测试管理
		<i class="fa fa-angle-double-right"></i>
		demo2
	</div>

	<div class="container">
		<h3>I am a demo2.</h3>
	</div>
</div>
@endsection