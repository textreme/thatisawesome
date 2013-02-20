				<div id="sidebar-course" class="fluid-sidebar sidebar span3" role="complementary">
				  
				  <?php
				  
				  // get the course id and current category
				  $current_category = false;
				  if ( $post->post_type == 'course' ) {
				    $course_id = $post->ID;
				  } elseif( $post->post_type == 'lesson' ) {
				    $course_id = get_post_meta($post->ID, '_lesson_course', true);
            if ( $current_categories = get_the_category( $post->ID ) )
              $current_category = $current_categories[0];
				  }
          if ( !$course_id ) $course_id = 0;
          
          // retrieve categories
          $cat_args = array( 'orderby' => 'id', 'order' => 'ASC', 'exclude' => '1', 'number' => 3 ); // first three categories that's not uncategorized
          $categories = get_categories($cat_args);
          if ( !$current_category ) $current_category = $categories[0];
          
          ?>
          
          <div class="accordion" id="course-accordion">
          <?php
          foreach($categories as $index=>$category) {
            $args = array(
              'cat' => $category->term_id, 'posts_per_page' => -1, 'meta_key' => '_lesson_course',
              'meta_value' => $course_id, 'post_type' => 'lesson', 'orderby' => 'menu_order', 'order' => 'ASC'
            );
            $lessons = get_posts($args);
            if ($lessons) { ?>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#course-accordion" href="#<?= $category->slug ?>">
                    <?= $category->name ?>
                  </a>
                </div>
                <div id="<?= $category->slug ?>" class="accordion-body collapse<?=($current_category->cat_ID==$category->catID)?' in':''?>">
                  <div class="accordion-inner">
                    <?php foreach($lessons as $lesson) { ?>
                      <a href="<?= get_permalink($lesson->ID); ?>"><?= $lesson->post_title ?></a>
                      <?php
                    } ?>
                  </div>
                </div>
              </div>
            <?php } // end if
          } // end for ?>
				  </div>
				
					<?php if ( is_active_sidebar( 'course' ) ) : ?>

						<?php dynamic_sidebar( 'course' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->

					<?php endif; ?>

				</div>