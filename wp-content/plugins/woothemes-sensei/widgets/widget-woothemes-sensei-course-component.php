<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Sensei Course Component Widget
 *
 * A WooThemes standardized component widget.
 *
 * @package WordPress
 * @subpackage Sensei
 * @category Widgets
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * protected $woo_widget_cssclass
 * protected $woo_widget_description
 * protected $woo_widget_idbase
 * protected $woo_widget_title
 *
 * - __construct()
 * - widget()
 * - update()
 * - form()
 * - load_component()
 */
class WooThemes_Sensei_Course_Component_Widget extends WP_Widget {
	protected $woo_widget_cssclass;
	protected $woo_widget_description;
	protected $woo_widget_idbase;
	protected $woo_widget_title;

	/**
	 * Constructor function.
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct() {
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_sensei_course_component';
		$this->woo_widget_description = __( 'This widget will output a list of Courses - New, Featured, Free, Paid, Active, Completed.', 'woothemes-sensei' );
		$this->woo_widget_idbase = 'sensei_course_component';
		$this->woo_widget_title = __( 'Sensei - Course Component', 'woothemes-sensei' );

		$this->woo_widget_componentslist = array(
												'usercourses' => __( 'New Courses', 'woothemes-sensei' ),
												'featuredcourses' => __( 'Featured Courses', 'woothemes-sensei' ),
												'activecourses' => __( 'My Active Courses', 'woothemes-sensei' ),
												'completedcourses' => __( 'My Completed Courses', 'woothemes-sensei' ),
												);

		// Add support for the WooCommerce shelf.
		if ( function_exists( 'is_woocommerce_activated' ) && is_woocommerce_activated() ) {
			$this->woo_widget_componentslist['freecourses'] = __( 'Free Courses', 'woothemes-sensei' );
			$this->woo_widget_componentslist['paidcourses'] = __( 'Paid Courses', 'woothemes-sensei' );
		}
		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->woo_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->woo_widget_idbase, $this->woo_widget_title, $widget_ops, $control_ops );
	} // End __construct()

	/**
	 * Display the widget on the frontend.
	 * @since  1.0.0
	 * @param  array $args     Widget arguments.
	 * @param  array $instance Widget settings for this instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );


		if ( in_array( $instance['component'], array_keys( $this->woo_widget_componentslist ) ) && ( 'activecourses' == $instance['component'] || 'completedcourses' == $instance['component'] ) && !is_user_logged_in() ) {
			// No Output
		} else {
			/* Our variables from the widget settings. */
			$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );

			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) { echo $before_title . $title . $after_title; }

			/* Widget content. */
			// Add actions for plugins/themes to hook onto.
			do_action( $this->woo_widget_cssclass . '_top' );

			if ( in_array( $instance['component'], array_keys( $this->woo_widget_componentslist ) ) ) {
				$this->load_component( $instance );
			}

			// Add actions for plugins/themes to hook onto.
			do_action( $this->woo_widget_cssclass . '_bottom' );

			/* After widget (defined by themes). */
			echo $after_widget;
		} // End If Statement

	} // End widget()

	/**
	 * Method to update the settings from the form() method.
	 * @since  1.0.0
	 * @param  array $new_instance New settings.
	 * @param  array $old_instance Previous settings.
	 * @return array               Updated settings.
	 */
	public function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* The select box is returning a text value, so we escape it. */
		$instance['component'] = esc_attr( $new_instance['component'] );

		/* The select box is returning a text value, so we escape it. */
		$instance['limit'] = esc_attr( $new_instance['limit'] );


		return $instance;
	} // End update()

	/**
	 * The form on the widget control in the widget administration area.
	 * Make use of the get_field_id() and get_field_name() function when creating your form elements. This handles the confusing stuff.
	 * @since  1.0.0
	 * @param  array $instance The settings for this instance.
	 * @return void
	 */
    public function form( $instance ) {

		/* Set up some default widget settings. */
		/* Make sure all keys are added here, even with empty string values. */
		$defaults = array(
						'title' => '',
						'component' => '',
						'limit' => 3
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes-sensei' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Component: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'component' ); ?>"><?php _e( 'Component:', 'woothemes-sensei' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'component' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'component' ); ?>">
			<?php foreach ( $this->woo_widget_componentslist as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php selected( $instance['component'], $k ); ?>><?php echo $v; ?></option>
			<?php } ?>
			</select>
		</p>
		<!-- Widget Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Courses (optional):', 'woothemes-sensei' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>

<?php
	} // End form()

	/**
	 * Load the desired component, if a method is available for it.
	 * @param  string $component The component to potentially be loaded.
	 * @since  5.0.8
	 * @return void
	 */
	protected function load_component ( $instance ) {
		global $woothemes_sensei, $current_user;
		// Get User Meta
		get_currentuserinfo();
		/*
		usercourses
		freecourses
		paidcourses
		featuredcourses
		activecourses
		completedcourses
		*/
		$course_ids = array();
		if ( 'activecourses' == esc_attr( $instance['component'] ) ) {
			$course_ids_include = WooThemes_Sensei_Utils::sensei_activity_ids( array( 'user_id' => $current_user->ID, 'type' => 'sensei_course_start' ) );
			$course_ids_exclude = WooThemes_Sensei_Utils::sensei_activity_ids( array( 'user_id' => $current_user->ID, 'type' => 'sensei_course_end' ) );
			$course_ids = array_diff( $course_ids_include, $course_ids_exclude );
		} elseif( 'completedcourses' == esc_attr( $instance['component'] ) ) {
			$course_ids = WooThemes_Sensei_Utils::sensei_activity_ids( array( 'user_id' => $current_user->ID, 'type' => 'sensei_course_end' ) );
		} // End If Statement

		$posts_array = $woothemes_sensei->post_types->course->course_query( intval( $instance['limit'] ), esc_attr( $instance['component'] ), $course_ids );
		if ( count( $posts_array ) > 0 ) { ?>
			<ul>
			<?php foreach ($posts_array as $post_item){
		    	$post_id = absint( $post_item->ID );
		    	$post_title = $post_item->post_title;
		    	$user_info = get_userdata( absint( $post_item->post_author ) );
		    	$author_link = get_author_posts_url( absint( $post_item->post_author ) );
		    	$author_display_name = $user_info->display_name;
		    	$author_id = $post_item->post_author;
		    ?>
		    	<li class="fix">
		    		<?php echo $woothemes_sensei->post_types->course->course_image( $post_id ); ?>
		    		<a href="<?php echo get_permalink( $post_id ); ?>" title="<?php echo esc_attr( $post_title ); ?>"><?php echo $post_title; ?></a>
		    		<br />
		    		<?php if ( isset( $woothemes_sensei->settings->settings[ 'course_author' ] ) && ( $woothemes_sensei->settings->settings[ 'course_author' ] ) ) { ?>
    					<span class="course-author"><?php _e( 'by ', 'woothemes-sensei' ); ?><a href="<?php echo $author_link; ?>" title="<?php echo esc_attr( $author_display_name ); ?>"><?php echo esc_html( $author_display_name ); ?></a></span>
    					<br />
    				<?php } // End If Statement ?>
    				<span class="course-lesson-count"><?php echo $woothemes_sensei->post_types->course->course_author_lesson_count( $author_id, $post_id ) . '&nbsp;' . __( 'Lectures', 'woothemes-sensei' ); ?></span>
    				<br />
    				<?php sensei_simple_course_price( $post_id ); ?>
		    	</li>
		    <?php } // End For Loop ?>
		    <?php if ( 'activecourses' == esc_attr( $instance['component'] ) || 'completedcourses' == esc_attr( $instance['component'] ) ) {
		    	$my_account_page_id = intval( $woothemes_sensei->settings->settings[ 'my_course_page' ] );
		    	echo '<li class="my-account fix"><a href="'. get_permalink( $my_account_page_id ) .'">'.__('My Courses', 'woothemes-sensei').' <span class="meta-nav">→</span></a></li>';
		    } // End If Statement ?>
		</ul>
		<?php } // End If Statement
	} // End load_component()
} // End Class
?>