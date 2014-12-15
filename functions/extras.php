<?php
/*   Custom Nav Walker
    --------------------------------------------------------------------------  */

    class Vital_Nav_Walker extends Walker_Nav_Menu {

        function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

            $default_classes = empty ( $item->classes ) ? array () : (array) $item->classes;

            $custom_classes = (array)get_post_meta( $item->ID, '_menu_item_classes', true );

            // Global class for all items
            $custom_classes[] = 'menu-item';

            // Is this a top-level menu item?
            if ($depth == 0)
                $custom_classes[] = 'menu-item-top-level';

            // Does this menu item have children?
            if (in_array('menu-item-has-children', $default_classes))
                $custom_classes[] = 'menu-item-has-children';

            // Is this menu item active? (Top level only)
            $active_classes = array('current-menu-item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_item', 'current-page-parent', 'current-page-ancestor');
            if ($depth == 0 && array_intersect($default_classes, $active_classes))
                $custom_classes[] = 'menu-item-active';

            // Give menu item a class based on its level/depth
            $level = $depth + 1;
            if ($depth > 0)
                $custom_classes[] = "menu-item-level-$level";

            $classes = join(' ', $custom_classes);

            ! empty ( $classes )
                and $classes = ' class="'. trim(esc_attr( $classes )) . '"';

            $output .= "<li $classes>";

            $attributes  = '';

            ! empty( $item->attr_title )
                and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
            ! empty( $item->target )
                and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
            ! empty( $item->xfn )
                and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
            ! empty( $item->url )
                and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

            $title = apply_filters( 'the_title', $item->title, $item->ID );

            $item_output = $args->before
                . "<a $attributes>"
                . $args->link_before
                . $title
                . '</a> '
                . $args->link_after
                . $description
                . $args->after;

            $output .= apply_filters(
                'walker_nav_menu_start_el'
            ,   $item_output
            ,   $item
            ,   $depth
            ,   $args
            );
        }
    }


/*   Custom Comment Template
    --------------------------------------------------------------------------  */

    function vital_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment; ?>
        <?php if ( $comment->comment_approved == '1' ): ?>
        <li>
            <div id="comment-<?php comment_ID() ?>" class="comment">
                <div class="comment-meta">
                    <?php echo get_avatar( $comment, 96, '', get_comment_author() ); ?>
                    <h4 class="comment-author"><?php comment_author_link() ?></h4>
                    <time class="comment-time"><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
                    <?php edit_comment_link(__('Edit Comment')); ?>
                </div>
                <div class="comment-text">
                    <?php comment_text() ?>
                    <?php comment_reply_link( array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
                </div>
            </div>
        <?php endif;
    }


/*   Custom "Reply" link text on comments
    --------------------------------------------------------------------------  */

    function vital_comment_reply_link($link, $args, $comment){
        $comment = get_comment( $comment );
        // If no comment author is blank, use 'Anonymous'
        if ( empty($comment->comment_author) ) {
            if (!empty($comment->user_id)){
                $user=get_userdata($comment->user_id);
                $author=$user->user_login;
            } else {
                $author = __('Anonymous');
            }
        } else {
            $author = $comment->comment_author;
        }
        // If the user provided more than a first name, use only first name
        if(strpos($author, ' ')){
            $author = substr($author, 0, strpos($author, ' '));
        }
        // Replace Reply Link with "Reply to &lt;Author First Name>"
        $reply_link_text = $args['reply_text'];
        $link = str_replace($reply_link_text, 'Reply to ' . $author, $link);

        return $link;
    }
    add_filter('comment_reply_link', 'vital_comment_reply_link', 10, 3);


