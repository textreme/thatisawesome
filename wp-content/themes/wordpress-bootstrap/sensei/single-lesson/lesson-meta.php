<?php
/**
 * The Template for displaying all single lesson meta data.
 *
 * Override this template by copying it to yourtheme/sensei/single-lesson/lesson-meta.php
 *
 * @author    WooThemes
 * @package   Sensei/Templates
 * @version     1.0.0
 */

global $post, $woothemes_sensei, $current_user;

// Get the meta info
$lesson_video_embed = get_post_meta( $post->ID, '_lesson_video_embed', true );
$lesson_course_id = get_post_meta( $post->ID, '_lesson_course', true );
$lesson_prerequisite = get_post_meta( $post->ID, '_lesson_prerequisite', true );

// Get Reset Settings
$reset_quiz_allowed = $woothemes_sensei->settings->settings[ 'quiz_reset_allowed' ];

// Get User Meta
get_currentuserinfo();

// Lesson Quiz Meta
$lesson_quizzes = $woothemes_sensei->frontend->lesson->lesson_quizzes( $post->ID );

// Handle Quiz Completion
if ( isset( $_POST['quiz_complete'] ) && wp_verify_nonce( $_POST[ 'woothemes_sensei_complete_lesson_noonce' ], 'woothemes_sensei_complete_lesson_noonce' ) ) {

    $lesson_quiz_id = 0;

    if ( 0 < count($lesson_quizzes) )  {
        foreach ($lesson_quizzes as $quiz_item){
            $lesson_quiz_id = $quiz_item->ID;
        } // End For Loop
    } // End If Statement

    $sanitized_submit = esc_html( $_POST['quiz_complete'] );

    if ( ! is_array($user_quizzes) ) {
        $user_quizzes = array();
    } // End If Statement

    $answers_array = array();

    switch ($sanitized_submit) {
        case __( 'Complete Lesson', 'woothemes-sensei' ):

            // Manual Grade
            $grade = 100;

            // Save Quiz Answers
            $args = array(
                                'post_id' => $lesson_quiz_id,
                                'username' => $current_user->user_login,
                                'user_email' => $current_user->user_email,
                                'user_url' => $current_user->user_url,
                                'data' => base64_encode( serialize( $answers_array ) ),
                                'type' => 'sensei_quiz_answers', /* FIELD SIZE 20 */
                                'parent' => 0,
                                'user_id' => $current_user->ID,
                                'action' => 'update'
                            );

            $activity_logged = WooThemes_Sensei_Utils::sensei_log_activity( $args );

            if ( $activity_logged ) {
                // Save Quiz Grade
                $args = array(
                                    'post_id' => $lesson_quiz_id,
                                    'username' => $current_user->user_login,
                                    'user_email' => $current_user->user_email,
                                    'user_url' => $current_user->user_url,
                                    'data' => $grade,
                                    'type' => 'sensei_quiz_grade', /* FIELD SIZE 20 */
                                    'parent' => 0,
                                    'user_id' => $current_user->ID,
                                    'action' => 'update'
                                );
                $activity_logged = WooThemes_Sensei_Utils::sensei_log_activity( $args );
                // Get Lesson Grading Setting
                if ( $activity_logged && 'passed' == $woothemes_sensei->settings->settings[ 'lesson_completion' ] ) {
                    $lesson_prerequisite = abs( round( doubleval( get_post_meta( $lesson_quiz_id, '_quiz_passmark', true ) ), 2 ) );
                    if ( $lesson_prerequisite <= $grade ) {
                        // Student has reached the pass mark and lesson is complete
                        $args = array(
                                            'post_id' => $post->ID,
                                            'username' => $current_user->user_login,
                                            'user_email' => $current_user->user_email,
                                            'user_url' => $current_user->user_url,
                                            'data' => 'Lesson completed and passed by the user',
                                            'type' => 'sensei_lesson_end', /* FIELD SIZE 20 */
                                            'parent' => 0,
                                            'user_id' => $current_user->ID
                                        );
                        $activity_logged = WooThemes_Sensei_Utils::sensei_log_activity( $args );
                    } // End If Statement
                } elseif ($activity_logged) {
                    // Mark lesson as complete
                    $args = array(
                                        'post_id' => $post->ID,
                                        'username' => $current_user->user_login,
                                        'user_email' => $current_user->user_email,
                                        'user_url' => $current_user->user_url,
                                        'data' => 'Lesson completed by the user',
                                        'type' => 'sensei_lesson_end', /* FIELD SIZE 20 */
                                        'parent' => 0,
                                        'user_id' => $current_user->ID
                                    );
                    $activity_logged = WooThemes_Sensei_Utils::sensei_log_activity( $args );
                } // End If Statement
            } else {
                // Something broke
            } // End If Statement

            break;
        case __( 'Reset Lesson', 'woothemes-sensei' ):
            // Remove existing user quiz meta
            $grade = '';
            $answers_array = array();
            // Check for quiz grade
            $delete_grades = WooThemes_Sensei_Utils::sensei_delete_activities( array( 'post_id' => $lesson_quiz_id, 'user_id' => $current_user->ID, 'type' => 'sensei_quiz_grade' ) );
            // Check for quiz answers
            $delete_answers = WooThemes_Sensei_Utils::sensei_delete_activities( array( 'post_id' => $lesson_quiz_id, 'user_id' => $current_user->ID, 'type' => 'sensei_quiz_answers' ) );
            // Check for lesson complete
            $delete_lesson_completion = WooThemes_Sensei_Utils::sensei_delete_activities( array( 'post_id' => $post->ID, 'user_id' => $current_user->ID, 'type' => 'sensei_lesson_end' ) );
            // Check for course complete
            $course_id = get_post_meta( $post->ID, '_lesson_course' ,true );
            $delete_course_completion = WooThemes_Sensei_Utils::sensei_delete_activities( array( 'post_id' => $course_id, 'user_id' => $current_user->ID, 'type' => 'sensei_course_end' ) );
            $messages = '<div class="woo-sc-box note">' . __( 'Lesson Reset Successfully.', 'woothemes-sensei' ) . '</div>';
            break;
        default:
            // Nothing
            break;

    } // End Switch Statement

} // End If Statement

