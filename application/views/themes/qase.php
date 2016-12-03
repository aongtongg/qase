<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=1280">
		<title><?php echo $title; ?></title>

    <!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="<?php echo root_url('images/favicon_cscmu.ico'); ?>" type="image/x-icon">

    <!-- CSS -->
		<link href="<?php echo base_url('assets/themes/qase/css/bootstrap-datepicker.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/themes/qase/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/themes/qase/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/themes/qase/css/custom.min.css'); ?>" rel="stylesheet">

		<link href="<?php echo root_url('css/reset.css'); ?>" rel="stylesheet">
		<link href="<?php echo root_url('css/style.css'); ?>" rel="stylesheet">
		<link href="<?php echo root_url('css/component.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/themes/qase/css/style.min.css'); ?>" rel="stylesheet">

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

		<style>
			/*body{font-family:"Sukhumvit Set";}*/
			.nav-form{width:100%;height:36px;background:url('<?php echo root_url('images/nav_menu_bg.png'); ?>');background-repeat:repeat-x;}
			.nav-head{float:left;width:145px;height:38px;line-height:38px;color:#313131;font-weight:bold;font-size:16px;background:url('<?php echo root_url('images/nav-l.png'); ?>');background-repeat:no-repeat;font-family:"Sukhumvit Set";}
			.nav-head:hover{color:#fdb5fe;font-weight:bold;background:url('<?php echo root_url('images/nav-t.png'); ?>');background-repeat:repeat-x;border-radius:3px;}
			.nav-head-end{float:left;width:2px;height:38px;background:url('<?php echo root_url('images/nav-l.png'); ?>');background-repeat:no-repeat;}
			.cpt{cursor:pointer;}
			.warper-h{margin-bottom:100px;}
			.niv-in{width:1024px;height:36px;}
			.niv-in-add{width:1024px;height:300px;}
			.import{width:100px;height:36px;background:none;font-size:16px;font-weight:bold;color:#bf8beb;font-family:"Sukhumvit Set";border:2px solid #bf8beb;}
			.import:hover{background:#ece6f2;}
			.va{vertical-align:middle;}
			.ta{text-align:center;}
			.nodata{width:924px;height:500px;line-height:500px;}
			.spc{float:left;width:100%;height:40px;}
			.inputt{float:left;width:344px;height:34px;line-height:34px;font-size:22px;padding:5px 5px 5px 10px;border:1px solid #d9d9d9;color:#313131;z-index:999}

			#wrapper>.bar{width:100%;height:45px;background:url(<?php echo root_url('images/research-b.jpg'); ?>);}
			#wrapper>.bar>.bar-form{float:right;width:600px;height:45px;line-height:45px;}
			#wrapper>.bar>.bar-form>.logout{float:right;width:130px;height:45px;line-height:45px;}
			#wrapper>.bar>.bar-form>.logout>input{width:120px;height:36px;line-height:28px;background:no-repeat rgb(50,50,50);border:1px solid rgb(212,160,255);border-radius:3px;font-size:18px;color:rgb(212,160,255);text-align:center;margin-top:4px;margin-left:5px}
			#wrapper>.bar>.bar-form>.logout>input:hover{background:#d4a0ff;background-repeat:repeat-x;color:#323232;border-radius:3px;}
			#wrapper>.bar>.bar-form>.role{float:right;width:100px;height:45px;line-height:45px;color:#d4a0ff;text-align:center;}
			#wrapper>.bar>.bar-form>.email{float:right;width:300px;height:45px;line-height:45px;color:#d4a0ff;text-align:center;}
		</style>
		<script>
				var BASE_URL = '<?php echo base_url(); ?>';
		</script>
	</head>
  <body>
		<header id="header">
	    <div id="headerbody">
        <div id="headerleft">
          <div id="headerlogo">
            <a href="<?php echo base_url(); ?>">
              <img src="<?php echo root_url('images/logo_img.png'); ?>" alt="">
            </a>
          </div>
          <div id="headertext">
						<img src="<?php echo root_url('images/logo_text.png'); ?>" alt="">
          </div>
        </div>
	    </div>
		</header>
		<center>
			<div class="nav-form">
				<center>
					<div class="niv-in">
						<div class="nav-head cpt" onclick="window.location.href='<?php echo root_url(); ?>'">Home</div>
						<div class="nav-head cpt" onclick="window.location.href='<?php echo base_url('admin'); ?>'" title="Quality Assurance">Quality Assurance</div>
						<?php if (isset($_SESSION['members_class']) && $_SESSION['members_class'] >= 2): ?>
						<div class="nav-head cpt" onclick="window.location.href='<?php echo base_url('teacher'); ?>'" title="อาจารย์">อาจารย์</div>
						<div class="nav-head cpt" onclick="window.location.href='<?php echo base_url('admin/courses'); ?>'" title="หลักสูตร">หลักสูตร</div>
						<div class="nav-head cpt" onclick="window.location.href='<?php echo base_url('admin/teacher_has_courses'); ?>'" title="หลักสูตร และบทบาท">หลักสูตร และบทบาท</div>
						<div class="nav-head cpt" onclick="window.location.href='<?php echo base_url('admin/roles'); ?>'" title="บทบาท">บทบาท</div>
						<?php endif; ?>
						<div class="nav-head-end"></div>
					</div>
				</center>
			</div>
		</center>
		<div id="wrap">
			<section id="main">
				<div id="wrapper">
					<div class="bar">
						<div class="bar-form">
							<?php if (isset($_SESSION['members_class']) && $_SESSION['members_class'] >= 0): ?>
							<div class="logout">
								<input type="button" onclick="window.location.href='<?php echo base_url('admin/logout'); ?>'" value="Signout" class="cpt">
							</div>
							<?php endif; ?>
							<?php
              function className($class)
              {
                  switch ($class) {
                      case '0':
                          $class = 'Normal';
                          break;
                      case '1':
                          $class = 'Viewer';
                          break;
                      case '2':
                          $class = 'Admin';
                          break;
                      case '3':
                          $class = 'Super Admin';
                          break;
                      default:
                          $class = 'Unknown';
                          break;
                  }

                  return $class;
              }
              ?>
							<div class="role"><?php echo isset($_SESSION['members_class']) ? className($_SESSION['members_class']) : ''; ?></div>
							<div class="email"><?php echo isset($_SESSION['members_email']) ? $_SESSION['members_email'] : ''; ?></div>
						</div>
					</div>
					<?php echo isset($breadcrumbs) ? $breadcrumbs : ''; ?>
		    	<?php if ($this->load->get_section('text_header') != ''): ?>
		    	<h1><?php echo $this->load->get_section('text_header'); ?></h1>
					<?php endif; ?>
				  <?php echo $output;?>
					<?php echo $this->load->get_section('sidebar'); ?>
				</div>
			</section>
		</div>
		<footer id="footer">
			<div id="footersitemap">
				<ul>
					<li>
						<ul>
							<li><a href="<?php echo root_url(); ?>">หน้าหลัก</a></li>
							<li><a href="<?php echo root_url(); ?>">เพิ่มงานวิจัย</a></li>
							<li><a href="<?php echo root_url(); ?>">แก้ไขงานวิจัย</a></li>
							<li><a href="<?php echo root_url(); ?>">API</a></li>
							<li><a href="<?php echo root_url(); ?>">Flush</a></li>
							<li><a href="<?php echo root_url(); ?>">Truncate</a></li>
							<li><a href="<?php echo root_url(); ?>">ติดต่อเรา</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div id="footerline"></div>
			<div id="footerbottom">
				<div id="footerbottomleft">
					<img src="<?php echo root_url('images/footer_logo.png'); ?>" alt="">
				</div>
				<div id="footerbottomline"></div>
				<div id="footerbottomcenter">
					<div>www.cs.science.cmu.ac.th</div>
					<div>Copyright 2011 Chiang Mai University, All right reserved</div>
					<div>จำนวนผู้เยี่ยมชม</div>
				</div>
			</div>
		</footer>
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
