<?php
/**
 * Script para copiar el contenido de una página del sitio TDC a la página de inicio
 */

// Cargar WordPress del sitio actual
require_once( __DIR__ . '/wp-load.php' );

// Conectar a la base de datos del sitio TDC para obtener el contenido de la página 24
$tdc_wpdb = new wpdb( 'wordpress', 'wordpress123', 'wordpress', 'localhost' );
$tdc_wpdb->set_prefix( 'wp_' ); // Ajustar si el prefijo es diferente

// Obtener el contenido completo de la página 24 del sitio TDC
$source_page = $tdc_wpdb->get_row( 
    $tdc_wpdb->prepare( 
        "SELECT * FROM {$tdc_wpdb->prefix}posts WHERE ID = %d", 
        24 
    ) 
);

if ( ! $source_page ) {
    echo "Error: No se pudo encontrar la página con ID 24 en el sitio TDC\n";
    exit;
}

echo "Página encontrada en TDC:\n";
echo "- Título: {$source_page->post_title}\n";
echo "- Tipo: {$source_page->post_type}\n";
echo "- Estado: {$source_page->post_status}\n";
echo "- Longitud del contenido: " . strlen( $source_page->post_content ) . " caracteres\n\n";

// Obtener también los metadatos de la página (custom fields, featured image, etc.)
$source_meta = $tdc_wpdb->get_results( 
    $tdc_wpdb->prepare( 
        "SELECT meta_key, meta_value FROM {$tdc_wpdb->prefix}postmeta WHERE post_id = %d", 
        24 
    ) 
);

// Actualizar la página de inicio (ID 12) con el contenido de la página 24
$page_id = 12; // ID de la página de inicio
$update_data = array(
    'ID'           => $page_id,
    'post_title'   => $source_page->post_title,
    'post_content' => $source_page->post_content,
    'post_excerpt' => $source_page->post_excerpt,
);

$result = wp_update_post( $update_data );

if ( $result && ! is_wp_error( $result ) ) {
    echo "✓ Contenido copiado exitosamente a la página de inicio (ID: {$page_id})\n\n";
    
    // Copiar los metadatos
    if ( $source_meta ) {
        echo "Copiando metadatos...\n";
        foreach ( $source_meta as $meta ) {
            // Evitar copiar algunos metadatos internos que no deberían duplicarse
            $skip_keys = array( '_edit_lock', '_edit_last' );
            if ( in_array( $meta->meta_key, $skip_keys ) ) {
                continue;
            }
            
            update_post_meta( $page_id, $meta->meta_key, maybe_unserialize( $meta->meta_value ) );
            echo "  - {$meta->meta_key}\n";
        }
        echo "✓ Metadatos copiados\n\n";
    }
    
    // Si la página tiene un template personalizado, establecerlo
    $template = get_post_meta( 24, '_wp_page_template', true );
    if ( $template && $template !== 'default' ) {
        update_post_meta( $page_id, '_wp_page_template', $template );
        echo "✓ Template personalizado configurado: {$template}\n";
    } else {
        // Asegurarse de que use el template por defecto para mostrar el contenido
        delete_post_meta( $page_id, '_wp_page_template' );
        echo "✓ Configurado para usar el template por defecto (mostrará el contenido completo)\n";
    }
    
    echo "\n✓ Proceso completado!\n";
    echo "Puedes ver tu página de inicio en: " . home_url() . "\n";
} else {
    echo "Error al actualizar la página de inicio\n";
    if ( is_wp_error( $result ) ) {
        echo "Error: " . $result->get_error_message() . "\n";
    }
}
