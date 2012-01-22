<!DOCTYPE html>
<html>
<head>
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
	<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
	<link rel="stylesheet" href="/resources/prime.css">
	<title>Squariz</title>
	<?php Yii::app()->clientScript->registerCoreScript('jquery')?>
	<script src="/resources/bootstrap-modal.js"></script>
</head>
<body>
<div class="topbar">
	<div class="fill">
		<div class="container">
			<a class="brand" href="/">Squariz</a>
			
			<ul class="nav">
	        	<li class="active"><?php echo CHtml::link('Your account', array('site/profile'))?></li>
	        </ul>
		</div>
	</div>
</div>

<div class="container">
	<div class="content">
		<div class="page-header">
		<h1><?php echo $this->pageHeader?></h1>
		</div>
		<div class="row">
			<div class="span14">
			<?php echo $content?>
			</div>
		</div>
	</div>

	<footer>
		<p>© <?php echo date('Y')?>, Disruptive Technologies, Inc. - <a href="https://twitter.com/disruptiveio">@disruptive.io</a></p>
	</footer>

</div>
</body>
</html>