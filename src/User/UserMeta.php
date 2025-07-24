<?php
/**
 * User Meta
 *
 * @link        https://gothiclandscape.com/
 * @since       1.0.0
 *
 * @package     Gothic Landscape Selections
 * @subpackage  Users
 * @author      Jeremy Scott
 * @link        https://jeremyescott.com/
 * @copyright   (c) 2018-2020 Gothic Landscape
 * @license     GPL-3.0++
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */
namespace Gothic\Selections\User;

use \WP_User;
use Gothic\Selections\Helper\Queries;
use Gothic\Selections\PostType\{
	Builder as BuilderPostType,
	Community as CommunityPostType };

/**
 * Class UserMeta
 *
 * @package Gothic\Selections\User
 *
 * @since 1.0.0
 *
 * @final
 */
final class UserMeta {

	/**
	 * User Meta Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'user_new_form', [ __CLASS__, 'new_user_profile_fields' ] );
		add_action( 'edit_user_profile', [ __CLASS__, 'show_profile_fields' ], 1 );
		add_action( 'show_user_profile', [ __CLASS__, 'show_profile_fields' ] );
		add_action( 'edit_user_profile_update', [ __CLASS__, 'save_profile_fields' ] );
	}

	/**
	 * Save User Meta
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public static function save_profile_fields( int $user_id ) : void {

		if ( ! current_user_can( 'gothic_salesperson_community' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['update_gothic_user'], 'update_gothic_user' ) ) {
			die( 'nonce_fail' );
		}

		$builder = '';

		if ( ! empty( $_POST['gothic_user_homebuilder'] ) ) {

			$builder = intval( $_POST['gothic_user_homebuilder'] );

			if ( get_post_type( $builder ) !== BuilderPostType::$key ) {
				$builder = '';
			}

			$current = get_user_meta( $user_id, 'gothic_user_homebuilder', true );

			if ( $builder !== $current ) {
				update_user_meta( $user_id, 'gothic_user_homebuilder', $builder );
			}
		}

		if ( ! empty( $_POST['gothic_user_community'] ) ) {

			$community = intval( $_POST['gothic_user_community'] );

			if ( get_post_type( $community ) !== CommunityPostType::$key ) {
				$community = '';
			}

			if ( ! $builder ) {
				$community = '';
			}

			if ( $community && ! (int) get_post_meta( $community, 'builder_id', true ) === $builder ) {
				$community = '';
			}

			$current = get_user_meta( $user_id, 'gothic_user_community', true );

			if ( $community !== $current ) {
				update_user_meta( $user_id, 'gothic_user_community', $community );
			}
		}
	}

	/**
	 * User Meta Profile Fields
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @param WP_User $user
	 *
	 * @return void
	 */
	public static function show_profile_fields( WP_User $user ) : void {
		?>
		<h2>Gothic Homebuilder Settings</h2>
		<?php wp_nonce_field( 'update_gothic_user', 'update_gothic_user' ); ?>
		<?php if ( current_user_can( 'gothic_salesperson_community' ) ) : ?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr class="gothic_user_homebuilder_wrap">
						<th><label for="gothic_user_homebuilder">Homebuilder</label></th>
						<td>
							<?php
							$current_builder = get_user_meta( $user->ID, 'gothic_user_homebuilder', true );

							if ( empty( $current_builder ) || ! is_numeric( $current_builder ) || get_post_type( (int) $current_builder ) !== BuilderPostType::$key ) {
								$current_builder = '';
							}

							$builders = Queries::builders();
							?>

							<?php if ( $builders ) : ?>

								<select name="gothic_user_homebuilder" id="gothic_user_homebuilder">

								<option value="-1" <?php selected( '', $current_builder ); ?> aria-disabled="true" disabled >
									<?php esc_html_e( 'Select a homebuilder', 'gothic-selections' ); ?>
								</option>

								<?php foreach ( $builders as $builder ) : ?>

									<option value="<?php echo intval( $builder->ID ); ?>" <?php selected( $current_builder, $builder->ID ); ?>>
										<?php echo esc_html( $builder->post_title ); ?>
									</option>

								<?php endforeach; ?>

								</select>

							<?php else : ?>

								<p>
									<strong>
										<?php __( 'Error: must define homebuilders first.', 'gothic-selections' ); ?>
									</strong>
								</p>

							<?php endif; ?>
							<p class="description">Select the homebuilder this user is associated with. They will only be able to initiate landscape orders for their homebuilder.</p>
						</td>
					</tr>
					<tr class="gothic_user_community_wrap">
						<th><label for="gothic_user_community">Home Salesperson Community</label></th>
						<td>
							<?php
							$current_community = get_user_meta( $user->ID, 'gothic_user_community', true );

							if ( empty( $current_community ) || ! is_numeric( $current_community ) || get_post_type( $current_community ) !== CommunityPostType::$key ) {
								$current_community = '';
							}

							if ( $current_builder ) {
								$communities = Queries::communities( [ 'builder_id' => $current_builder ] );
							} else {
								$communities = [];
							}
							?>

							<select name="gothic_user_community" id="gothic_user_community">

								<?php if ( $communities ) : ?>

									<option value="-1" <?php selected( '', $current_community ); ?> aria-disabled="true" disabled >
										<?php esc_html_e( 'Select a home community', 'gothic-selections' ); ?>
									</option>

									<?php foreach ( $communities as $community ) : ?>

										<option value="<?php echo intval( $community->ID ); ?>" <?php selected( $current_community, $community->ID ); ?>>
											<?php echo esc_html( $community->post_title ); ?>
										</option>

									<?php endforeach; ?>

								<?php else : ?>

									<option value="-1" selected="selected" aria-disabled="true" disabled>
										<?php esc_html_e( 'Select a builder before selecting a community.', 'gothic-selections' ); ?>
									</option>

								<?php endif; ?>

							</select>

							<?php if ( $current_builder && ! $communities ) : ?>

								<p class="error">
									<strong>
										<?php esc_html_e( 'There are no communities for this homebuilder.', 'gothic-selections' ); ?>
									</strong>
								</p>

							<?php endif; ?>
							<p class="description">Select the community this user is associated with. Users defined as "Builder Salespeople" will only be able to initiate landscape orders for their community. Users defined as "Builder Sales Managers" will not be affect by this setting.</p>
						</td>
					</tr>
				</tbody>
			</table>
		<?php else : ?>
			<table class="form-table" role="presentation">
				<tbody>
				<tr class="user-description-wrap">
					<th><label for="gothic_user_homebuilder">Homebuilder</label></th>
					<td>
						<input type="text" id="gothic_user_homebuilder" name="gothic_user_homebuilder" disabled value="<?php echo get_the_title( get_user_meta( 'gothic_user_homebuilder' ) ) ?: esc_html_e( 'No homebuilder is set for this user.', 'gothic-selections' ); ?>" />
						<p class="description">The homebuilder you're employed with.</p>
					</td>
				</tr>
				<tr class="user-description-wrap">
					<th><label for="gothic_user_community">Biographical Info</label></th>
					<td>
						<input type="text" id="gothic_user_community" name="gothic_user_community" disabled value="<?php echo get_the_title( get_user_meta( 'gothic_user_community' ) ) ?: esc_html_e( 'No homebuilder is set for this user.', 'gothic-selections' ); ?>" />
						<p class="description">The community you're assigned to. Please contact Gothic Landscape Operations to update.</p>
					</td>
				</tr>
				</tbody>
			</table>
		<?php endif; ?>
		<?php
	}

