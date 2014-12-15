<?php
/*  Clean up wp_head()
   -------------------------------------------------------------------------- */

    function head_cleanup() {
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'index_rel_link' );
        remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head');
    }
    add_action('init', 'head_cleanup');


/*  Clean up output of stylesheet links
    (Removes IDs and non-meaningful media attributes)
   -------------------------------------------------------------------------- */

    function clean_style_tag($input) {
        preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
        $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
        return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
    }
    add_filter('style_loader_tag', 'clean_style_tag');


/*   Limit number of post revisions kept
    --------------------------------------------------------------------------  */

    function custom_revisions_number($num, $post) {
        $num = 12;
        return $num;
    }
    add_filter('wp_revisions_to_keep', 'custom_revisions_number', 10, 2);
?>