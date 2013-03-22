<?php
/**
 * The Template for displaying all single lesons.
 *
 * Override this template by copying it to yourtheme/sensei/single-lesson.php
 *
 * @author    WooThemes
 * @package   Sensei/Templates
 * @version     1.0.0
 */
global $woothemes_sensei;
get_header(); ?>

      <div id="content" class="clearfix row">       
      
        <div id="main" class="span9 clearfix" role="main">

          <?php while ( have_posts() ) : the_post(); ?>
      
            <?php $woothemes_sensei->frontend->sensei_get_template_part( 'content', 'single-lesson' ); ?>
      
          <?php endwhile; // end of the loop. ?>
      
        </div> <!-- end #main -->
        
        <?php get_sidebar('lesson'); // sidebar 1 ?>
    
      </div> <!-- end #content -->

<?php get_footer(); ?>