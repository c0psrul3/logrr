<?php if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')): ?>

<!DOCTYPE html>

<html>
	
	<head>
		<title>Logrr</title>
		<link href="/static/css/reset.css" media="screen" rel="stylesheet" type="text/css">
		<link href="/static/css/style.css" media="screen" rel="stylesheet" type="text/css">
		<script src="/static/js/jquery.1.5.js" type="text/javascript"></script>
		<script src="/static/js/jquery.bbq.js" type="text/javascript"></script>
	</head>

	<body>
		<div class="header">
			<img src="/static/images/logo.png" width="" height="" title="Logrr - A Syslog Logging Interface" alt="Logrr" />
			<ul class='nav'>
				<li class="current"><a href='#/messages'>SysLog Messages</a></li>
				<li><a href="#/inputs">Inputs</a></li>
				<li><a href="#/devices">Devices</a></li>
			</ul>
		</div>
	  
		<ul class='subnav'>
			<li class="current"><a href="#/messages">All Messages</a></li>
			<?php foreach ($priorities as $key => $value): ?>
				<li><a href="#/messages/priority/<?php echo $key; ?>"><?php echo $value; ?></a></li>
			<?php endforeach; ?>
			<li class="search">
				<form action="/messages/search" method="GET">
					Search SysLog Messages: <input type="text" name="q" value=""> <button type="submit">Search</button>
				</form>
			</li>
		</ul>
		
		<div id="main">
			
			<div class="bbq-loading" style="display:none;"><img src="/static/images/ajaxload-15-white.gif" alt="Loading"/>&nbsp;Loading content...</div>
			
<?php endif; ?>