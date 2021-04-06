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
                        <span class="black-box"></span>


                <?php }
                wp_reset_postdata(  );?>
                <div class="scroll-wrapper">
                    <button id="back" class="datastream-scroll-back"><i class="fas fa-chevron-left"></i></button>
                    <div class="scroller" id="scroller">
                        <?php

                        // Query Datastream posts page 1
                        $datastreamPostsP1 = new WP_Query(array(
                            'posts_per_page' => 12,
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

                    </div>
                    <button id="forward" class="datastream-scroll-forward"><i class="fas fa-chevron-right"></i></button>
                    <?php wp_reset_postdata(  );?>
                </div>
            </div>
            <script>
                const scroller = document.getElementById('scroller');
                const forward = document.getElementById('forward');
                const back = document.getElementById('back');
                console.log(scroller, forward, back);

                let counter = 1;

                function scrollForward() {

                    // let moveLeft = counter * -1


                    if(counter < 9) {
                        scroller.style.left = `calc(-1 * ${counter} * (20vw - .7%))`;
                        counter++;
                        if(counter >= 2){
                        back.style.display = "inherit";
                        }
                    }
                    if(counter == 9) {
                        forward.style.display = "none";
                    }



                    console.log(counter);
                }

                forward.addEventListener("click", scrollForward);

                function scrollBack() {
                    counter--;
                    if(counter <= 1) {
                        back.style.display = "none";
                    }
                    if (counter <= 9) {
                        scroller.style.left = `calc(-1 * (${counter} - 1) * (20vw - .7%))`;
                        forward.style.display = "inherit";
                    }

                    console.log(counter);

                }

                back.addEventListener("click", scrollBack);
            </script>
        </section>

            <?php

    }

}


// Register Widget

function datastream_register_widget() {
	register_widget( 'Datastream_Widget' );
}

add_action( 'widgets_init', 'datastream_register_widget' ); ?>