<?php
/**
 * Demo Data Generator for Local Business Interviews
 * Run once to populate sample content, then delete this file
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LBI_Demo_Data {
    public static function generate() {
        // Sample interviews data
        $interviews = array(
            array(
                'title'       => 'Sarah Chen - Founder of Artisan Coffee Co.',
                'excerpt'     => 'How a passion for specialty coffee turned into a thriving local business.',
                'content'     => 'Sarah Chen started Artisan Coffee Co. five years ago with a simple mission: to bring specialty-grade coffee to the local community. What began as a weekend farmers market stand has grown into a beloved neighborhood institution.',
                'meta'        => array(
                    'interviewee_name'  => 'Sarah Chen',
                    'interviewee_title' => 'Founder & Owner',
                    'company_name'      => 'Artisan Coffee Co.',
                    'company_website'   => 'https://example.com',
                    'email'             => 'sarah@example.com',
                    'phone'             => '555-0101',
                    'founded_year'      => '2019',
                ),
                'category'    => 'Food & Beverage',
            ),
            array(
                'title'       => 'Marcus Williams - Digital Marketing Expert',
                'excerpt'     => 'Building a boutique marketing agency that prioritizes client success.',
                'content'     => 'Marcus Williams founded Digital Dreams Marketing to help small businesses compete in the digital marketplace. With over 10 years of experience, he believes every business deserves expert marketing support.',
                'meta'        => array(
                    'interviewee_name'  => 'Marcus Williams',
                    'interviewee_title' => 'CEO',
                    'company_name'      => 'Digital Dreams Marketing',
                    'company_website'   => 'https://example.com',
                    'email'             => 'marcus@example.com',
                    'phone'             => '555-0102',
                    'founded_year'      => '2020',
                ),
                'category'    => 'Marketing & Tech',
            ),
            array(
                'title'       => 'Elena Rodriguez - Sustainable Fashion Pioneer',
                'excerpt'     => 'Creating eco-friendly fashion without sacrificing style or quality.',
                'content'     => 'Elena Rodriguez launched EcoStyle Boutique with a vision to prove that sustainable fashion could be both beautiful and accessible. Her handcrafted pieces use only ethically sourced materials.',
                'meta'        => array(
                    'interviewee_name'  => 'Elena Rodriguez',
                    'interviewee_title' => 'Designer & Owner',
                    'company_name'      => 'EcoStyle Boutique',
                    'company_website'   => 'https://example.com',
                    'email'             => 'elena@example.com',
                    'phone'             => '555-0103',
                    'founded_year'      => '2018',
                ),
                'category'    => 'Fashion & Retail',
            ),
            array(
                'title'       => 'James Park - Local Fitness Innovation',
                'excerpt'     => 'How personalized training is transforming the fitness industry.',
                'content'     => 'James Park opened Momentum Fitness with a commitment to making personalized training affordable and accessible. He believes fitness should be empowering, not intimidating.',
                'meta'        => array(
                    'interviewee_name'  => 'James Park',
                    'interviewee_title' => 'Founder & Head Trainer',
                    'company_name'      => 'Momentum Fitness',
                    'company_website'   => 'https://example.com',
                    'email'             => 'james@example.com',
                    'phone'             => '555-0104',
                    'founded_year'      => '2017',
                ),
                'category'    => 'Health & Wellness',
            ),
            array(
                'title'       => 'Aisha Patel - Community Education Innovator',
                'excerpt'     => 'Bridging the gap between education and opportunity for underserved communities.',
                'content'     => 'Aisha Patel founded Learn Together Academy to provide free coding and tech skills training to students from underrepresented backgrounds. Her programs have impacted over 500 young people.',
                'meta'        => array(
                    'interviewee_name'  => 'Aisha Patel',
                    'interviewee_title' => 'Founder & Executive Director',
                    'company_name'      => 'Learn Together Academy',
                    'company_website'   => 'https://example.com',
                    'email'             => 'aisha@example.com',
                    'phone'             => '555-0105',
                    'founded_year'      => '2019',
                ),
                'category'    => 'Education & Tech',
            ),
            array(
                'title'       => 'David Morrison - Artisanal Furniture Master',
                'excerpt'     => 'Handcrafted furniture that tells a story of quality and craftsmanship.',
                'content'     => 'David Morrison\'s carpentry studio creates bespoke furniture pieces using traditional techniques and sustainable materials. Each piece is a unique work of art.',
                'meta'        => array(
                    'interviewee_name'  => 'David Morrison',
                    'interviewee_title' => 'Master Craftsman & Owner',
                    'company_name'      => 'Morrison Woodworks Studio',
                    'company_website'   => 'https://example.com',
                    'email'             => 'david@example.com',
                    'phone'             => '555-0106',
                    'founded_year'      => '2015',
                ),
                'category'    => 'Design & Craft',
            ),
        );

        // Sample directory listings
        $directories = array(
            array(
                'title'       => 'Artisan Coffee Co.',
                'excerpt'     => 'Specialty coffee and pastries in a cozy neighborhood setting.',
                'content'     => 'Specializing in single-origin specialty coffee, freshly baked pastries, and a welcoming atmosphere for the community.',
                'meta'        => array(
                    'business_name'    => 'Artisan Coffee Co.',
                    'website_url'      => 'https://example.com',
                    'email'            => 'hello@example.com',
                    'phone'            => '555-0201',
                    'address'          => '123 Main Street',
                    'featured'         => '1',
                ),
                'category'    => 'Food & Beverage',
                'city'        => 'Downtown',
            ),
            array(
                'title'       => 'Digital Dreams Marketing',
                'excerpt'     => 'Expert digital marketing strategies for small business growth.',
                'content'     => 'Full-service digital marketing agency specializing in SEO, social media, content, and paid advertising for local businesses.',
                'meta'        => array(
                    'business_name'    => 'Digital Dreams Marketing',
                    'website_url'      => 'https://example.com',
                    'email'            => 'contact@example.com',
                    'phone'            => '555-0202',
                    'address'          => '456 Tech Avenue',
                    'featured'         => '1',
                ),
                'category'    => 'Marketing & Tech',
                'city'        => 'Midtown',
            ),
            array(
                'title'       => 'EcoStyle Boutique',
                'excerpt'     => 'Sustainable fashion with style and purpose.',
                'content'     => 'Handcrafted, eco-friendly fashion pieces using ethically sourced materials. Supporting sustainable fashion since 2018.',
                'meta'        => array(
                    'business_name'    => 'EcoStyle Boutique',
                    'website_url'      => 'https://example.com',
                    'email'            => 'shop@example.com',
                    'phone'            => '555-0203',
                    'address'          => '789 Fashion Lane',
                    'featured'         => '1',
                ),
                'category'    => 'Fashion & Retail',
                'city'        => 'Arts District',
            ),
            array(
                'title'       => 'Momentum Fitness',
                'excerpt'     => 'Personalized training in an empowering environment.',
                'content'     => 'State-of-the-art fitness facility offering personal training, group classes, and nutrition coaching in a supportive community.',
                'meta'        => array(
                    'business_name'    => 'Momentum Fitness',
                    'website_url'      => 'https://example.com',
                    'email'            => 'join@example.com',
                    'phone'            => '555-0204',
                    'address'          => '321 Health Plaza',
                    'featured'         => '1',
                ),
                'category'    => 'Health & Wellness',
                'city'        => 'Fitness Hub',
            ),
            array(
                'title'       => 'Learn Together Academy',
                'excerpt'     => 'Free coding and tech skills training for underrepresented communities.',
                'content'     => 'Nonprofit providing rigorous, free coding bootcamps and mentorship to increase diversity in tech. Apply now for the next cohort!',
                'meta'        => array(
                    'business_name'    => 'Learn Together Academy',
                    'website_url'      => 'https://example.com',
                    'email'            => 'admissions@example.com',
                    'phone'            => '555-0205',
                    'address'          => '555 Education Blvd',
                    'featured'         => '1',
                ),
                'category'    => 'Education & Tech',
                'city'        => 'Innovation Hub',
            ),
            array(
                'title'       => 'Morrison Woodworks Studio',
                'excerpt'     => 'Handcrafted furniture with artistry and integrity.',
                'content'     => 'Custom furniture studio creating bespoke pieces using traditional techniques and sustainably sourced hardwoods.',
                'meta'        => array(
                    'business_name'    => 'Morrison Woodworks Studio',
                    'website_url'      => 'https://example.com',
                    'email'            => 'inquire@example.com',
                    'phone'            => '555-0206',
                    'address'          => '888 Craft Street',
                    'featured'         => '1',
                ),
                'category'    => 'Design & Craft',
                'city'        => 'Arts District',
            ),
        );

        // Get or create taxonomy terms
        $categories = array(
            'Food & Beverage',
            'Marketing & Tech',
            'Fashion & Retail',
            'Health & Wellness',
            'Education & Tech',
            'Design & Craft',
        );

        foreach ( $categories as $cat ) {
            if ( ! term_exists( $cat, 'business_category' ) ) {
                wp_insert_term( $cat, 'business_category' );
            }
        }

        if ( ! term_exists( 'Downtown', 'service_city' ) ) {
            wp_insert_term( 'Downtown', 'service_city' );
        }
        if ( ! term_exists( 'Midtown', 'service_city' ) ) {
            wp_insert_term( 'Midtown', 'service_city' );
        }
        if ( ! term_exists( 'Arts District', 'service_city' ) ) {
            wp_insert_term( 'Arts District', 'service_city' );
        }

        // Create interviews
        foreach ( $interviews as $interview ) {
            $post_id = wp_insert_post( array(
                'post_type'    => 'interview',
                'post_title'   => $interview['title'],
                'post_excerpt' => $interview['excerpt'],
                'post_content' => $interview['content'],
                'post_status'  => 'publish',
            ) );

            if ( $post_id ) {
                // Add meta
                foreach ( $interview['meta'] as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }

                // Add category
                $term = get_term_by( 'name', $interview['category'], 'business_category' );
                if ( $term ) {
                    wp_set_post_terms( $post_id, $term->term_id, 'business_category' );
                }
            }
        }

        // Create directory listings
        foreach ( $directories as $directory ) {
            $post_id = wp_insert_post( array(
                'post_type'    => 'directory',
                'post_title'   => $directory['title'],
                'post_excerpt' => $directory['excerpt'],
                'post_content' => $directory['content'],
                'post_status'  => 'publish',
            ) );

            if ( $post_id ) {
                // Add meta
                foreach ( $directory['meta'] as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }

                // Add category
                $term = get_term_by( 'name', $directory['category'], 'business_category' );
                if ( $term ) {
                    wp_set_post_terms( $post_id, $term->term_id, 'business_category' );
                }

                // Add city
                $city_term = get_term_by( 'name', $directory['city'], 'service_city' );
                if ( $city_term ) {
                    wp_set_post_terms( $post_id, $city_term->term_id, 'service_city', true );
                }
            }
        }
    }
}

// Run the generator
if ( is_admin() && current_user_can( 'manage_options' ) ) {
    LBI_Demo_Data::generate();
    wp_die( 'Demo data generated successfully! You can now delete this file.' );
}
