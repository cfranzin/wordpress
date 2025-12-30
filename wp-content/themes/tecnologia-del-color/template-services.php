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

<section class="section">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
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
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image">
                                <?php the_post_thumbnail( 'tdc-service' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="service-content">
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
                <p><?php _e( 'No hay servicios disponibles en este momento.', 'tecnologia-del-color' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
get_footer();
