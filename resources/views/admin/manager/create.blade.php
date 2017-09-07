@extends('admin.main')

@section('title')
<title>admin page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line" data-treeview="1" data-treeview-menu="3">
		<i class="fa fa-map-marker"></i>
		权限管理
		<i class="fa fa-angle-double-right"></i>
		管理员设置
		<i class="fa fa-angle-double-right"></i>
		添加管理员
	</div>
	<br><br>
	@include('errors.error')
	@include('errors.success')
	<form class="form-horizontal" action="{{ route('admin.managerStore') }}" method="post">
		{!! csrf_field() !!}
		<div class="form-group">
			<label class="col-sm-3 control-label">昵称</label>
			<div class="col-sm-5">
				<input type="text" name="昵称" class="form-control" value="{{ old('昵称') }}" placeholder="昵称" required="required">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">登录邮箱</label>
			<div class="col-sm-5">
				<input type="email" name="邮箱" class="form-control" value="{{ old('邮箱') }}" placeholder="邮箱" required="required">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">登录密码</label>
			<div class="col-sm-5">
				<input type="password" name="密码" class="form-control" placeholder="密码至少6位" required="required">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3">
				<button class="btn btn-primary">提交</button>
			</div>
		</div>
	</form>
	<div class="clearfix"></div>
</div>
@endsection