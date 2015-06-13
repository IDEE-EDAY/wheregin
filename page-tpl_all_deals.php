<?php
/*
	Template Name: All Deals
*/	
get_header();

$search_sidebar_location = couponxl_get_option( 'search_sidebar_location' );

$cur_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; //get curent page
$args = array(
	'post_status' => 'publish',
	'posts_per_page' => couponxl_get_option( 'offers_per_page' ),
	'post_type'	=> 'offer',
	'paged' => $cur_page,
	'orderby' => 'meta_value_num',
	'meta_key' => 'offer_expire',
	'order' => 'ASC',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'offer_start',
			'value' => current_time( 'timestamp' ),
			'compare' => '<='
		),
		array(
			'relation' => 'OR',
			array(
				'key' => 'offer_expire',
				'value' => current_time( 'timestamp' ),
				'compare' => '>='
			),
			array(
				'key' => 'offer_expire',
				'value' => '-1',
				'compare' => '='
			),
		),
        array(
            'relation' => 'OR',
            array(
                'key' => 'deal_status',
                'value' => 'sold_out',
                'compare' => '!='
            ),
            array(
                'key' => 'deal_status',
                'value' => 'sold_out',
                'compare' => 'NOT EXISTS'
            ),
        ),
        array(
        	'key' => 'offer_type',
        	'value' => 'deal',
        	'compare' => '='
        )
	),
	'tax_query' => array(
		'relation' => 'AND'
	)
);

if( !empty( $offer_sort ) ){
    $temp = explode( '-', $offer_sort );
    if( $temp[0] == 'date' ){
        unset( $args['meta_key'] );
        $args['orderby'] = $temp[0];
        $args['order'] = $temp[1];
    }
    else{
        if( $temp[0] == 'rate' ){
            $temp[0] = 'couponxl_average_rate';
        }
        $args['meta_key'] = $temp[0];
        $args['order'] = $temp[1];
    }
}

$deals = new WP_Query( $args );

$page_links_total =  $deals->max_num_pages;
$page_links = paginate_links( 
	array(
		'prev_next' => true,
		'end_size' => 2,
		'mid_size' => 2,
		'total' => $page_links_total,
		'current' => $cur_page,	
		'prev_next' => false,
		'type' => 'array'
	)
);

$pagination = couponxl_format_pagination( $page_links );
get_template_part( 'includes/title' );
?>
<section>
	<div class="container">

        <?php 
		        
        $content = get_the_content();
        if( !empty( $content ) ):
        ?>
            <div class="white-block">
                <div class="white-block-content">
                    <div class="page-content clearfix">
                        <?php echo apply_filters( 'the_content', $content ) ?>
                    </div>
                </div>
            </div>
        <?php
        endif;
        ?>
	
		<div class="row">

			<?php if( $search_sidebar_location == 'left' ): ?>
				<?php get_sidebar( 'deal' ) ?>
			<?php endif; ?>

			<div class="col-sm-<?php echo is_active_sidebar( 'sidebar-deal' ) ? '9' : '12' ?>">
				<?php 
				$permalink = couponxl_get_permalink_by_tpl( 'page-tpl_all_deals' );
				include( locate_template( 'includes/filter-bar.php' ) );

				if( $deals->have_posts() ): ?>
        			<div class="row masonry">
            			<?php
                        $col = is_active_sidebar( 'sidebar-search' ) ? '6' : '4';
                        if( $offer_view == 'list' ){
                            $col = 12;
                        }            			
            			while( $deals->have_posts() ){
            				$deals->the_post();
            				?>
            				<div class="col-sm-<?php echo esc_attr( $col ) ?> masonry-item">
            					<?php include( locate_template( 'includes/offers/offers.php' ) ); ?>
            				</div>
            				<?php
            			}
            			?>
                        <?php if( !empty( $pagination ) ): ?>
            			    <div class="col-sm-<?php echo  esc_attr( $col ) ?> masonry-item">
            			    	<ul class="pagination">
	            					<?php echo $pagination; ?>
	            				</ul>
            			    </div>
                        <?php endif; ?>
        			</div>
				<?php endif; ?>
			</div>


			<?php if( $search_sidebar_location == 'right' ): ?>
				<?php get_sidebar( 'deal' ) ?>
			<?php endif; ?>				
			
		</div>
	</div>
</section>
<?php get_footer(); ?>