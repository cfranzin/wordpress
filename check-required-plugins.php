<?php
/**
 * Script para detectar qué plugins usa el sitio TDC y cuáles faltan en el sitio actual
 */

// Cargar WordPress del sitio actual
require_once( __DIR__ . '/wp-load.php' );

// Configuración de la base de datos TDC
$tdc_db_name = 'tdcssmdj_wp119';
$tdc_db_user = 'tdcssmdj_wp119';
$tdc_db_pass = 'V()DS373(p@3L]V8';
$tdc_db_host = 'localhost';
$tdc_prefix = 'wphv_';

echo str_repeat( "=", 70 ) . "\n";
echo "VERIFICACIÓN DE PLUGINS NECESARIOS\n";
echo str_repeat( "=", 70 ) . "\n\n";

// Conectar a la base de datos TDC
$tdc_mysqli = new mysqli( $tdc_db_host, $tdc_db_user, $tdc_db_pass, $tdc_db_name );

if ( $tdc_mysqli->connect_error ) {
    echo "Error de conexión al sitio TDC: " . $tdc_mysqli->connect_error . "\n";
    exit;
}

// Obtener plugins activos del sitio TDC
$query = "SELECT option_value FROM {$tdc_prefix}options WHERE option_name = 'active_plugins'";
$result = $tdc_mysqli->query( $query );

if ( $result && $result->num_rows > 0 ) {
    $row = $result->fetch_assoc();
    $tdc_active_plugins = unserialize( $row['option_value'] );
    
    echo "PLUGINS ACTIVOS EN EL SITIO TDC:\n";
    echo str_repeat( "-", 70 ) . "\n";
    
    $tdc_plugin_info = array();
    
    foreach ( $tdc_active_plugins as $plugin_path ) {
        $parts = explode( '/', $plugin_path );
        $plugin_slug = $parts[0];
        $tdc_plugin_info[ $plugin_slug ] = $plugin_path;
        echo "  ✓ {$plugin_path}\n";
    }
    
    echo "\n";
}

$tdc_mysqli->close();

// Obtener plugins activos del sitio actual
$current_active_plugins = get_option( 'active_plugins', array() );

echo "PLUGINS ACTIVOS EN EL SITIO ACTUAL:\n";
echo str_repeat( "-", 70 ) . "\n";

if ( empty( $current_active_plugins ) ) {
    echo "  (No hay plugins activos)\n";
} else {
    foreach ( $current_active_plugins as $plugin_path ) {
        echo "  ✓ {$plugin_path}\n";
    }
}

echo "\n";

// Comparar y encontrar plugins faltantes
echo "ANÁLISIS DE PLUGINS NECESARIOS:\n";
echo str_repeat( "-", 70 ) . "\n\n";

$missing_plugins = array();
$installed_plugins = array();

// Obtener todos los plugins instalados (activos e inactivos)
if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$all_installed_plugins = get_plugins();

foreach ( $all_installed_plugins as $plugin_path => $plugin_data ) {
    $parts = explode( '/', $plugin_path );
    $installed_plugins[] = $parts[0];
}

// Identificar plugins críticos basados en los metadatos copiados
$critical_plugins = array(
    'elementor' => array(
        'name' => 'Elementor',
        'reason' => 'La página usa diseño de Elementor (_elementor_data)',
        'slug' => 'elementor',
    ),
    'essential-blocks' => array(
        'name' => 'Essential Blocks',
        'reason' => 'La página usa bloques reutilizables de Essential Blocks',
        'slug' => 'essential-blocks',
    ),
    'all-in-one-seo-pack' => array(
        'name' => 'All in One SEO (AIOSEO)',
        'reason' => 'La página tiene configuraciones SEO de AIOSEO',
        'slug' => 'all-in-one-seo-pack',
    ),
    'ultimate-addons-for-gutenberg' => array(
        'name' => 'Ultimate Addons for Gutenberg',
        'reason' => 'La página usa assets de UAG',
        'slug' => 'spectra-pro',
    ),
);

// Verificar qué plugins críticos faltan
foreach ( $critical_plugins as $key => $plugin_info ) {
    $is_installed = false;
    
    // Buscar si el plugin está instalado
    foreach ( $all_installed_plugins as $plugin_path => $plugin_data ) {
        if ( strpos( $plugin_path, $key ) !== false || 
             strpos( strtolower( $plugin_data['Name'] ), strtolower( $plugin_info['name'] ) ) !== false ) {
            $is_installed = true;
            
            // Verificar si está activo
            $is_active = in_array( $plugin_path, $current_active_plugins );
            
            if ( $is_active ) {
                echo "✓ {$plugin_info['name']}: INSTALADO Y ACTIVO\n";
            } else {
                echo "⚠ {$plugin_info['name']}: Instalado pero INACTIVO\n";
                echo "  → Necesitas activarlo en Plugins → Plugins instalados\n";
            }
            break;
        }
    }
    
    if ( ! $is_installed ) {
        echo "✗ {$plugin_info['name']}: NO INSTALADO\n";
        echo "  → {$plugin_info['reason']}\n";
        echo "  → Instalar desde: Plugins → Añadir nuevo → Buscar '{$plugin_info['name']}'\n";
        $missing_plugins[] = $plugin_info;
    }
    
    echo "\n";
}

// Verificar el tema activo
echo str_repeat( "-", 70 ) . "\n";
echo "TEMA ACTIVO:\n";
echo str_repeat( "-", 70 ) . "\n";

$current_theme = wp_get_theme();
echo "Sitio actual: {$current_theme->get( 'Name' )} (v{$current_theme->get( 'Version' )})\n";

// Obtener el tema del sitio TDC
$tdc_mysqli = new mysqli( $tdc_db_host, $tdc_db_user, $tdc_db_pass, $tdc_db_name );
$query = "SELECT option_value FROM {$tdc_prefix}options WHERE option_name = 'template'";
$result = $tdc_mysqli->query( $query );
if ( $result && $result->num_rows > 0 ) {
    $row = $result->fetch_assoc();
    $tdc_theme = $row['option_value'];
    echo "Sitio TDC: {$tdc_theme}\n\n";
    
    if ( strtolower( $current_theme->get_stylesheet() ) !== strtolower( $tdc_theme ) ) {
        echo "⚠ ADVERTENCIA: Los temas son diferentes.\n";
        echo "  Para una apariencia idéntica, instala el tema '{$tdc_theme}'\n";
    } else {
        echo "✓ Los sitios usan el mismo tema\n";
    }
}
$tdc_mysqli->close();

echo "\n";
echo str_repeat( "=", 70 ) . "\n";
echo "RESUMEN:\n";
echo str_repeat( "=", 70 ) . "\n";

if ( empty( $missing_plugins ) ) {
    echo "✓ Todos los plugins críticos están instalados.\n";
    echo "  Tu página debería verse correctamente.\n";
} else {
    echo "Se encontraron " . count( $missing_plugins ) . " plugin(s) faltante(s).\n\n";
    echo "ACCIONES RECOMENDADAS:\n";
    echo "1. Ve a: Panel de WordPress → Plugins → Añadir nuevo\n";
    echo "2. Busca e instala los siguientes plugins:\n";
    foreach ( $missing_plugins as $plugin ) {
        echo "   - {$plugin['name']}\n";
    }
    echo "3. Activa todos los plugins instalados\n";
    echo "4. Recarga tu página de inicio: " . home_url() . "\n";
}

echo "\n";
