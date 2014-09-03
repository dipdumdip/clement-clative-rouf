<?php 
 //error_reporting(0);
 
$title = (!empty($meta_data['title']) && isset($meta_data['title']) )? $meta_data['title'] : 'Home Page ';
$metaKeywords = (!empty($meta_data['metaKeywords']) && isset($meta_data['metaKeywords']) ) ? $meta_data['metaKeywords'] : 'Free,';
$metaDescription = (!empty($meta_data['metaDescription']) && isset($meta_data['metaDescription'])) ? $meta_data['metaDescription'] : 'Free online .';
$fb_title = (!empty($meta_data['fb_title']) && isset($meta_data['fb_title']) ) ? $meta_data['fb_title'] : $title;
$fb_image = (!empty($meta_data['fb_image']) && isset($meta_data['fb_image'])) ? $meta_data['fb_image'] : return_image('default');
$fb_url = (isset($meta_data['fb_url']) && !empty($meta_data['fb_url'])) ? ($meta_data['fb_url']) : $_SERVER['REQUEST_URI'];
	
	$company_symbol = (isset($meta_data['company_symbol']) && !empty($meta_data['company_symbol'])) ? $meta_data['company_symbol'] : '';
	$precision = (isset($meta_data['precision']) && !empty($meta_data['precision'])) ? $meta_data['precision'] : 1;

?>
<!DOCTYPE html>
<html>
	<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=IE8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title> <?php echo $title;?> </title>
			<meta property="og:url" content="<?php echo "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]".$fb_url; ?>" />
			<meta property="og:image" content="<?php echo $fb_image; ?>" />
			<meta property="og:type" content="news" />
			<meta property="og:title" content=" <?php echo $fb_title;?>" />
			<meta property="og:description" content=" <?php echo $metaDescription;?>" />

		<link rel='shortcut icon' type='image/x-icon' href="<?php echo base_url(); ?>img/favicon.ico"/>
		<meta name="description" content=" <?php echo $metaDescription; ?> " />
		<meta name="keywords" content="<?php echo $metaKeywords; ?>"/>
    <!-- Bootstrap -->
    <?php echo link_tag('css/bootstrap.min.css'); ?>
    <?php echo link_tag('css/styles.css'); ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
		<?php echo link_tag('css/jquery-ui.css'); ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/body_sript.js"></script>
		<script  type="text/javascript"> <?php echo JS_APP_URL; ?> </script>
	</head>
  <body>
    <div class="wrapper">
        <div class="row row-offcanvas row-offcanvas-left">
         
            <!-- main  col -->
            <div class="column col-sm-12 col-xs-12" id="main">
                
                <!-- top nav -->
                <div class="navbar navbar-blue navbar-static-top">  
                    <div class="navbar-header">
                      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle</span>
                        <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                      </button>
                      <a href="/" class="navbar-brand logo">b</a>
                    </div>
                    <nav class="collapse navbar-collapse" role="navigation">
                    <form class="navbar-form navbar-left">
                        <div class="input-group input-group-sm" style="max-width:360px;">
                          <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                          <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                          </div>
                        </div>
                    </form>
                    <ul class="nav navbar-nav">
                      <li>
                        <a href="#"><i class="glyphicon glyphicon-home"></i> Home</a>
                      </li>
                      <li>
                        <a href="#postModal" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Post</a>
                      </li>
                      <li>
                        <a href="#"><span class="badge">badge</span></a>
                      </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
                        <ul class="dropdown-menu">
                          <li><a href="">More</a></li>
                          <li><a href="">More</a></li>
                          <li><a href="">More</a></li>
                          <li><a href="">More</a></li>
                          <li><a href="">More</a></li>
                        </ul>
                      </li>
                    </ul>
                    </nav>
                </div>
                <!-- /top nav -->
              