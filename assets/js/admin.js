/**
 * Admin JavaScript for Local Business Interviews Plugin
 * Handles AJAX calls, modals, and interactive admin features
 */

(function($) {
	'use strict';

	const LBIAdmin = {
		/**
		 * Initialize
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind event handlers
		 */
		bindEvents: function() {
			// Approve button
			$(document).on('click', '.lbi-approve-btn', this.handleApprove.bind(this));
			
			// Reject button
			$(document).on('click', '.lbi-reject-btn', this.handleReject.bind(this));
			
			// Featured toggle
			$(document).on('click', '.lbi-toggle-featured', this.handleToggleFeatured.bind(this));
			
			// Modal close
			$(document).on('click', '.lbi-modal-close, .lbi-modal-overlay', this.closeModal.bind(this));
			
			// Tab navigation
			$(document).on('click', '.lbi-tab-button', this.handleTabClick.bind(this));
		},

		/**
		 * Handle approve action
		 */
		handleApprove: function(e) {
			e.preventDefault();
			const postId = $(e.target).data('post-id');
			
			if (!confirm(lbiAdmin.messages.approveSuccess)) {
				return;
			}

			this.showLoading($(e.target));

			$.ajax({
				url: lbiAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lbi_approve_submission',
					post_id: postId,
					_wpnonce: lbiAdmin.nonce
				},
				success: (response) => {
					if (response.success) {
						this.showNotification(response.data.message, 'success', true);
						setTimeout(() => location.reload(), 1500);
					} else {
						this.showNotification(response.data || lbiAdmin.messages.error, 'error');
					}
					this.hideLoading($(e.target));
				},
				error: () => {
					this.showNotification(lbiAdmin.messages.error, 'error');
					this.hideLoading($(e.target));
				}
			});
		},

		/**
		 * Handle reject action
		 */
		handleReject: function(e) {
			e.preventDefault();
			const postId = $(e.target).data('post-id');
			
			// Show modal for reason
			const reason = prompt('Please provide a reason for rejection:');
			if (reason === null) return;

			this.showLoading($(e.target));

			$.ajax({
				url: lbiAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lbi_reject_submission',
					post_id: postId,
					reason: reason,
					_wpnonce: lbiAdmin.nonce
				},
				success: (response) => {
					if (response.success) {
						this.showNotification(response.data.message, 'success', true);
						setTimeout(() => location.reload(), 1500);
					} else {
						this.showNotification(response.data || lbiAdmin.messages.error, 'error');
					}
					this.hideLoading($(e.target));
				},
				error: () => {
					this.showNotification(lbiAdmin.messages.error, 'error');
					this.hideLoading($(e.target));
				}
			});
		},

		/**
		 * Handle featured toggle
		 */
		handleToggleFeatured: function(e) {
			e.preventDefault();
			const postId = $(e.target).data('post-id');
			const $btn = $(e.target);

			this.showLoading($btn);

			$.ajax({
				url: lbiAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lbi_toggle_featured',
					post_id: postId,
					_wpnonce: lbiAdmin.nonce
				},
				success: (response) => {
					if (response.success) {
						$btn.toggleClass('active');
						this.showNotification(response.data.message, 'success');
					}
					this.hideLoading($btn);
				},
				error: () => {
					this.showNotification(lbiAdmin.messages.error, 'error');
					this.hideLoading($btn);
				}
			});
		},

		/**
		 * Handle tab clicks
		 */
		handleTabClick: function(e) {
			const $tab = $(e.target);
			const tabName = $tab.data('tab');
			
			if (!tabName) return;

			// Update active tab button
			$tab.siblings().removeClass('active');
			$tab.addClass('active');

			// Update active content
			const $content = $('[data-tab-content="' + tabName + '"]');
			$content.siblings('[data-tab-content]').removeClass('active');
			$content.addClass('active');
		},

		/**
		 * Show loading state
		 */
		showLoading: function($element) {
			$element.prop('disabled', true);
			const $loading = $('<span class="lbi-loading" style="margin-left: 8px;"></span>');
			$element.append($loading);
		},

		/**
		 * Hide loading state
		 */
		hideLoading: function($element) {
			$element.prop('disabled', false);
			$element.find('.lbi-loading').remove();
		},

		/**
		 * Show notification
		 */
		showNotification: function(message, type = 'info', isAutoClose = false) {
			const noticeClass = type === 'success' ? 'notice-success' : 
				type === 'error' ? 'notice-error' : 'notice-info';
			
			const $notice = $(`
				<div class="notice ${noticeClass} is-dismissible" style="margin-top: 20px;">
					<p>${message}</p>
				</div>
			`);

			$('h1').after($notice);

			// Auto-dismiss
			if (isAutoClose) {
				setTimeout(() => $notice.fadeOut(() => $notice.remove()), 3000);
			}

			// Handle manual dismiss
			$notice.find('.notice-dismiss').on('click', function() {
				$notice.fadeOut(() => $notice.remove());
			});
		},

		/**
		 * Close modal
		 */
		closeModal: function(e) {
			if ($(e.target).hasClass('lbi-modal-overlay') || $(e.target).hasClass('lbi-modal-close')) {
				const $overlay = $(e.target).closest('.lbi-modal-overlay');
				$overlay.removeClass('active');
			}
		},

		/**
		 * Open modal
		 */
		openModal: function(modalId) {
			$('[data-modal="' + modalId + '"]').addClass('active');
		}
	};

	// Initialize when DOM is ready
	$(document).ready(function() {
		LBIAdmin.init();
	});

	// Expose to global scope for inline handlers
	window.LBIAdmin = LBIAdmin;

})(jQuery);
