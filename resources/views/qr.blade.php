@extends('main')

@section('title')
<title>扫码网站</title>
@endsection
@section('keywords')
<meta name="keywords" content="扫码网站">
@endsection
@section('description')
<meta name="description" content="扫码网站">
@endsection

@section('content')
<div class="container">
    <style type="text/css">
        #myqr {position: fixed;display:none; width: 200px; height: 200px;padding-top: 22px;text-align: center; margin:auto; left: 0; right: 0;top:0;bottom:0; }
    </style>
    <form class="form-inline" role="form" action="/qr/store" method="post">
        {{ csrf_field() }}
      <div class="form-group" style="width:80%;">
        <label class="sr-only" for="name">网址</label>
        <input type="text" class="form-control" name="site" style="width:100%;" placeholder="请输入网址">
      </div>
      <button type="submit" class="btn btn-default">提交</button>
    </form>
    <br>
    <table class="table table-striped">
          <tbody>
            @foreach($sites as $site)
                <tr>
                    <td><i class="fa fa-map"></i></td>
                    <td>{{ $site->site }}</td>
                    <td>
                        <input type="number" id="{{ $site->id }}" class="sort" value="{{ $site->sort }}" style="width: 60px;">
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <div id="myqr"></div>
</div>
	@push('scripts')
        <script src="/js/jquery.qrcode.js"></script>
        <script src="/js/utf.js"></script>
	    <script>
            $(".fa-map").mouseenter(function(){
                let url = $(this).parent().next().text();
                $('#myqr').text('').qrcode({
                     render    : "canvas",
                     text    : url,
                     width : "196",               //二维码的宽度
                     height : "196",              //二维码的高度
                     background : "#ffffff",       //二维码的后景色
                     foreground : "#000000",        //二维码的前景色
                     src: "/images/icon_logo.jpg"             //二维码中间的图片
                 });
                $('#myqr').show();
            }).mouseleave(function(){
                $('#myqr').hide();
            }); 
            $('.sort').blur(function(){
                let value = $(this).val();
                let id = $(this).attr("id");
                $.ajax({
                    url: '/qr/sort/' + id,
                    type: 'get',
                    dataType: 'json',
                    data: {value: value},
                    success: function(data) {
                        window.location.reload();
                    }
                })
            }) ;
	    </script>
	@endpush
@endsection