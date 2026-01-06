<?php
/**
 * Template Name: Página de Servicios
 *
 * @package Tecnologia_del_Color
 */

get_header();
?>

<section class="page-header">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>

<section class="section services-section">
    <div class="container">
        <div class="services-intro">
            <p>Brindamos servicios de consultoría, capacitación y asesoramiento técnico para la medición de las propiedades físicas de materiales en los procesos de producción, desarrollo y control de calidad.</p>
            
            <p>Ofrecemos soluciones integrales en control de color y apariencia, ensayos físicos, envejecimiento acelerado, dosificación y agitación de pinturas, calibración de instrumentos, y buenas prácticas de laboratorio.</p>
            
            <p>Trabajamos junto a empresas que buscan optimizar su desempeño técnico y garantizar resultados confiables, trazables y reproducibles.</p>
            
            <p>Nuestros servicios están orientados a sectores como la industria automotriz, autopartista, motopartista, plásticos y films, pinturas y recubrimientos, textil, alimenticia, cosmética, adhesivos, entre otros.</p>
            
            <p><strong>Promovemos el conocimiento tecnológico y la calidad a través de la capacitación continua y la aplicación de normas internacionales como ISO 9001:2015.</strong></p>
        </div>
        
        <?php
        while ( have_posts() ) :
            the_post();
            if ( get_the_content() ) {
                echo '<div class="services-custom-content">';
                the_content();
                echo '</div>';
            }
        endwhile;
        ?>
        
        <div class="services-grid">
            <?php
            $services_query = new WP_Query( array(
                'post_type'      => 'servicio',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ) );
            
            if ( $services_query->have_posts() ) :
                while ( $services_query->have_posts() ) : $services_query->the_post();
                    ?>
                    <div class="service-card">
                        <div class="service-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'tdc-service' ); ?>
                            <?php else : ?>
                                <div class="service-placeholder">
                                    <span><?php the_title(); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="service-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'VER', 'tecnologia-del-color' ); ?></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p><?php _e( 'No hay servicios disponibles en este momento.', 'tecnologia-del-color' ); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Sección de consultas -->
        <section class="services-contact-section">
            <div class="contact-form-wrapper">
                <h2>CONSULTAS Y PRESUPUESTOS TÉCNICOS</h2>
                <?php echo do_shortcode('[contact-form-7 id="' . get_option('tdc_services_form_id', '') . '"]'); ?>
            </div>
        </section>
    </div>
</section>

<?php
get_footer();