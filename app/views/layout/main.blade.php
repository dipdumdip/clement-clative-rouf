<!DOCTYPE html>
<html>
	<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=IE8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> {{( !empty($meta_data['title']) && isset($meta_data['title']) )? $meta_data['title'] : 'Home Page '}} </title>
      <meta property="og:image" content="{{(!empty($meta_data['fb_image']) && isset($meta_data['fb_image'])) ? $meta_data['fb_image'] : 'default.jpg'}}" />
      <meta property="og:type" content="news" />
      <meta property="og:title" content="{{(!empty($meta_data['fb_title']) && isset($meta_data['fb_title']) ) ? $meta_data['fb_title'] : ''}}" />
      <meta property="og:description" content="{{(!empty($meta_data['metaDescription']) && isset($meta_data['metaDescription'])) ? $meta_data['metaDescription'] : 'Free online .'}}" />

    <link rel='shortcut icon' type='image/x-icon' href="{{ URL::asset('img/favicon.ico')}}"/>
    <meta name="description" content="{{(!empty($meta_data['metaKeywords']) && isset($meta_data['metaKeywords']) ) ? $meta_data['metaKeywords'] : 'Free,'}}" />
    <meta name="keywords" content="{{(!empty($meta_data['metaDescription']) && isset($meta_data['metaDescription'])) ? $meta_data['metaDescription'] : 'Free online .'}}"/>
   <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/new_total.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ximGlue_dash__.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.form.js.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/body_sript.js') }}"></script>
</head>
<body>
  <div class="wrapper">
    <div class="row row-offcanvas row-offcanvas-left">
         <!-- main  col -->
        <div class="column col-sm-12 col-xs-12" id="main">
                <!-- top nav -->
          @include('layout.navigation')
          @if(Session::has('global'))
            <div class="row row-offcanvas row-offcanvas-left">{{Session::get('global')}}</div>
          @endif

            <!-- /main -->
          @yield('content')
        </div>
    </div>
    <footer class="text-center">This Bootstrap 3 dashboard layout is compliments of <a href="http://www.bootply.com/85850"><strong>Bootply.com</strong></a></footer>
  </div>


  <!-- script references -->
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/scripts.js') }}"></script>
</body>
</html>