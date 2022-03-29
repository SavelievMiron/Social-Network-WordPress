<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://sailet.pro/
 * @since      1.0.0
 *
 * @package    Dao_Notifications
 * @subpackage Dao_Notifications/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dao_Notifications
 * @subpackage Dao_Notifications/public
 * @author     Sailet <sales@sailet.pro>
 */
class Dao_Notifications_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dao_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dao_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dao-notifications-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dao_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dao_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dao-notifications-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create user system notification
	 *
	 * @param int    $user_id User ID
	 * @param string $type Notification type
	 * @param string $message Message
	 *
	 * @return void
	 */
	public function create_system_notification( int $user_id, string $message ) {

		if ( empty( $message ) ) {
			throw new Exception( 'Message should not be empty.' );
		}

		if ( ! dao_user_exists( $user_id ) ) {
			throw new Exception( 'User with such ID doesn\'t exist.' );
		}

	}

	/**
	 * Create user meeting notification.
	 *
	 * @param int    $meeting_id Meeting ID
	 * @param string $type Notification type
	 * @param int    $user_id User ID is neede
	 *
	 * For reshedule notification type $user_id is mandatory. ID should belong to initiator (invitor or invited).
	 *
	 * @return void
	 */
	public function create_meeting_notification( int $meeting_id, string $type, int $user_id = null ) {

		if ( ! get_post_status( $meeting_id ) ) {
			throw new Exception( 'Meeting with such ID doesn\'t exist.' );
			return;
		}

		if ( ! in_array( $type, array( 'invitation', 'acceptance', 'rejection', 'reschedule', 'accept_reschedule', 'cancellation', 'after_meeting', 'demand-in-process', 'offer-in-process', 'did-not-agree' ) ) ) {
			throw new Exception( 'There are only 10 types of meeting notifications: invitation, acceptance, rejection, reschedule, accept_reschedule, cancellation, after_meeting, demand-in-process, offer-in-process, did-not-agree.' );
			return;
		}

		global $wpdb;

		$meeting = get_post( $meeting_id );

		$data = array(
			'post_id' => $meeting->ID,
			'type' => 'meeting',
			'created_at' => current_time( 'mysql' )
		);

		if ( $type === 'invitation' ) {

			$data['user_id'] = $meeting->invited_id;
			$invitor_link    = get_author_posts_url( $meeting->post_author );
			$invitor_name 	 = $this->get_user_display_name( $meeting->post_author );

			$invited_email   = $this->get_user_email( $data['user_id'] ); 

			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> приглашает вас на встречу "%3$s".', 'dao-consensus' ), $invitor_link, $invitor_name, $meeting->post_title );
			$this->send_email( $invited_email, 'DAOC: Приглашение на встречу', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'acceptance' ) {

			$data['user_id'] = $meeting->post_author;
			$invited_link    = get_author_posts_url( $meeting->invited_id );
			$invited_name 	 = $this->get_user_display_name( $meeting->invited_id );

			$invitor_email   = $this->get_user_email( $meeting->post_author );

			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> принял ваше приглашение на встречу "%3$s".', 'dao-consensus' ), $invited_link, $invited_name, $meeting->post_title );
			$this->send_email( $invitor_email, 'DAOC: Приглашение принято', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'rejection' ) {

			$data['user_id'] = $meeting->post_author;
			$invited_link    = get_author_posts_url( $meeting->invited_id );
			$invited_name    = $this->get_user_display_name( $meeting->invited_id );

			$invitor_email = $this->get_user_email( $meeting->post_author );
			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> отклонил ваше приглашение на встречу "%3$s".', 'dao-consensus' ), $invited_link, $invited_name, $meeting->post_title );
			$this->send_email( $invitor_email, 'DAOC: Приглашение отклонено', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
		
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'reschedule' ) {

			if ( is_null( $user_id ) ) {
				throw new Exception( 'For reshedule notification type $user_id is mandatory. ID should belong to initiator (invitor or invited).' );
				return;
			}

			// check whether initiator of rescheduling is invitor or invited
			if ( $user_id == $meeting->post_author ) {
				$data['user_id'] = $meeting->invited_id;
				$user_link = get_author_posts_url( $meeting->post_author );
				$user_name = $this->get_user_display_name( $meeting->post_author );
			} elseif ( $user_id == $meeting->invited_id ) {
				$data['user_id'] = $meeting->post_author;
				$user_link = get_author_posts_url( $meeting->invited_id );
				$user_name = $this->get_user_display_name( $meeting->invited_id );
			} else {
				throw new Exception( 'User ID should belong to initiator (invitor or invited).' );
				return;
			}

			$email = $this->get_user_email( $data['user_id'] );
			$datetime = date( 'd.m.Y H:i', $meeting->datetime );

			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> предлагает перенести встречу "%3$s" на %4$s.', 'dao-consensus' ), $user_link, $user_name, $meeting->post_title, $datetime );
			$this->send_email( $email, 'DAOC: Перенос встречи', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'accept_reschedule' ) {

			if ( is_null( $user_id ) ) {
				throw new Exception( 'For reshedule notification type $user_id is mandatory. ID should belong to initiator (invitor or invited).' );
				return;
			}
			$data['user_id'] = $user_id;

			// check whether initiator of rescheduling is invitor or invited
			if ( $user_id == $meeting->post_author ) {
				$data['user_id'] = $meeting->post_author;
				$user_link = get_author_posts_url( $meeting->post_author );
				$user_name = $this->get_user_display_name( $meeting->post_author );
			} elseif ( $user_id == $meeting->invited_id ) {
				$data['user_id'] = $meeting->invited_id;
				$user_link = get_author_posts_url( $meeting->invited_id );
				$user_name = $this->get_user_display_name( $meeting->invited_id );
			} else {
				throw new Exception( 'User ID should belong to initiator (invitor or invited).' );
				return;
			}

			$datetime = date('d.m.Y H:i', $meeting->datetime);

			$email = $this->get_user_email( $data['user_id'] );

			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> принял перенос встречи "%3$s" на %4$s.', 'dao-consensus' ), $user_link, $user_name, $meeting->post_title, $datetime );
			$this->send_email( $email, 'DAOC: Перенос встречи утверждён', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'cancellation' ) {

			$data['user_id'] = $meeting->invited_id;
			$invitor_link = get_author_posts_url( $meeting->post_author );
			$invitor_name = $this->get_user_display_name( $meeting->post_author );

			$invited_email = $this->get_user_email( $data['user_id'] );

			$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> отменил встречу "%3$s".', 'dao-consensus' ), $invitor_link, $invitor_name, $meeting->post_title );
			$this->send_email( $invited_email, 'DAOC: Встреча отменена', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к встречам</a>', get_permalink( 120 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'after_meeting' ) {

			$card = get_post( $meeting->card_id );

			/* addressor of notification */
			$data['user_id'] = ($card->post_type === 'offers') ? $meeting->post_author : $card->post_author;
			$data['type'] = 'after_meeting';

			$user = ($card->post_type === 'offers') ? $card->post_author : $meeting->post_author;
			$user_name = $this->get_user_display_name( $user );
			$user_link = get_author_posts_url( $user );

			$post_type = ($card->post_type === 'offers') ? 'предложении' : 'спросе';

			$email = $this->get_user_email( $data['user_id'] );

			$data['message'] = sprintf( __( 'Вы договорились с <a href="%1$s">%2$s</a> о %3$s <a href="%4$s">"%5$s"</a>?', 'dao-consensus' ), $user_link, $user_name, $post_type, get_permalink( $card->ID ), $card->post_title );
			$this->send_email( $email, 'DAOC: После встречи', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к профилю</a>', get_permalink( 21 )) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'demand-in-process' ) {

			$demand = get_post( $meeting->card_id );

			$data['type'] = 'demand-in-process';

			$customer_name = $this->get_user_display_name( $demand->post_author );
			$customer_link = get_author_posts_url( $demand->post_author );
			$customer_email = $this->get_user_email( $demand->post_author );

			$performer_name  = $this->get_user_display_name( $meeting->post_author );
			$performer_link  = get_author_posts_url( $meeting->post_author );
			$performer_email = $this->get_user_email( $meeting->post_author );

			// notify performer via personal cabinet and email
			$data['user_id'] = $meeting->post_author;
			$data['message'] = sprintf( __( 'Поздравляем! <a href="%1$s">%2$s</a> выбрал вас исполнителем своего спроса <a href="%3$s">%4$s</a>.', 'dao-consensus' ), $customer_link, $customer_name, get_permalink( $demand->ID ), $demand->post_title );
			$this->send_email( $performer_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

			// notify customer via personal cabinet and email
			$data['user_id'] = $demand->post_author;
			$data['message'] = sprintf( __( 'Статус спроса <a href="%1$s">"%2$s"</a> изменён на "В работе". Исполнитель: <a href="%3$s">%4$s</a>.', 'dao-consensus' ), get_permalink( $demand->ID ), $demand->post_title, $performer_link, $performer_name );
			$this->send_email( $customer_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'offer-in-process' ) {

			$offer = get_post( $meeting->card_id );

			$data['type'] = 'offer-in-process';

			$customer_name = $this->get_user_display_name( $meeting->post_author );
			$customer_link = get_author_posts_url( $meeting->post_author );
			$customer_email = $this->get_user_email( $meeting->post_author );

			$author_name  = $this->get_user_display_name( $offer->post_author );
			$author_link  = get_author_posts_url( $offer->post_author );
			$author_email = $this->get_user_email( $offer->post_author );

			// notify customer via personal cabinet and email
			$data['user_id'] = $meeting->post_author;
			$data['message'] = sprintf( __( 'Сделка по предложению <a href="%1$s">%2$s</a> <a href="%3$s">"%4$s"</a> заключена.', 'dao-consensus' ), $author_link, $author_name, get_permalink( $offer->ID ), $offer->post_title );
			$this->send_email( $customer_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

			// notify author of offer via personal cabinet and email
			$data['user_id'] = $offer->post_author;
			$data['message'] = sprintf( __( 'Поздравляем! <a href="%1$s">%2$s</a> решил воспользоваться вашим предложением <a href="%3$s">"%4$s"</a>.', 'dao-consensus' ), $customer_link, $customer_name, get_permalink( $offer->ID ), $offer->post_title );
			$this->send_email( $author_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
			$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

		} elseif ( $type === 'did-not-agree' ) {

			$data['type'] = 'did-not-agree';

			$card = get_post( $meeting->card_id );

			if ( $card->post_type === 'demands' ) {
				/* demand */
				$author_name = $this->get_user_display_name( $card->post_author );
				$author_link = get_author_posts_url( $card->post_author );
				$performer_email = $this->get_user_email( $meeting->post_author );

				$data['user_id'] = $meeting->post_author;
				$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> не выбрал вас исполнителем своего спроса <a href="%3$s">"%4$s"</a>.', 'dao-consensus' ), $author_link, $author_name, get_permalink( $card->ID ), $card->post_title );
				$this->send_email( $performer_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	
			} else {
				/* offer */
				$customer_name = $this->get_user_display_name( $meeting->post_author );
				$customer_link = get_author_posts_url( $meeting->post_author );
				$author_email = $this->get_user_email( $card->post_author );

				$data['user_id'] = $card->post_author;
				$data['message'] = sprintf( __( '<a href="%1$s">%2$s</a> отказался от вашего предложения <a href="%3$s">"%4$s"</a>.', 'dao-consensus' ), $customer_link, $customer_name, get_permalink( $card->ID ), $card->post_title );
				$this->send_email( $author_email, 'DAOC: Результат встречи', $data['message'] . ' ' . sprintf( '<a href="%s">Перейти к разделу "В работе"</a>', get_permalink( 126 ) ) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	
			}

		}
	}

	/**
	 * Create user transaction notification
	 *
	 * @param int    $user_id User ID
	 * @param string $type Notification type
	 *
	 * @return void
	 */
	public function create_transaction_notification( int $transaction_id, string $type ) {

		if ( ! get_post_status( $transaction_id ) ) {
			throw new Exception( "Transaction with such ID doesn't exist." );
			return;
		}

		if ( ! in_array( $type, array( 'completed', 'not-completed' ) ) ) {
			throw new Exception( 'There are only two types of transaction notification - completed, not-completed' );
			return;
		}

		$transaction = get_post( $transaction_id );

		$data = array(
			'post_id'	 => $transaction->ID,
			'type'		 => 'transaction',
			'created_at' => current_time( 'mysql' ),
		);

		$card = get_post( $transaction->card_id );

		global $wpdb;

		if ( $type === 'completed' ) {

			if ( $card->post_type === 'demands' ) {

				$performer_id = $card->performer;

				$author_name = $this->get_user_display_name( $card->post_author );
				$author_link = get_author_posts_url( $card->post_author );
				$author_email = $this->get_user_email( $card->post_author );

				$performer_name = $this->get_user_display_name( $performer_id );
				$performer_link = get_author_posts_url( $performer_id );
				$performer_email = $this->get_user_email( $performer_id );
	
				$data['user_id'] = $performer_id;
				$data['message'] = sprintf( '<a href="%1$s">%2$s</a> подтвердил выполнение спроса <a href="%3$s">"%4$s"</a>. <a href="%5$s?transaction_id=%6$d">Оставить отзыв</a>', $author_link, $author_name, get_permalink( $card->ID ), $card->post_title, get_permalink( 426 ), $transaction->ID );
				$this->send_email( $performer_email, 'DAOC: Выполнение спроса подтверждено', $data['message'] );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );
	
				$data['user_id'] = $card->post_author;
				$data['message'] = sprintf( 'Вы подтвердили выполнение спроса <a href="%1$s">"%2$s"</a> исполнителем <a href="%3$s">%4$s</a>. <a href="%5$s?transaction_id=%6$d">Оставить отзыв</a>', get_permalink( $card->ID ), $card->post_title, $performer_link, $performer_name, get_permalink( 426 ), $transaction->ID );
				$this->send_email( $author_email, 'DAOC: Выполнение спроса подтверждено', $data['message'] );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );
			
			} elseif ( $card->post_type === 'offers' ) {

				$customer_id = $transaction->initiator;

				$customer_name = $this->get_user_display_name( $customer_id );
				$customer_link = get_author_posts_url( $customer_id );
				$customer_email = $this->get_user_email( $customer_id );

				$author_name = $this->get_user_display_name( $card->post_author );
				$author_link = get_author_posts_url( $card->post_author );
				$author_email = $this->get_user_email( $card->post_author );
	
				$data['user_id'] = $card->post_author;
				$data['message'] = sprintf( '<a href="%1$s">%2$s</a> подтвердил выполнение предложения <a href="%3$s">"%4$s"</a>. <a href="%5$s?transaction_id=%6$d">Оставить отзыв</a>', $customer_link, $customer_name, get_permalink( $card->ID ), $card->post_title, get_permalink( 426 ), $transaction->ID );
				$this->send_email( $author_email, 'DAOC: Выполнение предложения подтверждено', $data['message'] );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	
	
				$data['user_id'] = $customer_id;
				$data['message'] = sprintf( 'Вы подтвердили выполнение предложения <a href="%1$s">"%2$s"</a> исполнителем <a href="%3$s">%4$s</a>. <a href="%5$s?transaction_id=%6$d">Оставить отзыв</a>', get_permalink( $card->ID ), $card->post_title, $author_link, $author_name, get_permalink( 426 ), $transaction->ID );
				$this->send_email( $customer_email, 'DAOC: Выполнение предложения подтверждено', $data['message'] );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

			} else {
				throw new Exception( 'The card on which transaction was made is neither demand nor offer.' );
				return;
			}

		} elseif ( $type === 'not-completed' ) {

			if ( $card->post_type === 'demands' ) {

				$performer_id = $card->performer;

				$author_name = $this->get_user_display_name( $card->post_author );
				$author_link = get_author_posts_url( $card->post_author );
				$performer_email = $this->get_user_email( $performer_id );

				$performer_name = $this->get_user_display_name( $performer_id );
				$performer_link = get_author_posts_url( $performer_id );
				$author_email = $this->get_user_email( $card->post_author );
	
				$data['user_id'] = $performer_id;
				$data['message'] = sprintf( '<a href="%1$s">%2$s</a> не подтвердил выполнение спроса <a href="%3$s">"%4$s"</a>.', $author_link, $author_name, get_permalink( $card->ID ), $card->post_title );
				$this->send_email( $performer_email, 'DAOC: Выполнение спроса не подтверждено', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к профилю</a>', get_permalink( 120 )) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	
	
				$data['user_id'] = $card->post_author;
				$data['message'] = sprintf( 'Вы подтвердили не выполнение спроса <a href="%1$s">"%2$s"</a> исполнителем <a href="%3$s">%4$s</a>.', get_permalink( $card->ID ), $card->post_title, $performer_link, $performer_name );
				$this->send_email( $performer_email, 'DAOC: Выполнение спроса не подтверждено', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к профилю</a>', get_permalink( 120 )) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	

			} elseif ( $card->post_type === 'offers' ) {

				$customer_id = $transaction->initiator;

				$author_name = $this->get_user_display_name( $card->post_author );
				$author_link = get_author_posts_url( $card->post_author );
				$author_email = $this->get_user_email( $card->post_author );

				$customer_name = $this->get_user_display_name( $customer_id );
				$customer_link = get_author_posts_url( $customer_id );
				$customer_email = $this->get_user_email( $customer_id );
	
				$data['user_id'] = $card->post_author;
				$data['message'] = sprintf( '<a href="%1$s">%2$s</a> не подтвердил выполнение предложения <a href="%3$s">"%4$s"</a>.', $customer_link, $customer_name, get_permalink( $card->ID ), $card->post_title );
				$this->send_email( $author_email, 'DAOC: Выполнение предложения не подтверждено', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к профилю</a>', get_permalink( 120 )) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );
	
				$data['user_id'] = $customer_id;
				$data['message'] = sprintf( 'Вы подтвердили не выполнение предложения <a href="%1$s">%2$s</a> <a href="%3$s">"%4$s"</a>.', $author_link, $author_name, get_permalink( $card->ID ), $card->post_title );
				$this->send_email( $customer_email, 'DAOC: Выполнение предложения не подтверждено', $data['message'] . ' ' . sprintf('<a href="%s">Перейти к профилю</a>', get_permalink( 120 )) );
				$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );	

			} else {
				throw new Exception( 'The card on which transaction was made is neither demand nor offer.' );
				return;
			}
		}
	}

	/**
	 * Create user card notification
	 *
	 * @param int    $card_id Card ID
	 * @param string $card_type Card type
	 * @param string $type Notification type
	 *
	 * @return void
	 */
	public function create_card_notification( int $card_id, string $type ) {

		if ( ! get_post_status( $card_id ) ) {
			throw new Exception( "Card with such ID doesn't exist." );
		}

		if ( ! in_array( $type, array( 'publish', 'in-process' ) ) ) {
			throw new Exception( 'There are only two card notification types: publish, in-process' );
		}

		$card = get_post( $card_id );

		if ( ! in_array( $card->post_type, array( 'offers', 'demands' ) ) ) {
			throw new Exception( 'There are only two types of card: offers and demands.' );
		}

		global $wpdb;

		$data = array(
			'user_id' => $card->post_author,
			'post_id' => $card->ID,
			'type'    => 'card',
			'created_at' => current_time('mysql', 1)
		);

		if ( $type === 'publish' ) {
			$data['message'] = ( $card_type === 'offers' )
			? sprintf( __( 'Ваше <a href="%1$s">предложение</a> успешно прошло модерацию.', 'dao-consensus' ), get_permalink( $card->ID ) )
			: sprintf( __( 'Ваш <a href="%1$s">спрос</a> успешно прошёл модерацию.', 'dao-consensus' ), get_permalink( $card->ID ) );
		} elseif ( $type === 'in-process' ) {
			if ( $card->post_type === 'demands' ) {
				$performer = get_userdata( $card->performer );
				$performer_name = $performer->display_name;
				$performer_link = get_author_posts_url( $card->performer );
	
				$data['message'] = sprintf( __( 'Cтатус вашего спроса <a href="%1$s">%2$s</a> изменён на "В работе". Исполнитель <a href="%3$s">%4$s</a>.', 'dao-consensus' ), get_permalink( $card->ID ), $card->post_title, $performer_link, $performer_name );	
			}
		}

		$wpdb->insert( "{$wpdb->prefix}user_notifications", $data );

	}

	/**
	 * Delete user notification
	 *
	 * @param int $id Notification ID
	 *
	 * @return void
	 */
	public function delete_notification( int $id ) {
		global $wpdb;
		$wpdb->query( 
			$wpdb->prepare( "DELETE FROM {$wpdb->prefix}user_notifications WHERE id = %d", $id )	
		);
	}

	/**
	 * Check user notification
	 *
	 * @param int $user_id User ID
	 *
	 * @return void
	 */
	public function check_user_notifications( int $user_id ) {

	}

	public function send_email( string $to, string $subject, string $message ) {
		$headers = array(
			'Content-Type: text/html; charset=UTF-8'
		);

		wp_mail( $to, $subject, $message, $headers );
	}

	public function get_user_display_name( int $user_id ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM {$wpdb->users} WHERE ID = %d", $user_id ) );	
	}

	public function get_user_email( int $user_id ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM {$wpdb->users} WHERE ID = %d", $user_id ) );	
	}

}
