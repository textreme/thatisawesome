				<div id="sidebar1" class="sidebar span3" role="complementary">
				  
				  <ul class="nav nav-tabs nav-stacked">
				  <?php 
				  /* show the tree of the current page's highest level ancestor */
          if ( is_page_template( 'page-subject.php' ) ) {
            $highest_parent = $post->ID;
          } else {
            $parents = get_post_ancestors( $post->ID );
            $highest_parent = (empty($parents)) ? 0 : array_pop($parents);
          }
          
				  wp_list_pages( array(
				    'title_li' => '',
				    'child_of' => $highest_parent
          ) ); ?>
          </ul>
				
					<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

						<?php dynamic_sidebar( 'sidebar1' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->

					<?php endif; ?>

				</div>