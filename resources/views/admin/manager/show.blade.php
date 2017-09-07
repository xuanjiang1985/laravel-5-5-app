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
		修改管理员
	</div>
	@include('errors.error')
	@include('errors.success')
	<br>
	<div class="container">
		<form class="form-horizontal" method="post" action="{{ route('admin.managerUpdate', ['id' => $manager->id]) }}">
			{{ method_field('PUT') }}
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="col-sm-3 control-label">账户</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" value="{{ $manager->email }}" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">昵称</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="昵称" value="{{ $manager->name }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">新密码</label>
				<div class="col-sm-5">
					<input type="password" class="form-control" name="新密码" value="" placeholder="至少6位，留空即不修改密码">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3">
					<button class="btn btn-primary">提交</button>
				</div>
			</div>
		</form>
	</div>
	
</div>

@endsection