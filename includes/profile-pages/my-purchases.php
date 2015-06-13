<?php
$purchases = new WP_Query(array(
	'post_type' => 'voucher',
	'posts_per_page' => '-1',
	'meta_query' => array(
		array(
			'key' => 'voucher_buyer_id',
			'value' => $current_user->ID,
			'compare' => '='
		)
	)
));

?>
<p class="pretable-loading"><?php _e( 'Loading...', 'couponxl' ) ?></p>
<div class="bt-table">
	<table data-toggle="table" data-search="true" data-classes="table table-striped">
		<thead>
		    <tr>
		        <th data-field="deal" data-sortable="true">
		        	<?php _e( 'Deal', 'couponxl' ); ?>
		        </th>
		        <th data-field="code" data-sortable="true">
		            <?php _e( 'Voucher Code', 'couponxl' ); ?>
		        </th>
		        <th data-field="status" data-sortable="true">
		            <?php _e( 'Voucher Status', 'couponxl' ); ?>
		        </th>
		        <th data-field="date" data-sortable="true">
		            <?php _e( 'Purchase Date', 'couponxl' ); ?>
		        </th>
		        <th data-field="price" data-sortable="true">
		            <?php _e( 'Price', 'couponxl' ); ?>
		        </th>	        
		    </tr>
		</thead>
		<?php

		if( $purchases->have_posts() ){
			?>
			<tbody>
				<?php
				while( $purchases->have_posts() ){
					$purchases->the_post();
					$deal = get_post( get_post_meta( get_the_ID(), 'voucher_deal', true ) );
					?>
					<tr>
						<td>
							<a href="<?php echo get_permalink( $deal->ID ); ?>" target="_blank">
								<?php echo $deal->post_title ?>
							</a>
						</td>
						<td>
							<?php echo get_post_meta( get_the_ID(), 'voucher_code', true ); ?>
						</td>
						<td>
							<?php 
							$voucher_status = get_post_meta( get_the_ID(), 'voucher_status', true );
							if( $voucher_status == 'used' ){
								echo _e( 'Used', 'couponxl' );
							}
							else{
								$voucher_expire = get_post_meta( $deal->ID, 'deal_voucher_expire', true );
								if( !empty( $voucher_expire ) && $voucher_expire <= current_time( 'timestamp' ) ){
									echo _e( 'Expired', 'couponxl' );
								}
								else{
									echo _e( 'Not Used', 'couponxl' );
								}
							} 
							?> 
						</td>
						<td>
							<?php the_time( get_option( get_option( 'date_format' ) ) ); ?>
						</td>
						<td>
							<?php echo couponxl_format_price_number( couponxl_get_deal_amount( $deal->ID ) ); ?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<?php
		}

		wp_reset_query();
		?>
	</table>
</div>