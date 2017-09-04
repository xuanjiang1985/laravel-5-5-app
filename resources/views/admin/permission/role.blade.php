@extends('admin.main')

@section('title')
<title>role page</title>
@endsection

@section('content')
<div class="content-wrapper" id="admin-content">
	<div id="nav-line">
		<i class="fa fa-map-marker"></i>
		权限管理
		<i class="fa fa-angle-double-right"></i>
		角色分配
	</div>
	@include('errors.error')
	<div class="table-responsive">
		<table class="table">
			<thead>
			    <tr>
			      <th>管理员昵称</th>
			      <th>管理员账号</th>
			      <th>已有角色</th>
			      <th>操作</th>
			    </tr>
			  </thead>
			  <tbody>
			  @foreach($managers as $manager)
			  	<?php $roles = $manager->Roles; ?>
			    <tr>
			      <td>{{ $manager->name }}</td>
			      <td>{{ $manager->email }}</td>
			      <td>
			      	@foreach($roles as $role)
			      	{{ $role->display_name }}<br>
			      	@endforeach
			      </td>
			      <td>
			      	@if($manager->id == 1)
			      	<button class="btn btn-success disabled"><i class="fa fa-lock"></i> 锁定</button>
			      	@else
			      	<a href="{{ route('admin.roleDispatch',['id' => $manager->id ]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> 分配角色</a>
			      	<a href="{{ route('admin.roleDelete',['id' => $manager->id ]) }}" class="btn btn-danger"><i class="fa fa-trash-o"></i> 删除角色</a>
			        @endif
			      </td>
			    </tr>
			   @endforeach
			  </tbody>
		</table>
	</div>
</div>
@endsection