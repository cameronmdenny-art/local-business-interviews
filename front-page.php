<?php
/**
 * Front Page Template for Local Business Interviews Plugin
 * Professional homepage with hero section, featured content, and CTAs
 */

// Ensure helpers are loaded
if ( ! class_exists( 'LBI_Helpers' ) ) {
	$helpers_path = plugin_dir_path( __FILE__ ) . 'includes/helpers.php';
	if ( file_exists( $helpers_path ) ) {
		require_once $helpers_path;
	}
}

get_header();
?>

<style>
	/* Enhanced Homepage Styles */
	:root {
		--lbi-gold: #bfa673;
		--lbi-dark: #1a1a1a;
		--lbi-light: #f5f5f5;
		--lbi-text: #333;
		--lbi-light-text: #666;
		--transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
	}

	.lbi-homepage {
		overflow: hidden;
	}

	/* Hero Section */
	.lbi-hero {
		position: relative;
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(135deg, var(--lbi-dark) 0%, #2a2a2a 100%);
		color: white;
		overflow: hidden;
	}

	.lbi-hero::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-image: 
			radial-gradient(circle at 20% 50%, rgba(191, 166, 115, 0.1) 0%, transparent 50%),
			radial-gradient(circle at 80% 80%, rgba(191, 166, 115, 0.05) 0%, transparent 50%);
		pointer-events: none;
	}

	.lbi-hero-content {
		position: relative;
		z-index: 1;
		max-width: 800px;
		text-align: center;
		padding: 2rem;
		animation: fadeInUp 0.8s ease-out 0.2s both;
	}

	.lbi-hero h1 {
		font-size: clamp(2.5rem, 8vw, 4rem);
		line-height: 1.1;
		margin-bottom: 1.5rem;
		font-weight: 700;
		letter-spacing: -1px;
	}

	.lbi-hero p {
		font-size: clamp(1rem, 3vw, 1.3rem);
		color: rgba(255, 255, 255, 0.9);
		margin-bottom: 2.5rem;
		line-height: 1.6;
		font-weight: 300;
	}

	.lbi-hero-buttons {
		display: flex;
		gap: 1rem;
		justify-content: center;
		flex-wrap: wrap;
		animation: fadeInUp 0.8s ease-out 0.4s both;
	}

	.lbi-btn {
		display: inline-block;
		padding: 14px 32px;
		border-radius: 6px;
		font-weight: 600;
		text-decoration: none;
		transition: var(--transition);
		border: 2px solid transparent;
		cursor: pointer;
		font-size: 1rem;
		text-align: center;
		letter-spacing: 0.5px;
	}

	.lbi-btn-primary {
		background-color: var(--lbi-gold);
		color: white;
		box-shadow: 0 4px 15px rgba(191, 166, 115, 0.3);
	}

	.lbi-btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(191, 166, 115, 0.4);
		background-color: #a68f5e;
	}

	.lbi-btn-secondary {
		background-color: transparent;
		color: white;
		border-color: white;
	}

	.lbi-btn-secondary:hover {
		background-color: rgba(255, 255, 255, 0.1);
		transform: translateY(-2px);
	}

	/* Sections */
	.lbi-section {
		padding: 80px 20px;
		max-width: 1200px;
		margin: 0 auto;
	}

	.lbi-section-header {
		text-align: center;
		margin-bottom: 60px;
		animation: fadeInUp 0.8s ease-out;
	}

	.lbi-section-header h2 {
		font-size: clamp(2rem, 5vw, 3rem);
		margin-bottom: 1rem;
		color: var(--lbi-dark);
		font-weight: 700;
	}

	.lbi-section-header p {
		font-size: 1.1rem;
		color: var(--lbi-light-text);
		max-width: 600px;
		margin: 0 auto;
		line-height: 1.7;
	}

	/* Featured Grid */
	.lbi-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
		gap: 30px;
		margin-bottom: 40px;
	}

	.lbi-card {
		background: white;
		border-radius: 8px;
		overflow: hidden;
		border: 1px solid #e0e0e0;
		transition: var(--transition);
		animation: fadeInUp 0.6s ease-out;
		animation-fill-mode: both;
	}

	.lbi-card:nth-child(1) { animation-delay: 0.1s; }
	.lbi-card:nth-child(2) { animation-delay: 0.2s; }
	.lbi-card:nth-child(3) { animation-delay: 0.3s; }
	.lbi-card:nth-child(4) { animation-delay: 0.4s; }
	.lbi-card:nth-child(5) { animation-delay: 0.5s; }
	.lbi-card:nth-child(6) { animation-delay: 0.6s; }

	.lbi-card:hover {
		transform: translateY(-10px);
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
		border-color: var(--lbi-gold);
	}

	.lbi-card-image {
		height: 220px;
		background: var(--lbi-light);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 4rem;
		overflow: hidden;
		background-size: cover;
		background-position: center;
		position: relative;
	}

	.lbi-card-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}

	.lbi-card-content {
		padding: 25px;
	}

	.lbi-card-category {
		display: inline-block;
		padding: 6px 12px;
		background-color: var(--lbi-gold);
		color: white;
		border-radius: 20px;
		font-size: 0.85rem;
		font-weight: 600;
		margin-bottom: 12px;
		text-decoration: none;
		transition: var(--transition);
	}

	.lbi-card-category:hover {
		opacity: 0.8;
	}

	.lbi-card-title {
		font-size: 1.4rem;
		margin-bottom: 10px;
		color: var(--lbi-dark);
		font-weight: 700;
		line-height: 1.3;
	}

	.lbi-card-title a {
		color: inherit;
		text-decoration: none;
		transition: color 0.3s;
	}

	.lbi-card-title a:hover {
		color: var(--lbi-gold);
	}

	.lbi-card-excerpt {
		color: var(--lbi-light-text);
		font-size: 0.95rem;
		line-height: 1.6;
		margin-bottom: 16px;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.lbi-card-link {
		display: inline-block;
		color: var(--lbi-gold);
		text-decoration: none;
		font-weight: 600;
		font-size: 0.95rem;
		transition: var(--transition);
		position: relative;
		padding-bottom: 4px;
	}

	.lbi-card-link::after {
		content: '→';
		margin-left: 6px;
		transition: transform 0.3s;
		display: inline-block;
	}

	.lbi-card-link:hover {
		color: var(--lbi-dark);
	}

	.lbi-card-link:hover::after {
		transform: translateX(4px);
	}

	/* Empty State */
	.lbi-empty-state {
		text-align: center;
		padding: 60px 20px;
		color: var(--lbi-light-text);
	}

	.lbi-empty-state p {
		margin-bottom: 20px;
		font-size: 1.1rem;
	}

	/* CTA Section */
	.lbi-cta-section {
		background: var(--lbi-gold);
		color: white;
		text-align: center;
		padding: 80px 20px;
		margin-top: 80px;
	}

	.lbi-cta-content {
		max-width: 600px;
		margin: 0 auto;
		animation: fadeInUp 0.8s ease-out;
	}

	.lbi-cta-content h2 {
		font-size: clamp(1.8rem, 5vw, 2.8rem);
		margin-bottom: 1.5rem;
		font-weight: 700;
	}

	.lbi-cta-content p {
		font-size: 1.1rem;
		margin-bottom: 2rem;
		opacity: 0.95;
	}

	.lbi-cta-btn {
		background-color: white;
		color: var(--lbi-gold);
		padding: 16px 36px;
		border-radius: 6px;
		font-weight: 600;
		text-decoration: none;
		display: inline-block;
		transition: var(--transition);
		border: 2px solid white;
	}

	.lbi-cta-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
	}

	/* Footer */
	.lbi-footer {
		background: var(--lbi-dark);
		color: white;
		text-align: center;
		padding: 40px 20px;
		font-size: 0.95rem;
	}

	.lbi-footer p {
		margin: 0;
		opacity: 0.8;
	}

	/* Animations */
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

	@keyframes slideInLeft {
		from {
			opacity: 0;
			transform: translateX(-30px);
		}
		to {
			opacity: 1;
			transform: translateX(0);
		}
	}

	@keyframes slideInRight {
		from {
			opacity: 0;
			transform: translateX(30px);
		}
		to {
			opacity: 1;
			transform: translateX(0);
		}
	}

	/* Responsive */
	@media (max-width: 768px) {
		.lbi-section {
			padding: 60px 20px;
		}

		.lbi-hero {
			min-height: auto;
			padding: 80px 20px;
		}

		.lbi-hero-buttons {
			flex-direction: column;
		}

		.lbi-btn {
			width: 100%;
		}

		.lbi-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}

		.lbi-section-header {
			margin-bottom: 40px;
		}

		.lbi-card-image {
			height: 180px;
		}
	}

	/* Loading Animation */
	@keyframes pulse {
		0%, 100% {
			opacity: 1;
		}
		50% {
			opacity: 0.5;
		}
	}

	.lbi-loading {
		animation: pulse 1.5s ease-in-out infinite;
	}
