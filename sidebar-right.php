<?php 
	if ( is_active_sidebar( 'sidebar-right' ) ){
		?>
		<div class="col-md-3">
			<?php dynamic_sidebar( 'sidebar-right' ); ?>
		</div>
		<?php
	}
?>