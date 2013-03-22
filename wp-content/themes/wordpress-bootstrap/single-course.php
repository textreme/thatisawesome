<?php
/**
 * The Template for displaying all single courses.
 *
 * Override this template by copying it to yourtheme/sensei/single-course.php
 *
 * @author    WooThemes
 * @package   Sensei/Templates
 * @version     1.0.0
 */
global $woothemes_sensei;
get_header(); ?>

    <div id="content" class="clearfix row">
            
        <?php get_sidebar(); // sidebar 1 ?>
      
        <div id="main" class="span9 clearfix" role="main">

        <?php while ( have_posts() ) : the_post(); ?>
          
          <?php $woothemes_sensei->frontend->sensei_get_template_part( 'content', 'single-course' ); ?>
    
        <?php endwhile; // end of the loop. ?>

        </div>
        
    </div>

<?php get_footer(); ?>