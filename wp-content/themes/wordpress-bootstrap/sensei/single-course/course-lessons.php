<?php
/**
 * The Template for displaying all single course meta information.
 *
 * Override this template by copying it to yourtheme/sensei/single-course/course-lessons.php
 *
 * @author    WooThemes
 * @package   Sensei/Templates
 * @version     1.0.0
 */

global $post, $woothemes_sensei, $current_user, $wp_query;

$html = '';
// Get Course Lessons
$lessons_completed = 0;

$object = $wp_query->get_queried_object();
if ( property_exists( $object, 'taxonomy' ) && $object->taxonomy == 'course-category' ) {
  $category_id = $object->term_taxonomy_id;
  $post_args = array( 
          'post_type'     => 'lesson',
          'numberposts'      => -1,
          'orderby'           => 'menu_order',
          'order'             => 'ASC',
          'course-category'   => $object->slug,
          'suppress_filters'  => 0
          );
  $course_lessons = get_posts( $post_args );            
  
} else {
  $course_lessons = $woothemes_sensei->frontend->course->course_lessons( $post->ID );
}







$total_lessons = count( $course_lessons );
// Check if the user is taking the course
$is_user_taking_course = WooThemes_Sensei_Utils::sensei_check_for_activity( array( 'post_id' => $post->ID, 'user_id' => $current_user->ID, 'type' => 'sensei_course_start' ) );

// Get User Meta
get_currentuserinfo();

if ( 0 < $total_lessons ) {
  
    if ( is_user_logged_in() && $is_user_taking_course ) {          
        $html .= '<div>';
        $html .= '<span class="course-completion-rate">' . sprintf( __( 'Currently completed %1$s of %2$s on total', 'woothemes-sensei' ), '######', $total_lessons ) . '</span>';
        $html .= '<div class="meter+++++"><span style="width: @@@@@%">@@@@@%</span></div>';
        $html .= '</div>';
    } // End If Statement

    $html .= '<ul class="thumbnails">';
    
      $lesson_count = 1;
      $lessons_completed = 0;
      foreach ($course_lessons as $lesson_item){
        
          $user_lesson_end = '';
          if ( is_user_logged_in() ) {
            // Check if Lesson is complete
            $user_lesson_end =  WooThemes_Sensei_Utils::sensei_get_activity_value( array( 'post_id' => $lesson_item->ID, 'user_id' => $current_user->ID, 'type' => 'sensei_lesson_end', 'field' => 'comment_content' ) );
            if ( '' != $user_lesson_end ) {
              $lessons_completed++;
            } // End If Statement
          } // End If Statement
          // Get Lesson data
          $complexity_array = $woothemes_sensei->frontend->lesson->lesson_complexities();
          $lesson_length = get_post_meta( $lesson_item->ID, '_lesson_length', true );
          $lesson_complexity = get_post_meta( $lesson_item->ID, '_lesson_complexity', true );
          if ( '' != $lesson_complexity ) { $lesson_complexity = $complexity_array[$lesson_complexity]; }
          $user_info = get_userdata( absint( $lesson_item->post_author ) );
        
          $html .= '<li class="span3">';
            $html .= '<a class="thumbnail" href="'.get_permalink( $lesson_item->ID ).'">';
              if ( has_post_thumbnail($lesson_item->ID) ) {
                $html .= get_the_post_thumbnail( $lesson_item->ID, 'full' );
              } else {
                $html .= '<img src="http://placehold.it/270x270" alt="">';
              }
              $html .= '<h5>'.$lesson_item->post_title.'</h5>';
              $html .= '<p>';

              if ( '' != $lesson_length ) {
                $html .= '<span class="lesson-length">' . __( 'Length: ', 'woothemes-sensei' ) . $lesson_length . __( ' minutes', 'woothemes-sensei' ) . '</span>'; 
              }
    
              if ( '' != $lesson_complexity ) {
                 $html .= '<span class="lesson-complexity">' . __( 'Complexity: ', 'woothemes-sensei' ) . $lesson_complexity .'</span>'; 
              }
              
              if ( '' != $user_lesson_end ) {
                $html .= '<span class="lesson-status complete">' . __( 'Complete', 'woothemes-sensei' ) .'</span>';
                
              } else {
                  // Get Lesson Status
                  $lesson_quizzes = $woothemes_sensei->frontend->lesson->lesson_quizzes( $lesson_item->ID );
                  if ( 0 < count($lesson_quizzes) )  {
                      foreach ($lesson_quizzes as $quiz_item){
                          // Check quiz grade
                          $user_quiz_answers =  WooThemes_Sensei_Utils::sensei_get_activity_value( array( 'post_id' => $quiz_item->ID, 'user_id' => $current_user->ID, 'type' => 'sensei_quiz_answers', 'field' => 'comment_content' ) );
                          if ( '' != $user_quiz_answers ) {
                              $html .= '<span class="lesson-status in-progress">' . __( 'In Progress', 'woothemes-sensei' ) .'</span>';
                          } // End If Statement
                      } // End For Loop
                  } // End If Statement
              }
              
              $html .= '</p>';
              $html .= '</a>';          
              $html .= '</li>';

          $lesson_count++;

      } // End For Loop

      if ( is_user_logged_in() && $is_user_taking_course ) {
        // Add dynamic data to the output
        $html = str_replace( '######', $lessons_completed, $html );
        $progress_percentage = abs( round( ( doubleval( $lessons_completed ) * 100 ) / ( $total_lessons ), 0 ) );
        /* if ( 0 == $progress_percentage ) { $progress_percentage = 5; } */
        $html = str_replace( '@@@@@', $progress_percentage, $html );
        if ( 50 < $progress_percentage ) { $class = ' green'; } elseif ( 25 <= $progress_percentage && 50 >= $progress_percentage ) { $class = ' orange'; } else { $class = ' red'; }
        $html = str_replace( '+++++', $class, $html );
      } // End If Statement

    $html .= '</ul>';

} // End If Statement
// Output the HTML
echo $html; ?>