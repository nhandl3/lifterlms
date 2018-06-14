<?php
defined( 'ABSPATH' ) || exit;

/**
 * Bulk Enrollment Class
 *
 * @since	[version]
 * @version	[version]
 */
class LLMS_Student_Bulk_Enroll {

	/**
	 * Admin notices
	 *
	 * @var array
	 */
	public $user_admin_notices = array();

	/**
	 * Product (Course/Membership) ID
	 *
	 * @var int
	 */
	public $product_id = 0;

	/**
	 * Product Post Title
	 *
	 * @var string
	 */
	public $product_title = '';

	/**
	 * User IDs
	 *
	 * @var int
	 */
	public $user_ids = array();

	/**
	 * Constructor
	 *
	 * @since	[version]
	 * @version	[version]
	 */
	public function __construct() {
		// hook into extra ui on users table to display product selection
		add_action( 'manage_users_extra_tablenav', array( $this, 'display_product_selection_for_bulk_users' ) );

	}

	/**
	 * Displays ui for selecting product to bulk enroll users into
	 *
	 * @param	string $which
	 * @since	[version]
	 * @version	[version]
	 */
	public function display_product_selection_for_bulk_users( $which ) {

		// the attributes need to be different for top and bottom of the table
		$id = 'bottom' === $which ? 'llms_bulk_enroll_product2' : 'llms_bulk_enroll_product';
		$submit = 'bottom' === $which ? 'llms_bulk_enroll2' : 'llms_bulk_enroll';
		?>
		<div class="alignleft actions">
			<label class="screen-reader-text" for="_llms_bulk_enroll_product">
				<?php _e( 'Choose Course/Membership', 'lifterlms' ); ?>
			</label>
			<select id="<?php echo $id; ?>" class="llms-bulk-enroll-product" data-post-type="llms_membership,course" name="<?php echo $id; ?>" style="min-width:200px;max-width:auto;">
			</select>
			<input type="submit" name="<?php echo $submit; ?>" id="<?php echo $submit; ?>" class="button" value="<?php esc_attr_e( 'Enroll', 'lifterlms' ); ?>">
		</div>
		<?php
	}

}

return new LLMS_Student_Bulk_Enroll();
