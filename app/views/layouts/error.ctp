<?php
//Make sure these are always added first to asset (before stuff in your views)
$this->Html->script(array('plugins','script'),array('inline'=>false));
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  <!--[if IE]><![endif]-->

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <title><?php echo $title_for_layout;?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width; initial-scale=1.0">

  <link rel="shortcut icon" href="<?php echo Router::url('/teammaker.ico'); ?>">
  <link rel="apple-touch-icon" href="<?php echo Router::url('/teammaker_32.png'); ?>">
                
  <?php   	  	
	$this->Html->css(array('style','cake.generic','custom'),NULL,array('inline'=>false));		
	echo $asset->scripts_for_layout('css');
	
	//Don't include handheld in asset because it needs media="handheld"
	echo $this->Html->css(array('handheld'),null,array('media'=>'handheld'));	
	
	//Example of how to use google webfonts - see webroot/css/custom.css
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
    <h1>Oops there was an error</h1>
	<?php echo $content_for_layout ?>
    </div>
    
    <footer id="footer">
	<?php echo $this->element('footer');?>
    </footer>
  </div> <!--! end of #container -->

  <!-- Javascript at the bottom for fast page loading -->
  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="/js/jquery-1.4.3.min.js"%3E%3C/script%3E'))</script>

<?php
  	echo $asset->scripts_for_layout('js');
?>

  <!--[if lt IE 7 ]>
	<?php echo $html->script('dd_belatedpng')?>
  <![endif]-->  
</body>
</html>