	/**
	 * New User Meta Profile Fields
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function new_user_profile_fields() : void {
		?>
		<h2>Gothic Homebuilder Settings</h2>
		<?php wp_nonce_field( 'update_gothic_user', 'update_gothic_user' ); ?>
		<?php if ( current_user_can( 'gothic_salesperson_community' ) ) : ?>
			<table class="form-table" role="presentation">
				<tbody>
				<tr class="gothic_user_homebuilder_wrap">
					<th><label for="gothic_user_homebuilder">Homebuilder</label></th>
					<td>
						<?php $builders = Queries::builders(); ?>

						<?php if ( $builders ) : ?>

							<select name="gothic_user_homebuilder" id="gothic_user_homebuilder">

								<option value="-1" selected="selected" aria-disabled="true" disabled>
									<?php esc_html_e( 'Select a homebuilder', 'gothic-selections' ); ?>
								</option>

								<?php foreach ( $builders as $builder ) : ?>

									<option value="<?php echo intval( $builder->ID ); ?>">
										<?php echo esc_html( $builder->post_title ); ?>
									</option>

								<?php endforeach; ?>

							</select>

						<?php else : ?>

							<p>
								<strong>
									<?php __( 'Error: must define homebuilders first.', 'gothic-selections' ); ?>
								</strong>
							</p>

						<?php endif; ?>
						<p class="description">Select the homebuilder this user is associated with. They will only be
							able to initiate landscape orders for their homebuilder.</p>
					</td>
				</tr>
				<tr class="gothic_user_community_wrap">
					<th><label for="gothic_user_community">Home Salesperson Community</label></th>
					<td>
						<select name="gothic_user_community" id="gothic_user_community">

							<option value="-1" selected="selected" aria-disabled="true" disabled>
								<?php esc_html_e( 'Select a builder before selecting a community.', 'gothic-selections' ); ?>
							</option>

						</select>

						<p class="description">Select the community this user is associated with. Users defined as
							"Builder Salespeople" will only be able to initiate landscape orders for their community.
							Users defined as "Builder Sales Managers" will not be affect by this setting.</p>
					</td>
				</tr>
				</tbody>
			</table>
		<?php endif; ?>
		<?php
	}
}
