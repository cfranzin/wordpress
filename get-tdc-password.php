<?php
/**
 * Leer credenciales de la base de datos TDC
 */

$tdc_config_path = 'd:/xampp/htdocs/tdc/wp-config.php';

if ( ! file_exists( $tdc_config_path ) ) {
    echo "Error: No se encontró wp-config.php\n";
    exit;
}

$config_content = file_get_contents( $tdc_config_path );

// Extraer la contraseña
preg_match( "/define\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $config_content, $db_pass_match );

$db_pass = $db_pass_match[1] ?? '';

echo "Contraseña de la base de datos TDC: {$db_pass}\n";
