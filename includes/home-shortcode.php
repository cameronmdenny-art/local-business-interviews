<?php
/**
 * Home Page Shortcode for Local Business Interviews
 * Use: [lbi_home_page]
 * 
 * @package LocalBusinessInterviews
 */

if (!defined('ABSPATH')) {
	exit;
}

function lbi_home_page_shortcode() {
	ob_start();
	?>
	<style>
		:root {
			--lbi-gold: #bfa673;
			--lbi-dark: #1a1a1a;
			--lbi-light: #f5f5f5;
			--lbi-text: #333;
		}

		.lbi-hero {
			position: relative;
			min-height: 600px;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			background: linear-gradient(135deg, var(--lbi-dark) 0%, #2a2a2a 100%);
			overflow: hidden;
		}

		.lbi-hero::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(191,166,115,.05)" stroke-width="1"/></pattern></defs><rect width="1200" height="600" fill="url(%23grid)"/></svg>');
			opacity: 0.3;
		}

		.lbi-hero-content {
			position: relative;
			z-index: 2;
			max-width: 800px;
			padding: 2rem;
		}

		.lbi-logo {
			max-width: 120px;
			height: auto;
			margin: 0 auto 2rem;
			display: block;
		}

		.lbi-hero h1 {
			font-size: clamp(2rem, 8vw, 3.5rem);
			margin: 1rem 0;
			font-weight: 700;
			letter-spacing: -1px;
			line-height: 1.2;
		}

		.lbi-hero p {
			font-size: 1.25rem;
			margin: 1.5rem 0;
			color: rgba(255, 255, 255, 0.9);
			line-height: 1.6;
		}

		.lbi-cta-button {
			display: inline-block;
			background-color: var(--lbi-gold);
			color: var(--lbi-dark);
			padding: 1rem 2.5rem;
			font-size: 1rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 1px;
			border: 2px solid var(--lbi-gold);
			border-radius: 50px;
			text-decoration: none;
			transition: all 0.3s ease;
			margin-top: 2rem;
			cursor: pointer;
		}

		.lbi-cta-button:hover {
			background-color: transparent;
			color: var(--lbi-gold);
		}

		.lbi-content-section {
			padding: 4rem 2rem;
			background-color: var(--lbi-light);
		}

		.lbi-content-wrapper {
			max-width: 1200px;
			margin: 0 auto;
		}

		.lbi-section-title {
			font-size: 2.5rem;
			text-align: center;
			margin-bottom: 3rem;
			color: var(--lbi-dark);
			font-weight: 700;
		}

		.lbi-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 2rem;
			margin-bottom: 3rem;
		}

		.lbi-card {
			background: white;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
			transition: all 0.3s ease;
			display: flex;
			flex-direction: column;
		}

		.lbi-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
		}

		.lbi-card-image {
			width: 100%;
			height: 250px;
			object-fit: cover;
			background-color: #e0e0e0;
		}

		.lbi-card-body {
			padding: 1.5rem;
			flex-grow: 1;
			display: flex;
			flex-direction: column;
		}

		.lbi-card-category {
			font-size: 0.85rem;
			color: var(--lbi-gold);
			font-weight: 600;
			text-transform: uppercase;
			margin-bottom: 0.5rem;
		}

		.lbi-card-title {
			font-size: 1.3rem;
			font-weight: 700;
			margin: 0.5rem 0 1rem;
			color: var(--lbi-dark);
			line-height: 1.4;
		}

		.lbi-card-excerpt {
			font-size: 0.95rem;
			color: #666;
			margin-bottom: 1rem;
			flex-grow: 1;
			line-height: 1.6;
		}

		.lbi-card-link {
			color: var(--lbi-gold);
			text-decoration: none;
			font-weight: 600;
			display: inline-block;
			margin-top: auto;
			transition: color 0.3s ease;
		}

		.lbi-card-link:hover {
			color: var(--lbi-dark);
		}

		.lbi-empty-state {
			text-align: center;
			padding: 3rem 2rem;
			color: #999;
		}

		.lbi-empty-state p {
			font-size: 1.1rem;
			margin: 1rem 0;
		}

		@media (max-width: 768px) {
			.lbi-hero {
				min-height: 400px;
			}

			.lbi-hero h1 {
				font-size: 2rem;
			}

			.lbi-content-section {
				padding: 2rem 1rem;
			}

			.lbi-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>

	<!-- Hero Section -->
	<section class="lbi-hero">
		<div class="lbi-hero-content">
			<?php
			$custom_logo_id = get_theme_mod('custom_logo');
			if ($custom_logo_id) {
				echo wp_get_attachment_image($custom_logo_id, 'medium', false, ['class' => 'lbi-logo']);
			}
			?>

			<h1><?php echo wp_kses_post(get_option('lbi_hero_title', 'Discover Local Business Stories')); ?></h1>
			<p><?php echo wp_kses_post(get_option('lbi_hero_subtitle', 'Meet the entrepreneurs and leaders shaping our community')); ?></p>

			<a href="<?php echo esc_url(get_permalink(get_page_by_path('submit-interview'))); ?>" class="lbi-cta-button">
				Request to be Featured
			</a>
		</div>
	</section>

	<!-- Featured Interviews Section -->
	<section class="lbi-content-section">
		<div class="lbi-content-wrapper">
			<h2 class="lbi-section-title">Featured Interviews</h2>

			<?php
			$interviews = new WP_Query([
				'post_type'      => 'interview',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			]);

			if ($interviews->have_posts()) {
				echo '<div class="lbi-grid">';

				while ($interviews->have_posts()) {
					$interviews->the_post();
					$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
					$category = '';

					$terms = get_the_terms(get_the_ID(), 'business_category');
					if (!is_wp_error($terms) && !empty($terms)) {
						$category = $terms[0]->name;
					}

					?>
					<article class="lbi-card">
						<?php if ($featured_image) { ?>
							<img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>" class="lbi-card-image" />
						<?php } else { ?>
							<div class="lbi-card-image" style="background: linear-gradient(135deg, #bfa673, #8b7355); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">📰</div>
						<?php } ?>

						<div class="lbi-card-body">
							<?php if ($category) { ?>
								<span class="lbi-card-category"><?php echo esc_html($category); ?></span>
							<?php } ?>

							<h3 class="lbi-card-title"><?php the_title(); ?></h3>

							<p class="lbi-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>

							<a href="<?php the_permalink(); ?>" class="lbi-card-link">
								Read Full Interview →
							</a>
						</div>
					</article>
					<?php
				}

				echo '</div>';
				wp_reset_postdata();
			} else {
				echo '<div class="lbi-empty-state"><p>No interviews available yet. Check back soon!</p></div>';
			}
			?>
		</div>
	</section>

	<!-- Directory Preview Section -->
	<section class="lbi-content-section" style="background-color: white;">
		<div class="lbi-content-wrapper">
			<h2 class="lbi-section-title">Business Directory</h2>

			<?php
			$directory = new WP_Query([
				'post_type'      => 'directory',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			]);

			if ($directory->have_posts()) {
				echo '<div class="lbi-grid">';

				while ($directory->have_posts()) {
					$directory->the_post();
					$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
					$category = '';
					$city = '';

					$category_terms = get_the_terms(get_the_ID(), 'business_category');
					if (!is_wp_error($category_terms) && !empty($category_terms)) {
						$category = $category_terms[0]->name;
					}

					$city_terms = get_the_terms(get_the_ID(), 'service_city');
					if (!is_wp_error($city_terms) && !empty($city_terms)) {
						$city = $city_terms[0]->name;
					}

					?>
					<article class="lbi-card">
						<?php if ($featured_image) { ?>
							<img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>" class="lbi-card-image" />
						<?php } else { ?>
							<div class="lbi-card-image" style="background: linear-gradient(135deg, #bfa673, #8b7355); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">🏢</div>
						<?php } ?>

						<div class="lbi-card-body">
							<?php if ($category) { ?>
								<span class="lbi-card-category"><?php echo esc_html($category); ?></span>
							<?php } ?>

							<h3 class="lbi-card-title"><?php the_title(); ?></h3>

							<?php if ($city) { ?>
								<p style="font-size: 0.9rem; color: #999; margin: 0.5rem 0;">📍 <?php echo esc_html($city); ?></p>
							<?php } ?>

							<p class="lbi-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>

							<a href="<?php the_permalink(); ?>" class="lbi-card-link">
								View Details →
							</a>
						</div>
					</article>
					<?php
				}

				echo '</div>';
				wp_reset_postdata();
			} else {
				echo '<div class="lbi-empty-state"><p>No businesses in the directory yet. <a href="' . esc_url(get_permalink(get_page_by_path('submit-interview'))) . '" style="color: #bfa673;">Submit your business</a> to be featured!</p></div>';
			}
			?>
		</div>
	</section>

	<?php
	return ob_get_clean();
}

// Register shortcode on init hook
function lbi_register_home_shortcode() {
	add_shortcode('lbi_home_page', 'lbi_home_page_shortcode');
}
add_action('init', 'lbi_register_home_shortcode', 5);

// Intercept homepage and output custom content
function lbi_homepage_template_redirect() {
	if (is_front_page() && !is_admin()) {
		get_header();
		echo lbi_home_page_shortcode();
		get_footer();
		exit;
	}
}
add_action('template_redirect', 'lbi_homepage_template_redirect', 5);
