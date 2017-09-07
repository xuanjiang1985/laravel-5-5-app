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
	</div>
	<div>
		<a href="{{ route('admin.managerCreate') }}" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> 添加管理员</a>
	</div>
	<div class="clearfix"></div>
	<br>
	@include('errors.error')
	@include('errors.success')
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>管理员昵称</th>
					<th>管理员账户</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			@foreach($managers as $manager)
				<tr>
					<td>{{ $manager->name }}</td>
					<td>{{ $manager->email }}</td>
					<td>
						<a href="{{ route('admin.managerShow',['id' => $manager->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i> 修改</a>
						<button class="btn btn-danger" data-link="{{ route('admin.managerDelete', ['id' => $manager->id ]) }}"><i class="fa fa-trash"></i> 删除</button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
{!! csrf_field() !!}
<script>
	$(function(){
		//delete manager
		$(".btn-danger").click(function(){
			var name = $(this).parents("tr").find("td").eq(0).text();
			var url = $(this).attr("data-link");

			if(!confirm("确定要删除管理员：" + name + "?")){
				return false;
			}

			$.ajax({
			type:"DELETE",
			url: url,
			headers: {"X-CSRF-TOKEN": $("input[name='_token']").val()},
			dataType:'json',
            success: function(data){
            	window.location.reload();
            },
            error: function(data){
            	$("#ajax-status").html('<i class="fa fa-times"></i> ' + data.statusText);
				$("#ajax-status").show();
				$("#ajax-status").fadeOut(2500);
            }
		});
	});
		
});
</script>
@endsection