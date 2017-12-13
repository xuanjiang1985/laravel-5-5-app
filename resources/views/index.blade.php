@extends('main')

@section('title')
<title>index</title>
@endsection
@section('keywords')
<meta name="keywords" content="index page">
@endsection
@section('description')
<meta name="description" content="home page">
@endsection

@section('content')
<style type="text/css">
	.collect {margin-bottom: 5px; }
</style>
<div class="container">
	<div class="row">
		<div class="col-sm-3 text-center">省份</div>
		<div class="col-sm-3 text-center">市</div>
		<div class="col-sm-3 text-center">区/县</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-3">
			<select class="form-control" id="province">
				<option value="0" >请选择省份</option>
				@foreach($province as $item)
				<option value="{{$item->id}}">{{$item->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-3">
			<select class="form-control" id="city">
				<option value="0" >请选择市</option>
			</select>
		</div>
		<div class="col-sm-6" id="district">
			<p><input type="text" name="" value=""></p>
		</div>
	</div>
	<br><br><br>
	<div class="row">
		<div class="col-sm-3">
			<select class="form-control" id="province2">
				<option value="0" >请选择省份</option>
				@foreach($province as $item)
				<option value="{{$item->id}}">{{$item->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-sm-3">
			<div id="city2">
				
			</div>
		</div>
		<div class="col-sm-6" id="district2">
		
		</div>
	</div>
	<br>
</div>
	@push('scripts')
	    <script>
	    	$("#province").change(function(){
	    		var id = $(this).val();
	    		$.ajax({
	    			type: 'get',
	    			url: '/city/' + id,
	    			dataType: 'json',
	    			success: function(data) {
	    				$option = '<option value="0" >请选择市</option>';
	    				$.each(data, function(index, value) {
	    					$option += '<option value="' + value.id + '" >' + value.name + ' | ' + value.id + ' | ' + value.address_baixing +'</option>';
	    				});
	    				$("#city").html($option);
	    			},
	    			error: function(data) {
	    				alert("服务器错误");
	    			}
	    		})
	    	});

	    	$("#province2").change(function(){
	    		var id = $(this).val();
	    		$.ajax({
	    			type: 'get',
	    			url: '/city/' + id,
	    			dataType: 'json',
	    			success: function(data) {
	    				$option = '';
	    				$.each(data, function(index, value) {
	    					$option += '<button class="btn btn-primary collect" data-value="' + value.address_baixing +'">' + value.name + ' | ' + value.id + ' | ' + value.address_baixing + '</button>';
	    				});
	    				$("#city2").html($option);
	    			},
	    			error: function(data) {
	    				alert("服务器错误");
	    			}
	    		})
	    	});
	    	$("#city2").on("click", ".collect", collect);
	    	function collect() {
	    		var city = $(this).data("value");
	    		$("#district2").html("正在爬取" + city +".baixing.com ...");
	    		$.ajax({
	    			type: 'get',
	    			url: '/district`/' + id,
	    			dataType: 'json',
	    			data: {city: city},
	    			success: function(data) {
	    				$option = '';
	    				$.each(data, function(index, value) {
	    					$option += '<p>' + value.name + ' <input type="text" class="jinput" value="' + value.address_baixing + '" id="' + value.id + '"></p>';
	    				});
	    				$("#district").html($option);
	    			},
	    			error: function(data) {
	    				console.log("服务器错误");
	    			}
	    		})
	    	}

	    	$("#city").change(function(){
	    		var id = $(this).val();
	    		$.ajax({
	    			type: 'get',
	    			url: '/district/' + id,
	    			dataType: 'json',
	    			success: function(data) {
	    				$option = '';
	    				$.each(data, function(index, value) {
	    					$option += '<p>' + value.name + ' <input type="text" class="jinput" value="' + value.address_baixing + '" id="' + value.id + '"></p>';
	    				});
	    				$("#district").html($option);
	    			},
	    			error: function(data) {
	    				console.log("服务器错误");
	    			}
	    		})
	    	});
	    	$("#district").on("blur", ".jinput", update);
	    	function update() {
	    		const id = $(this).attr("id");
	    		const value = $(this).val();
	    		$.ajax({
	    			type: 'get',
	    			url: '/districtupdate',
	    			dataType: 'json',
	    			data: {id: id, value: value},
	    			success: function(data) {
	    				$('#ajax-status').show().html('<span class="text-white">更新成功</span>').fadeOut(1000);
	    			},
	    			error: function(data) {
	    				console.log("服务器错误");
	    			}
	    		})
	    	}
	    </script>
	@endpush
@endsection