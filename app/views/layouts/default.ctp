<?php
//Make sure these are always added first into asset before your stuff in views
$this->Html->script(array('plugins','script'),array('inline'=>false));
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  
  <!-- www.phpied.com/conditional-comments-block-downloads/ -->
  <!--[if IE]><![endif]-->

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <title><?php echo $title_for_layout;?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!--  Mobile viewport optimized: j.mp/bplateviewport -->
  <!-- <meta name="viewport" content="width=device-width; initial-scale=1.0"> -->

  <link rel="shortcut icon" href="<?php echo Router::url('/teammaker.ico'); ?>">
  <link rel="apple-touch-icon" href="<?php echo Router::url('/teammaker_32.png'); ?>">
         
  <!-- Moving jquery to the top, cuz some jquery plugins don't play with with it at the bottom of the page -->
  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
  <!-- JQuery UI -->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo Router::url('/js/jquery-1.4.4.min.js'); ?>"%3E%3C/script%3E'))</script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo Router::url('/js/jquery-1.8.7-ui.min.js'); ?>"%3E%3C/script%3E'))</script>
        
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/cupertino/jquery-ui.css">
  <?php   	  	
	$this->Html->css(array('style','cake.generic', 'superfish','custom'),NULL,array('inline'=>false));	
	echo $asset->scripts_for_layout('css');	
	
	//Don't include handheld in asset because it needs media="handheld"
	echo $this->Html->css(array('handheld'),null,array('media'=>'handheld'));	
	
	echo $this->Html->css('http://fonts.googleapis.com/css?family=Ubuntu',NULL,array('inline'=>true));
  ?>
  
</head>

<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

  <div id="container">
    <header id="header">
	<?php echo $this->element('header');?>
    </header>
    
    <div id="content">
	<?php echo $content_for_layout ?>
    </div>
    
    <footer id="footer">
	<?php echo $this->element('footer');?>
    </footer>
  </div> <!--! end of #container -->


  <!-- Javascript at the bottom for fast page loading -->
 
  <?php 
	echo $asset->scripts_for_layout('js');
	echo $asset->scripts_for_layout('codeblock');
  ?>

  <!--[if lt IE 7 ]>
	<?php echo $html->script('dd_belatedpng')?>
  <![endif]-->
</body>
</html>