// Check the lesson is complete
$user_lesson_end =  WooThemes_Sensei_Utils::sensei_get_activity_value( array( 'post_id' => $post->ID, 'user_id' => $current_user->ID, 'type' => 'sensei_lesson_end', 'field' => 'comment_content' ) );
$user_lesson_complete = false;
if ( '' != $user_lesson_end ) {
    $user_lesson_complete = true;
} // End If Statement
// Check for prerequisite lesson completions
$user_prerequisite_lesson_end =  WooThemes_Sensei_Utils::sensei_get_activity_value( array( 'post_id' => $lesson_prerequisite, 'user_id' => $current_user->ID, 'type' => 'sensei_lesson_end', 'field' => 'comment_content' ) );
$user_lesson_prerequisite_complete = false;
if ( '' != $user_prerequisite_lesson_end ) {
    $user_lesson_prerequisite_complete = true;
} // End If Statement

$html = '';
// Check that the course has been started
if ( ! WooThemes_Sensei_Utils::sensei_check_for_activity( array( 'post_id' => $lesson_course_id, 'user_id' => $current_user->ID, 'type' => 'sensei_course_start' ) ) ) { ?>
    <section class="lesson-meta">

      <header>

        <a href="<?php echo esc_url( get_permalink( $lesson_course_id->ID ) ); ?>" title="<?php echo esc_attr( __( 'Sign Up', 'woothemes-sensei' ) ); ?>"><?php _e( 'Please Sign Up for the course before starting the lesson.', 'woothemes-sensei' ); ?></a>

      </header>

    </section>

<?php } else {

    if ( 'http' == substr( $lesson_video_embed, 0, 4) ) {
        // V2 - make width and height a setting for video embed
        $lesson_video_embed = wp_oembed_get( esc_url( $lesson_video_embed )/*, array( 'width' => 100 , 'height' => 100)*/ );
    } // End If Statement
    ?>
    <section class="lesson-meta">

        <div class="video"><?php echo html_entity_decode($lesson_video_embed); ?></div>
        <?php echo ( isset( $message ) ) ? $messages : ''; ?>

    </section>

    <section class="lesson-course">
      <?php _e( 'Back to ', 'woothemes-sensei' ); ?><a href="<?php echo esc_url( get_permalink( $lesson_course_id ) ); ?>" title="<?php echo esc_attr( __( 'Back to the course', 'woothemes-sensei' ) ); ?>"><?php echo get_the_title( $lesson_course_id ); ?></a>
    </section>
<?php } // End If Statement ?>