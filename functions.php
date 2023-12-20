<?php
/**
 * astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );


	wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);

	wp_enqueue_script('jquery');

	wp_localize_script('custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

//  -------------------- button post filter ---------------
function trainerspost_shortcode() {
    ob_start();
    get_template_part('trainerpost/train');
    return ob_get_clean();
}
add_shortcode('trainerspostshortcode', 'trainerspost_shortcode');

function test_ajax_handler() {
    $nonce = sanitize_text_field($_POST['nonce']);
    check_ajax_referer('get_filtered_img', 'nonce');
    $setCategori = sanitize_text_field($_POST['key']);

    $args = array(
        'post_type'      => 'service',
        'posts_per_page' => -1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug', 
                'terms'    => $setCategori,
            ),
        ),
    );

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_title = get_the_title();
            $featured_image_url = get_the_post_thumbnail_url();
			
			?>
			<div class="card-box">
				<?php
						if ( $featured_image_url ) {
					?>
						<div class="card-img">
							<?= get_the_post_thumbnail(get_the_ID(), 'full');?>
						</div>
						<?php
						}
						?>
                            <div class="card-body">
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <h6> <?= $designation ?> </h6>
                                <?= the_excerpt(  )?> 
                            </div>
                        </div>
			<?php
        }
        wp_reset_postdata();
    } else {
        echo '<div style="text-align: center">No posts found</div>';
    }
}

add_action('wp_ajax_test', 'test_ajax_handler');
add_action('wp_ajax_nopriv_test', 'test_ajax_handler'); 
