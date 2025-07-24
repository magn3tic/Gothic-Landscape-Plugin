<?php
/**
 * Template: Selections Step Model (New)
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

use Gothic\Selections\Helper\{Template, Misc};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
	<h1><?php esc_html_e( 'Your Builder, Community, and Model', 'gothic-selections' ); ?></h1>

	<p><?php esc_html_e( 'Your preferences request was started for you by your builder. Please start by confirming the information below is correct. If there is a problem, stop and notify your builder. Only they can make changes to this info.', 'gothic-selections' ); ?></p>
	<ul class="boxes">
		<li class="homebuilder field-container">
			<?php $builder = get_post( get_post_meta( get_the_id(), 'builder_id', true ) ); ?>
			<div class="field">
				<label for="homebuilder">Your Homebuilder</label>
				<h4><?php echo esc_html( $builder->post_title ); ?></h4>
			</div>
			<?php $image = get_the_post_thumbnail_url( $builder->ID, 'preferences-tiles' ) ?: Misc::get_placeholder_image(); ?>
			<img src="<?php echo esc_url( $image ); ?>" height="160" width="280" alt="<?php echo esc_attr( $builder->post_name ); ?>"/>
		</li>

		<li class="community field-container">
			<?php $community = get_post( get_post_meta( get_the_id(), 'community_id', true ) ); ?>
			<div class="field">
				<label for="homebuilder">Your Community</label>
				<h4><?php echo esc_html( $community->post_title ); ?></h4>
			</div>
			<?php $image = get_the_post_thumbnail_url( $community->ID, 'preferences-tiles' ) ?: Misc::get_placeholder_image(); ?>
			<img src="<?php echo esc_url( $image ); ?>" height="160" width="280" alt="<?php echo esc_attr( $builder->post_name ); ?>"/>
		</li>

		<li class="model field-container">
			<?php $model = get_post( get_post_meta( get_the_id(), 'model_id', true ) ); ?>
			<div class="field">
				<label for="homebuilder">Your Model</label>
				<h4><?php echo esc_html( $model->post_title ); ?></h4>
			</div>
			<?php $image = get_the_post_thumbnail_url( $model->ID, 'preferences-tiles' ) ?: Misc::get_placeholder_image(); ?>
			<img src="<?php echo esc_url( $image ); ?>" height="160" width="280" alt="<?php echo esc_attr( $builder->post_name ); ?>"/>
		</li>

	</ul>
	<footer>
		<button class="c-mx-2">Continue</button>
		<span class="c-mt-4">Or,</span> <a href="#notify-problem" class="c-mx-2 c-mt-4 crunch-button crunch-button__text-only crunch-button__text-only--secondary-color  notify-problem">stop and notify us there is an error.</a>
	</footer>
	<div id="notify-problem" class="notify-problem-container" style="display:none;">
		<h2>Contact Your Builder</h2>
		<p>Contact your builder to let them know something isn't right. They will make necessary corrections
			and notify you when you can continue.</p>
		<?php
		Template::form_field( array(
			'type'  => 'text',
			'label' => __( 'Your Name', 'gothic-selections' ),
			'name'  => 'contact_name',
			'value' => get_post_meta( get_the_id(), 'home_buyer', true ),
		), true );
		?>
		<?php
		Template::form_field( array(
			'type'     => 'email',
			'template' => 'text',
			'label'    => __( 'Your Preferred Email Address', 'gothic-selections' ),
			'name'     => 'contact_email',
			'value'    => get_post_meta( get_the_id(), 'email', true ),
		), true );
		?>
		<?php
		Template::form_field( array(
			'type'        => 'textarea',
			'label'       => __( 'Explanation Of What is Wrong.', 'gothic-selections' ),
			'description' => __( 'Please explain the problems you saw. Your salesperson will need to update these for you.', 'gothic-selections' ),
			'name'        => 'comments',
		), true );
		?>
		<button type="submit" name="_problem" value="model" onClick="parent.jQuery.fancybox.close();" >Send Message</button>
	</div>