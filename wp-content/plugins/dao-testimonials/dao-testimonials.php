<?php
/**
 * Plugin Name:       DAO Testimonials
 * Plugin URI:        https://sailet.pro
 * Description:       This plugin is responsible for functionality of user testimonials
 * Author:            Sailet
 * Author URI:        https://sailet.pro
 * Text Domain:       dao-testimonials
 * Domain Path:       /languages
 * Version:           0.1.0
 * Requires at least: 5.4
 * Requires PHP:      7.2
 *
 * @package         Admin_Table_Tut
 */

defined( 'ABSPATH' ) || exit;

/**
 * Adding WP List table class if it's not available.
 */
if ( ! class_exists( \WP_List_Table::class ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Testimonials_Table.
 *
 * @since 0.1.0
 * @package Admin_Table_Tut
 * @see WP_List_Table
 */
class Testimonials_Table extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Testimonial', 'sp' ), 
			'plural'   => __( 'Testimonials', 'sp' ), 
			'ajax'     => false
		] );

	}


	/**
	 * Retrieve testimonials from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_testimonials( $per_page = 10, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}user_testimonials";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a testimonial record.
	 *
	 * @param int $id testimonial id
	 */
	public static function delete_testimonial( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}user_testimonials",
			[ 'ID' => $id ],
			[ '%s' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}user_testimonials";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no testimonials data is available */
	public function no_items() {
		_e( 'Пока что никаких отзывов нету.', 'dao-consensus' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		global $wpdb;

		switch ( $column_name ) {
			case 'author_id':
				$author_name = $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM {$wpdb->users} WHERE ID = %d", $item[ $column_name ] ) );
				$author_link = get_author_posts_url( $item[ $column_name ] );
				return sprintf( '<a href="%1$s">%2$s</a>', $author_link, $author_name );
			case 'receiver_id':
				$receiver_name = $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM {$wpdb->users} WHERE ID = %d", $item[ $column_name ] ) );
				$receiver_link = get_author_posts_url( $item[ $column_name ] );
				return sprintf( '<a href="%1$s">%2$s</a>', $receiver_link, $receiver_name );
			case 'transaction_id':
				$transaction = get_post( $item[ $column_name ] );
				return sprintf( '<a href="%1$s">%2$s</a>', get_permalink( $transaction->ID ), $transaction->post_title );
			case 'message':
				return $item[ $column_name ];
			case 'rating': 
				return sprintf( '<span>%d</span> <button aria-label="Quality - %d, Professionality - %d, Cost - %d, Sociability - %d, Deadline - %d" data-microtip-position="right" role="tooltip">', ( $item[ 'quality' ] + $item[ 'professionality' ] + $item[ 'cost' ] + $item[ 'sociability' ] + $item[ 'deadline' ] ) / 5, $item[ 'quality' ], $item[ 'professionality' ], $item[ 'cost' ], $item[ 'sociability' ], $item[ 'deadline' ] );
			case 'created_at':
				return date( 'd.m.Y H:i:s', strtotime( $item[ $column_name ] ) );
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_change( $item ) {
		return sprintf(
			'<input class="change_data-btn" type="button" data-id="%s" value="Змінити" />', $item['ID']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_testimonial' );

		$title = '<strong>' . $item['Email'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&testimonial=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'author_id' => __( 'Автор', 'dao-consensus' ),
			'receiver_id'    => __( 'Получатель', 'dao-consensus' ),
			'transaction_id'    => __( 'Сделка', 'dao-consensus' ),
			'message'    => __( 'Отзыв', 'dao-consensus' ),
			'rating'    => __( 'Рейтинг' , 'dao-consensus' ),
			'created_at'      => __( 'Дата', 'dao-consensus' ),
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'rating'    => array('rating', false),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'		
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'testimonials_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_testimonials( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_testimonial' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_testimonial( absint( $_GET['testimonial'] ) );
		        wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		
		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_testimonial( $id );

			}

		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}

	}

}

class DAO_Testimonials_Plugin {

	// class instance
	static $instance;

	// Testimonials_List_Table object
	public $testimonials_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}



	public function plugin_menu() {
		$hook = add_menu_page(
			__( 'Отзывы', 'dao-consensus' ),
			__( 'Отзывы', 'dao-consensus' ),
			'manage_options',
			'testimonials',
			[$this, 'list_of_testimonials_page'],
			'dashicons-feedback',
			30
		);

		add_action( "load-$hook", [ $this, 'screen_option_testimonials' ] );
		add_action( "load-$hook", [ $this, 'plugin_enqueue_styles_and_scripts' ] );
	}
	
	public function plugin_enqueue_styles_and_scripts() {
		wp_enqueue_style( 'microtip', plugin_dir_url( __FILE__ ) . 'assets/css/microtip.min.css', array(), false );
	}

	/**
	 * Plugin List of Testimonials Page
	*/
	public function list_of_testimonials_page () {?>
		<style>
			.wp-list-table .column-author_id {
				width: 10%;
			}
			.wp-list-table .column-receiver_id {
				width: 10%;
			}
			.wp-list-table .column-transaction_id {
				width: 15%;
			}
			.wp-list-table .column-message {
				width: 45%;
			}
			.wp-list-table .column-rating {
				width: 10%;
			}
			.wp-list-table .column-date {
				width: 10%;
			}
			.wp-list-table td, .wp-list-table th {
				text-align: left;
				vertical-align: middle;
			}
			.wp-list-table thead tr th {
				white-space: nowrap;
				width: 100px;
				word-break: keep-all;
			}
			.wp-list-table button[role=tooltip] {
				width: 15px;
				height: 15px;
				background: url( '<?= plugin_dir_url( __FILE__ ) ?>/assets/img/question-mark.svg' ) transparent no-repeat center / contain;
				border: 0;
				position: relative;
				top: -5px;
				margin-left: 7px;
			}
			.wp-list-table button[role=tooltip]:after {
				white-space: pre-wrap;
			}
		</style>
		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-3">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post" style="overflow: auto;">
								<?php
								$this->testimonials_obj->prepare_items();
								$this->testimonials_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?}
	
	/**
	 * Screen options for testimonials list
	 */
	public function screen_option_testimonials() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Testimonials',
			'default' => 10,
			'option'  => 'testimonials_per_page'
		];

		add_screen_option( $option, $args );

		$this->testimonials_obj = new Testimonials_Table();
	}

	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	DAO_Testimonials_Plugin::get_instance();
} );