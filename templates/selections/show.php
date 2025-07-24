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

use Gothic\Selections\Helper\Misc;

/**
 * Defined before includes:
 *
 * @var array $errors
 * @var array $request
 * @var array $data
 */
	$history = json_decode(get_post_meta( get_the_ID(), "order_history", true),true) ?? false;
	$status = get_post_meta( get_the_ID(), '_status', true );
	if ( ! in_array( $status, array_keys( Misc::statuses() ), true ) ) {
		$status = 'error';
	}
	?>
	<div>
		<h1><?php esc_html_e( 'Preference Order', 'gothic-selections' ); ?></h1>
		<div class="c-pt-4"></div>

		<?php gothic_selections_get_template( 'loggedin-single-header', [], 'selections/parts', false, true ); ?>

		<hr/>
				
		<?php if ( ! empty( $history ) ) : ?>
			
			<div class="history c-mt-4">
				<h4 class="selections-form-group-header">History</h4>
				<ul class="history-container c-mx-4 c-mt-4">
					<?php foreach($history as $history_item):?>
						<li class="c-py-1">
							<span class="font-weight-medium name"><?= $history_item["history_entry_title"] ?></span>
							<span> - </span>
							<span class=""><?= date("Y/m/d g:i:s A", strtotime($history_item["history_entry_date"]["date"])); ?></span>
						</li>
					<?php endforeach;?>
				</ul>
			</div>

		<?php endif; ?>

		<?php if ( $status !== 'voided' ) : ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'Contact Information', 'gothic-selections' ); ?></h4>
		
		<?php if ( gothic_selections_form_value( 'home_buyer', $request ) ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Name', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'home_buyer', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'email' ) ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Email Address', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'email', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'phone' ) ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Phone Number', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'phone', $request ); ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'About Home', 'gothic-selections' ); ?></h4>

		<?php
		$builder   = gothic_selections_form_value( 'builder_id', $request );
		$community = gothic_selections_form_value( 'community_id', $request );
		$model     = gothic_selections_form_value( 'model_id', $request );
		?>
		<?php if ( $builder ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Homebuilder', 'gothic-selections' ); ?></h4>
				<?php
				$builder = get_post( $builder );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $builder->ID, 'preferences-tiles' ) ); ?>"
					     height="160" width="280" class="adjustable-element w-100 h-auto" alt="<?php echo get_the_title( $builder->post_name ); ?>"
					     title="<?php echo get_the_title( $builder->post_name ); ?>">
					<br/><?php echo $builder->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $community ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Community', 'gothic-selections' ); ?></h4>
				<?php
				$community = get_post( $community );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $community->ID, 'preferences-tiles' ) ); ?>"
					     height="160" width="280" class="adjustable-element w-100 h-auto" alt="<?php echo get_the_title( $community->post_name ); ?>"
					     title="<?php echo get_the_title( $community->post_name ); ?>">
					<br/><?php echo $community->post_title; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( $model ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Model', 'gothic-selections' ); ?></h4>
				<?php
				$model = get_post( $model );
				?>
				<p>
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( $model->ID, 'preferences-tiles' ) ); ?>"
					     height="160" width="280" class="adjustable-element w-100 h-auto" alt="<?php echo get_the_title( $model->post_name ); ?>"
					     title="<?php echo get_the_title( $model->post_name ); ?>">
					<br/><?php echo $model->post_title; ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'address' ) || gothic_selections_form_value( 'city', $request ) || gothic_selections_form_value( 'state', $request ) || gothic_selections_form_value( 'zip', $request ) ) : ?>
			<div class="selection-group c-mt-4">
				<h4><?php esc_html_e( 'Home Address', 'gothic-selections' ); ?></h4>
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
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Lot Number', 'gothic-selections' ) ?></h4>
				<p><?php echo gothic_selections_form_value( 'lot', $request ); ?></p></div>
		<?php endif; ?>

		<?php if ( gothic_selections_form_value( 'builder_rep_name', $request ) ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Builder Sales Associate Name', 'gothic-selections' ); ?></h4>
				<p><?php echo gothic_selections_form_value( 'builder_rep_name', $request ); ?></p></div>
		<?php endif; ?>

		<hr />

		<h4 style="text-align: center"><?php esc_html_e( 'Selections', 'gothic-selections' ); ?></h4>

		<?php
		$front     = gothic_selections_form_value( 'package_id', $request );
		$back      = gothic_selections_form_value( 'backyard_id', $request );
		$palette   = gothic_selections_form_value( 'palette_id', $request );
		$back_pal  = gothic_selections_form_value( 'by_palette_id', $request );
		$back_opt_in = gothic_selections_form_value( 'opt_in_backyard_upsell', $request );
		$comments  = gothic_selections_form_value( 'comments', $request );
		?>

		<?php if ( $front ) : ?>
		<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Front Yard Package', 'gothic-selections' ); ?></h4>
			<?php
			$front = get_post( $front );
			$image = get_post_meta( $model->ID, 'plan-' . $front->ID . '-image', true );
			$full  = get_post_meta( $model->ID, 'plan-' . $front->ID . '-full', true );
			?>
			<?php if ( $image ) : ?>
			<aside class="image w-50" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
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
			<?php else: ?>
			<p><?php esc_html_e( 'No image attached.', 'gothic-selections' ); ?></p>
			<?php endif;?>
		</div>
		<?php endif; ?>
		<?php if ( $palette ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Front Yard Plant Palette', 'gothic-selections' ); ?></h4>
				<?php
				$palette = get_post( $palette );
				?>
				<div class="palette">
					<?php $palletteImage = esc_url( get_the_post_thumbnail_url( $palette->ID, 'preferences-tiles' ) );?>
					<?php if($palletteImage):?>
					<img src="<?=$palletteImage ?>"
					     height="160" width="280" class="adjustable-element w-100 h-auto" alt="<?php echo get_the_title( $palette->post_name ); ?>"
					     title="<?php echo get_the_title( $palette->post_name ); ?>">
					<br/>
					<?php else:?>
						<p><?php esc_html_e( 'No image attached.', 'gothic-selections' ); ?></p>
					<?php endif;?>
					
					<?php echo $palette->post_title; ?>
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
		<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Back Yard Package', 'gothic-selections' ); ?></h4>
			<?php
			$back  = get_post( $back );
			$image = get_post_meta( $model->ID, 'plan-' . $back->ID . '-image', true );
			$full  = get_post_meta( $model->ID, 'plan-' . $back->ID . '-full', true );
			?>
			<?php if ( $image ): ?>
			<aside class="image w-50" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $image, 'model-plan-tile' ) ); ?>');">
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
			<?php else: ?>
				<p><?php esc_html_e( 'No image attached.', 'gothic-selections' ); ?></p>
			<?php endif;?>
		</div>
		<?php endif; ?>

		<?php if ( $back_pal ) : ?>
			<div class="selection-group c-mt-4">
				<h4><?php esc_html_e( 'Back Yard Plant Palette', 'gothic-selections' ); ?></h4>
				<div class="palette">
					<?php
					$back_pal = get_post( $back_pal );
					?>
					<p class="palette">
						<img src="<?php echo esc_url( get_the_post_thumbnail_url( $back_pal->ID, 'preferences-tiles' ) ); ?>"
						     height="160" width="280" class="adjustable-element w-100 h-auto" alt="<?php echo get_the_title( $back_pal->post_name ); ?>"
						     title="<?php echo get_the_title( $back_pal->post_name ); ?>">
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
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Back Yard More Info', 'gothic-selections' ); ?></h4>
				<p>
					<?php esc_html_e( 'Okay for Gothic Landscape to reach out about backyard options.', 'gothic-selections' ); ?>
				</p>
			</div>
		<?php endif; ?>


		<?php if ( $comments ) : ?>
			<div class="selection-group c-mt-4"><h4><?php esc_html_e( 'Comments', 'gothic-selections' ); ?></h4>
				<p><?php echo $comments; ?></p></div>
		<?php endif; ?>


	</div>
	<footer>
		<button><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'landscaping/selection' ) ) ); ?>"><?php esc_html_e( 'Go Back', 'gothic-selections' ); ?></a></button>
	</footer>
<?php endif; ?>
<?php
