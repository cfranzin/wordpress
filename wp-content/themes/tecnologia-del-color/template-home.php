<?php
/**
 * Template Name: Página de Inicio
 *
 * @package Tecnologia_del_Color
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <p class="hero-tagline"><?php _e( 'SOMOS', 'tecnologia-del-color' ); ?></p>
            <h1><?php _e( 'TECNOLOGÍA DEL COLOR', 'tecnologia-del-color' ); ?></h1>
            <p class="hero-subtitle"><?php _e( 'Una empresa al servicio de la Industria', 'tecnologia-del-color' ); ?></p>
            <p><?php _e( 'En TDC ofrecemos soluciones para optimizar tus procesos, mejorar la productividad y reducir costos.', 'tecnologia-del-color' ); ?></p>
            <div class="hero-buttons">
                <a href="#servicios" class="btn btn-primary"><?php _e( 'Nuestros Servicios', 'tecnologia-del-color' ); ?></a>
                <a href="#contacto" class="btn btn-secondary"><?php _e( 'Contactar', 'tecnologia-del-color' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2><?php _e( 'Somos una empresa líder con 25 años de experiencia', 'tecnologia-del-color' ); ?></h2>
                <p><?php _e( 'Brindamos una amplia gama de servicios y productos de las mejores representadas del mundo. Nuestra meta es convertirnos en el proveedor de referencia en la industria, con tecnología de primer nivel.', 'tecnologia-del-color' ); ?></p>
                
                <ul class="about-features">
                    <li><?php _e( '25 años de experiencia en la industria', 'tecnologia-del-color' ); ?></li>
                    <li><?php _e( 'Soluciones integrales para todas las industrias', 'tecnologia-del-color' ); ?></li>
                    <li><?php _e( 'Tecnología de primer nivel mundial', 'tecnologia-del-color' ); ?></li>
                    <li><?php _e( 'Atención personalizada y soporte continuo', 'tecnologia-del-color' ); ?></li>
                    <li><?php _e( 'Certificación ISO 9001:2015', 'tecnologia-del-color' ); ?></li>
                </ul>
                
                <a href="<?php echo esc_url( home_url( '/nosotros' ) ); ?>" class="btn btn-primary"><?php _e( 'Conocer más', 'tecnologia-del-color' ); ?></a>
            </div>
            <div class="about-image">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-placeholder.jpg' ); ?>" alt="<?php _e( 'Tecnología del Color', 'tecnologia-del-color' ); ?>">
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="servicios" class="section">
    <div class="container">
        <div class="section-title">
            <h2><?php _e( 'INSTRUMENTACIÓN, ENSAYO Y SERVICIOS', 'tecnologia-del-color' ); ?></h2>
            <p><?php _e( 'PARA TODAS LAS INDUSTRIAS', 'tecnologia-del-color' ); ?></p>
        </div>
        
        <p style="text-align: center; max-width: 800px; margin: 0 auto 40px;">
            <?php _e( 'Desde la selección del equipo hasta la posventa: instalación, capacitación de usuarios, calibraciones y soporte remoto/onsite. Atención personalizada para asegurar repetibilidad y cumplimiento ASTM/ISO.', 'tecnologia-del-color' ); ?>
        </p>
        
        <div class="services-grid">
            <?php
            $services_query = new WP_Query( array(
                'post_type'      => 'servicio',
                'posts_per_page' => 3,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ) );
            
            if ( $services_query->have_posts() ) :
                while ( $services_query->have_posts() ) : $services_query->the_post();
                    ?>
                    <div class="service-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image">
                                <?php the_post_thumbnail( 'tdc-service' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="service-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'Ver más', 'tecnologia-del-color' ); ?></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Default services if none exist
                ?>
                <div class="service-card">
                    <div class="service-content">
                        <h3><?php _e( 'SERVICIO TÉCNICO', 'tecnologia-del-color' ); ?></h3>
                        <p><?php _e( 'Soporte técnico especializado, mantenimiento y calibraciones certificadas.', 'tecnologia-del-color' ); ?></p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-content">
                        <h3><?php _e( 'PRODUCTOS', 'tecnologia-del-color' ); ?></h3>
                        <p><?php _e( 'Instrumentación y equipos de las mejores marcas del mundo.', 'tecnologia-del-color' ); ?></p>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-content">
                        <h3><?php _e( 'ATENCIÓN PERSONALIZADA', 'tecnologia-del-color' ); ?></h3>
                        <p><?php _e( 'Asesoramiento técnico y soporte continuo para todos nuestros clientes.', 'tecnologia-del-color' ); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Clients Section -->
<section class="section clients-section">
    <div class="container">
        <div class="section-title">
            <h2><?php _e( 'NUESTROS CLIENTES', 'tecnologia-del-color' ); ?></h2>
            <p><?php _e( 'Empresas líderes confían en nosotros', 'tecnologia-del-color' ); ?></p>
        </div>
        
        <div class="clients-grid">
            <?php
            $clients_query = new WP_Query( array(
                'post_type'      => 'cliente',
                'posts_per_page' => 10,
                'orderby'        => 'rand',
            ) );
            
            if ( $clients_query->have_posts() ) :
                while ( $clients_query->have_posts() ) : $clients_query->the_post();
                    if ( has_post_thumbnail() ) :
                        ?>
                        <div class="client-logo">
                            <?php the_post_thumbnail( 'tdc-client' ); ?>
                        </div>
                        <?php
                    endif;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="contacto" class="cta-section">
    <div class="container">
        <h2><?php _e( '¿Listo para optimizar tus procesos?', 'tecnologia-del-color' ); ?></h2>
        <p><?php _e( 'Contactanos hoy y descubre cómo podemos ayudarte a mejorar tu productividad', 'tecnologia-del-color' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="btn btn-secondary"><?php _e( 'Contactar Ahora', 'tecnologia-del-color' ); ?></a>
    </div>
</section>

<?php
get_footer();
