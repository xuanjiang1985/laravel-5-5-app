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
	</div>
	@include('errors.success')
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
			    <tr>
			      <th>角色名称</th>			   
			      <th>操作</th>
			    </tr>
			  </thead>
			  <tbody>
			  @foreach($roles as $role)
			    <tr>
			      <td>{{ $role->display_name }}</td>
			      <td><a href="{{ route('admin.permissionAttach',['id' => $role->id]) }}" class="btn btn-primary"><i class="fa fa-cogs"></i> 分配权限</a></td>   
			    </tr>
			  @endforeach
			  </tbody>
		</table>
	</div>
</div>
@endsection