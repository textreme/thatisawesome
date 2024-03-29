<!doctype html>  

<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title>
			<?php if ( !is_front_page() ) { echo wp_title( ' ', true, 'left' ); echo ' | '; }
			echo bloginfo( 'name' ); echo ' - '; bloginfo( 'description', 'display' );  ?> 
		</title>
				
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- icons & favicons -->
		<!-- For iPhone 4 -->
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/h/apple-touch-icon.png">
		<!-- For iPad 1-->
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/m/apple-touch-icon.png">
		<!-- For iPhone 3G, iPod Touch and Android -->
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon-precomposed.png">
		<!-- For Nokia -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/icons/l/apple-touch-icon.png">
		<!-- For everything else -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
				
		<!-- media-queries.js (fallback) -->
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
		<![endif]-->

		<!-- html5.js -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

  		<link rel="stylesheet/less" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/bootstrap.less">
  		<link rel="stylesheet/less" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/responsive.less">

		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->

		<!-- theme options from options panel -->
		<?php get_wpbs_theme_options(); ?>
		
		<script type="text/javascript"
       src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>

		<?php 

			// check wp user level
			get_currentuserinfo(); 
			// store to use later
			global $user_level; 

			// get list of post names to use in 'typeahead' plugin for search bar
			if(of_get_option('search_bar', '1')) { // only do this if we're showing the search bar in the nav

				global $post;
				$tmp_post = $post;
				$get_num_posts = 40; // go back and get this many post titles
				$args = array( 'numberposts' => $get_num_posts );
				$myposts = get_posts( $args );
				$post_num = 0;

				global $typeahead_data;
				$typeahead_data = "[";

				foreach( $myposts as $post ) :	setup_postdata($post);
					$typeahead_data .= '"' . get_the_title() . '",';
				endforeach;

				$typeahead_data = substr($typeahead_data, 0, strlen($typeahead_data) - 1);

				$typeahead_data .= "]";

				$post = $tmp_post;

			} // end if search bar is used

		?>
				
	</head>
	
	<body <?php body_class(); ?>>
				
		<header role="banner">
		
			<div id="inner-header" class="clearfix">
				
				<div class="navbar navbar-inverse navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container nav-container">
							<nav role="navigation">
								<a class="brand" id="logo" title="<?php echo get_bloginfo('description'); ?>" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
								
								<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							        <span class="icon-bar"></span>
							        <span class="icon-bar"></span>
							        <span class="icon-bar"></span>
								</a>
								
								<div class="nav-collapse">
									<?php bones_main_nav(); // Adjust using Menus in Wordpress Admin ?>
								</div>
								
							</nav>
							
							<form class="navbar-search pull-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
								<input name="s" id="s" type="text" class="search-query" autocomplete="off" placeholder="<?php _e('Search','bonestheme'); ?>" data-provide="typeahead" data-items="4" data-source='<?php echo $typeahead_data; ?>'>
							</form>
							
						</div>
					</div>
				</div>
			
			</div> <!-- end #inner-header -->
		
		</header> <!-- end header -->
		
		<div class="container">
		  
		  <!-- featured image for pages -->
		  <?php if ( $post->post_type=='course' && has_post_thumbnail($post->ID) ) { ?>        
        <div class="row">        
          <div class="span12 featured_image"><?= get_the_post_thumbnail( $post->ID, 'full' ); ?></div>
        </div>
      <?php } ?>
      
      <!-- breadcrumbs -->
      <?php if ( !is_front_page() ) {
        
        $object = $wp_query->get_queried_object();
        $active_title = get_the_title();
        $ancestors = array();
        $categories = array();
        
        if ( property_exists( $object, 'taxonomy' ) && $object->taxonomy == 'course-category' ) {
          $categories = get_ancestors( $object->term_taxonomy_id, 'course-category');
          $active_title = $object->name;         
          
        } elseif ( property_exists( $object, 'post_type' ) && $object->post_type == 'course' ) {
          $ancestors = get_post_ancestors( $object->ID );
          
        } elseif ( property_exists( $object, 'post_type' ) && $object->post_type == 'lesson' ) {
          $course_id = get_post_meta($object->ID, '_lesson_course', true);
          $ancestors = get_post_ancestors( $course_id );
          array_unshift( $ancestors, $course_id );
        }    
          
        $ancestors = array_reverse( $ancestors );
        $categories = array_reverse( $categories );
        
        ?>      
      <ul class="breadcrumb">
        <li><a href="<?= get_home_url() ?>">Home</a> <span class="divider">/</span></li>
        <?php foreach( $ancestors as $aid ) { ?>
          <li><a href="<?= get_permalink($aid) ?>"><?= get_the_title($aid) ?></a> <span class="divider">/</span></li>
        <?php } ?>
        <?php foreach( $categories as $cid ) { $category = get_term($cid,'course-category'); ?>
          <li><a href="<?= get_term_link($cid,'course-category') ?>"><?= $category->name ?></a> <span class="divider">/</span></li>
        <?php } ?>      
        <li class="active"><?= $active_title ?></li>
      </ul>
      <?php } ?>
