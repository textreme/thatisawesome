        <?php
        global $post, $woothemes_sensei;
        $current_lesson = false;
        
        if ( $post->post_type == 'quiz' ) {
          $lesson_id = get_post_meta($post->ID, '_quiz_lesson', true);
          $current_lesson = get_post( $lesson_id );
          
        } elseif ( $post->post_type == 'post' ) {
          $tags = wp_get_post_tags( $post->ID );
          foreach( $tags as $tag ) {
            if ( $current_lesson = get_page_by_path($tag->name,OBJECT,'lesson') ) {
              break;
            }
          }
          
        } else {
          $current_lesson = $post;
        }
        
        if ( $current_lesson ) {
        
          $lesson_quizzes = $woothemes_sensei->frontend->lesson->lesson_quizzes( $current_lesson->ID );
          
          $course_id = get_post_meta($current_lesson->ID, '_lesson_course', true);
          $course_lessons = $woothemes_sensei->frontend->course->course_lessons( $course_id );
          
          $lesson_posts = get_posts ( array(
             'post_type' => 'post',
             'tag' => $current_lesson->post_name ,
             'post_status' => 'publish'
          ) );
          
          ?>       
          
          <div id="sidebar-course" class="sidebar span3" role="complementary">
            
            <div class="accordion" id="course-accordion">
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#course-accordion" href="#accordion-lessons">
                    Lessons
                  </a>
                </div>
                <div id="accordion-lessons" class="accordion-body collapse <?=($post->post_type == 'lesson')?'in':''?>">
                  <div class="accordion-inner">
                    <?php foreach( $course_lessons as $lesson ) { ?>
                      <a href="<?= get_permalink($lesson->ID); ?>" class="<?=($current_lesson->ID==$lesson->ID)?'selected':''?>">
                        <?php if ( has_post_thumbnail($lesson->ID) ) {
                          echo get_the_post_thumbnail( $lesson->ID, 'thumbnail' );
                        } ?>
                        <?= $lesson->post_title ?>
                      </a>
                    <?php } ?>
                  </div>
                </div>
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#course-accordion" href="#accordion-quizzes">
                    Quizzes
                  </a>
                </div>
                <div id="accordion-quizzes" class="accordion-body collapse <?=($post->post_type == 'quiz')?'in':''?>">
                  <div class="accordion-inner">
                      <?php foreach ($lesson_quizzes as $quiz_item){ ?>                      
                        <a href="<?= get_permalink($quiz_item->ID); ?>" class="<?=($post->ID==$quiz_item->ID)?'selected':''?>">
                          <?php if ( has_post_thumbnail($quiz_item->ID) ) {
                            echo get_the_post_thumbnail( $quiz_item->ID, 'thumbnail' );
                          } ?>
                          <?= $quiz_item->post_title ?>
                        </a>
                      <?php } ?>
                  </div>
                </div>
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#course-accordion" href="#accordion-posts">
                    Posts
                  </a>
                </div>
                <div id="accordion-posts" class="accordion-body collapse <?=($post->post_type == 'post')?'in':''?>">
                  <div class="accordion-inner">
                      <?php foreach ($lesson_posts as $lesson_post){ ?>                      
                        <a href="<?= get_permalink($lesson_post->ID); ?>" class="<?=($post->ID==$lesson_post->ID)?'selected':''?>">
                          <?php if ( has_post_thumbnail($lesson_post->ID) ) {
                            echo get_the_post_thumbnail( $lesson_post->ID, 'thumbnail' );
                          } ?>
                          <?= $lesson_post->post_title ?>
                        </a>
                      <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          
          <?php } // end if ?>
        
          <?php if ( is_active_sidebar( 'lesson' ) ) : ?>

            <?php dynamic_sidebar( 'lesson' ); ?>

          <?php else : ?>

            <!-- This content shows up if there are no widgets defined in the backend. -->

          <?php endif; ?>

        </div>