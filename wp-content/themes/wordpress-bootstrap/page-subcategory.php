<?php
/*
Template Name: Sub-Category Page
*/
?>

<?php get_header(); ?>
      
      <div id="content" class="clearfix row">
            
        <?php get_sidebar(); // sidebar 1 ?>
      
        <div id="main" class="span8 clearfix" role="main">

          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          
          <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
            
            <header>
              
              <div class="page-header"><h1><?php the_title(); ?></h1></div>
            
            </header> <!-- end article header -->
          
            <section class="post_content">
              <?php the_content(); ?>
          
            </section> <!-- end article section -->
            
            <footer></footer> <!-- end article footer -->
          
          </article> <!-- end article -->
          
          <?php endwhile; ?>  
          
          <?php else : ?>
          
          <article id="post-not-found">
              <header>
                <h1><?php _e("Not Found", "bonestheme"); ?></h1>
              </header>
              <section class="post_content">
                <p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
              </section>
              <footer>
              </footer>
          </article>
          
          <?php endif; ?>
          
          <ul class="thumbnails">
          <?php
          // retrieve all this category's courses
          $args = array( 'posts_per_page' => -1, 'post_parent'=> $post->ID, 'post_type' => 'course', 'orderby' => 'menu_order', 'order' => 'ASC' );
          $courses = get_posts( $args );
          foreach( $courses as $course ) : ?>
            <li class="span3">
              <a class="thumbnail" href="<?= get_permalink($course->ID); ?>">
                <?php if ( has_post_thumbnail($course->ID) ) {
                  echo get_the_post_thumbnail( $course->ID, 'full' );
                } else { ?>
                  <img src="http://placehold.it/270x270" alt="">
                <?php } ?>
                <h5><?= $course->post_title ?></h5>
                <p><?= $course->post_excerpt ?></p>
              </a>
            </li>
          <?php endforeach; ?>
          </ul>
      
        </div> <!-- end #main -->
    
      </div> <!-- end #content -->

<?php get_footer(); ?>