</style>

<main id="main" class="site-main lbi-homepage">
	<!-- Hero Section -->
	<section class="lbi-hero" aria-label="Welcome">
		<div class="lbi-hero-content">
			<h1><?php echo esc_html( get_option( 'lbi_hero_title', 'Discover Local Business Stories' ) ); ?></h1>
			<p><?php echo esc_html( get_option( 'lbi_hero_subtitle', 'Meet the entrepreneurs and leaders shaping our community' ) ); ?></p>
			<div class="lbi-hero-buttons">
				<a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="lbi-btn lbi-btn-primary">
					<?php esc_html_e( 'Request to be Featured', 'local-business-interviews' ); ?>
				</a>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'directory' ) ); ?>" class="lbi-btn lbi-btn-secondary">
					<?php esc_html_e( 'Explore Directory', 'local-business-interviews' ); ?>
				</a>
			</div>
		</div>
	</section>

	<!-- Featured Interviews Section -->
	<section class="lbi-section" aria-labelledby="interviews-title">
		<div class="lbi-section-header">
			<h2 id="interviews-title"><?php esc_html_e( 'Featured Interviews', 'local-business-interviews' ); ?></h2>
			<p><?php esc_html_e( 'Inspiring stories from local business leaders and entrepreneurs', 'local-business-interviews' ); ?></p>
		</div>

		<?php
		if ( class_exists( 'LBI_Helpers' ) && method_exists( 'LBI_Helpers', 'get_featured_interviews' ) ) {
			$interviews = LBI_Helpers::get_featured_interviews( 6 );
		} else {
			$interviews = new WP_Query( array(
				'post_type'      => 'interview',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );
		}
		
		if ( $interviews->have_posts() ) :
		?>
			<div class="lbi-grid">
				<?php
				while ( $interviews->have_posts() ) :
					$interviews->the_post();
					$post_id = get_the_ID();
					$company_name = get_post_meta( $post_id, 'company_name', true );
					$category = get_the_terms( $post_id, 'business_category' );
				?>
					<article class="lbi-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="lbi-card-image">
								<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
							</div>
						<?php else : ?>
							<div class="lbi-card-image">📰</div>
						<?php endif; ?>
						
						<div class="lbi-card-content">
							<?php if ( ! empty( $category ) && ! is_wp_error( $category ) ) : ?>
								<a href="<?php echo esc_url( get_term_link( $category[0] ) ); ?>" class="lbi-card-category">
									<?php echo esc_html( $category[0]->name ); ?>
								</a>
							<?php endif; ?>
							
							<h3 class="lbi-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							
							<?php if ( ! empty( $company_name ) ) : ?>
								<p style="font-size: 0.9rem; color: var(--lbi-light-text); margin-bottom: 12px;">
									<strong><?php echo esc_html( $company_name ); ?></strong>
								</p>
							<?php endif; ?>
							
							<p class="lbi-card-excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ); ?>
							</p>
							
							<a href="<?php the_permalink(); ?>" class="lbi-card-link">
								<?php esc_html_e( 'Read Full Interview', 'local-business-interviews' ); ?>
							</a>
						</div>
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<div style="text-align: center; margin-top: 40px;">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'interview' ) ); ?>" class="lbi-btn lbi-btn-primary">
					<?php esc_html_e( 'View All Interviews', 'local-business-interviews' ); ?>
				</a>
			</div>
		<?php
		else :
		?>
			<div class="lbi-empty-state">
				<p><?php esc_html_e( 'No interviews yet. Be the first to share your story!', 'local-business-interviews' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="lbi-btn lbi-btn-primary">
					<?php esc_html_e( 'Submit Your Interview', 'local-business-interviews' ); ?>
				</a>
			</div>
		<?php
		endif;
		?>
	</section>

	<!-- Business Directory Section -->
	<section class="lbi-section" style="background-color: var(--lbi-light);" aria-labelledby="directory-title">
		<div class="lbi-section-header">
			<h2 id="directory-title"><?php esc_html_e( 'Local Business Directory', 'local-business-interviews' ); ?></h2>
			<p><?php esc_html_e( 'Discover and support local businesses in your community', 'local-business-interviews' ); ?></p>
		</div>

		<?php
		if ( class_exists( 'LBI_Helpers' ) && method_exists( 'LBI_Helpers', 'get_featured_directory' ) ) {
			$directory = LBI_Helpers::get_featured_directory( 6 );
		} else {
			$directory = new WP_Query( array(
				'post_type'      => 'directory',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => 'featured',
						'value'   => '1',
						'compare' => '=',
					),
				),
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );
		}
		
		if ( $directory->have_posts() ) :
		?>
			<div class="lbi-grid">
				<?php
				while ( $directory->have_posts() ) :
					$directory->the_post();
					$post_id = get_the_ID();
					$featured = get_post_meta( $post_id, 'featured', true );
					$category = get_the_terms( $post_id, 'business_category' );
					$city = get_the_terms( $post_id, 'service_city' );
				?>
					<article class="lbi-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="lbi-card-image">
								<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
							</div>
						<?php else : ?>
							<div class="lbi-card-image">🏢</div>
						<?php endif; ?>
						
						<div class="lbi-card-content">
							<?php if ( ! empty( $featured ) ) : ?>
								<div style="display: inline-block; padding: 6px 12px; background-color: #27ae60; color: white; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-bottom: 12px;">
									<?php esc_html_e( 'Featured', 'local-business-interviews' ); ?>
								</div>
							<?php endif; ?>
							
							<?php if ( ! empty( $category ) && ! is_wp_error( $category ) ) : ?>
								<a href="<?php echo esc_url( get_term_link( $category[0] ) ); ?>" class="lbi-card-category">
									<?php echo esc_html( $category[0]->name ); ?>
								</a>
							<?php endif; ?>
							
							<h3 class="lbi-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							
							<?php if ( ! empty( $city ) && ! is_wp_error( $city ) ) : ?>
								<p style="font-size: 0.9rem; color: var(--lbi-light-text); margin-bottom: 12px;">
									📍 <?php echo esc_html( $city[0]->name ); ?>
								</p>
							<?php endif; ?>
							
							<p class="lbi-card-excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ); ?>
							</p>
							
							<a href="<?php the_permalink(); ?>" class="lbi-card-link">
								<?php esc_html_e( 'View Details', 'local-business-interviews' ); ?>
							</a>
						</div>
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<div style="text-align: center; margin-top: 40px;">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'directory' ) ); ?>" class="lbi-btn lbi-btn-primary">
					<?php esc_html_e( 'Browse Full Directory', 'local-business-interviews' ); ?>
				</a>
			</div>
		<?php
		else :
		?>
			<div class="lbi-empty-state">
				<p><?php esc_html_e( 'No businesses yet. Submit your business to join our community!', 'local-business-interviews' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/submit-directory/' ) ); ?>" class="lbi-btn lbi-btn-primary">
					<?php esc_html_e( 'Add Your Business', 'local-business-interviews' ); ?>
				</a>
			</div>
		<?php
		endif;
		?>
	</section>

	<!-- CTA Section -->
	<section class="lbi-cta-section">
		<div class="lbi-cta-content">
			<h2><?php esc_html_e( 'Ready to Share Your Story?', 'local-business-interviews' ); ?></h2>
			<p><?php esc_html_e( 'Join our community of local business leaders and get featured today', 'local-business-interviews' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/submit-interview/' ) ); ?>" class="lbi-cta-btn">
				<?php esc_html_e( 'Submit Your Interview', 'local-business-interviews' ); ?>
			</a>
		</div>
	</section>
</main>

<?php get_footer(); ?>
