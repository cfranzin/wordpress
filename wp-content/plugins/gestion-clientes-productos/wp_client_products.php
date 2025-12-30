<?php
/**
 * Plugin Name: Gestión de Clientes y Productos
 * Plugin URI: https://tuempresa.com
 * Description: Sistema de gestión de clientes, productos y certificados
 * Version: 1.0.0
 * Author: Tu Nombre
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

class GestionClientesProductos {
    
    public function __construct() {
        register_activation_hook(__FILE__, array($this, 'activar_plugin'));
        add_action('init', array($this, 'registrar_post_types'));
        add_action('admin_menu', array($this, 'agregar_menu_admin'));
        add_action('add_meta_boxes', array($this, 'agregar_meta_boxes'));
        add_action('save_post', array($this, 'guardar_meta_boxes'));
        add_shortcode('portal_cliente', array($this, 'mostrar_portal_cliente'));
        add_action('wp_ajax_cliente_login', array($this, 'procesar_login'));
        add_action('wp_ajax_nopriv_cliente_login', array($this, 'procesar_login'));
        add_action('wp_ajax_cliente_logout', array($this, 'procesar_logout'));
        add_action('wp_enqueue_scripts', array($this, 'cargar_scripts'));
    }
    
    public function activar_plugin() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();
        
        // Tabla de clientes
        $sql_clientes = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gcp_clientes (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nombre varchar(200) NOT NULL,
            email varchar(100) NOT NULL,
            usuario varchar(50) NOT NULL,
            password varchar(255) NOT NULL,
            activo tinyint(1) DEFAULT 1,
            fecha_creacion datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY usuario (usuario)
        ) $charset;";
        
        // Tabla de productos
        $sql_productos = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gcp_productos (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nombre varchar(200) NOT NULL,
            descripcion text,
            fecha_creacion datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";
        
        // Tabla de certificados
        $sql_certificados = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gcp_certificados (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nombre varchar(200) NOT NULL,
            archivo_url varchar(500),
            fecha_emision date,
            fecha_vencimiento date,
            fecha_creacion datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";
        
        // Tabla relación cliente-producto-certificado
        $sql_relaciones = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}gcp_cliente_producto (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            cliente_id mediumint(9) NOT NULL,
            producto_id mediumint(9) NOT NULL,
            certificado_id mediumint(9),
            fecha_asignacion datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY cliente_id (cliente_id),
            KEY producto_id (producto_id),
            KEY certificado_id (certificado_id)
        ) $charset;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_clientes);
        dbDelta($sql_productos);
        dbDelta($sql_certificados);
        dbDelta($sql_relaciones);
    }
    
    public function registrar_post_types() {
        // Este plugin usa tablas personalizadas
    }
    
    public function agregar_menu_admin() {
        add_menu_page(
            'Gestión de Clientes',
            'Clientes y Productos',
            'manage_options',
            'gcp-dashboard',
            array($this, 'pagina_dashboard'),
            'dashicons-groups',
            30
        );
        
        add_submenu_page(
            'gcp-dashboard',
            'Clientes',
            'Clientes',
            'manage_options',
            'gcp-clientes',
            array($this, 'pagina_clientes')
        );
        
        add_submenu_page(
            'gcp-dashboard',
            'Productos',
            'Productos',
            'manage_options',
            'gcp-productos',
            array($this, 'pagina_productos')
        );
        
        add_submenu_page(
            'gcp-dashboard',
            'Certificados',
            'Certificados',
            'manage_options',
            'gcp-certificados',
            array($this, 'pagina_certificados')
        );
        
        add_submenu_page(
            'gcp-dashboard',
            'Asignaciones',
            'Asignaciones',
            'manage_options',
            'gcp-asignaciones',
            array($this, 'pagina_asignaciones')
        );
    }
    
    public function pagina_dashboard() {
        global $wpdb;
        
        $total_clientes = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}gcp_clientes");
        $total_productos = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}gcp_productos");
        $total_certificados = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}gcp_certificados");
        
        ?>
        <div class="wrap">
            <h1>Panel de Control - Gestión de Clientes y Productos</h1>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
                <div style="background: #fff; padding: 20px; border-left: 4px solid #2271b1;">
                    <h2><?php echo $total_clientes; ?></h2>
                    <p>Clientes Registrados</p>
                </div>
                <div style="background: #fff; padding: 20px; border-left: 4px solid #00a32a;">
                    <h2><?php echo $total_productos; ?></h2>
                    <p>Productos</p>
                </div>
                <div style="background: #fff; padding: 20px; border-left: 4px solid #d63638;">
                    <h2><?php echo $total_certificados; ?></h2>
                    <p>Certificados</p>
                </div>
            </div>
            <div style="margin-top: 30px; background: #fff; padding: 20px;">
                <h2>Uso del Portal de Clientes</h2>
                <p>Para mostrar el portal de clientes en una página, usa el shortcode:</p>
                <code style="background: #f0f0f1; padding: 10px; display: block;">[portal_cliente]</code>
            </div>
        </div>
        <?php
    }
    
    public function pagina_clientes() {
        global $wpdb;
        
        // Procesar formulario
        if (isset($_POST['gcp_guardar_cliente']) && check_admin_referer('gcp_cliente_nonce')) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $email = sanitize_email($_POST['email']);
            $usuario = sanitize_user($_POST['usuario']);
            
            if (isset($_POST['cliente_id']) && !empty($_POST['cliente_id'])) {
                // Actualizar
                $data = array('nombre' => $nombre, 'email' => $email, 'usuario' => $usuario);
                if (!empty($_POST['password'])) {
                    $data['password'] = wp_hash_password($_POST['password']);
                }
                $wpdb->update(
                    $wpdb->prefix . 'gcp_clientes',
                    $data,
                    array('id' => intval($_POST['cliente_id']))
                );
                echo '<div class="notice notice-success"><p>Cliente actualizado correctamente.</p></div>';
            } else {
                // Insertar
                $password = wp_hash_password($_POST['password']);
                $wpdb->insert(
                    $wpdb->prefix . 'gcp_clientes',
                    array(
                        'nombre' => $nombre,
                        'email' => $email,
                        'usuario' => $usuario,
                        'password' => $password
                    )
                );
                echo '<div class="notice notice-success"><p>Cliente creado correctamente.</p></div>';
            }
        }
        
        // Eliminar cliente
        if (isset($_GET['eliminar']) && check_admin_referer('gcp_eliminar_' . $_GET['eliminar'])) {
            $wpdb->delete($wpdb->prefix . 'gcp_clientes', array('id' => intval($_GET['eliminar'])));
            echo '<div class="notice notice-success"><p>Cliente eliminado.</p></div>';
        }
        
        $clientes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_clientes ORDER BY fecha_creacion DESC");
        
        ?>
        <div class="wrap">
            <h1>Gestión de Clientes</h1>
            
            <h2>Agregar Nuevo Cliente</h2>
            <form method="post" style="background: #fff; padding: 20px; margin-bottom: 20px;">
                <?php wp_nonce_field('gcp_cliente_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="nombre">Nombre</label></th>
                        <td><input type="text" id="nombre" name="nombre" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" id="email" name="email" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="usuario">Usuario</label></th>
                        <td><input type="text" id="usuario" name="usuario" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="password">Contraseña</label></th>
                        <td><input type="password" id="password" name="password" required class="regular-text"></td>
                    </tr>
                </table>
                <p><input type="submit" name="gcp_guardar_cliente" class="button button-primary" value="Guardar Cliente"></p>
            </form>
            
            <h2>Clientes Registrados</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente->id; ?></td>
                        <td><?php echo esc_html($cliente->nombre); ?></td>
                        <td><?php echo esc_html($cliente->email); ?></td>
                        <td><?php echo esc_html($cliente->usuario); ?></td>
                        <td><?php echo $cliente->fecha_creacion; ?></td>
                        <td>
                            <a href="?page=gcp-clientes&eliminar=<?php echo $cliente->id; ?>&_wpnonce=<?php echo wp_create_nonce('gcp_eliminar_' . $cliente->id); ?>" 
                               onclick="return confirm('¿Estás seguro?')" class="button button-small">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public function pagina_productos() {
        global $wpdb;
        
        if (isset($_POST['gcp_guardar_producto']) && check_admin_referer('gcp_producto_nonce')) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $descripcion = sanitize_textarea_field($_POST['descripcion']);
            
            $wpdb->insert(
                $wpdb->prefix . 'gcp_productos',
                array('nombre' => $nombre, 'descripcion' => $descripcion)
            );
            echo '<div class="notice notice-success"><p>Producto creado correctamente.</p></div>';
        }
        
        if (isset($_GET['eliminar']) && check_admin_referer('gcp_eliminar_prod_' . $_GET['eliminar'])) {
            $wpdb->delete($wpdb->prefix . 'gcp_productos', array('id' => intval($_GET['eliminar'])));
            echo '<div class="notice notice-success"><p>Producto eliminado.</p></div>';
        }
        
        $productos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_productos ORDER BY fecha_creacion DESC");
        
        ?>
        <div class="wrap">
            <h1>Gestión de Productos</h1>
            
            <h2>Agregar Nuevo Producto</h2>
            <form method="post" style="background: #fff; padding: 20px; margin-bottom: 20px;">
                <?php wp_nonce_field('gcp_producto_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="nombre">Nombre del Producto</label></th>
                        <td><input type="text" id="nombre" name="nombre" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="descripcion">Descripción</label></th>
                        <td><textarea id="descripcion" name="descripcion" rows="4" class="large-text"></textarea></td>
                    </tr>
                </table>
                <p><input type="submit" name="gcp_guardar_producto" class="button button-primary" value="Guardar Producto"></p>
            </form>
            
            <h2>Productos Registrados</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto->id; ?></td>
                        <td><?php echo esc_html($producto->nombre); ?></td>
                        <td><?php echo esc_html($producto->descripcion); ?></td>
                        <td><?php echo $producto->fecha_creacion; ?></td>
                        <td>
                            <a href="?page=gcp-productos&eliminar=<?php echo $producto->id; ?>&_wpnonce=<?php echo wp_create_nonce('gcp_eliminar_prod_' . $producto->id); ?>" 
                               onclick="return confirm('¿Estás seguro?')" class="button button-small">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public function pagina_certificados() {
        global $wpdb;
        
        if (isset($_POST['gcp_guardar_certificado']) && check_admin_referer('gcp_certificado_nonce')) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $archivo_url = esc_url_raw($_POST['archivo_url']);
            $fecha_emision = sanitize_text_field($_POST['fecha_emision']);
            $fecha_vencimiento = sanitize_text_field($_POST['fecha_vencimiento']);
            
            $wpdb->insert(
                $wpdb->prefix . 'gcp_certificados',
                array(
                    'nombre' => $nombre,
                    'archivo_url' => $archivo_url,
                    'fecha_emision' => $fecha_emision,
                    'fecha_vencimiento' => $fecha_vencimiento
                )
            );
            echo '<div class="notice notice-success"><p>Certificado creado correctamente.</p></div>';
        }
        
        if (isset($_GET['eliminar']) && check_admin_referer('gcp_eliminar_cert_' . $_GET['eliminar'])) {
            $wpdb->delete($wpdb->prefix . 'gcp_certificados', array('id' => intval($_GET['eliminar'])));
            echo '<div class="notice notice-success"><p>Certificado eliminado.</p></div>';
        }
        
        $certificados = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_certificados ORDER BY fecha_creacion DESC");
        
        ?>
        <div class="wrap">
            <h1>Gestión de Certificados</h1>
            
            <h2>Agregar Nuevo Certificado</h2>
            <form method="post" style="background: #fff; padding: 20px; margin-bottom: 20px;">
                <?php wp_nonce_field('gcp_certificado_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="nombre">Nombre del Certificado</label></th>
                        <td><input type="text" id="nombre" name="nombre" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="archivo_url">URL del Archivo</label></th>
                        <td><input type="url" id="archivo_url" name="archivo_url" class="regular-text" placeholder="https://..."></td>
                    </tr>
                    <tr>
                        <th><label for="fecha_emision">Fecha de Emisión</label></th>
                        <td><input type="date" id="fecha_emision" name="fecha_emision"></td>
                    </tr>
                    <tr>
                        <th><label for="fecha_vencimiento">Fecha de Vencimiento</label></th>
                        <td><input type="date" id="fecha_vencimiento" name="fecha_vencimiento"></td>
                    </tr>
                </table>
                <p><input type="submit" name="gcp_guardar_certificado" class="button button-primary" value="Guardar Certificado"></p>
            </form>
            
            <h2>Certificados Registrados</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha Emisión</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificados as $cert): ?>
                    <tr>
                        <td><?php echo $cert->id; ?></td>
                        <td><?php echo esc_html($cert->nombre); ?></td>
                        <td><?php echo $cert->fecha_emision; ?></td>
                        <td><?php echo $cert->fecha_vencimiento; ?></td>
                        <td>
                            <?php if ($cert->archivo_url): ?>
                            <a href="<?php echo esc_url($cert->archivo_url); ?>" target="_blank" class="button button-small">Ver</a>
                            <?php endif; ?>
                            <a href="?page=gcp-certificados&eliminar=<?php echo $cert->id; ?>&_wpnonce=<?php echo wp_create_nonce('gcp_eliminar_cert_' . $cert->id); ?>" 
                               onclick="return confirm('¿Estás seguro?')" class="button button-small">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public function pagina_asignaciones() {
        global $wpdb;
        
        if (isset($_POST['gcp_asignar']) && check_admin_referer('gcp_asignacion_nonce')) {
            $cliente_id = intval($_POST['cliente_id']);
            $producto_id = intval($_POST['producto_id']);
            $certificado_id = !empty($_POST['certificado_id']) ? intval($_POST['certificado_id']) : null;
            
            $wpdb->insert(
                $wpdb->prefix . 'gcp_cliente_producto',
                array(
                    'cliente_id' => $cliente_id,
                    'producto_id' => $producto_id,
                    'certificado_id' => $certificado_id
                )
            );
            echo '<div class="notice notice-success"><p>Asignación realizada correctamente.</p></div>';
        }
        
        if (isset($_GET['eliminar']) && check_admin_referer('gcp_eliminar_asig_' . $_GET['eliminar'])) {
            $wpdb->delete($wpdb->prefix . 'gcp_cliente_producto', array('id' => intval($_GET['eliminar'])));
            echo '<div class="notice notice-success"><p>Asignación eliminada.</p></div>';
        }
        
        $clientes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_clientes ORDER BY nombre");
        $productos = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_productos ORDER BY nombre");
        $certificados = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gcp_certificados ORDER BY nombre");
        
        $asignaciones = $wpdb->get_results("
            SELECT cp.*, 
                   c.nombre as cliente_nombre,
                   p.nombre as producto_nombre,
                   cert.nombre as certificado_nombre
            FROM {$wpdb->prefix}gcp_cliente_producto cp
            LEFT JOIN {$wpdb->prefix}gcp_clientes c ON cp.cliente_id = c.id
            LEFT JOIN {$wpdb->prefix}gcp_productos p ON cp.producto_id = p.id
            LEFT JOIN {$wpdb->prefix}gcp_certificados cert ON cp.certificado_id = cert.id
            ORDER BY cp.fecha_asignacion DESC
        ");
        
        ?>
        <div class="wrap">
            <h1>Asignación de Productos a Clientes</h1>
            
            <h2>Nueva Asignación</h2>
            <form method="post" style="background: #fff; padding: 20px; margin-bottom: 20px;">
                <?php wp_nonce_field('gcp_asignacion_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="cliente_id">Cliente</label></th>
                        <td>
                            <select id="cliente_id" name="cliente_id" required class="regular-text">
                                <option value="">Seleccionar cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente->id; ?>"><?php echo esc_html($cliente->nombre); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="producto_id">Producto</label></th>
                        <td>
                            <select id="producto_id" name="producto_id" required class="regular-text">
                                <option value="">Seleccionar producto...</option>
                                <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto->id; ?>"><?php echo esc_html($producto->nombre); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="certificado_id">Certificado (opcional)</label></th>
                        <td>
                            <select id="certificado_id" name="certificado_id" class="regular-text">
                                <option value="">Sin certificado</option>
                                <?php foreach ($certificados as $cert): ?>
                                <option value="<?php echo $cert->id; ?>"><?php echo esc_html($cert->nombre); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p><input type="submit" name="gcp_asignar" class="button button-primary" value="Asignar"></p>
            </form>
            
            <h2>Asignaciones Actuales</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Certificado</th>
                        <th>Fecha Asignación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asignaciones as $asig): ?>
                    <tr>
                        <td><?php echo esc_html($asig->cliente_nombre); ?></td>
                        <td><?php echo esc_html($asig->producto_nombre); ?></td>
                        <td><?php echo $asig->certificado_nombre ? esc_html($asig->certificado_nombre) : '-'; ?></td>
                        <td><?php echo $asig->fecha_asignacion; ?></td>
                        <td>
                            <a href="?page=gcp-asignaciones&eliminar=<?php echo $asig->id; ?>&_wpnonce=<?php echo wp_create_nonce('gcp_eliminar_asig_' . $asig->id); ?>" 
                               onclick="return confirm('¿Estás seguro?')" class="button button-small">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public function agregar_meta_boxes() {
        // Reservado para futuras funcionalidades
    }
    
    public function guardar_meta_boxes($post_id) {
        // Reservado para futuras funcionalidades
    }
    
    public function cargar_scripts() {
        wp_enqueue_script('jquery');
    }
    
    public function mostrar_portal_cliente() {
        if (isset($_COOKIE['gcp_cliente_id'])) {
            return $this->mostrar_dashboard_cliente();
        } else {
            return $this->mostrar_formulario_login();
        }
    }
    
    public function mostrar_formulario_login() {
        ob_start();
        ?>
        <div class="gcp-login-container">
            <div class="gcp-login-box">
                <h2>Portal de Clientes</h2>
                <form id="gcp-login-form">
                    <div class="gcp-form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="gcp-usuario" name="usuario" required>
                    </div>
                    <div class="gcp-form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="gcp-password" name="password" required>
                    </div>
                    <div class="gcp-form-group">
                        <button type="submit" class="gcp-btn-primary">Ingresar</button>
                    </div>
                    <div id="gcp-login-message"></div>
                </form>
            </div>
        </div>
        <style>
            .gcp-login-container { max-width: 400px; margin: 50px auto; }
            .gcp-login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .gcp-login-box h2 { text-align: center; margin-bottom: 25px; color: #333; }
            .gcp-form-group { margin-bottom: 20px; }
            .gcp-form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
            .gcp-form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
            .gcp-btn-primary { background: #0073aa; color: #fff; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
            .gcp-btn-primary:hover { background: #005a87; }
            #gcp-login-message { margin-top: 15px; padding: 10px; border-radius: 4px; display: none; }
            .gcp-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; display: block; }
            .gcp-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; display: block; }
        </style>
        <script>
        jQuery(document).ready(function($) {
            $('#gcp-login-form').on('submit', function(e) {
                e.preventDefault();
                var usuario = $('#gcp-usuario').val();
                var password = $('#gcp-password').val();
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'cliente_login',
                        usuario: usuario,
                        password: password
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#gcp-login-message').removeClass('gcp-error').addClass('gcp-success').text(response.data.message).show();
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            $('#gcp-login-message').removeClass('gcp-success').addClass('gcp-error').text(response.data.message).show();
                        }
                    },
                    error: function() {
                        $('#gcp-login-message').removeClass('gcp-success').addClass('gcp-error').text('Error al procesar la solicitud').show();
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    
    public function mostrar_dashboard_cliente() {
        global $wpdb;
        
        $cliente_id = intval($_COOKIE['gcp_cliente_id']);
        $cliente = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}gcp_clientes WHERE id = %d",
            $cliente_id
        ));
        
        if (!$cliente) {
            setcookie('gcp_cliente_id', '', time() - 3600, '/');
            return $this->mostrar_formulario_login();
        }
        
        $productos = $wpdb->get_results($wpdb->prepare("
            SELECT p.*, cp.fecha_asignacion, c.id as certificado_id, c.nombre as certificado_nombre, 
                   c.archivo_url, c.fecha_emision, c.fecha_vencimiento
            FROM {$wpdb->prefix}gcp_cliente_producto cp
            INNER JOIN {$wpdb->prefix}gcp_productos p ON cp.producto_id = p.id
            LEFT JOIN {$wpdb->prefix}gcp_certificados c ON cp.certificado_id = c.id
            WHERE cp.cliente_id = %d
            ORDER BY cp.fecha_asignacion DESC
        ", $cliente_id));
        
        ob_start();
        ?>
        <div class="gcp-dashboard-container">
            <div class="gcp-dashboard-header">
                <div>
                    <h2>Bienvenido, <?php echo esc_html($cliente->nombre); ?></h2>
                    <p>Email: <?php echo esc_html($cliente->email); ?></p>
                </div>
                <button id="gcp-logout-btn" class="gcp-btn-secondary">Cerrar Sesión</button>
            </div>
            
            <div class="gcp-productos-section">
                <h3>Mis Productos y Certificados</h3>
                
                <?php if (empty($productos)): ?>
                    <div class="gcp-no-products">
                        <p>No tienes productos asignados en este momento.</p>
                    </div>
                <?php else: ?>
                    <div class="gcp-productos-grid">
                        <?php foreach ($productos as $producto): ?>
                        <div class="gcp-producto-card">
                            <h4><?php echo esc_html($producto->nombre); ?></h4>
                            <p class="gcp-producto-desc"><?php echo esc_html($producto->descripcion); ?></p>
                            <p class="gcp-fecha-asignacion">
                                <strong>Asignado:</strong> <?php echo date('d/m/Y', strtotime($producto->fecha_asignacion)); ?>
                            </p>
                            
                            <?php if ($producto->certificado_id): ?>
                            <div class="gcp-certificado-info">
                                <h5>📄 Certificado</h5>
                                <p><strong><?php echo esc_html($producto->certificado_nombre); ?></strong></p>
                                <?php if ($producto->fecha_emision): ?>
                                <p><small>Emisión: <?php echo date('d/m/Y', strtotime($producto->fecha_emision)); ?></small></p>
                                <?php endif; ?>
                                <?php if ($producto->fecha_vencimiento): ?>
                                <p><small>Vencimiento: <?php echo date('d/m/Y', strtotime($producto->fecha_vencimiento)); ?></small></p>
                                <?php endif; ?>
                                <?php if ($producto->archivo_url): ?>
                                <a href="<?php echo esc_url($producto->archivo_url); ?>" target="_blank" class="gcp-btn-download">
                                    Descargar Certificado
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div class="gcp-no-certificado">
                                <p><small>Sin certificado asociado</small></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
            .gcp-dashboard-container { max-width: 1200px; margin: 30px auto; padding: 20px; }
            .gcp-dashboard-header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
            .gcp-dashboard-header h2 { margin: 0 0 10px 0; color: #333; }
            .gcp-dashboard-header p { margin: 0; color: #666; }
            .gcp-btn-secondary { background: #666; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
            .gcp-btn-secondary:hover { background: #444; }
            .gcp-productos-section { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .gcp-productos-section h3 { margin-top: 0; color: #333; border-bottom: 2px solid #0073aa; padding-bottom: 10px; }
            .gcp-no-products { text-align: center; padding: 40px; color: #666; }
            .gcp-productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
            .gcp-producto-card { background: #f9f9f9; padding: 20px; border-radius: 8px; border-left: 4px solid #0073aa; }
            .gcp-producto-card h4 { margin: 0 0 10px 0; color: #0073aa; font-size: 18px; }
            .gcp-producto-desc { color: #555; margin-bottom: 15px; font-size: 14px; }
            .gcp-fecha-asignacion { font-size: 13px; color: #666; margin-bottom: 15px; }
            .gcp-certificado-info { background: #fff; padding: 15px; border-radius: 6px; margin-top: 15px; }
            .gcp-certificado-info h5 { margin: 0 0 10px 0; color: #333; font-size: 16px; }
            .gcp-certificado-info p { margin: 5px 0; font-size: 14px; }
            .gcp-btn-download { display: inline-block; background: #00a32a; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; margin-top: 10px; font-size: 14px; }
            .gcp-btn-download:hover { background: #008a20; }
            .gcp-no-certificado { background: #fff3cd; padding: 10px; border-radius: 6px; margin-top: 15px; text-align: center; }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#gcp-logout-btn').on('click', function() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'cliente_logout'
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    
    public function procesar_login() {
        global $wpdb;
        
        $usuario = sanitize_user($_POST['usuario']);
        $password = $_POST['password'];
        
        $cliente = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}gcp_clientes WHERE usuario = %s AND activo = 1",
            $usuario
        ));
        
        if ($cliente && wp_check_password($password, $cliente->password)) {
            setcookie('gcp_cliente_id', $cliente->id, time() + 86400, '/');
            wp_send_json_success(array('message' => 'Login exitoso. Redirigiendo...'));
        } else {
            wp_send_json_error(array('message' => 'Usuario o contraseña incorrectos'));
        }
    }
    
    public function procesar_logout() {
        setcookie('gcp_cliente_id', '', time() - 3600, '/');
        wp_send_json_success();
    }
}

// Inicializar el plugin
new GestionClientesProductos();