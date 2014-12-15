<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php ( is_front_page() ? wp_title() : wp_title( '|', true, 'right' ) ) ?> <?php bloginfo('name') ?></title>
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico">
    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<div class="main">