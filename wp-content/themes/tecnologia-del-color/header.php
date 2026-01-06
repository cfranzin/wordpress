<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <!-- Header Main -->
    <div class="header-main">
        <div class="container">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
                        <span>TECNOLOGÍA</span> DEL COLOR
                    </a>
                <?php endif; ?>
            </div>
            
            <nav class="main-navigation" id="site-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>
            
            <button class="menu-toggle" id="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-icon">☰</span>
            </button>
        </div>
    </div>
</header>

<main id="main-content" class="site-main">
