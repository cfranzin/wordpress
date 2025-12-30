<?php
/**
 * Template for displaying producto archive
 *
 * @package Tecnologia_del_Color
 */

get_header();
?>

<section class="page-header">
    <div class="container">
        <h1><?php _e( 'PRODUCTOS', 'tecnologia-del-color' ); ?></h1>
    </div>
</section>

<section class="section products-section">
    <div class="container">
        <div class="products-intro">
            <p>Nuestra red de representadas reúne tecnologías de medición de color y apariencia, ensayos físicos, envejecimiento acelerado, dosificación y agitación, electroquímica y más. No es solo provisión de equipos: acompañamos la selección técnica, la puesta en marcha, las calibraciones con trazabilidad, el mantenimiento y la formación del usuario, asegurando repetibilidad, documentación para auditorías y cumplimiento normativo (ASTM/ISO/IEC/GLP).</p>
            <p><strong>Desplegá cada marca para ver líneas de producto, aplicaciones por industria y recursos técnicos.</strong></p>
        </div>
        
        <div class="products-grid">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'tdc-service' ); ?>
                            <?php else : ?>
                                <div class="product-brand-placeholder">
                                    <span><?php the_title(); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'Ver detalles', 'tecnologia-del-color' ); ?></a>
                        </div>
                    </div>
                    <?php
                endwhile;
            else :
                ?>
                <p><?php _e( 'No hay productos disponibles en este momento.', 'tecnologia-del-color' ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php
        // Pagination
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => __( '← Anterior', 'tecnologia-del-color' ),
            'next_text' => __( 'Siguiente →', 'tecnologia-del-color' ),
        ) );
        ?>
    </div>
</section>

<?php
get_footer();
