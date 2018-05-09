@extends('main')

@section('title')
<title>index</title>
@endsection
@section('keywords')
<meta name="keywords" content="index page">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('description')
<meta name="description" content="home page">
@endsection

@section('content')
<div class="container">
    <div class="content">
        <div class="show-area"></div>
        <div class="write-area">
            <div><input name="name" id="name" type="text" placeholder="input your name" required="required"></div>
            <div>
                <input name="message" id="message" required="required" placeholder="input your message...">
            </div>   
            <div><button class="btn btn-default send">发送</button></div>
            <div><button class="btn btn-success rec">重连</button></div>                 
        </div>
    </div>
 </div>
	@push('scripts')
	    <script>
	    	//websocket
    $(function(){
            var wsurl = 'ws://localhost:6001/echo?channels=public,econo';
            var websocket;
            var i = 0;

                websocket = new WebSocket(wsurl);

                //连接建立
                websocket.onopen = function(evevt){
                    console.log("Connected to WebSocket server.");
                    $('.show-area').append('<p class="bg-info message"><i class="icon-cog"></i>Connected to WebSocket server!</p>');
                }
                //收到消息
                websocket.onmessage = function(event) {
                    var msg = JSON.parse(event.data); //解析收到的json消息数据
                    console.log(msg);
                    var type = msg.type; // 消息类型
                    var umsg = msg.event; //消息文本
                    var uname = msg.data.token; //发送人
                    i++;
                    //if(type == 'usermsg'){
                        $('.show-area').append('<p class="bg-success message"><a name="'+i+'"></a><span class="label label-info">'+uname+':</span>&nbsp;'+umsg+'</p>');
                    //}
                    // if(type == 'system'){
                    //     $('.show-area').append('<p class="bg-warning message"><a name="'+i+'"></a><i class="icon-cog"></i>'+umsg+'</p>');
                    // }
                    
                    //$('#message').val(''); 
                    //window.location.hash = '#'+i;
                }

                //发生错误
                websocket.onerror = function(event){
                    i++;
                    console.log("Connected to WebSocket server error");
                    $('.show-area').append('<p class="bg-danger message"><a name="'+i+'"></a><i class="icon-cog"></i>Connect to WebSocket server error.</p>');
                    window.location.hash = '#'+i;
                }

                //连接关闭
                websocket.onclose = function(event){
                    i++;
                    console.log('websocket Connection Closed. ');
                    $('.show-area').append('<p class="bg-warning message"><a name="'+i+'"></a><i class="icon-cog"></i>websocket Connection Closed.</p>');
                    window.location.hash = '#'+i;
                }

                function send(){
                    var name = $('#name').val();
                    var message = $('#message').val();
                    if(!name){
                        alert('请输入用户名!');
                        return false;
                    }
                    if(!message){
                        alert('发送消息不能为空!');
                        return false;
                    }
                    var msg = {
                        message: message,
                        name: name
                    };
                    try{  
                        websocket.send(JSON.stringify(msg)); 
                    } catch(ex) {  
                        console.log(ex);
                    }  
                }

                //按下enter键发送消息
                $(window).keydown(function(event){
                    if(event.keyCode == 13){
                        console.log('user enter');
                        send();
                    }
                });

                //点发送按钮发送消息
                $('.send').bind('click',function(){
                    send();
                });            
}); 
	    </script>
	@endpush
@endsection