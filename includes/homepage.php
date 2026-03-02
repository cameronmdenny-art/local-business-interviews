<?php
/**
 * Homepage content for Local Business Interviews
 * Complete self-contained homepage with all CSS and markup
 * 
 * @package LocalBusinessInterviews
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Output complete homepage on front page
 */
function lbi_output_homepage() {
	// Only on front page, not in admin, not for AJAX
	if (!is_front_page() || is_admin() || defined('DOING_AJAX')) {
		return;
	}

	// Start output buffering to prevent any issues
	ob_end_clean();
	
	?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title(); ?></title>
	<?php wp_head(); ?>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		:root {
			--lbi-gold: #bfa673;
			--lbi-dark: #1a1a1a;
			--lbi-light: #f5f5f5;
			--lbi-text: #333;
		}

		body { 
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
			color: var(--lbi-text);
			line-height: 1.6;
		}

		.lbi-header {
			background: white;
			padding: 1.5rem 2rem;
			border-bottom: 1px solid #e0e0e0;
			position: sticky;
			top: 0;
			z-index: 100;
		}

		.lbi-header-inner {
			max-width: 1200px;
			margin: 0 auto;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.lbi-header-logo {
			font-size: 1.5rem;
			font-weight: 700;
			color: var(--lbi-dark);
			text-decoration: none;
		}

		.lbi-header-nav {
			display: flex;
			gap: 2rem;
			list-style: none;
		}

		.lbi-header-nav a {
			color: var(--lbi-text);
			text-decoration: none;
			font-weight: 500;
			transition: color 0.3s;
		}

		.lbi-header-nav a:hover {
			color: var(--lbi-gold);
		}

		.lbi-hero {
			position: relative;
			min-height: 600px;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(42, 42, 42, 0.85) 100%), 
						url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(191,166,115,.1)" stroke-width="1"/></pattern></defs><rect width="1200" height="600" fill="%231a1a1a"/><rect width="1200" height="600" fill="url(%23grid)"/></svg>');
			background-size: cover, auto;
			background-position: center, 0 0;
			overflow: hidden;
		}

		.lbi-hero-content {
			position: relative;
			z-index: 2;
			max-width: 800px;
			padding: 2rem;
			animation: fadeInUp 0.8s ease-out;
		}

		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.lbi-logo {
			max-width: 100px;
			height: auto;
			margin: 0 auto 1.5rem;
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
			box-shadow: 0 4px 15px rgba(191, 166, 115, 0.3);
		}

		.lbi-cta-button:hover {
			background-color: transparent;
			color: var(--lbi-gold);
			box-shadow: 0 6px 20px rgba(191, 166, 115, 0.4);
			transform: translateY(-2px);
		}

		.lbi-content-section {
			padding: 5rem 2rem;
			background-color: var(--lbi-light);
		}

		.lbi-content-section.white {
			background-color: white;
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

		.lbi-section-title::after {
			content: '';
			display: block;
            width: 60px;
            height: 4px;
            background-color: var(--lbi-gold);
            margin: 1rem auto 0;
            border-radius: 2px;
		}

		.lbi-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 2rem;
			margin-bottom: 0;
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
			display: block;
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
			letter-spacing: 0.5px;
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
				padding: 3rem 1rem;
			}

			.lbi-grid {
				grid-template-columns: 1fr;
			}

			.lbi-header-nav {
				gap: 1rem;
				font-size: 0.9rem;
			}
		}
	</style>
</head>
<body <?php body_class('lbi-homepage'); ?>>
	<?php wp_body_open(); ?>

	<!-- Header -->
	<header class="lbi-header">
		<div class="lbi-header-inner">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="lbi-header-logo">
				<?php bloginfo('name'); ?>
			</a>
			<nav>
				<ul class="lbi-header-nav">
					<li><a href="<?php echo esc_url(home_url('/directory/')); ?>">Directory</a></li>
					<li><a href="<?php echo esc_url(home_url('/submit-interview/')); ?>">Submit Interview</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<main id="primary" class="site-main">
		<!-- Hero Section -->
		<section class="lbi-hero">
			<div class="lbi-hero-content">
				<h1><?php echo wp_kses_post(get_option('lbi_hero_title', 'Discover Local Business Stories')); ?></h1>
				<p><?php echo wp_kses_post(get_option('lbi_hero_subtitle', 'Meet the entrepreneurs and leaders shaping our community')); ?></p>
				<a href="<?php echo esc_url(home_url('/submit-interview/')); ?>" class="lbi-cta-button">
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
								<div class="lbi-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #bfa673, #8b7355); color: white; font-size: 3rem;">📰</div>
							<?php } ?>
							<div class="lbi-card-body">
								<?php if ($category) { ?>
									<span class="lbi-card-category"><?php echo esc_html($category); ?></span>
								<?php } ?>
								<h3 class="lbi-card-title"><?php the_title(); ?></h3>
								<p class="lbi-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
								<a href="<?php the_permalink(); ?>" class="lbi-card-link">Read Full Interview →</a>
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

		<!-- Directory Section -->
		<section class="lbi-content-section white">
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
								<div class="lbi-card-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #bfa673, #8b7355); color: white; font-size: 3rem;">🏢</div>
							<?php } ?>
							<div class="lbi-card-body">
								<?php if ($category) { ?>
									<span class="lbi-card-category"><?php echo esc_html($category); ?></span>
								<?php } ?>
								<h3 class="lbi-card-title"><?php the_title(); ?></h3>
								<?php if ($city) { ?>
									<p style="font-size: 0.9rem; color: #999; margin: 0.5rem 0; font-weight: 500;">📍 <?php echo esc_html($city); ?></p>
								<?php } ?>
								<p class="lbi-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
								<a href="<?php the_permalink(); ?>" class="lbi-card-link">View Details →</a>
							</div>
						</article>
						<?php
					}
					echo '</div>';
					wp_reset_postdata();
				} else {
					echo '<div class="lbi-empty-state"><p>No businesses in the directory yet. <a href="' . esc_url(home_url('/submit-interview/')) . '" style="color: var(--lbi-gold); text-decoration: none;">Submit your business</a> to be featured!</p></div>';
				}
				?>
			</div>
		</section>
	</main>

	<?php wp_footer(); ?>
</body>
</html><?php
	exit; // Ensure nothing else outputs
}

// Hook early before WordPress renders anything
add_action('wp', 'lbi_output_homepage', 1);

