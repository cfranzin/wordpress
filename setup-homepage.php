<?php
/**
 * Script para configurar una página estática como página de inicio
 */

// Cargar WordPress
require_once( __DIR__ . '/wp-load.php' );

// Verificar si ya existe una página llamada "Inicio" o "Home"
$inicio_page = get_page_by_title( 'Inicio', OBJECT, 'page' );
$home_page = get_page_by_title( 'Home', OBJECT, 'page' );

$page_id = null;

if ( $inicio_page ) {
    $page_id = $inicio_page->ID;
    echo "Página 'Inicio' encontrada (ID: {$page_id})\n";
} elseif ( $home_page ) {
    $page_id = $home_page->ID;
    echo "Página 'Home' encontrada (ID: {$page_id})\n";
} else {
    // Crear una nueva página de inicio
    $page_data = array(
        'post_title'    => 'Inicio',
        'post_content'  => '<h1>Bienvenido a nuestro sitio</h1><p>Esta es la página de inicio.</p>',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
    );
    
    $page_id = wp_insert_post( $page_data );
    
    if ( $page_id && ! is_wp_error( $page_id ) ) {
        echo "Nueva página 'Inicio' creada (ID: {$page_id})\n";
    } else {
        echo "Error al crear la página de inicio\n";
        exit;
    }
}

// Configurar WordPress para mostrar una página estática como página de inicio
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_id );

// Opcional: crear una página para el blog
$blog_page = get_page_by_title( 'Blog', OBJECT, 'page' );
if ( ! $blog_page ) {
    $blog_page_data = array(
        'post_title'    => 'Blog',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
    );
    
    $blog_page_id = wp_insert_post( $blog_page_data );
    
    if ( $blog_page_id && ! is_wp_error( $blog_page_id ) ) {
        update_option( 'page_for_posts', $blog_page_id );
        echo "Página 'Blog' creada para mostrar las entradas (ID: {$blog_page_id})\n";
    }
} else {
    update_option( 'page_for_posts', $blog_page->ID );
    echo "Página 'Blog' configurada para mostrar las entradas (ID: {$blog_page->ID})\n";
}

echo "\n✓ Configuración completada exitosamente!\n";
echo "- La página de inicio ahora es: " . get_the_title( $page_id ) . "\n";
echo "- Las entradas del blog se mostrarán en: " . get_the_title( get_option( 'page_for_posts' ) ) . "\n";
echo "\nPuedes visitar tu sitio en: " . home_url() . "\n";
