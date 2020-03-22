<?php
/**
 * Plugin Name:       WP Documents Library     
 * Description:       This plugin creates the "Documents" page to see a list of the titles of all the Documents and also create the "Thematics" page to view the list of Documents through the  categories.
 * Version:           2.3.2
 * Author:            labarta
 * Author URI:        https://labarta.es
 * License:           GPL-2.0+
 * Text Domain:       wpdlib
 * Domain Path:       /languages
**/


defined( 'ABSPATH' ) or die( '¡Sin trampas!' );

require plugin_dir_path(__FILE__) . '/includes/wpdlib-editor.php';
require plugin_dir_path(__FILE__) . '/includes/wpdlib-lists.php';
require plugin_dir_path(__FILE__) . '/includes/wpdlib-metabox.php';
require plugin_dir_path(__FILE__) . '/includes/wpdlib-taxonomies.php';


/* Añadiendo estilos CSS */

function wpdlib_agregar_estilos () {
    wp_enqueue_style( 'wp-documents-library', plugins_url('/css/wpdlib-documents.css', __FILE__) );
}

add_action( 'wp_enqueue_scripts', 'wpdlib_agregar_estilos' );

/* Cargador imágenes */

function wpdlib_admin_media_scripts() {
        wp_enqueue_media();
        wp_enqueue_script('admin-script', plugins_url('/js/my-script.js',__FILE__), array('jquery'),'1', true );
}

add_action('admin_enqueue_scripts', 'wpdlib_admin_media_scripts');


/* Idiomas */

add_action( 'plugins_loaded', 'wpdlib_plugin_load_textdomain' );

function wpdlib_plugin_load_textdomain() {
load_plugin_textdomain( 'wpdlib', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

/* REGISTRAR CUSTOM POST TYPE */

function wpdlib_documentos_post_type() {

	$labels = array(
		'name'                  => _x( 'Documents', 'Post Type General Name', 'wpdlib' ),
		'singular_name'         => _x( 'Document', 'Post Type Singular Name', 'wpdlib' ),
		'menu_name'             => _x( 'Documents', 'wpdlib' ),
		'name_admin_bar'        => _x( 'Documents', 'wpdlib' ),
		'archives'              => __( 'Document Archives', 'wpdlib' ),
		'attributes'            => __( 'Document Attributes', 'wpdlib' ),
		'parent_item_colon'     => __( 'Parent Document:', 'wpdlib' ),
		'all_items'             => __( 'All Documents', 'wpdlib' ),
		'add_new_item'          => __( 'Add New Document', 'wpdlib' ),
		'add_new'               => __( 'Add New', 'wpdlib' ),
		'new_item'              => __( 'New Document', 'wpdlib' ),
		'edit_item'             => __( 'Edit Document', 'wpdlib' ),
		'update_item'           => __( 'Update Document', 'wpdlib' ),
		'view_item'             => __( 'View Document', 'wpdlib' ),
		'view_items'            => __( 'View Documents', 'wpdlib' ),
		'search_items'          => __( 'Search Document', 'wpdlib' ),
		'not_found'             => __( 'Not found', 'wpdlib' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'wpdlib' ),
		'featured_image'        => __( 'Featured Image', 'wpdlib' ),
		'set_featured_image'    => __( 'Set featured image', 'wpdlib' ),
		'remove_featured_image' => __( 'Remove featured image', 'wpdlib' ),
		'use_featured_image'    => __( 'Use as featured_image', 'wpdlib' ),
		'insert_into_item'      => __( 'Insert into document', 'wpdlib' ),
		'uploaded_to_this_item' => __( 'Uploaded to this document', 'wpdlib' ),
		'items_list'            => __( 'Documents list', 'wpdlib' ),
		'items_list_navigation' => __( 'Documents list navigation', 'wpdlib' ),
		'filter_items_list'     => __( 'Filter documents list', 'wpdlib' ),
	);
  
	$rewrite = array(
		'slug'                  => 'documents',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
  
	$args = array(
		'label'                 => __( 'Document', 'wpdlib' ),
		'description'           => __( 'Description', 'wpdlib' ),
		'labels'                => $labels,
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-media-document',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	    'supports'=>array(
                 'title', 
                 'thumbnail', 
   		         'editor',
//		         'excerpt',
//			     'custom-fields',
             ),
		);
	
register_post_type( 'documents_post_type', $args );
}
add_action( 'init', 'wpdlib_documentos_post_type', 0 );


function wpdlib_classic_editor_none() {
   echo '<style type="text/css">
            body.post-type-documents_post_type #postdivrich {
            display: none;
            }
         </style>';
}

add_action('admin_head', 'wpdlib_classic_editor_none');