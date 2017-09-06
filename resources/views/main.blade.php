<!doctype html>
<html lang="zh">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 minimum-scale=1.0 maximum-scale=1.0 user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @yield('title')
    @yield('keywords')
    @yield('description')
    <link rel="stylesheet" href="{{ mix('/css/all.css') }}">
    <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
    <!-- HTML5 Shim 和 Respond.js 用于让 IE8 支持 HTML5元素和媒体查询 -->
      <!-- 注意： 如果通过 file://  引入 Respond.js 文件，则该文件无法起效果 -->
      <!--[if lt IE 9]>
         <script src="/js/html5shiv.js"></script>
         <script src="/js/respond.min.js"></script>
      <![endif]-->
</head>
<body>
    <header class="alert alert-success">
        header <i class="fa fa-clock-o"></i>
    </header>
    @yield('content')
    <footer class="alert alert-success" id="footer">
        footer
    </footer>
    <div id="ajax-status"> </div>
    <div id="back-to-top"><span class="fa fa-arrow-up fa-3x"></span></div>
</body>
<script src="{{ mix('/js/all.js') }}"></script>
</html>