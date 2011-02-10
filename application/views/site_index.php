<?php if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')): ?>

<!DOCTYPE html>

<html>
	
	<head>
		<title>Syslog Web</title>
		<link href="/static/css/reset.css" media="screen" rel="stylesheet" type="text/css">
		<link href="/static/css/style.css" media="screen" rel="stylesheet" type="text/css">
		<script src="/static/js/jquery.1.5.js" type="text/javascript"></script>
		<script src="/static/js/jquery.bbq.js" type="text/javascript"></script>
	</head>

	<body>
		<div class="header">
			<img src="/static/images/logo.png" width="" height="" title="Logrr - A Syslog Logging Interface" alt="Logrr" />
			<ul class='nav'>
				<li class="current"><a href='/'>SysLog Messages</a></li>
				<li><a href='/'>Inputs</a></li>
				<li><a href='/'>Devices</a></li>
			</ul>
		</div>
	  
		<ul class='subnav'>
		</ul>
		
		<div id="main">
			
			<div class="bbq-loading" style="display:none;"><img src="/static/images/ajaxload-15-white.gif" alt="Loading"/>Loading content...</div>
			
<?php endif; ?>

			<h1 class='wi'>Log History</h1>
			<center><img src="/graph/base/day" /></center>
			
			<h1 class='wi'>Recent Syslog Messages</h1>
			<p class='intro'>The list below contains all recent syslog messages.</p>
			
			<table class='workers'>
				<tr>
					<th width="150">Date</th>
					<th width="100">Host</th>
					<th width="100">Tag</th>
					<th>Severity</th>
					<th width="*">Message</th>
				</tr>
				<?php foreach($logs as $log): ?>
				<tr>
					<td valign="top"><?php echo $log->ReceivedAt; ?></td>
					<td valign="top"><a href="/#/host/<?php echo $log->FromHost; ?>"><?php echo $log->FromHost; ?></a></td>
					<td valign="top"><?php echo str_replace(':', '', $log->SysLogTag); ?></td>
					<td valign="top">
						<center>
							<?php
								$class = 'error';
								switch ($log->Priority) {
									case '0':
									case '1':
									case '2':
									case '3':
									case '4':
									case '5':
										$class = 'error';
										break;
									case '6':
									case '7':
										$class = 'info';
										break;
								}
							?>
							<a class="<?php echo $class; ?>-tag" href="#/priority/<?php echo $log->Priority; ?>"><?php echo $priorities[$log->Priority]; ?></a>
						</center>
					</td>
					<td><?php echo $log->Message; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			
			<div class="">
				<?php echo $this->pagination->create_links(); ?>
			</div>
		
<?php if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')): ?>
		</div>
	
		<div id="footer">
			<p>Powered by <a href="http://github.com/pyhub/syslogweb">SyslogWeb</a> version 0.1</p>
		</div>
	
		<script type="text/javascript">
			$(function(){
  
				var cache = { '': $('.bbq-default') };
				
				$(window).bind( 'hashchange', function(e) {
				  
					var url = $.param.fragment();
					$( '#main' ).children( ':visible' ).hide();
					url && $( 'a[href="#' + url + '"]' ).addClass( 'bbq-current' );
					
					if (!url) url = '/';
					
					if ( cache[ url ] ) {
						cache[ url ].show();
					} else {
						$( '.bbq-loading' ).show();
						cache[ url ] = $( '<div class="bbq-item"/>' )
							.appendTo( '#main' )
							.load( url, function(){
								$( '.bbq-loading' ).hide();
							});
					}
						
				})
				
				$(window).trigger( 'hashchange' );
				
			});
		</script>
	
	</body>

</html>
<?php endif; ?>