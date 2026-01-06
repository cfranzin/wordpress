<?php
/**
 * Script final para copiar el contenido completo de la página 24 del sitio TDC
 */

// Cargar WordPress del sitio actual
require_once( __DIR__ . '/wp-load.php' );

// Configuración de la base de datos TDC
$tdc_db_name = 'tdcssmdj_wp119';
$tdc_db_user = 'tdcssmdj_wp119';
$tdc_db_pass = 'V()DS373(p@3L]V8';
$tdc_db_host = 'localhost';
$tdc_prefix = 'wphv_';

echo "Conectando a la base de datos del sitio TDC...\n";

// Conectar a la base de datos TDC
$tdc_mysqli = new mysqli( $tdc_db_host, $tdc_db_user, $tdc_db_pass, $tdc_db_name );

if ( $tdc_mysqli->connect_error ) {
    echo "Error de conexión: " . $tdc_mysqli->connect_error . "\n";
    exit;
}

echo "✓ Conectado exitosamente\n\n";

// Obtener el contenido completo de la página 24
$source_post_id = 24;
$query = "SELECT * FROM {$tdc_prefix}posts WHERE ID = {$source_post_id}";
$result = $tdc_mysqli->query( $query );

if ( ! $result || $result->num_rows === 0 ) {
    echo "Error: No se encontró la página con ID {$source_post_id}\n";
    $tdc_mysqli->close();
    exit;
}

$source_page = $result->fetch_assoc();

echo "Página fuente encontrada:\n";
echo "- Título: {$source_page['post_title']}\n";
echo "- Longitud del contenido: " . strlen( $source_page['post_content'] ) . " caracteres\n";
echo "- Extracto: " . ( ! empty( $source_page['post_excerpt'] ) ? 'Sí' : 'No' ) . "\n\n";

// Obtener todos los metadatos de la página
$meta_query = "SELECT meta_key, meta_value FROM {$tdc_prefix}postmeta WHERE post_id = {$source_post_id}";
$meta_result = $tdc_mysqli->query( $meta_query );
$source_meta = array();

if ( $meta_result && $meta_result->num_rows > 0 ) {
    while ( $meta_row = $meta_result->fetch_assoc() ) {
        $source_meta[] = $meta_row;
    }
    echo "✓ Se encontraron " . count( $source_meta ) . " metadatos\n\n";
}

$tdc_mysqli->close();

// Actualizar la página de inicio del sitio actual
$target_page_id = 12; // ID de la página de inicio

echo "Actualizando página de inicio (ID: {$target_page_id})...\n";

$update_data = array(
    'ID'           => $target_page_id,
    'post_title'   => $source_page['post_title'],
    'post_content' => $source_page['post_content'],
    'post_excerpt' => $source_page['post_excerpt'],
);

$result = wp_update_post( $update_data, true );

if ( is_wp_error( $result ) ) {
    echo "Error al actualizar la página: " . $result->get_error_message() . "\n";
    exit;
}

echo "✓ Contenido principal copiado exitosamente\n\n";

// Copiar todos los metadatos
echo "Copiando metadatos...\n";

// Primero, eliminar metadatos existentes que no queremos mantener
$skip_keys = array( '_edit_lock', '_edit_last', '_wp_old_slug', '_wp_old_date' );

foreach ( $source_meta as $meta ) {
    $meta_key = $meta['meta_key'];
    $meta_value = $meta['meta_value'];
    
    // Saltar algunos metadatos internos
    if ( in_array( $meta_key, $skip_keys, true ) ) {
        continue;
    }
    
    // Deserializar si es necesario
    $unserialized = @unserialize( $meta_value );
    if ( $unserialized !== false || $meta_value === 'b:0;' ) {
        $meta_value = $unserialized;
    }
    
    // Actualizar el metadato
    update_post_meta( $target_page_id, $meta_key, $meta_value );
    
    echo "  ✓ {$meta_key}\n";
}

echo "\n✓ Metadatos copiados exitosamente\n\n";

// Asegurarse de que la página use el template correcto
$template = get_post_meta( $target_page_id, '_wp_page_template', true );

if ( $template && $template !== 'default' ) {
    echo "✓ Template personalizado: {$template}\n";
} else {
    echo "✓ Usando template por defecto\n";
}

// Verificar que siga siendo la página de inicio
$show_on_front = get_option( 'show_on_front' );
$page_on_front = get_option( 'page_on_front' );

if ( $show_on_front !== 'page' || $page_on_front != $target_page_id ) {
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $target_page_id );
    echo "✓ Confirmado como página de inicio\n";
}

echo "\n" . str_repeat( "=", 60 ) . "\n";
echo "✓ PROCESO COMPLETADO EXITOSAMENTE\n";
echo str_repeat( "=", 60 ) . "\n\n";
echo "Tu página de inicio ahora tiene el mismo contenido que la página\n";
echo "del sitio TDC. Puedes verla en: " . home_url() . "\n\n";
echo "Nota: Los elementos visuales dependerán de que tu tema actual\n";
echo "soporte los mismos bloques/shortcodes que el sitio TDC.\n";
