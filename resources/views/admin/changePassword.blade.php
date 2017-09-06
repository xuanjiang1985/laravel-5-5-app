@extends('admin.main')

@section('title')
<title>admin page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line">
		<i class="fa fa-map-marker"></i>
		修改我的密码
	</div>
	@include('errors.error')
	@include('errors.success')
	<form class="form-horizontal" method="post" action="{{ route('admin.changedPassword') }}">
		{!! csrf_field() !!}
		<div class="form-group">		
				<label class="col-sm-3 control-label">旧密码</label>
			<div class="col-sm-6">
				<input type="password" name="旧密码" class="form-control" placeholder="至少6位" required="required">
			</div>
		</div>
		<div class="form-group">		
				<label class="col-sm-3 control-label">新密码</label>
			<div class="col-sm-6">
				<input type="password" name="新密码" class="form-control" placeholder="至少6位" required="required">
			</div>
		</div>
		<div class="form-group">		
				<label class="col-sm-3 control-label">确认新密码</label>
			<div class="col-sm-6">
				<input type="password" name="新密码_confirmation" class="form-control" placeholder="至少6位" required="required">
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