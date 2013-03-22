				<div id="sidebar1" class="sidebar span3" role="complementary">
				  
				  <ul class="nav nav-tabs nav-stacked">

          <?php 
            global $wp_query;
            
            $object = $wp_query->get_queried_object();
            $category_id = 0;
            $current_category_id = 0;
            $original_category_id = 0;
            
            if ( property_exists( $object, 'taxonomy' ) && $object->taxonomy == 'course-category' ) {              
              $category_id = $object->term_taxonomy_id;  
              $current_category_id = $category_id;  
              
            } elseif ( property_exists( $object, 'post_type' ) && $object->post_type == 'course' ) {
              if ( $terms = get_the_terms( $object->ID, 'course-category' ) ) {
                
                $term_ids = array();
                foreach( $terms as $term ) {
                  array_push( $term_ids, $term->term_id );
                }
                foreach( $terms as $term ) {
                  if ( !in_array( $term->parent, $term_ids) ) {
                    $category_id = $term->term_taxonomy_id;
                    $current_category_id = $category_id;
                  }
                }                
              }
            }

            if ( $parents = get_ancestors( $category_id, 'course-category') ) {
              $category_id = array_pop($parents);
              if ( $parents ) {
                $category_id = array_pop($parents);
              } elseif ( $current_category_id ) {
                $category_id = $current_category_id;
              }
            }
            
            $current_category = get_term_by('id', $category_id, 'course-category');
            
            wp_list_categories( array(
              'title_li' => '<a>'.$current_category->name.'</a>',
              'child_of' => $category_id,
              'current_category' => $current_category_id,
              'taxonomy' => 'course-category'
            ));
          ?> 

          </ul>
				
					<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

						<?php dynamic_sidebar( 'sidebar1' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->

					<?php endif; ?>

				</div>