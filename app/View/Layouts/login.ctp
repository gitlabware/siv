<!DOCTYPE html>
<!--[if IEMobile 7]><html class="no-js iem7 oldie linen"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie linen" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie linen" lang="en"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9 linen" lang="en"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js linen" lang="en"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Sistema Inventarios</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- http://davidbcalhoun.com/2010/viewport-metatag -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<!-- For all browsers -->
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/reset.css?v=1">
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/style.css?v=1">
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/colors.css?v=1">
	<link rel="stylesheet" media="print" href="<?php echo $this->webroot; ?>css/print.css?v=1">
	<!-- For progressively larger displays -->
	<link rel="stylesheet" media="only all and (min-width: 480px)" href="<?php echo $this->webroot; ?>css/480.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 768px)" href="<?php echo $this->webroot; ?>css/768.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 992px)" href="<?php echo $this->webroot; ?>css/992.css?v=1">
	<link rel="stylesheet" media="only all and (min-width: 1200px)" href="<?php echo $this->webroot; ?>css/1200.css?v=1">
	<!-- For Retina displays -->
	<link rel="stylesheet" media="only all and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5)" href="<?php echo $this->webroot; ?>css/2x.css?v=1">

	<!-- Additional styles -->
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/styles/form.css?v=1">
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/styles/switches.css?v=1">

	<!-- Login pages styles -->
	<link rel="stylesheet" media="screen" href="<?php echo $this->webroot; ?>css/login.css?v=1">

	<!-- JavaScript at bottom except for Modernizr -->
	<script src="<?php echo $this->webroot; ?>js/libs/modernizr.custom.js"></script>

	<!-- For Modern Browsers -->
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>img/favicons/favicon.png">
	<!-- For everything else -->
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>img/favicons/favicon.ico">
	<!-- For retina screens -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->webroot; ?>img/favicons/apple-touch-icon-retina.png">
	<!-- For iPad 1-->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->webroot; ?>img/favicons/apple-touch-icon-ipad.png">
	<!-- For iPhone 3G, iPod Touch and Android -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo $this->webroot; ?>img/favicons/apple-touch-icon.png">

	<!-- iOS web-app metas -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- Startup image for web apps -->
	<link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
	<link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
	<link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/iphone.png" media="screen and (max-device-width: 320px)">

	<!-- Microsoft clear type rendering -->
	<meta http-equiv="cleartype" content="on">

	<!-- IE9 Pinned Sites: http://msdn.microsoft.com/en-us/library/gg131029.aspx -->
	<meta name="application-name" content="Developr Admin Skin">
	<meta name="msapplication-tooltip" content="Cross-platform admin template.">
	<meta name="msapplication-starturl" content="http://www.display-inline.fr/demo/developr">
	<!-- These custom tasks are examples, you need to edit them to show actual pages -->
	<meta name="msapplication-task" content="name=Agenda;action-uri=http://www.display-inline.fr/demo/developr/agenda.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">
	<meta name="msapplication-task" content="name=My profile;action-uri=http://www.display-inline.fr/demo/developr/profile.html;icon-uri=http://www.display-inline.fr/demo/developr/img/favicons/favicon.ico">
</head>

<body dir="rtl">

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>

	<!-- JavaScript at the bottom for fast page loading -->

	<!-- Scripts -->
	<script src="<?php echo $this->webroot; ?>js/libs/jquery-1.8.2.min.js"></script>
	<script src="<?php echo $this->webroot; ?>js/setup.js"></script>

	<!-- Template functions -->
	<script src="<?php echo $this->webroot; ?>js/developr.input.js"></script>
	<script src="<?php echo $this->webroot; ?>js/developr.message.js"></script>
	<!--<script src="<?php echo $this->webroot; ?>js/developr.notify.js"></script>-->
	<script src="<?php echo $this->webroot; ?>js/developr.tooltip.js"></script>

</body>
</html>