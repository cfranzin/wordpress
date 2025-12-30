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
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="header-contact">
                <span><?php echo esc_html( get_theme_mod( 'tdc_phone', '(54-11) 4761-2300' ) ); ?></span>
                <?php 
                $whatsapp = get_theme_mod( 'tdc_whatsapp', '5491132832399' );
                if ( $whatsapp ) : 
                ?>
                    <a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>" target="_blank">
                        WhatsApp
                    </a>
                <?php endif; ?>
            </div>
            <div class="header-social">
                <a href="https://ar.linkedin.com/company/tecnologiadelcolor" target="_blank" rel="noopener">LinkedIn</a>
                <a href="https://www.facebook.com/tecnologiadelcolor" target="_blank" rel="noopener">Facebook</a>
                <a href="https://www.instagram.com/tecnologia.del.color/" target="_blank" rel="noopener">Instagram</a>
            </div>
        </div>
    </div>
    
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
