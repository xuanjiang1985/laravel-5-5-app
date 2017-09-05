@extends('admin.main')

@section('title')
<title>admin page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line">
		<i class="fa fa-map-marker"></i>
		权限管理
		<i class="fa fa-angle-double-right"></i>
		角色权限设置
		<i class="fa fa-angle-double-right"></i>
		分配权限(角色名：{{ $role->display_name }})
	</div>
	<div class="container">
		<br>
		<form class="form-horizontal" method="post" action="">
		{!! csrf_field() !!}
			<div class="form-group">
				@foreach($permissions as $permission)
				    <label class="checkbox-inline">
				    	<input type="checkbox" value="{{ $permission->id }}" name="checkbox[]">{{ $permission->display_name }}
				    </label>
				@endforeach
			</div>
			<div class="form-group">
				<button class="btn btn-primary">提交</button>
			</div>
		</form>
	</div>
</div>
@endsection