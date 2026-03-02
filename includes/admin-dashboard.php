<?php
/**
 * Admin Dashboard for Local Business Interviews Plugin
 * Manages submissions, approvals, settings, and analytics
 *
 * @package LocalBusinessInterviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LBI_Admin_Dashboard {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'wp_ajax_lbi_approve_submission', array( $this, 'handle_approve_submission' ) );
		add_action( 'wp_ajax_lbi_reject_submission', array( $this, 'handle_reject_submission' ) );
		add_action( 'wp_ajax_lbi_toggle_featured', array( $this, 'handle_toggle_featured' ) );
		add_action( 'wp_ajax_lbi_batch_action', array( $this, 'handle_batch_action' ) );
	}

	/**
	 * Register admin menu items
	 */
	public function register_admin_menu() {
		add_menu_page(
			esc_html__( 'Local Business Interviews', 'local-business-interviews' ),
			esc_html__( 'Business Interviews', 'local-business-interviews' ),
			'manage_options',
			'lbi-dashboard',
			array( $this, 'render_dashboard' ),
			'dashicons-format-chat',
			25
		);

		add_submenu_page(
			'lbi-dashboard',
			esc_html__( 'Dashboard', 'local-business-interviews' ),
			esc_html__( 'Dashboard', 'local-business-interviews' ),
			'manage_options',
			'lbi-dashboard',
			array( $this, 'render_dashboard' )
		);

		add_submenu_page(
			'lbi-dashboard',
			esc_html__( 'Pending Interviews', 'local-business-interviews' ),
			esc_html__( 'Pending Interviews', 'local-business-interviews' ),
			'manage_options',
			'lbi-pending-interviews',
			array( $this, 'render_pending_interviews' )
		);

		add_submenu_page(
			'lbi-dashboard',
			esc_html__( 'Pending Directory', 'local-business-interviews' ),
			esc_html__( 'Pending Directory', 'local-business-interviews' ),
			'manage_options',
			'lbi-pending-directory',
			array( $this, 'render_pending_directory' )
		);

		add_submenu_page(
			'lbi-dashboard',
			esc_html__( 'Settings', 'local-business-interviews' ),
			esc_html__( 'Settings', 'local-business-interviews' ),
			'manage_options',
			'lbi-settings',
			array( $this, 'render_settings' )
		);
	}

	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'lbi-' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'lbi-admin-css',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin.css',
			array(),
			'1.0.0'
		);

		wp_enqueue_script(
			'lbi-admin-js',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/admin.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_localize_script(
			'lbi-admin-js',
			'lbiAdmin',
			array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'lbi_admin_nonce' ),
				'messages' => array(
					'approveSuccess' => esc_html__( 'Submission approved successfully!', 'local-business-interviews' ),
					'rejectSuccess'  => esc_html__( 'Submission rejected.', 'local-business-interviews' ),
					'error'          => esc_html__( 'An error occurred. Please try again.', 'local-business-interviews' ),
				),
			)
		);
	}

	/**
	 * Render main dashboard
	 */
	public function render_dashboard() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'local-business-interviews' ) );
		}

		echo '<div class="wrap lbi-admin-dashboard">';
		
		// Header
		echo '<div class="lbi-dashboard-header">';
		echo '<h1>' . esc_html__( 'Local Business Interviews Dashboard', 'local-business-interviews' ) . '</h1>';
		echo '<p>' . esc_html__( 'Manage submissions, approve content, and monitor community engagement', 'local-business-interviews' ) . '</p>';
		echo '</div>';

		// Stats Grid
		echo '<div class="lbi-dashboard-grid">';
		
		// Pending Submissions
		$pending_interviews = wp_count_posts( 'interview' );
		$pending_interview_count = isset( $pending_interviews->pending ) ? $pending_interviews->pending : 0;
		echo '<div class="lbi-stat-card warning">';
		echo '<div class="lbi-stat-label">' . esc_html__( 'Pending Interviews', 'local-business-interviews' ) . '</div>';
		echo '<div class="lbi-stat-value">' . intval( $pending_interview_count ) . '</div>';
		echo '<div class="lbi-stat-description"><a href="' . esc_url( admin_url( 'admin.php?page=lbi-pending-interviews' ) ) . '" style="color: var(--lbi-warning); text-decoration: none;">' . esc_html__( 'Review pending →', 'local-business-interviews' ) . '</a></div>';
		echo '</div>';

		// Published Interviews
		$published_interviews = wp_count_posts( 'interview' );
		$published_interview_count = isset( $published_interviews->publish ) ? $published_interviews->publish : 0;
		echo '<div class="lbi-stat-card success">';
		echo '<div class="lbi-stat-label">' . esc_html__( 'Published Interviews', 'local-business-interviews' ) . '</div>';
		echo '<div class="lbi-stat-value">' . intval( $published_interview_count ) . '</div>';
		echo '<div class="lbi-stat-description">' . esc_html__( 'Active content', 'local-business-interviews' ) . '</div>';
		echo '</div>';

		// Featured Businesses
		$featured = new WP_Query( array(
			'post_type'   => array( 'interview', 'directory' ),
			'meta_key'    => 'featured',
			'meta_value'  => '1',
			'post_status' => 'publish',
			'fields'      => 'ids',
			'nopaging'    => true,
		) );
		$featured_count = $featured->found_posts;
		echo '<div class="lbi-stat-card info">';
		echo '<div class="lbi-stat-label">' . esc_html__( 'Featured Items', 'local-business-interviews' ) . '</div>';
		echo '<div class="lbi-stat-value">' . intval( $featured_count ) . '</div>';
		echo '<div class="lbi-stat-description">' . esc_html__( 'Highlighted content', 'local-business-interviews' ) . '</div>';
		echo '</div>';

		// Rate Limited IPs
		$transients = wp_cache_get( 'lbi_rate_limits' );
		$rate_limit_count = is_array( $transients ) ? count( $transients ) : 0;
		echo '<div class="lbi-stat-card danger">';
		echo '<div class="lbi-stat-label">' . esc_html__( 'Rate Limited IPs', 'local-business-interviews' ) . '</div>';
		echo '<div class="lbi-stat-value">' . intval( $rate_limit_count ) . '</div>';
		echo '<div class="lbi-stat-description">' . esc_html__( 'Currently restricted', 'local-business-interviews' ) . '</div>';
		echo '</div>';

		echo '</div>'; // End Stats Grid

		// Recent Submissions
		echo '<div class="lbi-submissions-table" style="margin-bottom: 40px;">';
		echo '<div style="padding: 20px; background: white; border-bottom: 1px solid #e0e0e0;">';
		echo '<h3 style="margin: 0; font-size: 1.2rem; color: var(--lbi-dark);">' . esc_html__( 'Recent Submissions', 'local-business-interviews' ) . '</h3>';
		echo '</div>';

		$recent = new WP_Query( array(
			'post_type'      => array( 'interview', 'directory' ),
			'post_status'    => 'pending',
			'posts_per_page' => 10,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );

		if ( $recent->have_posts() ) {
			echo '<table>';
			echo '<thead>';
			echo '<tr>';
			echo '<th>' . esc_html__( 'Title', 'local-business-interviews' ) . '</th>';
			echo '<th>' . esc_html__( 'Type', 'local-business-interviews' ) . '</th>';
			echo '<th>' . esc_html__( 'Date', 'local-business-interviews' ) . '</th>';
			echo '<th>' . esc_html__( 'Risk', 'local-business-interviews' ) . '</th>';
			echo '<th>' . esc_html__( 'Actions', 'local-business-interviews' ) . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			while ( $recent->have_posts() ) {
				$recent->the_post();
				$post_id = get_the_ID();
				$post_type = get_post_type_object( get_post_type( $post_id ) );
				$recaptcha_score = floatval( get_post_meta( $post_id, 'recaptcha_score', true ) );
				
				$risk_level = 'low';
				if ( $recaptcha_score < 0.3 ) {
					$risk_level = 'high';
				} elseif ( $recaptcha_score < 0.6 ) {
					$risk_level = 'medium';
				}

				echo '<tr class="pending">';
				echo '<td><strong>' . esc_html( get_the_title() ) . '</strong></td>';
				echo '<td>' . esc_html( $post_type->labels->singular_name ) . '</td>';
				echo '<td>' . esc_html( get_the_date( 'M d, Y' ) ) . '</td>';
				echo '<td>' . $this->render_risk_indicator( $risk_level, $recaptcha_score ) . '</td>';
				echo '<td>';
				echo '<div class="lbi-btn-group">';
				echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ) . '" class="lbi-btn-sm lbi-btn-secondary-sm">' . esc_html__( 'Edit', 'local-business-interviews' ) . '</a>';
				echo '<button class="lbi-btn-sm lbi-btn-success-sm lbi-approve-btn" data-post-id="' . intval( $post_id ) . '">' . esc_html__( 'Approve', 'local-business-interviews' ) . '</button>';
				echo '<button class="lbi-btn-sm lbi-btn-danger-sm lbi-reject-btn" data-post-id="' . intval( $post_id ) . '">' . esc_html__( 'Reject', 'local-business-interviews' ) . '</button>';
				echo '</div>';
				echo '</td>';
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
			wp_reset_postdata();
		} else {
			echo '<div style="padding: 40px; text-align: center; background: white;">';
			echo '<p style="color: #999; margin: 0;">' . esc_html__( 'No pending submissions.', 'local-business-interviews' ) . '</p>';
			echo '</div>';
		}

		echo '</div>'; // End Recent Submissions

		echo '</div>'; // End wrap
	}

	/**
	 * Render pending interviews
	 */
	public function render_pending_interviews() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'local-business-interviews' ) );
		}

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Pending Interviews', 'local-business-interviews' ) . '</h1>';

		$pending = new WP_Query( array(
			'post_type'      => 'interview',
			'post_status'    => 'pending',
			'posts_per_page' => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );

		if ( $pending->have_posts() ) {
			while ( $pending->have_posts() ) {
				$pending->the_post();
				$post_id = get_the_ID();
				$this->render_submission_card( $post_id, 'interview' );
			}
			wp_reset_postdata();
		} else {
			echo '<div class="lbi-empty-state">';
			echo '<p>' . esc_html__( 'No pending interviews to review.', 'local-business-interviews' ) . '</p>';
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Render pending directory submissions
	 */
	public function render_pending_directory() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'local-business-interviews' ) );
		}

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Pending Directory Submissions', 'local-business-interviews' ) . '</h1>';

		$pending = new WP_Query( array(
			'post_type'      => 'directory',
			'post_status'    => 'pending',
			'posts_per_page' => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );

		if ( $pending->have_posts() ) {
			while ( $pending->have_posts() ) {
				$pending->the_post();
				$post_id = get_the_ID();
				$this->render_submission_card( $post_id, 'directory' );
			}
			wp_reset_postdata();
		} else {
			echo '<div class="lbi-empty-state">';
			echo '<p>' . esc_html__( 'No pending directory submissions to review.', 'local-business-interviews' ) . '</p>';
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Render a submission card for review
	 */
	private function render_submission_card( $post_id, $type ) {
		$title = get_the_title( $post_id );
		$date = get_the_date( 'F j, Y \a\t g:ia', $post_id );
		$recaptcha_score = floatval( get_post_meta( $post_id, 'recaptcha_score', true ) );
		$submitter_ip = get_post_meta( $post_id, 'submitter_ip', true );
		$admin_notes = get_post_meta( $post_id, 'admin_notes', true );

		$risk_level = 'low';
		if ( $recaptcha_score < 0.3 ) {
			$risk_level = 'high';
		} elseif ( $recaptcha_score < 0.6 ) {
			$risk_level = 'medium';
		}

		?>
		<div class="lbi-approval-card">
			<div class="lbi-approval-header">
				<div>
					<h3 class="lbi-approval-title"><?php echo esc_html( $title ); ?></h3>
					<div class="lbi-approval-meta">
						<span>📅 <?php echo esc_html( $date ); ?></span>
						<span>🔒 <?php echo esc_html( $submitter_ip ); ?></span>
					</div>
				</div>
				<?php echo $this->render_risk_indicator( $risk_level, $recaptcha_score ); ?>
			</div>

			<div class="lbi-approval-content">
				<?php
				if ( 'interview' === $type ) {
					echo '<p><strong>' . esc_html__( 'Interviewee:', 'local-business-interviews' ) . '</strong> ' . esc_html( get_post_meta( $post_id, 'interviewee_name', true ) ) . '</p>';
					echo '<p><strong>' . esc_html__( 'Company:', 'local-business-interviews' ) . '</strong> ' . esc_html( get_post_meta( $post_id, 'company_name', true ) ) . '</p>';
					echo '<p><strong>' . esc_html__( 'Website:', 'local-business-interviews' ) . '</strong> <a href="' . esc_url( get_post_meta( $post_id, 'company_website', true ) ) . '" target="_blank">' . esc_html( get_post_meta( $post_id, 'company_website', true ) ) . '</a></p>';
				} elseif ( 'directory' === $type ) {
					echo '<p><strong>' . esc_html__( 'Business:', 'local-business-interviews' ) . '</strong> ' . esc_html( get_post_meta( $post_id, 'business_name', true ) ) . '</p>';
					echo '<p><strong>' . esc_html__( 'Contact:', 'local-business-interviews' ) . '</strong> ' . esc_html( get_post_meta( $post_id, 'email', true ) ) . ' | ' . esc_html( get_post_meta( $post_id, 'phone', true ) ) . '</p>';
					echo '<p><strong>' . esc_html__( 'Address:', 'local-business-interviews' ) . '</strong> ' . esc_html( get_post_meta( $post_id, 'address', true ) ) . '</p>';
				}
				?>
			</div>

			<?php if ( ! empty( $admin_notes ) ) : ?>
				<div style="padding: 16px; background-color: #fff3cd; border-radius: 4px; margin-bottom: 16px;">
					<p style="margin: 0; color: #856404;"><strong><?php esc_html_e( 'Admin Notes:', 'local-business-interviews' ); ?></strong></p>
					<p style="margin: 8px 0 0 0; color: #856404;"><?php echo esc_html( $admin_notes ); ?></p>
				</div>
			<?php endif; ?>

			<div class="lbi-approval-actions">
				<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) ); ?>" class="lbi-btn-sm lbi-btn-secondary-sm">
					<?php esc_html_e( 'Edit Full Details', 'local-business-interviews' ); ?>
				</a>
				<button class="lbi-btn-sm lbi-btn-success-sm lbi-approve-btn" data-post-id="<?php echo intval( $post_id ); ?>">
					<?php esc_html_e( '✓ Approve', 'local-business-interviews' ); ?>
				</button>
				<button class="lbi-btn-sm lbi-btn-danger-sm lbi-reject-btn" data-post-id="<?php echo intval( $post_id ); ?>">
					<?php esc_html_e( '✕ Reject', 'local-business-interviews' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Render risk indicator
	 */
	private function render_risk_indicator( $risk_level, $score ) {
		$class = 'lbi-risk-indicator ' . $risk_level;
		$label = '';
		
		if ( 'high' === $risk_level ) {
			$label = esc_html__( 'High Risk', 'local-business-interviews' );
		} elseif ( 'medium' === $risk_level ) {
			$label = esc_html__( 'Medium Risk', 'local-business-interviews' );
		} else {
			$label = esc_html__( 'Low Risk', 'local-business-interviews' );
		}

		return '<span class="' . esc_attr( $class ) . '"><span class="lbi-risk-dot"></span> ' . esc_html( $label ) . ' (' . number_format( $score, 2 ) . ')</span>';
	}

	/**
	 * Render settings page
	 */
	public function render_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'local-business-interviews' ) );
		}

		if ( isset( $_POST['submit'] ) ) {
			check_admin_referer( 'lbi_settings_nonce' );

			update_option( 'lbi_hero_title', sanitize_text_field( $_POST['lbi_hero_title'] ?? '' ) );
			update_option( 'lbi_hero_subtitle', sanitize_text_field( $_POST['lbi_hero_subtitle'] ?? '' ) );

			echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully.', 'local-business-interviews' ) . '</p></div>';
		}

		$hero_title = get_option( 'lbi_hero_title', 'Discover Local Business Stories' );
		$hero_subtitle = get_option( 'lbi_hero_subtitle', 'Meet the entrepreneurs and leaders shaping our community' );

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Local Business Interviews Settings', 'local-business-interviews' ); ?></h1>

			<form method="post" action="">
				<?php wp_nonce_field( 'lbi_settings_nonce' ); ?>

				<div class="lbi-settings-box">
					<h3><?php esc_html_e( 'Homepage Hero Section', 'local-business-interviews' ); ?></h3>
					
					<div class="lbi-settings-field">
						<label for="lbi_hero_title"><?php esc_html_e( 'Hero Title', 'local-business-interviews' ); ?></label>
						<input type="text" name="lbi_hero_title" id="lbi_hero_title" value="<?php echo esc_attr( $hero_title ); ?>" />
						<p class="lbi-settings-hint"><?php esc_html_e( 'Main headline displayed on the homepage', 'local-business-interviews' ); ?></p>
					</div>

					<div class="lbi-settings-field">
						<label for="lbi_hero_subtitle"><?php esc_html_e( 'Hero Subtitle', 'local-business-interviews' ); ?></label>
						<input type="text" name="lbi_hero_subtitle" id="lbi_hero_subtitle" value="<?php echo esc_attr( $hero_subtitle ); ?>" />
						<p class="lbi-settings-hint"><?php esc_html_e( 'Supporting text displayed below the main headline', 'local-business-interviews' ); ?></p>
					</div>
				</div>

				<div class="lbi-settings-box">
					<h3><?php esc_html_e( 'Security Settings', 'local-business-interviews' ); ?></h3>
					<p><?php esc_html_e( 'All security settings are configured through environment variables and cannot be changed in the admin panel for security reasons. These include reCAPTCHA keys, rate limits, and spam detection thresholds.', 'local-business-interviews' ); ?></p>
				</div>

				<?php submit_button( esc_html__( 'Save Settings', 'local-business-interviews' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle AJAX approve submission
	 */
	public function handle_approve_submission() {
		check_ajax_referer( 'lbi_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Permission denied', 'local-business-interviews' ), 403 );
		}

		$post_id = intval( $_POST['post_id'] ?? 0 );
		if ( ! $post_id ) {
			wp_send_json_error( esc_html__( 'Invalid post ID', 'local-business-interviews' ) );
		}

		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'interview', 'directory' ), true ) ) {
			wp_send_json_error( esc_html__( 'Invalid post', 'local-business-interviews' ) );
		}

		wp_update_post( array(
			'ID'          => $post_id,
			'post_status' => 'publish',
		) );

		update_post_meta( $post_id, 'approval_status', 'approved' );

		// Send approval email
		do_action( 'lbi_submission_approved', $post_id );

		wp_send_json_success( array(
			'message' => esc_html__( 'Submission approved successfully!', 'local-business-interviews' ),
		) );
	}

	/**
	 * Handle AJAX reject submission
	 */
	public function handle_reject_submission() {
		check_ajax_referer( 'lbi_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Permission denied', 'local-business-interviews' ), 403 );
		}

		$post_id = intval( $_POST['post_id'] ?? 0 );
		$reason = sanitize_text_field( $_POST['reason'] ?? '' );

		if ( ! $post_id ) {
			wp_send_json_error( esc_html__( 'Invalid post ID', 'local-business-interviews' ) );
		}

		wp_trash_post( $post_id );
		update_post_meta( $post_id, 'approval_status', 'rejected' );
		update_post_meta( $post_id, 'admin_notes', $reason );

		// Send rejection email
		do_action( 'lbi_submission_rejected', $post_id );

		wp_send_json_success( array(
			'message' => esc_html__( 'Submission rejected.', 'local-business-interviews' ),
		) );
	}

	/**
	 * Handle toggle featured status
	 */
	public function handle_toggle_featured() {
		check_ajax_referer( 'lbi_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Permission denied', 'local-business-interviews' ), 403 );
		}

		$post_id = intval( $_POST['post_id'] ?? 0 );
		if ( ! $post_id ) {
			wp_send_json_error( esc_html__( 'Invalid post ID', 'local-business-interviews' ) );
		}

		$featured = get_post_meta( $post_id, 'featured', true );
		$new_value = empty( $featured ) ? '1' : '';

		update_post_meta( $post_id, 'featured', $new_value );

		wp_send_json_success( array(
			'featured' => ! empty( $new_value ),
			'message' => ! empty( $new_value ) 
				? esc_html__( 'Added to featured', 'local-business-interviews' )
				: esc_html__( 'Removed from featured', 'local-business-interviews' ),
		) );
	}

	/**
	 * Handle batch actions
	 */
	public function handle_batch_action() {
		check_ajax_referer( 'lbi_admin_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Permission denied', 'local-business-interviews' ), 403 );
		}

		$action = sanitize_text_field( $_POST['action_type'] ?? '' );
		$post_ids = array_map( 'intval', $_POST['post_ids'] ?? array() );

		if ( empty( $post_ids ) ) {
			wp_send_json_error( esc_html__( 'No posts selected', 'local-business-interviews' ) );
		}

		foreach ( $post_ids as $post_id ) {
			if ( 'approve' === $action ) {
				wp_update_post( array(
					'ID'          => $post_id,
					'post_status' => 'publish',
				) );
				update_post_meta( $post_id, 'approval_status', 'approved' );
				do_action( 'lbi_submission_approved', $post_id );
			} elseif ( 'reject' === $action ) {
				wp_trash_post( $post_id );
				update_post_meta( $post_id, 'approval_status', 'rejected' );
				do_action( 'lbi_submission_rejected', $post_id );
			}
		}

		wp_send_json_success( array(
			'message' => sprintf(
				esc_html__( 'Successfully %s %d submission(s)', 'local-business-interviews' ),
				'approve' === $action ? 'approved' : 'rejected',
				count( $post_ids )
			),
		) );
	}
}

// Initialize the admin dashboard
if ( is_admin() ) {
	new LBI_Admin_Dashboard();
}
