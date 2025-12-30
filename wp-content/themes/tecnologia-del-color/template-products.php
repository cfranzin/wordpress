<?php
/**
 * Template Name: Página de Productos
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

<section class="section products-section">
    <div class="container">
        <div class="products-intro">
            <p>Nuestra red de representadas reúne tecnologías de medición de color y apariencia, ensayos físicos, envejecimiento acelerado, dosificación y agitación, electroquímica y más. No es solo provisión de equipos: acompañamos la selección técnica, la puesta en marcha, las calibraciones con trazabilidad, el mantenimiento y la formación del usuario, asegurando repetibilidad, documentación para auditorías y cumplimiento normativo (ASTM/ISO/IEC/GLP).</p>
            <p><strong>Desplegá cada marca para ver líneas de producto, aplicaciones por industria y recursos técnicos.</strong></p>
        </div>
        
        <?php
        while ( have_posts() ) :
            the_post();
            if ( get_the_content() ) {
                the_content();
            }
        endwhile;
        ?>
        
        <div class="products-grid">
            <?php
            $products_query = new WP_Query( array(
                'post_type'      => 'producto',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ) );
            
            if ( $products_query->have_posts() ) :
                while ( $products_query->have_posts() ) : $products_query->the_post();
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
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e( 'Ver detalles', 'tecnologia-del-color' ); ?></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p><?php _e( 'No hay productos disponibles en este momento.', 'tecnologia-del-color' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
get_footer();
