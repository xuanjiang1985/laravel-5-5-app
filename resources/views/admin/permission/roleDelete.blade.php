@extends('admin.main')

@section('title')
<title>admin page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line" data-treeview="1" data-treeview-menu="1">
		<i class="fa fa-map-marker"></i>
		权限管理
		<i class="fa fa-angle-double-right"></i>
		角色分配
		<i class="fa fa-angle-double-right"></i>
		删除角色(管理员：{{ $user->name }})
	</div>
	<div class="container">
		<br>
		<form class="form-horizontal" method="post" action="{{ route('admin.roleDeleted',['id' => $user->id ]) }}">
		{!! csrf_field() !!}
			<div class="form-group">
				@foreach($userRoles as $role)
				<div class="checkbox">
				    <label><input type="checkbox" value="{{ $role->id }}" name="checkbox[]">{{ $role->display_name }}</label>
				</div>
				@endforeach
			</div>
			<div class="form-group">
				<button class="btn btn-danger">删除</button>
			</div>
		</form>
	</div>
</div>
@endsection