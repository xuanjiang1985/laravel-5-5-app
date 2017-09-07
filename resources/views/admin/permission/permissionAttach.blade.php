@extends('admin.main')

@section('title')
<title>admin page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line" data-treeview="1" data-treeview-menu="2">
		<i class="fa fa-map-marker"></i>
		权限管理
		<i class="fa fa-angle-double-right"></i>
		角色权限设置
		<i class="fa fa-angle-double-right"></i>
		分配权限(角色名：{{ $role->display_name }})
	</div>
	<div class="container">
		<br>
		<form class="form-horizontal" method="post" action="{{ route('admin.permissionAttached', ['id' => $role->id]) }}">
		{!! csrf_field() !!}
			<div class="form-group">
				@foreach($permissions as $permission)
					    <label class="checkbox-inline">
					    @if(in_array($permission->id, $hasPermissions))
					    	<input type="checkbox" value="{{ $permission->id }}" name="checkbox[]" checked="checked">{{ $permission->display_name }}
					    @else
					    	<input type="checkbox" value="{{ $permission->id }}" name="checkbox[]">{{ $permission->display_name }}
					    @endif
					    </label>
					@if($permission->item == 1) <hr> @endif
				@endforeach
			</div>
			<div class="form-group">
				<button class="btn btn-primary">提交</button>
			</div>
		</form>
	</div>
</div>
@endsection