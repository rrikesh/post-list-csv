<?php
/**
 * Plugin Name: Post List CSV
 * Version: 1.0
 * Description: Uses WP-CLI to generate a CSV with the posts and pages from a WordPress website. Call using <code>wp post-list-csv</code>
 * Author: Rikesh Ramlochund
 * Author URI: https://rrikesh.com
 */


if ( class_exists( 'WP_CLI' ) ) {
    /**
     * Export the CSV
     *
     */
    function rr_post_list_csv( $args, $assoc_args ) {
        $site_name = get_option('blogname');
        $args = [
            'post_type' =>['post', 'page'],
            'posts_per_page' => -1
        ];
        $csv_data = [];
        $queried_posts = new WP_Query( $args );

        WP_CLI::log('Fetching posts...');
        while( $queried_posts->have_posts() ):
            $queried_posts->the_post();

            $csv_line = [
                 'Post ID' => get_the_ID(),
                 'Title' => get_the_title(),
                 'Permalink' => get_the_permalink()
            ];
            array_push( $csv_data, $csv_line );
        endwhile;

        # Write to CSV
        $file_pointer = fopen( $site_name . '.csv', 'w');
        WP_CLI\Utils\write_csv( $file_pointer, $csv_data, ['Post ID', 'Title', 'Permalink'] );
        fclose( $file_pointer);

        WP_CLI::success('The CSV file has been generated');
    };

    WP_CLI::add_command( 'post-list-csv', 'rr_post_list_csv' );

  }
