<?php
/**
 * Template: Selections Step Packages (New)
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Templates / Selection Steps
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Defined before includes:
 *
 * @var array $errors
 * @var array $request
 * @var array $data
 */
?>
	<div>
		<h1><?php esc_html_e( 'Thank You!', 'gothic-selections' ); ?></h1>

		<p>Thank you for completing your Landscape Selection. We are looking forward to
			installing your landscaping on your new home when its time. Below is a recap
			of your provided information and selections.</p>

		<hr/>
		<h4 style="text-align: center"><?php esc_html_e( 'Your Contact Information', 'gothic-selections' ); ?></h4>

		<?php if ( gothic_selections_form_value( 'home_buyer', $request ) ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Your Name', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'home_buyer', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'email' ) ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Email Address', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'email', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'phone' ) ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Phone Number', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'phone', $request ); ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'About Your New Home', 'gothic-selections' ); ?></h4>

		<?php
		$builder   = gothic_selections_form_value( 'builder_id', $request );
		$community = gothic_selections_form_value( 'community_id', $request );
		$model     = gothic_selections_form_value( 'model_id', $request );
		?>
		<?php if ( $builder ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Your Homebuilder', 'gothic-selections' ); ?></h4>
				<?php
				$builder = get_post( $builder );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $builder->ID, 'large' ) ); ?>"
					     height="160" width="280" alt="<?php echo get_the_title( $builder->post_name ); ?>"
					     title="<?php echo get_the_title( $builder->post_name ); ?>" class="adjustable-element w-100 h-auto">
					<br/>
					<?php echo $builder->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $community ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Your Community', 'gothic-selections' ); ?></h4>
				<?php
				$community = get_post( $community );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $community->ID, 'large' ) ); ?>"
					     height="160" width="280" alt="<?php echo get_the_title( $community->post_name ); ?>"
					     title="<?php echo get_the_title( $community->post_name ); ?>" class="adjustable-element w-100 h-auto">
					<br/><?php echo $community->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $model ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Your Model', 'gothic-selections' ); ?></h4>
				<?php
				$model = get_post( $model );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $model->ID, 'large' ) ); ?>"
					     height="160" width="280" alt="<?php echo get_the_title( $model->post_name ); ?>"
					     title="<?php echo get_the_title( $model->post_name ); ?>" class="adjustable-element w-100 h-auto">
					<br/><?php echo $model->post_title; ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'address' ) || gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) : ?>
			<div class="selection-group">
				<h4><?php esc_html_e( 'New Home Address', 'gothic-selections' ); ?></h4>
				<p>
					<?php
					echo gothic_selections_form_value( 'address', $request );

					if ( gothic_selections_form_value( 'address' ) && ( gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) ) :
						echo '<br />';
					endif;
					echo gothic_selections_form_value( 'city', $request );
					if ( gothic_selections_form_value( 'city' ) && gothic_selections_form_value( 'state', $request ) ) {
						echo ', ';
					}
					echo strtoupper( gothic_selections_form_value( 'state', $request ) );
					echo ' ';
					echo gothic_selections_form_value( 'zip', $request );
					?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'lot', $request ) ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'New Home Lot Number', 'gothic-selections' ) ?></h4>
				<p><?php echo gothic_selections_form_value( 'lot', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'builder_rep_name', $request ) ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Builder Sales Associate Name', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'builder_rep_name', $request ); ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'Your Selections', 'gothic-selections' ); ?></h4>

		<?php
		$front     = gothic_selections_form_value( 'package_id', $request );
		$back      = gothic_selections_form_value( 'backyard_id', $request );
		$palette   = gothic_selections_form_value( 'palette_id', $request );
		$back_pal  = gothic_selections_form_value( 'by_palette_id', $request );
		$back_opt_in = gothic_selections_form_value( 'opt_in_backyard_upsell', $request );
		$comments = gothic_selections_form_value( 'comments', $request );
		?>

		<?php if ( $front ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Front Yard Package', 'gothic-selections' ); ?></h4>
				<div class="landscape">
				<?php
				$front = get_post( $front );
				$image = get_post_meta( $model->ID, 'plan-' . $front->ID . '-image', true );
				$full  = get_post_meta( $model->ID, 'plan-' . $front->ID . '-full', true );
				?>
				<?php if ( $image ) : ?>
				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="#package-expanded-<?php echo esc_attr( $front->ID ); ?>"></a>
					<?php endif; ?>
				</aside>
				<aside id="package-expanded-<?php echo esc_attr( $front->ID ); ?>" class="package-expanded" style="width:600px;display:none;padding:0;">
					<figure style="width: 100%;">
						<img src="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" style="width:100%;"/>
						<figcaption style="color:white;background-color:#00a2e5;padding:30px 20px;">
							<?php esc_html_e( 'Plans shown are for illustration purposes only. Per-lot plans will vary based on several factors, including lot size, elevation, whether it is a corner, and more.', 'gothic-landscape' ); ?>
							<a href="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" class="printable" style="text-decoration: underline; color:white"><?php esc_html_e( 'Print', 'gothic-selections' ); ?></a>
						</figcaption>
					</figure>
				</aside>
				<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( $palette ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Front Yard Plant Palette', 'gothic-selections' ); ?></h4>
				<?php
				$palette = get_post( $palette );
				?>
				<div class="palette">
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $palette->ID, 'large' ) ); ?>"
					     height="160" width="280" alt="<?php echo get_the_title( $palette->post_name ); ?>"
					     title="<?php echo get_the_title( $palette->post_name ); ?>" class="adjustable-element w-100 h-auto">
					<br/><?php echo $palette->post_title; ?>
					<div class="expand">
						<a class="text-secondary" href="#palette-<?php echo esc_attr( $palette->ID ); ?>">
							<?php esc_html_e( 'See plants in this palette.', 'gothic-selections' ); ?>
						</a>
					</div>

					<?php
					echo '<div id="palette-' . esc_attr( $palette->ID ) . '" class="palette-popout" style="display:none;">';
					$materials_types = get_terms(
						array(
							'taxonomy'   => 'materials-types',
							'hide_empty' => true,
							'order'      => 'DESC',
							'orderby'    => 'name'
						)
					);
					foreach ( $materials_types as $term => $args ) {
						$contents = get_post_meta( $palette->ID, $args->slug . '-field', true );
						if ( ! empty( $contents ) ) {
							$plants = new WP_Query( array(
								'post_type' => 'materials',
								'post__in'  => array_map( 'intval', $contents ),
								'order'     => 'ASC'
							) );
							if ( ! is_wp_error( $plants ) && $plants->have_posts() ) {
								echo '<h2>' . esc_html( $args->name ) . '</h2>';

								$plants_list = $plants->get_posts();

								foreach ( $plants_list as $plant ) {

									echo '<div class="palette_item">';

									if ( get_the_post_thumbnail_url( $plant->ID ) ) {
										$thumb = get_the_post_thumbnail_url( $plant->ID );
									} else {
										$thumb = 'http://via.placeholder.com/300x200';
									}

									printf( '<div class="palette_item_image" style="background-image: url(\'%s\')" /></div>', esc_url( $thumb ) );
									echo '<p class="palette_item_name">' . esc_html( $plant->post_title ) . '</p>';
									echo '<p class="palette_item_subname">' . esc_html( get_the_subtitle( $plant->ID, '', '', false ) ) . '</p>';

									echo '</div>';

								}
							}
						}
					}
					?>
					<p>
						<em><?php esc_html_e( 'Plant materials from this palette will be used in your landscape installation, but the exact items will be selected based on availability and seasonal considerations.', 'gothic-selections' ); ?></em>
					</p>
					<?php
					echo '</div>';
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $back ) : ?>
			<div class="selection-group">
				<h4><?php esc_html_e( 'Back Yard Package', 'gothic-selections' ); ?></h4>
				<div class="landscape">
				<?php
				$back  = get_post( $back );
				$image = get_post_meta( $model->ID, 'plan-' . $back->ID . '-image', true );
				$full  = get_post_meta( $model->ID, 'plan-' . $back->ID . '-full', true );
				?>
				<?php if ( $image ) : ?>

				<aside class="image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
					<?php if ( $full ) : ?>
						<a href="#package-expanded-<?php echo esc_attr( $back->ID ); ?>"></a>
					<?php endif; ?>
				</aside>
				<aside id="package-expanded-<?php echo esc_attr( $back->ID ); ?>" class="package-expanded" style="width:600px;display:none;padding:0;">
					<figure style="width: 100%;">
						<img src="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" style="width:100%;"/>
						<figcaption style="color:white;background-color:#00a2e5;padding:30px 20px;">
							<?php esc_html_e( 'Plans shown are for illustration purposes only. Per-lot plans will vary based on several factors, including lot size, elevation, whether it is a corner, and more.', 'gothic-landscape' ); ?>
							<a href="<?php echo esc_url( wp_get_attachment_image_url( $full, 'medium' ) ); ?>" class="printable" style="text-decoration: underline; color:white"><?php esc_html_e( 'Print', 'gothic-selections' ); ?></a>
						</figcaption>
					</figure>
				</aside>
				<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $back_pal ) : ?>
			<div class="selection-group">
				<h4><?php esc_html_e( 'Back Yard Plant Palette', 'gothic-selections' ); ?></h4>
				<div class="palette">
				<?php
				$back_pal = get_post( $back_pal );
				?>
				<p class="palette">
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $back_pal->ID, 'large' ) ); ?>"
					     height="160" width="280" alt="<?php echo get_the_title( $back_pal->post_name ); ?>"
					     title="<?php echo get_the_title( $back_pal->post_name ); ?>" class="adjustable-element w-100 h-auto"> 
					<br/><?php echo $back_pal->post_title; ?>
				</p>
					<div class="expand">
						<a class="text-secondary" href="#palette-<?php echo esc_attr( $back_pal->ID ); ?>">
							<?php esc_html_e( 'See plants in this palette.', 'gothic-selections' ); ?>
						</a>
					</div>

					<?php
					echo '<div id="palette-' . esc_attr( $back_pal->ID ) . '" class="palette-popout" style="display:none;">';
					$materials_types = get_terms(
						array(
							'taxonomy'   => 'materials-types',
							'hide_empty' => true,
							'order'      => 'DESC',
							'orderby'    => 'name'
						)
					);
					foreach ( $materials_types as $term => $args ) {
						$contents = get_post_meta( $back_pal->ID, $args->slug . '-field', true );
						if ( ! empty( $contents ) ) {
							$plants = new WP_Query( array(
								'post_type' => 'materials',
								'post__in'  => array_map( 'intval', $contents ),
								'order'     => 'ASC'
							) );
							if ( ! is_wp_error( $plants ) && $plants->have_posts() ) {
								echo '<h2>' . esc_html( $args->name ) . '</h2>';

								$plants_list = $plants->get_posts();

								foreach ( $plants_list as $plant ) {

									echo '<div class="palette_item">';

									if ( get_the_post_thumbnail_url( $plant->ID ) ) {
										$thumb = get_the_post_thumbnail_url( $plant->ID );
									} else {
										$thumb = 'http://via.placeholder.com/300x200';
									}

									printf( '<div class="palette_item_image" style="background-image: url(\'%s\')" /></div>', esc_url( $thumb ) );
									echo '<p class="palette_item_name">' . esc_html( $plant->post_title ) . '</p>';
									echo '<p class="palette_item_subname">' . esc_html( get_the_subtitle( $plant->ID, '', '', false ) ) . '</p>';

									echo '</div>';

								}
							}
						}
					}
					?>
					<p>
						<em><?php esc_html_e( 'Plant materials from this palette will be used in your landscape installation, but the exact items will be selected based on availability and seasonal considerations.', 'gothic-selections' ); ?></em>
					</p>
					<?php
					echo '</div>';
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $back_opt_in ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Back Yard More Info', 'gothic-selections' ); ?></h4>
				<p>
					<?php esc_html_e( 'Yes, you\'d like Gothic Landscape to reach out to you about backyard options.', 'gothic-selections' ); ?>
				</p>
			</div>
		<?php endif; ?>


		<?php if ( $comments ) : ?>
			<div class="selection-group"><h4><?php esc_html_e( 'Your Comments', 'gothic-selections' ); ?></h4>
				<p><?php echo $comments; ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'Care for Your Landscape', 'gothic-selections' ); ?></h4>

		<p>
			<?php esc_html_e( 'Once you\'ve moved into your new home, come back to our site and access our helpful Landscape Owner\'s Guide and Plant Guide to learn how to care for your newly installed Gothic Landscape!', 'gothic-selections' ); ?></p>

	</div>
	<footer>
		<a class="button" href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscaping/owners-guide') ) ); ?>"><?php esc_html_e( 'Owner\'s Guide', 'gothic-selections' ); ?></a>
		<a class="button" href="<?php echo esc_url( get_home_url() . '/landscaping/plant-guide' ); ?>"><?php esc_html_e( 'Plant Guide', 'gothic-selections' ); ?></a>
		<a class="button button-secondary" href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Exit', 'gothic-selections' ); ?></a>
	</footer>
<?php
