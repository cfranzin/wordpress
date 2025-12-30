<?php
/**
 * Tecnología del Color Functions
 *
 * @package Tecnologia_del_Color
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function tdc_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Menú Principal', 'tecnologia-del-color' ),
        'footer'  => __( 'Menú Footer', 'tecnologia-del-color' ),
    ) );
    
    // Add image sizes
    add_image_size( 'tdc-hero', 1920, 1080, true );
    add_image_size( 'tdc-service', 600, 400, true );
    add_image_size( 'tdc-client', 300, 200, true );
}
add_action( 'after_setup_theme', 'tdc_theme_setup' );

/**
 * Enqueue styles and scripts
 */
function tdc_enqueue_assets() {
    // Styles
    wp_enqueue_style( 'tdc-style', get_stylesheet_uri(), array(), '1.0.0' );
    wp_enqueue_style( 'tdc-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );
    
    // Scripts
    wp_enqueue_script( 'tdc-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), '1.0.0', true );
    
    // Localize script
    wp_localize_script( 'tdc-main', 'tdcData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'tdc-nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'tdc_enqueue_assets' );

/**
 * Register widget areas
 */
function tdc_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer 1', 'tecnologia-del-color' ),
        'id'            => 'footer-1',
        'description'   => __( 'Primera columna del footer', 'tecnologia-del-color' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 2', 'tecnologia-del-color' ),
        'id'            => 'footer-2',
        'description'   => __( 'Segunda columna del footer', 'tecnologia-del-color' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 3', 'tecnologia-del-color' ),
        'id'            => 'footer-3',
        'description'   => __( 'Tercera columna del footer', 'tecnologia-del-color' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 4', 'tecnologia-del-color' ),
        'id'            => 'footer-4',
        'description'   => __( 'Cuarta columna del footer', 'tecnologia-del-color' ),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'tdc_widgets_init' );

/**
 * Custom Post Type: Servicios
 */
function tdc_register_services_post_type() {
    $labels = array(
        'name'               => __( 'Servicios', 'tecnologia-del-color' ),
        'singular_name'      => __( 'Servicio', 'tecnologia-del-color' ),
        'add_new'            => __( 'Añadir Nuevo', 'tecnologia-del-color' ),
        'add_new_item'       => __( 'Añadir Nuevo Servicio', 'tecnologia-del-color' ),
        'edit_item'          => __( 'Editar Servicio', 'tecnologia-del-color' ),
        'new_item'           => __( 'Nuevo Servicio', 'tecnologia-del-color' ),
        'view_item'          => __( 'Ver Servicio', 'tecnologia-del-color' ),
        'search_items'       => __( 'Buscar Servicios', 'tecnologia-del-color' ),
        'not_found'          => __( 'No se encontraron servicios', 'tecnologia-del-color' ),
        'not_found_in_trash' => __( 'No se encontraron servicios en la papelera', 'tecnologia-del-color' ),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'servicios' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );
    
    register_post_type( 'servicio', $args );
}
add_action( 'init', 'tdc_register_services_post_type' );

/**
 * Custom Post Type: Productos
 */
function tdc_register_products_post_type() {
    $labels = array(
        'name'               => __( 'Productos', 'tecnologia-del-color' ),
        'singular_name'      => __( 'Producto', 'tecnologia-del-color' ),
        'add_new'            => __( 'Añadir Nuevo', 'tecnologia-del-color' ),
        'add_new_item'       => __( 'Añadir Nuevo Producto', 'tecnologia-del-color' ),
        'edit_item'          => __( 'Editar Producto', 'tecnologia-del-color' ),
        'new_item'           => __( 'Nuevo Producto', 'tecnologia-del-color' ),
        'view_item'          => __( 'Ver Producto', 'tecnologia-del-color' ),
        'search_items'       => __( 'Buscar Productos', 'tecnologia-del-color' ),
        'not_found'          => __( 'No se encontraron productos', 'tecnologia-del-color' ),
        'not_found_in_trash' => __( 'No se encontraron productos en la papelera', 'tecnologia-del-color' ),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'productos' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-products',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );
    
    register_post_type( 'producto', $args );
}
add_action( 'init', 'tdc_register_products_post_type' );

/**
 * Custom Post Type: Clientes
 */
function tdc_register_clients_post_type() {
    $labels = array(
        'name'               => __( 'Clientes', 'tecnologia-del-color' ),
        'singular_name'      => __( 'Cliente', 'tecnologia-del-color' ),
        'add_new'            => __( 'Añadir Nuevo', 'tecnologia-del-color' ),
        'add_new_item'       => __( 'Añadir Nuevo Cliente', 'tecnologia-del-color' ),
        'edit_item'          => __( 'Editar Cliente', 'tecnologia-del-color' ),
        'new_item'           => __( 'Nuevo Cliente', 'tecnologia-del-color' ),
        'view_item'          => __( 'Ver Cliente', 'tecnologia-del-color' ),
        'search_items'       => __( 'Buscar Clientes', 'tecnologia-del-color' ),
        'not_found'          => __( 'No se encontraron clientes', 'tecnologia-del-color' ),
        'not_found_in_trash' => __( 'No se encontraron clientes en la papelera', 'tecnologia-del-color' ),
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'clientes' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'thumbnail' ),
    );
    
    register_post_type( 'cliente', $args );
}
add_action( 'init', 'tdc_register_clients_post_type' );

/**
 * Add custom meta boxes
 */
function tdc_add_contact_meta_boxes() {
    add_meta_box(
        'tdc_contact_info',
        __( 'Información de Contacto', 'tecnologia-del-color' ),
        'tdc_contact_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tdc_add_contact_meta_boxes' );

function tdc_contact_meta_box_callback( $post ) {
    wp_nonce_field( 'tdc_save_contact_meta', 'tdc_contact_nonce' );
    
    $phone = get_post_meta( $post->ID, '_tdc_phone', true );
    $whatsapp = get_post_meta( $post->ID, '_tdc_whatsapp', true );
    $email = get_post_meta( $post->ID, '_tdc_email', true );
    $address = get_post_meta( $post->ID, '_tdc_address', true );
    
    ?>
    <p>
        <label for="tdc_phone"><?php _e( 'Teléfono:', 'tecnologia-del-color' ); ?></label>
        <input type="text" id="tdc_phone" name="tdc_phone" value="<?php echo esc_attr( $phone ); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="tdc_whatsapp"><?php _e( 'WhatsApp:', 'tecnologia-del-color' ); ?></label>
        <input type="text" id="tdc_whatsapp" name="tdc_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="tdc_email"><?php _e( 'Email:', 'tecnologia-del-color' ); ?></label>
        <input type="email" id="tdc_email" name="tdc_email" value="<?php echo esc_attr( $email ); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="tdc_address"><?php _e( 'Dirección:', 'tecnologia-del-color' ); ?></label>
        <textarea id="tdc_address" name="tdc_address" rows="3" style="width: 100%;"><?php echo esc_textarea( $address ); ?></textarea>
    </p>
    <?php
}

function tdc_save_contact_meta( $post_id ) {
    if ( ! isset( $_POST['tdc_contact_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['tdc_contact_nonce'], 'tdc_save_contact_meta' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['tdc_phone'] ) ) {
        update_post_meta( $post_id, '_tdc_phone', sanitize_text_field( $_POST['tdc_phone'] ) );
    }
    
    if ( isset( $_POST['tdc_whatsapp'] ) ) {
        update_post_meta( $post_id, '_tdc_whatsapp', sanitize_text_field( $_POST['tdc_whatsapp'] ) );
    }
    
    if ( isset( $_POST['tdc_email'] ) ) {
        update_post_meta( $post_id, '_tdc_email', sanitize_email( $_POST['tdc_email'] ) );
    }
    
    if ( isset( $_POST['tdc_address'] ) ) {
        update_post_meta( $post_id, '_tdc_address', sanitize_textarea_field( $_POST['tdc_address'] ) );
    }
}
add_action( 'save_post', 'tdc_save_contact_meta' );

/**
 * Customizer settings
 */
function tdc_customize_register( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'tdc_contact_section', array(
        'title'    => __( 'Información de Contacto', 'tecnologia-del-color' ),
        'priority' => 30,
    ) );
    
    // WhatsApp number
    $wp_customize->add_setting( 'tdc_whatsapp', array(
        'default'           => '5491132832399',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'tdc_whatsapp', array(
        'label'   => __( 'Número de WhatsApp', 'tecnologia-del-color' ),
        'section' => 'tdc_contact_section',
        'type'    => 'text',
    ) );
    
    // Phone number
    $wp_customize->add_setting( 'tdc_phone', array(
        'default'           => '(54-11) 4761-2300',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'tdc_phone', array(
        'label'   => __( 'Teléfono', 'tecnologia-del-color' ),
        'section' => 'tdc_contact_section',
        'type'    => 'text',
    ) );
    
    // Address
    $wp_customize->add_setting( 'tdc_address', array(
        'default'           => 'Bernardo de Irigoyen 1717, B1604AFQ Florida, Buenos Aires, Argentina',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'tdc_address', array(
        'label'   => __( 'Dirección', 'tecnologia-del-color' ),
        'section' => 'tdc_contact_section',
        'type'    => 'textarea',
    ) );
}
add_action( 'customize_register', 'tdc_customize_register' );

/**
 * Contact form AJAX handler
 */
function tdc_contact_form_handler() {
    check_ajax_referer( 'tdc-nonce', 'nonce' );
    
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
    
    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => __( 'Por favor complete todos los campos requeridos.', 'tecnologia-del-color' ) ) );
    }
    
    $to = get_option( 'admin_email' );
    $subject = sprintf( __( 'Nuevo mensaje de contacto desde %s', 'tecnologia-del-color' ), get_bloginfo( 'name' ) );
    $body = sprintf(
        "Nombre: %s\nEmail: %s\nTeléfono: %s\n\nMensaje:\n%s",
        $name,
        $email,
        $phone,
        $message
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_send_json_success( array( 'message' => __( '¡Mensaje enviado correctamente! Nos pondremos en contacto pronto.', 'tecnologia-del-color' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Error al enviar el mensaje. Por favor intente nuevamente.', 'tecnologia-del-color' ) ) );
    }
}
add_action( 'wp_ajax_tdc_contact_form', 'tdc_contact_form_handler' );
add_action( 'wp_ajax_nopriv_tdc_contact_form', 'tdc_contact_form_handler' );

/**
 * Agregar productos automáticamente al menú como subítems
 */
function tdc_add_products_to_menu( $items, $args ) {
    // Solo aplicar al menú principal
    if ( $args->theme_location !== 'primary' ) {
        return $items;
    }
    
    // Buscar el ítem "Productos" en el menú
    $menu_items = wp_get_nav_menu_items( $args->menu );
    $productos_item = null;
    $productos_id = 0;
    
    if ( $menu_items ) {
        foreach ( $menu_items as $item ) {
            // Buscar por título o URL que contenga "productos"
            if ( stripos( $item->title, 'Productos' ) !== false || 
                 stripos( $item->url, 'productos' ) !== false ) {
                $productos_item = $item;
                $productos_id = $item->ID;
                break;
            }
        }
    }
    
    // Si no encontramos el ítem "Productos", retornar sin modificar
    if ( ! $productos_id ) {
        return $items;
    }
    
    // Obtener todos los productos publicados
    $productos = get_posts( array(
        'post_type'      => 'producto',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );
    
    if ( empty( $productos ) ) {
        return $items;
    }
    
    // Construir submenú de productos
    $submenu_items = '';
    foreach ( $productos as $producto ) {
        $submenu_items .= '<li class="menu-item menu-item-type-post_type menu-item-object-producto">';
        $submenu_items .= '<a href="' . get_permalink( $producto->ID ) . '">' . esc_html( $producto->post_title ) . '</a>';
        $submenu_items .= '</li>';
    }
    
    // Agregar clase para indicar que tiene submenú
    $items = str_replace(
        'id="menu-item-' . $productos_id . '"',
        'id="menu-item-' . $productos_id . '" class="menu-item-has-children"',
        $items
    );
    
    // Insertar el submenú después del ítem Productos
    $items = str_replace(
        'id="menu-item-' . $productos_id . '"><a',
        'id="menu-item-' . $productos_id . '"><a',
        $items
    );
    
    // Buscar el cierre del <li> del ítem Productos e insertar el submenú antes
    $pattern = '/(id="menu-item-' . $productos_id . '"[^>]*>.*?<\/a>)/s';
    $replacement = '$1<ul class="sub-menu">' . $submenu_items . '</ul>';
    $items = preg_replace( $pattern, $replacement, $items );
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'tdc_add_products_to_menu', 10, 2 );

/**
 * Agregar CSS para submenús de productos
 */
function tdc_submenu_styles() {
    ?>
    <style>
        .main-navigation .sub-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--color-white);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            min-width: 250px;
            z-index: 999;
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }
        
        .main-navigation .menu-item-has-children {
            position: relative;
        }
        
        .main-navigation .menu-item-has-children:hover .sub-menu {
            display: block;
        }
        
        .main-navigation .sub-menu li {
            padding: 0;
        }
        
        .main-navigation .sub-menu a {
            display: block;
            padding: 12px 25px;
            color: var(--color-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .main-navigation .sub-menu a:hover {
            background: var(--color-light);
            color: var(--color-accent);
            border-left-color: var(--color-accent);
        }
        
        @media (max-width: 768px) {
            .main-navigation .sub-menu {
                position: static;
                display: none;
                box-shadow: none;
                background: rgba(0, 51, 102, 0.05);
                padding: 10px 0;
            }
            
            .main-navigation .menu-item-has-children.active .sub-menu {
                display: block;
            }
            
            .main-navigation .sub-menu a {
                padding: 10px 20px 10px 40px;
                font-size: 14px;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'tdc_submenu_styles' );