/*   SMART EXCERPT
     http://www.distractedbysquirrels.com/blog/wordpress-improved-dynamic-excerpt

     Returns an excerpt which is not longer than the given length and always
     ends with a complete sentence. If first sentence is longer than length,
     it will add the standard ellipsis… Length is the number of characters.

     Usage: <?php smart_excerpt(450); >
    --------------------------------------------------------------------------  */

    function smart_excerpt($length) {
        global $post;
        $text = $post->post_excerpt;
        if ( '' == $text ) {
            $text = get_the_content('');
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]>', $text);
        }
        $text = strip_shortcodes($text);
        $text = strip_tags($text); // use ' $text = strip_tags($text,'<p><a>'); ' if you need to keep some tags
        if ( empty($length) ) {
            $length = 300;
        }
        $text = substr($text,0,$length);
        $excerpt = reverse_strrchr($text, '.', 1);
        if( $excerpt ) {
            echo apply_filters('the_excerpt',$excerpt);
        } else {
            echo apply_filters('the_excerpt',$text . '…');
        }
    }
    function reverse_strrchr($haystack, $needle, $trail) {
        return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
    }


/*  NICE SEARCH
    http://txfx.net/wordpress-plugins/nice-search

    Redirects search results from /?s=query to /search/query/
    and converts %20 to +
   -------------------------------------------------------------------------- */

    function cws_nice_search_redirect() {
        global $wp_rewrite;
        if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
            return;

        $search_base = $wp_rewrite->search_base;
        if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
            wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
            exit();
        }
    }
    add_action( 'template_redirect', 'cws_nice_search_redirect' );

    function nice_search_redirect() {
        global $wp_rewrite;
        if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
            return;
        }

        $search_base = $wp_rewrite->search_base;
        if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
            wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
            exit();
        }
    }
    add_action('template_redirect', 'nice_search_redirect');


/*   ADD CUSTOM CLASSES FIELD TO WIDGETS
     http://kucrut.org/add-custom-classes-to-any-widget
    --------------------------------------------------------------------------  */

    function kc_widget_form_extend( $instance, $widget ) {
        if ( !isset($instance['classes']) )
            $instance['classes'] = null;
        $row = "<p>\n";
        $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Additional Classes <small>(separate with spaces)</small></label>\n";
        $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\n";
        $row .= "</p>\n";

        echo $row;
        return $instance;
    }
    add_filter( 'widget_form_callback', 'kc_widget_form_extend', 10, 2 );

    function kc_widget_update( $instance, $new_instance ) {
        $instance['classes'] = $new_instance['classes'];
        return $instance;
    }
    add_filter( 'widget_update_callback', 'kc_widget_update', 10, 2 );

    function kc_dynamic_sidebar_params( $params ) {
        global $wp_registered_widgets;
        $widget_id  = $params[0]['widget_id'];
        $widget_obj = $wp_registered_widgets[$widget_id];
        $widget_opt = get_option($widget_obj['callback'][0]->option_name);
        $widget_num = $widget_obj['params'][0]['number'];

        if ( isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']) )
            $params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );

        return $params;
    }
    add_filter( 'dynamic_sidebar_params', 'kc_dynamic_sidebar_params' );


/*   Custom Pagination
     Usage: <?php pagination(); ?>
    -------------------------------------------------------------------------- */

    function pagination($before = '', $after = '') {
        global $wpdb, $wp_query;
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
        if ( $numposts <= $posts_per_page ) { return; }
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
        $pages_to_show = 7;
        $pages_to_show_minus_1 = $pages_to_show-1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
        if($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
        echo $before.'<nav class="pagination"><ol>'."";
        echo '<li class="page-link prev-page">';
        previous_posts_link('&larr; Newer');
        echo '</li>';
        for($i = $start_page; $i  <= $end_page; $i++) {
            if($i == $paged) {
                echo '<li class="page-link current-page">'.$i.'</li>';
            } else {
                echo '<li class="page-link"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
            }
        }
        echo '<li class="page-link next-page">';
        next_posts_link('Older &rarr;');
        echo '</li>';
        echo '</ol></nav>'.$after."";
    }


/*   Allow shortcodes in text widgets
    -------------------------------------------------------------------------- */

    add_filter('widget_text', 'do_shortcode');


/*   Increase JPG compression slightly
    -------------------------------------------------------------------------- */

    function vtl_jpeg_quality( $quality ) {
        return 60;
    }
    add_filter( 'jpeg_quality', 'vtl_jpeg_quality' );
?>