<?php 

	$custom_query_args = wp_parse_args(array( 
		'posts_per_page' => $showposts,  
		'nopaging' => 0,  
		'post_status' => 'publish', 
		'ignore_sticky_posts' => true, 
		'category_name' => $ntax, 
		'category__not_in' => array($exclude), 
		'offset' => $offset, 
		'order'             => $order,
		'post_type' => $posttype ,
		'orderby' => $orderby
		));

	

	// Get current page and append to custom query parameters array
	if ( get_query_var( 'paged' ) ) {
	    $custom_query_args['paged'] = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
	    $custom_query_args['paged'] = get_query_var( 'page' );
	} else {
	    $custom_query_args['paged'] = 1;
	}

	// Instantiate custom query
	$custom_query = new WP_Query( $custom_query_args );



	$terms1 = get_terms($terms, array(
				 	'hide_empty' => 1,
					'orderby'  => 'count', 
					'order'             => $order,
					'number' => $filter,
					'parent' => 0,
					'hide_empty' => true,
				 )); 
		       	$count = count($terms1); 
		        if($hide_filter !== 'yes'): 
				echo '<div class="mimo-masonry-filter"><ul class="mimo-masonry-filter-ul">';  
		        echo '<li ><a class="selected"  href="#" data-filter="*" title=".post">All</a></li>';  
		        if ( $count > 0 ){  
					$replaced = array(' ', '<', '>', '&', '{', '}', '*', 'amp;');
		            foreach ( $terms1 as $term ) {  
		  				$termname = strtolower($term->slug);  
		                echo '<li class="'.esc_html($termname).'"><a href="#" title=".post" data-filter=".'.esc_html($termname).'">'.esc_html($term->name).'</a></li>';  
		            }  
		        }  
		        echo '</ul><div class="clear"></div></div>'; 
		    	endif;

// Output custom query loop
if ( $custom_query->have_posts() ) :  ?>
<div class="mimo-masonry-container mimo-masonry-<?php echo esc_html($posttype); ?>">
	<div class="mimo-masonry-masonry-widget mimo-masonry-masonry-<?php echo esc_html($columns); ?> <?php if($infinite == 'auto' || $infinite == 'button') : ?> mimo-masonry-infinite-widget<?php endif; ?> <?php if($infinite == 'auto' ) : ?>mimo-masonry-infinite-auto<?php endif; ?> <?php if($infinite == 'button' ) : ?>mimo-masonry-infinite-button<?php endif; ?>">
		<?php 
			do_action('mimo_masonry_before_loop'); 
			while ( $custom_query->have_posts() ) :

		        $custom_query->the_post(); 
		    	$mimo_masonry_classes = Mimo_Masonry::mimo_masonry_get_cat_slug($terms);
		    	$mimo_masonry_classes .= ' mimo-masonry-masonry-item';


		    	  ?>
					<article id="post-<?php the_ID(); ?>"  <?php post_class($mimo_masonry_classes); ?>>
					<div class="mimo-masonry-post-container" >
						<div class="mimo-masonry-inside-post-container " >
						<?php do_action('mimo_masonry_before_content'); ?>
							<?php if(is_sticky( ) && ! is_single() ) : ?>
								<div class="mimo-masonry-sticky"><i class="fa fa-star"></i></div>
							<?php endif;  ?>

							<?php if($hide_image == 'no') : Mimo_Masonry::mimo_masonry_blog_slider($imagesize); endif; ?>

							<div class="mimo-masonry-all<?php if ( has_post_thumbnail() && ! post_password_required() ) :  ?>-content content<?php endif; ?> <?php if ( ! has_post_thumbnail() && ! post_password_required() ) :  ?>mimo-masonry-top-border<?php endif; ?>">
								<div class="mimo-masonry-inside-content">
								<?php if($hide_meta == 'no') : ?>

										<div class="mimo-masonry-meta-top">
											<?php 	$category = get_the_terms(get_the_id(), $terms); 
											if($category):
													$term = array_pop($category);
													if($term ){
														echo '<a href="'.esc_url( get_term_link($term->slug , $terms ) ).'">'.esc_html($term->name).'</a>';
													}; 
											endif;	 ?>
										</div>

									<?php endif; ?>
									<!-- Show title -->
									<?php if($hide_title == 'no'): ?>
												<div class="mimo-masonry-entry-header">
													<h2 class="mimo-masonry-entry-title">
														<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), $title_number ); ?></a>
													</h2>
												</div>

									<?php endif; 
									//Show Excerpt
									if($hide_excerpt == 'no') :
										?><div class="mimo-masonry-excerpt">
											<?php echo wp_trim_words(get_the_excerpt(),$excerpt_number); ?>
										</div> <?php	
									endif; ?>
									<!-- Show meta -->
									
									
									
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
							<?php do_action('mimo_masonry_after_content'); ?>
						</div>
					</div>
				</article><!-- #post -->

				<?php endwhile; 
				do_action('mimo_masonry_after_loop'); ?>
    </div>
    
    </div>		
    <?php endif;  ?>
    <?php if($hide_pagination !== 'yes') : ?>
    <div class="mimo-masonry-page-nav" role="navigation">
		<span class="screen-reader-text"><?php __( 'Posts navigation', 'mimo-masonry' ); ?></span>
		<nav class="mimo-masonry-nav-links">

			<?php previous_posts_link( 'Previous Posts' ); ?>
			<?php 	next_posts_link( 'Next Posts', $custom_query->max_num_pages ); ?>
		</nav><!-- .nav-links -->
		<div class="clear"></div>
		<Div class="mimo-masonry-no-more-posts"><em><?php esc_html_e( 'No more posts, sorry', 'mimo-masonry' ); ?></em></Div>
	</div><!-- .navigation -->
	<?php endif;  ?>
<?php  wp_reset_postdata(); ?>