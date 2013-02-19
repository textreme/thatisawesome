				<div id="sidebar_course1" class="fluid-sidebar sidebar span3" role="complementary">
				  
				  <?php
				  
				  // get the course id
				  if ( $post->post_type == 'course' ) {
				    $course_id = $post->ID;
				  } elseif( $post->post_type == 'lesson' ) {
				    $course_id = get_post_meta($post->ID, '_lesson_course', true);
				  }
          if ( !$course_id ) $course_id = 0;
          
          // retrieve categories
          $cat_args = array( 'orderby' => 'id', 'order' => 'ASC', 'exclude' => '1', 'number' => 3 ); // first three categories that's not uncategorized
          $categories = get_categories($cat_args);
          
          ?>
          
          <ul>
          <?php
          foreach($categories as $category) {
            $args = array(
              'cat' => $category->term_id, 'posts_per_page' => -1, 'meta_key' => '_lesson_course',
              'meta_value' => $course_id, 'post_type' => 'lesson', 'orderby' => 'menu_order', 'order' => 'ASC'
            );
            $lessons = get_posts($args);
            if ($lessons) {
              echo '<li>' . $category->name . '<ul>';
              foreach($lessons as $lesson) { ?>
                <li><a href="<?= get_permalink($lesson->ID); ?>"><?= $lesson->post_title ?></a></li>
                <?php
              }
              echo '</ul></li>';
            }
          } ?>
				  </ul>
				
					<?php if ( is_active_sidebar( 'course' ) ) : ?>

						<?php dynamic_sidebar( 'course' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->

					<?php endif; ?>

				</div>