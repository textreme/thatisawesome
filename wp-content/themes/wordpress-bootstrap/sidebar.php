				<div id="sidebar1" class="fluid-sidebar sidebar span3" role="complementary">
				  
				  <ul>
				  <?php 
				  /* show the tree of the current page's highest level ancestor */
				  $parents = get_post_ancestors( $post->ID );
          $highest_parent = (empty($parents)) ? 0 : array_pop($parents);
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