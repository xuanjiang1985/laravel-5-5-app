@extends('main')

@section('title')
<title>login for admin center</title>
@endsection
@section('content')
<div class="container">
	<div class="panel panel-default">
	    <div class="panel-heading">
	        后台登录
	    </div>
	    <div class="panel-body">
	    @include('errors.error')
	    <br>
	        <form class="form-horizontal" method="post" action="{{ route('admin.login') }}">
	        	{!! csrf_field() !!}
	        	<div class="form-group">
	        		<div class="col-sm-offset-3 col-sm-6">
	        			<input type="email" class="form-control" name="email" placeholder="email" required="required" value="{{ old('email') }}">
	        		</div>
	        	</div>
	        	<div class="form-group">
	        		<div class="col-sm-offset-3 col-sm-6">
	        			<input type="password" class="form-control" name="password" placeholder="password" required="required">
	        		</div>
	        	</div>
	        	<div class="form-group">
	        		<div class="col-sm-offset-3 col-sm-6">
	        			<button class="btn btn-primary">登录</button>
	        		</div>
	        	</div>
	        </form>
	    </div>
	</div>
</div>
@endsection