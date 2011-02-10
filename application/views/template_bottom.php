<?php if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')): ?>
		</div>
	
		<div id="footer">
			<p>Powered by <a href="rsyslog-web">SyslogWeb</a> version 0.1</p>
		</div>
	
		<script type="text/javascript">
			$(function(){
  
				var cache = { '': $('.bbq-default') };
				
				$(window).bind( 'hashchange', function(e) {
					
					$( '#main' ).children( ':visible' ).hide();
					$( '.current' ).removeClass( 'current' );
					
					var url = $.param.fragment();
					if (!url) url = '/messages';
					url && $( '.subnav a[href="#' + url + '"]' ).parent().addClass( 'current' );
					
                    var path = url.split('/');
                    path = path[1];
                    path && $( '.header a[href="#/' + path + '"]' ).parent().addClass( 'current' );
                    
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