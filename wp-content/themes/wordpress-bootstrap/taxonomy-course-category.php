<?php
global $woothemes_sensei, $wp_query, $post;

// set the post to the current course
$category = $wp_query->get_queried_object();
if ( $category && $courses = get_posts ( array(
     'post_type' => 'course',
     'course-category' => $category->slug
  ) ) ) {
  $post = $courses[0];
}

?>

<?php get_header(); ?>
      
      <div id="content" class="clearfix row">
            
        <?php get_sidebar(); // sidebar 1 ?>
      
        <div id="main" class="span9 clearfix" role="main">

          <?php $woothemes_sensei->frontend->sensei_get_template_part( 'content', 'single-course' ); ?>
      
        </div> <!-- end #main -->
    
      </div> <!-- end #content -->

<?php get_footer(); ?>