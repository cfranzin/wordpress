# Copilot Instructions for WordPress Core Codebase

## Overview
WordPress core is a monolithic PHP application with a hook-based extensibility system. The codebase uses procedural PHP with global variables and no build step. Changes go live immediately after file save.

## Request Lifecycle
1. `index.php` → `wp-blog-header.php` loads environment
2. `wp-load.php` finds and includes `wp-config.php` (DB credentials, constants)
3. `wp-settings.php` loads core libraries in order (~50+ files from `wp-includes/`)
4. `wp()` sets up query from URL
5. `template-loader.php` determines and loads theme template

## Hooks System (Core Architecture)
WordPress extensibility is built on hooks. **Never modify core files directly.**

**Add functionality:**
```php
// Action hook (no return value)
add_action( 'init', 'my_function' );
function my_function() { /* ... */ }

// Filter hook (modifies values)
add_filter( 'the_content', 'my_filter' );
function my_filter( $content ) { return $content . 'Added text'; }
```

**Fire hooks in code:**
```php
do_action( 'my_custom_action', $arg1, $arg2 );  // Triggers callbacks
$value = apply_filters( 'my_filter', $value, $arg1 );  // Passes through filters
```

Defined in `wp-includes/plugin.php`. Stored in global `$wp_filter` array.

## Database Access Pattern
Always use `$wpdb` global object. Never write raw SQL strings.

```php
global $wpdb;

// Secure queries with prepare()
$results = $wpdb->get_results( 
    $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d", $post_id ) 
);

// Helper methods
$wpdb->insert( $table, $data, $format );  // INSERT
$wpdb->update( $table, $data, $where, $format, $where_format );  // UPDATE
$wpdb->get_var( $query );  // Single value
$wpdb->get_row( $query );  // Single row
$wpdb->get_results( $query );  // Multiple rows
```

Table names in `$wpdb`: `$wpdb->posts`, `$wpdb->users`, `$wpdb->options`, etc. Full list in `class-wpdb.php`.

## Database Schema Management
- Schema defined in `wp-admin/includes/schema.php`
- `wp_get_db_schema()` returns CREATE TABLE statements
- `populate_options()` sets default options on install
- Add new tables: modify `wp_get_db_schema()` (separate for single/multisite)
- Add new options: modify `$defaults` array in `populate_options()`
- Schema auto-upgrades when `$wp_db_version` increments

## Security & Data Sanitization
**Always escape output, sanitize input, validate everything:**
```php
// Output escaping
esc_html( $text );              // HTML content
esc_attr( $text );              // HTML attributes
esc_url( $url );                // URLs
esc_js( $text );                // JavaScript

// Input sanitization
sanitize_text_field( $input );  // Basic text
wp_unslash( $input );           // Remove magic quotes
wp_kses( $html, $allowed );     // Strip unwanted HTML

// Nonces (use for ALL forms and AJAX)
wp_nonce_field( 'action_name' );          // Output in form
check_admin_referer( 'action_name' );     // Verify in handler
```

## AJAX Pattern
Register handlers via `wp_ajax_` hooks. See `wp-admin/admin-ajax.php` and `wp-admin/includes/ajax-actions.php`.

```php
// Register AJAX handler (in plugin/theme)
add_action( 'wp_ajax_my_action', 'my_ajax_handler' );         // For logged-in users
add_action( 'wp_ajax_nopriv_my_action', 'my_ajax_handler' );  // For guests

function my_ajax_handler() {
    check_ajax_referer( 'my_nonce' );  // Security check
    
    // Process request
    $result = array( 'success' => true );
    wp_send_json_success( $result );
}
```

Frontend AJAX calls go to `wp-admin/admin-ajax.php?action=my_action`.

## REST API Pattern
Register routes on `rest_api_init` hook. Namespace all custom routes.

```php
add_action( 'rest_api_init', 'register_my_routes' );
function register_my_routes() {
    register_rest_route( 'myplugin/v1', '/items', array(
        'methods'             => 'GET',
        'callback'            => 'get_items',
        'permission_callback' => '__return_true',  // REQUIRED
    ) );
}
```

Core REST endpoints in `wp-includes/rest-api/endpoints/`. See `wp-includes/rest-api.php`.

## Caching Pattern
Use WordPress object cache API. Persistent with Redis/Memcached, or transient in-memory.

```php
// Object cache (fast, request-scoped by default)
wp_cache_set( 'key', $data, 'group', $expire_seconds );
$data = wp_cache_get( 'key', 'group' );

// Transients (persistent across requests, DB-backed)
set_transient( 'key', $data, $expire_seconds );
$data = get_transient( 'key' );

// Cache invalidation
wp_cache_set_last_changed( 'posts' );  // Timestamp-based invalidation
```

## Multisite Functions
When `is_multisite()` returns true, use multisite APIs from `wp-includes/ms-functions.php`:

```php
switch_to_blog( $blog_id );   // Change context to different site
// ... operate on other site ...
restore_current_blog();        // Restore original context

get_blogs_of_user( $user_id ); // Sites user belongs to
```

## Plugin Structure
Minimum plugin file (`wp-content/plugins/my-plugin.php`):
```php
<?php
/*
Plugin Name: My Plugin
Description: Plugin description
Version: 1.0
*/

// Security check
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

// Hook into WordPress
add_action( 'init', 'my_plugin_init' );
function my_plugin_init() {
    // Plugin logic here
}
```

See `hello.php` for reference plugin. Activate via WP Admin → Plugins.

## Admin Area Conventions
- Admin pages in `wp-admin/`
- Check capabilities: `current_user_can( 'edit_posts' )` before sensitive operations
- Examples: `edit-form-comment.php`, `edit-link-form.php`, `admin-ajax.php`

## Global Variables (Core Pattern)
WordPress relies heavily on globals. Must declare before use in functions:
```php
global $wpdb;        // Database object
global $wp_roles;    // Roles/capabilities
global $wp_filter;   // Hooks registry
global $wp_query;    // Main query object
```

## Localization
All user-facing strings must be translatable:
```php
__( 'Text', 'textdomain' );              // Returns translated string
_e( 'Text', 'textdomain' );              // Echoes translated string
_x( 'Text', 'context', 'textdomain' );   // Translate with context
esc_html__( 'Text', 'textdomain' );      // Escape and translate
```

## Debugging
In `wp-config.php`:
```php
define( 'WP_DEBUG', true );              // Enable debug mode
define( 'WP_DEBUG_DISPLAY', false );     // Don't display errors
define( 'WP_DEBUG_LOG', true );          // Log to wp-content/debug.log
```

Use `error_log( print_r( $var, true ) )` for debugging output.

## Key Files Reference
- `wp-load.php` - Bootstrap entry
- `wp-settings.php` - Core initialization sequence (loads ~50+ files)
- `wp-includes/plugin.php` - Hook system implementation
- `wp-includes/class-wpdb.php` - Database abstraction layer
- `wp-includes/rest-api.php` - REST API registration
- `wp-includes/cache.php` - Object cache API
- `wp-includes/ms-functions.php` - Multisite utilities
- `wp-admin/includes/schema.php` - Database schema and default options
- `wp-admin/admin-ajax.php` - AJAX request router
- `wp-content/plugins/hello.php` - Example plugin
