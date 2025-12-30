# Tecnología del Color - WordPress Theme

Tema personalizado de WordPress para **Tecnología del Color**, empresa líder en instrumentación, ensayo y servicios para todas las industrias.

## Características

### ✨ Funcionalidades Principales
- **Diseño Responsive**: Completamente adaptable a dispositivos móviles, tablets y escritorio
- **Custom Post Types**: Servicios, Productos y Clientes con gestión individual
- **Formulario de Contacto**: Sistema de contacto integrado con envío por email y AJAX
- **WhatsApp Float Button**: Botón flotante para contacto directo por WhatsApp
- **Certificaciones ISO**: Sección destacada para certificaciones de calidad
- **Redes Sociales**: Integración con LinkedIn, Facebook e Instagram

### 🎨 Características de Diseño
- Colores corporativos personalizados (azul primario #003366, naranja acento #ff6600)
- Tipografía moderna y profesional
- Animaciones suaves en hover y scroll
- Hero section impactante con degradados
- Grids responsivos para servicios, productos y clientes
- Footer con múltiples widgets personalizables

### 📱 Templates Incluidos
1. **Home Page** (`template-home.php`) - Página de inicio con hero, servicios, clientes y CTA
2. **Services Page** (`template-services.php`) - Listado completo de servicios
3. **Contact Page** (`template-contact.php`) - Formulario de contacto con información
4. **Single Post** (`single.php`) - Posts individuales
5. **Default Page** (`page.php`) - Páginas estáticas

### 🔧 Custom Post Types
- **Servicios**: Para gestionar los servicios de la empresa
- **Productos**: Para el catálogo de productos e instrumentación
- **Clientes**: Para mostrar logos y testimonios de clientes

## Instalación

1. **Copiar el tema**: Coloca la carpeta `tecnologia-del-color` en `wp-content/themes/`

2. **Activar el tema**: 
   - Ve a Apariencia → Temas en el panel de WordPress
   - Activa "Tecnología del Color"

3. **Configurar el menú**:
   - Ve a Apariencia → Menús
   - Crea un menú con las siguientes páginas: Inicio, Productos, Servicios, Novedades, Contacto, Clientes
   - Asigna el menú a la ubicación "Menú Principal"

4. **Crear páginas necesarias**:
   - Inicio (asignar template "Página de Inicio")
   - Servicios (asignar template "Página de Servicios")
   - Contacto (asignar template "Página de Contacto")
   - Productos
   - Novedades (Blog)
   - Clientes

5. **Configurar el Customizer**:
   - Ve a Apariencia → Personalizar → Información de Contacto
   - Configura:
     - Número de WhatsApp: 5491132832399
     - Teléfono: (54-11) 4761-2300
     - Dirección: Bernardo de Irigoyen 1717, B1604AFQ Florida, Buenos Aires, Argentina

6. **Añadir contenido**:
   - Crea servicios en el menú "Servicios"
   - Añade productos en "Productos"
   - Sube logos de clientes en "Clientes"

## Uso

### Crear un Servicio
1. Ve a Servicios → Añadir Nuevo
2. Escribe el título (ej: "Servicio Técnico")
3. Añade la descripción completa en el editor
4. Sube una imagen destacada (recomendado: 600x400px)
5. Publica

### Crear un Producto
1. Ve a Productos → Añadir Nuevo
2. Añade título, descripción e imagen
3. Publica

### Crear un Cliente
1. Ve a Clientes → Añadir Nuevo
2. Añade el nombre del cliente como título
3. Sube el logo como imagen destacada (recomendado: 300x200px)
4. Publica

### Personalizar Colores y Estilos
Los colores principales están definidos en `style.css` usando variables CSS:
```css
:root {
    --color-primary: #003366;    /* Azul corporativo */
    --color-secondary: #0066cc;  /* Azul secundario */
    --color-accent: #ff6600;     /* Naranja acento */
}
```

### Widgets en Footer
El tema tiene 4 áreas de widgets en el footer. Para configurarlas:
1. Ve a Apariencia → Widgets
2. Arrastra widgets a las áreas Footer 1, 2, 3 y 4

## Estructura de Archivos

```
tecnologia-del-color/
├── style.css                 # Estilos principales del tema
├── functions.php             # Funciones y configuración del tema
├── header.php                # Cabecera del sitio
├── footer.php                # Pie de página
├── index.php                 # Template principal
├── single.php                # Post individual
├── page.php                  # Página estática
├── template-home.php         # Template página de inicio
├── template-services.php     # Template página de servicios
├── template-contact.php      # Template página de contacto
├── assets/
│   ├── css/
│   │   └── main.css         # Estilos adicionales
│   └── js/
│       └── main.js          # JavaScript del tema
├── template-parts/
│   ├── content.php          # Template para contenido de posts
│   └── content-none.php     # Template sin resultados
└── inc/                     # Archivos de inclusión adicionales
```

## Personalización Avanzada

### Modificar el Hero Section
Edita `template-home.php` y modifica la sección `.hero-section` con tu contenido.

### Añadir más Custom Post Types
Edita `functions.php` y añade nuevos post types siguiendo el patrón de `tdc_register_services_post_type()`.

### Cambiar el formulario de contacto
El handler AJAX está en `functions.php` - función `tdc_contact_form_handler()`.
El formulario está en `template-contact.php`.
El JavaScript está en `assets/js/main.js`.

## Soporte y Contacto

Para soporte o consultas sobre el tema:
- Email: info@tecnologiadelcolor.com
- Teléfono: (54-11) 4761-2300
- WhatsApp: +54 9 11 3283-2399

## Créditos

- **Diseño**: Basado en el sitio web de Tecnología del Color
- **Desarrollo**: Tema WordPress personalizado
- **Versión**: 1.0.0
- **Año**: 2025

## Licencia

Este tema es propiedad de Tecnología del Color y está diseñado específicamente para su uso.

---

**Tecnología del Color** - 25 años de experiencia al servicio de la industria
