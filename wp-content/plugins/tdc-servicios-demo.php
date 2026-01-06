<?php
/**
 * Plugin Name: TDC Servicios Demo Data
 * Description: Crea servicios de demostración para Tecnología del Color
 * Version: 1.0
 * Author: TDC Team
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Crear servicios de demostración
 */
function tdc_create_demo_services() {
    // Eliminar servicios existentes primero
    $existing_services = get_posts( array(
        'post_type'   => 'servicio',
        'numberposts' => -1,
        'post_status' => 'any',
    ) );
    
    foreach ( $existing_services as $service ) {
        wp_delete_post( $service->ID, true );
    }
    
    $servicios = array(
        array(
            'title'   => 'CALIBRACIÓN',
            'subtitle' => 'SERVICIO TÉCNICO',
            'excerpt' => 'Calibración, certificaciones, mantenimiento y reparaciones de equipos.',
            'content' => '<h2>SERVICIO TÉCNICO DE CALIBRACIÓN</h2>

<p>Ofrecemos servicios integrales de calibración con certificados trazables a patrones nacionales e internacionales, garantizando la precisión y confiabilidad de sus instrumentos de medición.</p>

<h3>Servicios de Calibración</h3>
<ul>
<li>Calibración de espectrofotómetros y colorímetros</li>
<li>Calibración de brillómetros</li>
<li>Calibración de medidores de espesor</li>
<li>Calibración de balanzas y equipos de pesaje</li>
<li>Calibración de pH-metros y conductímetros</li>
<li>Certificados con trazabilidad INTI/OAA</li>
</ul>

<h3>Mantenimiento Preventivo</h3>
<ul>
<li>Inspección y diagnóstico de equipos</li>
<li>Limpieza y ajuste de componentes</li>
<li>Actualización de firmware</li>
<li>Verificación de funcionamiento</li>
<li>Reporte técnico completo</li>
</ul>

<h3>Reparaciones</h3>
<ul>
<li>Diagnóstico y presupuesto sin cargo</li>
<li>Reparación de equipos de todas las marcas</li>
<li>Repuestos originales</li>
<li>Garantía de servicio</li>
</ul>

<h3>Beneficios</h3>
<ul>
<li>Cumplimiento de normativas ISO 9001, ISO 17025</li>
<li>Trazabilidad completa</li>
<li>Servicio técnico especializado</li>
<li>Soporte post-calibración</li>
</ul>',
        ),
        array(
            'title'   => 'CAPACITACIÓN',
            'subtitle' => 'CURSOS',
            'excerpt' => 'Cursos de capacitación abiertos y cursos diseñados a medida.',
            'content' => '<h2>PROGRAMAS DE CAPACITACIÓN</h2>

<p>Brindamos capacitación técnica especializada para empresas e instituciones, con programas diseñados para mejorar las competencias de su personal en medición, control de calidad y ensayos de materiales.</p>

<h3>Cursos Abiertos</h3>
<ul>
<li>Fundamentos de medición de color</li>
<li>Gestión de color en procesos industriales</li>
<li>Interpretación de resultados de ensayos</li>
<li>Normativas ISO aplicadas al control de calidad</li>
<li>Buenas prácticas de laboratorio (GLP)</li>
</ul>

<h3>Cursos In-Company</h3>
<ul>
<li>Programas diseñados según necesidades específicas</li>
<li>Capacitación en planta</li>
<li>Formación práctica con equipos propios</li>
<li>Materiales didácticos personalizados</li>
<li>Certificado de asistencia</li>
</ul>

<h3>Temas Especializados</h3>
<ul>
<li>Colorimetría y control de color</li>
<li>Ensayos de envejecimiento acelerado</li>
<li>Ensayos de abrasión y resistencia</li>
<li>Dosificación y formulación de pinturas</li>
<li>Calibración y mantenimiento de equipos</li>
</ul>

<h3>Modalidades</h3>
<ul>
<li>Presencial en nuestras instalaciones</li>
<li>In-company en sus instalaciones</li>
<li>Virtual sincrónico</li>
<li>Modalidad mixta</li>
</ul>',
        ),
        array(
            'title'   => 'ENSAYOS',
            'subtitle' => 'LABORATORIO DE ENSAYOS',
            'excerpt' => 'Servicio de ensayos físicos sobre materiales a pedido.',
            'content' => '<h2>LABORATORIO DE ENSAYOS</h2>

<p>Nuestro laboratorio está equipado con tecnología de última generación para realizar ensayos físicos sobre materiales, garantizando resultados confiables y reproducibles para el control de calidad y desarrollo de productos.</p>

<h3>Ensayos de Color y Apariencia</h3>
<ul>
<li>Medición de color (CIELAB, RGB, etc.)</li>
<li>Diferencia de color (ΔE)</li>
<li>Medición de brillo (multiángulo)</li>
<li>Medición de opacidad</li>
<li>Evaluación visual de color</li>
</ul>

<h3>Ensayos de Resistencia</h3>
<ul>
<li>Ensayos de envejecimiento acelerado (QUV, Q-SUN)</li>
<li>Resistencia a la niebla salina</li>
<li>Ensayos de abrasión (Taber)</li>
<li>Resistencia química</li>
<li>Resistencia al impacto</li>
</ul>

<h3>Ensayos Físicos</h3>
<ul>
<li>Espesor de película húmeda y seca</li>
<li>Adherencia (cross-hatch, pull-off)</li>
<li>Dureza (lápiz, péndulo)</li>
<li>Flexibilidad y plegado</li>
<li>Viscosidad y densidad</li>
</ul>

<h3>Características del Servicio</h3>
<ul>
<li>Informes técnicos detallados</li>
<li>Resultados según normas ASTM/ISO</li>
<li>Tiempos de respuesta acordados</li>
<li>Confidencialidad garantizada</li>
<li>Asesoramiento técnico incluido</li>
</ul>',
        ),
    );
    
    foreach ( $servicios as $index => $servicio ) {
        $post_id = wp_insert_post( array(
            'post_title'   => $servicio['title'],
            'post_content' => $servicio['content'],
            'post_excerpt' => $servicio['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'servicio',
            'menu_order'   => $index + 1,
        ) );
        
        if ( ! is_wp_error( $post_id ) ) {
            // Guardar el subtítulo como meta
            update_post_meta( $post_id, '_tdc_service_subtitle', $servicio['subtitle'] );
        }
    }
}

// Activar el plugin y crear servicios
register_activation_hook( __FILE__, 'tdc_create_demo_services' );

// Función para forzar actualización de servicios
function tdc_force_update_services() {
    if ( isset( $_GET['tdc_update_services'] ) && current_user_can( 'manage_options' ) ) {
        delete_option( 'tdc_demo_services_created' );
        tdc_create_demo_services();
        update_option( 'tdc_demo_services_created', 'yes' );
        wp_redirect( admin_url( 'edit.php?post_type=servicio&updated=1' ) );
        exit;
    }
}
add_action( 'admin_init', 'tdc_force_update_services' );

// También crear servicios si se accede por primera vez o si no existen
add_action( 'init', function() {
    if ( get_option( 'tdc_demo_services_created' ) !== 'yes' ) {
        tdc_create_demo_services();
        update_option( 'tdc_demo_services_created', 'yes' );
    }
}, 20 );

// Agregar botón de actualización en el admin
add_action( 'admin_notices', function() {
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'servicio' ) {
        echo '<div class="notice notice-info"><p>';
        echo '<strong>¿Necesitas actualizar los servicios?</strong> ';
        echo '<a href="' . admin_url( 'edit.php?post_type=servicio&tdc_update_services=1' ) . '" class="button">Actualizar servicios demo</a>';
        echo '</p></div>';
    }
} );
