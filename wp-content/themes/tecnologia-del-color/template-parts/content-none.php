<?php
/**
 * Template part for displaying a message when posts are not found
 *
 * @package Tecnologia_del_Color
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php _e( 'No se encontraron resultados', 'tecnologia-del-color' ); ?></h1>
    </header>

    <div class="page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :
            ?>
            <p><?php
                printf(
                    wp_kses(
                        __( '¿Listo para publicar tu primer post? <a href="%1$s">Empieza aquí</a>.', 'tecnologia-del-color' ),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
            ?></p>
        <?php elseif ( is_search() ) : ?>
            <p><?php _e( 'Lo sentimos, no se encontraron resultados para tu búsqueda. Por favor intenta con otras palabras clave.', 'tecnologia-del-color' ); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e( 'Parece que no encontramos lo que buscabas. Intenta realizar una búsqueda.', 'tecnologia-del-color' ); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
