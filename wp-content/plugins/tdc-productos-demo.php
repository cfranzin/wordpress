<?php
/**
 * Plugin Name: TDC Productos Demo Data
 * Description: Crea productos de demostración para Tecnología del Color
 * Version: 1.0
 * Author: TDC Team
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Crear productos de demostración
 */
function tdc_create_demo_products() {
    // Eliminar productos existentes primero
    $existing_products = get_posts( array(
        'post_type'   => 'producto',
        'numberposts' => -1,
        'post_status' => 'any',
    ) );
    
    foreach ( $existing_products as $product ) {
        wp_delete_post( $product->ID, true );
    }
    
    $productos = array(
        array(
            'title'   => 'Q-LAB',
            'content' => '<p>Equipos para ensayos de intemperismo, envejecimiento acelerado y corrosión.</p><h3>Líneas de Producto</h3><ul><li>Cámaras de intemperismo QUV</li><li>Cámaras de niebla salina Q-FOG</li><li>Cámaras de humedad Q-SUN</li><li>Equipos para ensayos climáticos</li><li>Accesorios y consumibles</li></ul><h3>Aplicaciones</h3><ul><li>Industria automotriz</li><li>Pinturas y recubrimientos</li><li>Plásticos y polímeros</li><li>Textiles y materiales</li></ul>',
            'excerpt' => 'Equipos para ensayos de intemperismo, envejecimiento acelerado y corrosión.',
        ),
        array(
            'title'   => 'COROB',
            'content' => '<p>Dosificadoras y mezcladoras de alta tecnología para pinturas y otros materiales.</p><h3>Productos Principales</h3><ul><li>Sistemas de tintometría</li><li>Dosificadores automáticos</li><li>Agitadores industriales</li><li>Software de gestión</li><li>Soluciones customizadas</li></ul><h3>Beneficios</h3><ul><li>Precisión en dosificación</li><li>Ahorro de tiempo y material</li><li>Repetibilidad garantizada</li><li>Integración con sistemas de gestión</li></ul>',
            'excerpt' => 'Dosificadoras y mezcladoras de alta tecnología para pinturas y otros materiales.',
        ),
        array(
            'title'   => 'GTI',
            'content' => '<p>Cabinas y luminarias normalizadas, para comparación de colores.</p><h3>Productos</h3><ul><li>Cabinas de luz estándar</li><li>Luminarias portátiles</li><li>Equipos de evaluación visual</li><li>Sistemas de iluminación profesional</li></ul><h3>Iluminantes Normalizados</h3><ul><li>D65 - Luz día 6500K</li><li>D50 - Luz día 5000K</li><li>A - Incandescente</li><li>TL84 - Fluorescente</li><li>UV - Ultravioleta</li></ul>',
            'excerpt' => 'Cabinas y luminarias normalizadas, para comparación de colores.',
        ),
        array(
            'title'   => 'GOnDO',
            'content' => '<p>Instrumentos de medición científicos e industriales como pH-metros, medidores de conductividad, oxígeno disuelto, turbidez y cloro libre.</p><h3>Línea de Productos</h3><ul><li>pH-metros de laboratorio y portátiles</li><li>Conductímetros digitales</li><li>Medidores de oxígeno disuelto</li><li>Turbidímetros de precisión</li><li>Medidores de cloro libre</li><li>Multiparámetros</li></ul><h3>Aplicaciones</h3><ul><li>Control de calidad de agua</li><li>Laboratorios de análisis</li><li>Industria alimentaria</li><li>Tratamiento de efluentes</li></ul>',
            'excerpt' => 'Instrumentos de medición científicos e industriales como pH-metros, medidores de conductividad, oxígeno disuelto, turbidez y cloro libre.',
        ),
        array(
            'title'   => 'BYK',
            'content' => '<p>Instrumentos para ensayos físicos, control del color y apariencia.</p><h3>Equipos de Medición</h3><ul><li>Espectrofotómetros portátiles</li><li>Brillómetros multiángulo</li><li>Medidores de espesor de película</li><li>Equipos de control de calidad</li><li>Instrumentos de apariencia</li></ul><h3>Sectores</h3><ul><li>Industria automotriz</li><li>Pinturas y recubrimientos</li><li>Plásticos</li><li>Impresión y embalaje</li><li>Cosmética</li></ul>',
            'excerpt' => 'Instrumentos para ensayos físicos, control del color y apariencia.',
        ),
        array(
            'title'   => 'Ohaus',
            'content' => '<p>Balanzas, analizadores de húmedad y pH-metros, precisos y confiables.</p><h3>Productos</h3><ul><li>Balanzas analíticas</li><li>Balanzas de precisión</li><li>Balanzas industriales</li><li>Analizadores de húmedad</li><li>pH-metros de laboratorio</li><li>Agitadores y homogeneizadores</li></ul><h3>Características</h3><ul><li>Alta precisión y repetibilidad</li><li>Calibración automática</li><li>Conectividad USB/RS232</li><li>Certificados de calibración</li></ul>',
            'excerpt' => 'Balanzas, analizadores de húmedad y pH-metros, precisos y confiables.',
        ),
        array(
            'title'   => 'TABER',
            'content' => '<p>Equipos para ensayos de abrasión.</p><h3>Instrumentos</h3><ul><li>Abrasímetro rotatorio Taber</li><li>Equipos de resistencia a la abrasión</li><li>Accesorios y consumibles</li><li>Ruedas abrasivas certificadas</li></ul><h3>Normativas</h3><ul><li>ASTM D4060</li><li>ISO 5470-1</li><li>ASTM D3884</li><li>Cumplimiento normativo internacional</li></ul>',
            'excerpt' => 'Equipos para ensayos de abrasión.',
        ),
        array(
            'title'   => 'ANDILOG',
            'content' => '<p>Dinamómetros para ensayos de tracción, compresión y fuerza.</p><h3>Equipos</h3><ul><li>Dinamómetros digitales</li><li>Bancos de ensayo motorizados</li><li>Accesorios de sujeción</li><li>Software de análisis de datos</li></ul><h3>Aplicaciones</h3><ul><li>Ensayos de tracción</li><li>Ensayos de compresión</li><li>Medición de fuerza de apertura</li><li>Control de calidad de materiales</li><li>I+D de productos</li></ul>',
            'excerpt' => 'Dinamómetros para ensayos de tracción, compresión y fuerza.',
        ),
        array(
            'title'   => 'BOUSSEY',
            'content' => '<p>Herramientas para ensayos de tensión superficial y estática.</p><h3>Productos</h3><ul><li>Medidores de tensión superficial</li><li>Tintas de prueba</li><li>Equipos de tratamiento corona</li><li>Medidores de adherencia</li></ul><h3>Industrias</h3><ul><li>Impresión y conversión</li><li>Plásticos y films</li><li>Recubrimientos</li><li>Control de calidad de superficies</li></ul>',
            'excerpt' => 'Herramientas para ensayos de tensión superficial y estática.',
        ),
        array(
            'title'   => 'VMA Getzmann - Dispermat',
            'content' => '<p>Equipos para ensayos de dispersión, molinos y agitadores.</p><h3>Línea de Productos</h3><ul><li>Dispersores de laboratorio</li><li>Molinos de perlas</li><li>Agitadores de alta velocidad</li><li>Homogeneizadores</li><li>Equipos de escala piloto</li></ul><h3>Sectores de Aplicación</h3><ul><li>Pinturas y tintas</li><li>Cosméticos</li><li>Farmacéutica</li><li>Química fina</li><li>Investigación y desarrollo</li></ul>',
            'excerpt' => 'Equipos para ensayos de dispersión, molinos y agitadores.',
        ),
    );
    
    foreach ( $productos as $index => $producto ) {
        $post_id = wp_insert_post( array(
            'post_title'   => $producto['title'],
            'post_content' => $producto['content'],
            'post_excerpt' => $producto['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'producto',
            'menu_order'   => $index + 1,
        ) );
        
        if ( ! is_wp_error( $post_id ) ) {
            // Aquí podrías agregar una imagen destacada si tienes las URLs de las imágenes
            // set_post_thumbnail( $post_id, $attachment_id );
        }
    }
}

// Activar el plugin y crear productos
register_activation_hook( __FILE__, 'tdc_create_demo_products' );

// Función para forzar actualización de productos
function tdc_force_update_products() {
    if ( isset( $_GET['tdc_update_products'] ) && current_user_can( 'manage_options' ) ) {
        delete_option( 'tdc_demo_products_created' );
        tdc_create_demo_products();
        update_option( 'tdc_demo_products_created', 'yes' );
        wp_redirect( admin_url( 'edit.php?post_type=producto&updated=1' ) );
        exit;
    }
}
add_action( 'admin_init', 'tdc_force_update_products' );

// También crear productos si se accede por primera vez o si no existen
add_action( 'init', function() {
    if ( get_option( 'tdc_demo_products_created' ) !== 'yes' ) {
        tdc_create_demo_products();
        update_option( 'tdc_demo_products_created', 'yes' );
    }
}, 20 );

// Agregar botón de actualización en el admin
add_action( 'admin_notices', function() {
    $screen = get_current_screen();
    if ( $screen->post_type === 'producto' ) {
        echo '<div class="notice notice-info"><p>';
        echo '<strong>¿Necesitas actualizar los productos?</strong> ';
        echo '<a href="' . admin_url( 'edit.php?post_type=producto&tdc_update_products=1' ) . '" class="button">Actualizar productos demo</a>';
        echo '</p></div>';
    }
} );
