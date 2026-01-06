<?php
/**
 * Script para detectar la configuración de la base de datos del sitio TDC
 */

// Verificar si existe el archivo wp-config.php del sitio TDC
$tdc_config_path = 'd:/xampp/htdocs/tdc/wp-config.php';

if ( ! file_exists( $tdc_config_path ) ) {
    echo "Error: No se encontró el archivo wp-config.php en: {$tdc_config_path}\n";
    echo "Por favor, verifica la ruta del sitio TDC.\n";
    exit;
}

echo "✓ Archivo wp-config.php encontrado en TDC\n";
echo "Leyendo configuración...\n\n";

// Leer el contenido del archivo
$config_content = file_get_contents( $tdc_config_path );

// Extraer información de la base de datos
preg_match( "/define\(\s*['\"]DB_NAME['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $config_content, $db_name_match );
preg_match( "/define\(\s*['\"]DB_USER['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $config_content, $db_user_match );
preg_match( "/define\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $config_content, $db_pass_match );
preg_match( "/define\(\s*['\"]DB_HOST['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $config_content, $db_host_match );
preg_match( "/\\$table_prefix\s*=\s*['\"]([^'\"]+)['\"]/", $config_content, $prefix_match );

$db_name = $db_name_match[1] ?? '';
$db_user = $db_user_match[1] ?? '';
$db_pass = $db_pass_match[1] ?? '';
$db_host = $db_host_match[1] ?? '';
$table_prefix = $prefix_match[1] ?? 'wp_';

echo "Configuración de la base de datos TDC:\n";
echo "- DB_NAME: {$db_name}\n";
echo "- DB_USER: {$db_user}\n";
echo "- DB_HOST: {$db_host}\n";
echo "- Table Prefix: {$table_prefix}\n\n";

// Intentar conectar a la base de datos
try {
    $mysqli = new mysqli( $db_host, $db_user, $db_pass, $db_name );
    
    if ( $mysqli->connect_error ) {
        echo "Error de conexión: " . $mysqli->connect_error . "\n";
        exit;
    }
    
    echo "✓ Conexión exitosa a la base de datos\n\n";
    
    // Detectar el prefijo correcto de las tablas buscando específicamente la tabla posts
    echo "Buscando tabla de posts...\n";
    $tables_result = $mysqli->query( "SHOW TABLES" );
    $posts_table = '';
    
    if ( $tables_result ) {
        while ( $table_row = $tables_result->fetch_array() ) {
            $table_name = $table_row[0];
            echo "  Analizando tabla: {$table_name}\n";
            // Buscar tabla que termine exactamente en 'posts' (no _posts ni posts en medio)
            if ( preg_match( '/^(\w+_)posts$/', $table_name ) && ! strpos( $table_name, 'aioseo' ) && ! strpos( $table_name, 'rank_math' ) ) {
                $posts_table = $table_name;
                $table_prefix = preg_replace( '/posts$/', '', $table_name );
                echo "\n✓ Tabla de posts encontrada: {$posts_table}\n";
                echo "✓ Prefijo detectado: {$table_prefix}\n\n";
                break;
            }
        }
    }
    
    if ( ! $posts_table ) {
        echo "Error: No se encontró la tabla de posts\n";
        echo "Listando todas las tablas:\n";
        $tables_result = $mysqli->query( "SHOW TABLES" );
        while ( $table_row = $tables_result->fetch_array() ) {
            echo "  - {$table_row[0]}\n";
        }
        exit;
    }
    
    // Verificar si existe la página con ID 24
    $result = $mysqli->query( "SELECT ID, post_title, post_type, post_status, LENGTH(post_content) as content_length FROM {$posts_table} WHERE ID = 24" );
    
    if ( $result && $result->num_rows > 0 ) {
        $page = $result->fetch_assoc();
        echo "✓ Página encontrada:\n";
        echo "- ID: {$page['ID']}\n";
        echo "- Título: {$page['post_title']}\n";
        echo "- Tipo: {$page['post_type']}\n";
        echo "- Estado: {$page['post_status']}\n";
        echo "- Longitud del contenido: {$page['content_length']} caracteres\n";
    } else {
        echo "× No se encontró ninguna página con ID 24\n";
        echo "Listando las primeras páginas disponibles:\n\n";
        
        $result = $mysqli->query( "SELECT ID, post_title, post_type, post_status FROM {$posts_table} WHERE post_type = 'page' ORDER BY ID LIMIT 10" );
        
        if ( $result ) {
            while ( $page = $result->fetch_assoc() ) {
                echo "  ID {$page['ID']}: {$page['post_title']} [{$page['post_status']}]\n";
            }
        }
    }
    
    $mysqli->close();
    
} catch ( Exception $e ) {
    echo "Error: " . $e->getMessage() . "\n";
}
