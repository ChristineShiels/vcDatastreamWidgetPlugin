<?php
/**
* Plugin Name: Datastream Widget Plugin
* Plugin URI: http://thisgrrlcodes.ca
* Description: A widget to display post thumbnails chronologically with a pinned feature post.
* Version: 1.0
* Author: Christine Shiels
* Author URI: http://thisgrrlcodes.ca
* Text Domain: datastream-widget
*/



// Widget class
class Datastream_Widget extends WP_Widget {

    // Widget Setup
    // main constructor
    function __construct() {
        // widget settings
        $widget_ops = array (
            'classname' => 'datastream_widget',
            'description' => esc_html__('A widget that displays a list of post thumbnails  with a pinned featured post.', 'datastream-widget'),
        );

        // Widget control settings
        $control_ops = array( 'id_base' => 'datastream_widget' );

        // Create the widget
        parent::__construct(
            'datastream_widget',
            esc_html__('Datastream Widget', 'datastream-widget'),
            $widget_ops,
            $control_ops );
    }

    // Widget admin form
    function form( $instance ) {

        // set defaults
        $defaults = array(
            'title' => 'Title'
        );

        // Parse current settings with defaults
        $instance = wp_parse_args( (array) $instance, $defaults );

        // Widget Title ?>

        <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php _e( 'Widget Title', 'datastream-widget' ); ?>
            </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
            type="text" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

        <?php
    }

    // define data saved by widget (update widget)
    function update( $new_instance, $old_instance ) {
        $instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
    }

    // display widget on front end (on site)
    function widget( $args, $instance ) {
        extract( $args );
        // print_r($instance);

        // check and define variables
        global $post;
        $title = apply_filters('widget_title', $instance['title'] );


        // WP core before_widget hook (always include)
        // echo $before_widget;

        // Output code for widget display ?>

        <section class="datastream-widget">
            <div class="datastream-title">
                <h4 class="mvp-widget-home-title">
                <span class="mvp-widget-home-title">
                <?php echo esc_html($title); ?>
                </span>
                </h4>
            </div>

            <div class="datastream-content-wrapper">
            
                <?php
                // Query Datastream Featured post
                $datastreamFeaturedPost = new WP_Query(array(
                    'posts_per_page' => 1,
                    'post_type' => 'post',
                    'category_name' => 'datastream',
                    'tag' => 'datastream-featured',
                ));
                // print_r($datastreamFeaturedPost->title);

                while($datastreamFeaturedPost->have_posts(  )) {
                    $datastreamFeaturedPost->the_post(); ?>
                    <div class="datastream-featured-content-card">
                        <a href="<?php the_permalink(); ?>" class="datastream-featured-post">
                            <div class="datastream-featured-image">
                                <h2 class="featured-label">Featured</h2>
                                <?php the_post_thumbnail( 'medium', [ 'class' => 'datastream-featured-thumbnail' ] );?>
                            </div>
                        </a>
                        <a href="<?php the_permalink(); ?>" class="datastream-featured-title-link datastream-title-link"><h2 class="datastream-post-title"><?php the_title( ) ?></h2>
                        </a>
                    </div>
                <?php }
                wp_reset_postdata(  );?>
                <div class="scroll-wrapper">
                    <div class="scrolling-section" id="section1">
                        <a class="datastream-scroll-back" href="#section3"><</i></a>
                        <?php
                        
                        // Query Datastream posts page 1
                        $datastreamPostsP1 = new WP_Query(array(
                            'posts_per_page' => 4,
                            'post_type' => 'post',
                            'category_name' => 'datastream',
                            'tag__not_in' => '5065'
                        ));
                        // if($datastreamPostsP1->have_posts()) {
                            $already_posted = array();
                            while($datastreamPostsP1->have_posts(  )) {
                                $datastreamPostsP1->the_post(); ?>
                                
                                <div class="datastream-content-card">
                                    <a href="<?php the_permalink(); ?>" class="datastream-post">
                                        <?php the_post_thumbnail( 'medium', [ 'class' => 'datastream-thumbnail' ] );?>
                                    </a>
                                    <a href="<?php the_permalink(); ?>" class="datastream-title-link"><h2 class="datastream-post-title"><?php the_title( ) ?></h2>
                                    </a>
                                </div>
                                <?php $already_posted[] = get_the_ID( );
                            }
                        // } ?>
                        <a class="datastream-scroll-forward" href="#section2">></i></a>
                    </div>
                    <?php wp_reset_postdata(  );?>
                    
                    <div class="scrolling-section" id="section2">
                        <a class="datastream-scroll-back" href="#section1"><</i></a>
                        <?php
                        
                        // Query Datastream posts
                        $datastreamPostsP2 = new WP_Query(array(
                            'posts_per_page' => 4,
                            'post_type' => 'post',
                            'category_name' => 'datastream',
                            'tag__not_in' => '5065',
                            'post__not_in' => $already_posted
                        ));
                        // if($datastreamPostsP2->have_posts()) {
                            while($datastreamPostsP2->have_posts(  )) {
                                $datastreamPostsP2->the_post(); ?>
                                
                                <div class="datastream-content-card">
                                    <a href="<?php the_permalink(); ?>" class="datastream-post">
                                        <?php the_post_thumbnail( 'medium', [ 'class' => 'datastream-thumbnail' ] );?>
                                    </a>
                                    <a href="<?php the_permalink(); ?>" class="datastream-title-link"><h2 class="datastream-post-title"><?php the_title( ) ?></h2>
                                    </a>
                                </div>
                                <?php $already_posted[] = get_the_ID( );
                            }
                        // } ?>
                        <a class="datastream-scroll-forward" href="#section3">></i></a>
                    </div>
                    <?php wp_reset_postdata(  );?>

                    <div class="scrolling-section" id="section3">
                        <a class="datastream-scroll-back" href="#section2"><</i></a>
                        <?php
                        
                        // Query Datastream posts
                        $datastreamPostsP3 = new WP_Query(array(
                            'posts_per_page' => 4,
                            'post_type' => 'post',
                            'category_name' => 'datastream',
                            'tag__not_in' => '5065',
                            'post__not_in' => $already_posted
                        ));
                        // if($datastreamPostsP3->have_posts()) {
                            while($datastreamPostsP3->have_posts(  )) {
                                $datastreamPostsP3->the_post(); ?>
                                
                                <div class="datastream-content-card">
                                    <a href="<?php the_permalink(); ?>" class="datastream-post">
                                        <?php the_post_thumbnail( 'medium', [ 'class' => 'datastream-thumbnail' ] );?>
                                    </a>
                                    <a href="<?php the_permalink(); ?>" class="datastream-title-link"><h2 class="datastream-post-title"><?php the_title( ) ?></h2>
                                    </a>
                                </div>
                                <?php $already_posted[] = get_the_ID( );
                            }
                        // } ?>
                        <a class="datastream-scroll-forward" href="#section1">></i></a>
                    </div>
                    <?php wp_reset_postdata(  );?>
                </div>
            </div>
        </section>


            <?php

    }

}


// Register Widget

function datastream_register_widget() {
	register_widget( 'Datastream_Widget' );
}

add_action( 'widgets_init', 'datastream_register_widget' ); ?>