<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title; ?></title>

    <!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="<?php echo base_url('assets/themes/qase/ico/favicon.png'); ?>" type="image/x-icon">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets/themes/qase/ico/apple-touch-icon-144-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets/themes/qase/ico/apple-touch-icon-114-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets/themes/qase/ico/apple-touch-icon-72-precomposed.png'); ?>">
		<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/themes/qase/ico/apple-touch-icon-57-precomposed.png'); ?>">

    <!-- CSS -->
		<link href="<?php echo base_url('assets/themes/qase/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/themes/qase/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/themes/qase/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/themes/qase/css/custom.min.css'); ?>" rel="stylesheet">

		<script src="<?php echo base_url('assets/themes/qase/js/jquery-1.12.4.min.js'); ?>"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

		<?php
    /** -- Copy from here -- */
    if (!empty($meta)) {
        foreach ($meta as $name => $content) {
            echo "\n\t\t";
            ?><meta name="<?php echo $name;
            ?>" content="<?php echo $content;
            ?>" /><?php

        }
    }
    echo "\n";

    if (!empty($canonical)) {
        echo "\n\t\t";
        ?><link rel="canonical" href="<?php echo $canonical?>" /><?php

    }
    echo "\n\t";

    foreach ($css as $file) {
        echo "\n\t\t";
        ?><link rel="stylesheet" href="<?php echo $file;
        ?>" type="text/css" /><?php

    } echo "\n\t";

    foreach ($js as $file) {
        echo "\n\t\t";
        ?><script src="<?php echo $file;
        ?>"></script><?php

    } echo "\n\t";

    /* -- to here -- */
		?>

	</head>
  <body>
		<nav class="navbar navbar-default navbar-fixed-top store-header">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Quality Assurance</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				<a class="navbar-brand" href="<?php echo base_url(); ?>"><font class="banner">Quality Assurance</font></a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class=""><a href="<?php echo base_url('teacher'); ?>" title="อาจารย์">อาจารย์</a></li>
					<li class=""><a href="<?php echo base_url('admin'); ?>" title="ระบบหลังบ้าน">ระบบหลังบ้าน</a></li>
					<!--<li class="dropdown ">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" title="test">test <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class=""><a href="/stores/glasses" title="test sub">test sub</a></li>
						</ul>
					</li>-->
				</ul>
			</div>
			</div>
		</nav>
    <div class="container">
    	<?php if ($this->load->get_section('text_header') != '') { ?>
    	<h1><?php echo $this->load->get_section('text_header'); ?></h1>
    	<?php } ?>
		  <?php echo $output;?>
			<?php echo $this->load->get_section('sidebar'); ?>
      <hr/>
      <footer>
      	<div class="row">
	        <div class="col-xs-6">
						Copyright &copy; <a href="<?php echo base_url(); ?>">QASE</a>
	        </div>
        </div>
      </footer>
    </div>
		<script src="<?php echo base_url('assets/themes/qase/js/bootstrap-datepicker.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/qase/js/bootstrap.min.js'); ?>"></script>
		<script>
			$(document).ready(function(){
				var height = $('body>nav.navbar').height();
				$('body').css('padding-top', height);
				//var heightFooter = $('body>.footer').height() - 58;
				//$('body>.container').css('padding-bottom', heightFooter);
				$( window ).resize(function() {
					var height = $('body>nav.navbar').height();
					$('body').css('padding-top', height);
					//var heightFooter = $('body>.footer').height() - 58;
					//$('body>.container').css('padding-bottom', heightFooter);
				});
			});
		</script>
	</body>
</html>
