<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* METABOX EDITOR */

// Inicializamos el meta box.
function custom_editor_meta_Box(){
    add_meta_box (
     	'custom-editor',
      	__('WP Editor', 'custom-editor'),
      	'custom_editor',
      	'documents_post_type'
      );
 }     

// Mostramos la caja del meta box
function custom_editor($post) {
	
	$content = get_post_meta($post-> ID, 'custom_editor', true);

	// Esta función agrega el Editor Wysiwyg
	wp_editor (
		$content ,
		'custom_editor',
		array ( "media_buttons" => true )  // Aquí podemos añadir o suprimir funciones del editor
	);
}

// Guardamos los datos que hay en el meta box.
function custom_editor_save_postdata($post_id) {

	if( isset( $_POST ['custom_editor_nonce'] ) && isset( $_POST['documents_post_type'] ) ) {

		// No guardar si el usuario no ha hecho cambios.
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
		}

		// Verificando si la entrada proviene del formulario apropiado
		if( ! wp_verify_nonce ( $_POST['custom_editor_nonce']) ) {
		return;
		}

		// Nos aseguramos de que el usuario tiene permiso
		if( 'post' == $_POST['documents_post_type']) {
			if( ! current_user_can( 'edit_post', $post_id) ) {
				return;
			}
		}
	}

	if (!empty($_POST['custom_editor'])) {

		$data = $_POST['custom_editor'];


	update_post_meta($post_id, 'custom_editor', $data);

    }
  }
  
add_action('save_post', 'custom_editor_save_postdata');

add_action('admin_init', 'custom_editor_meta_box');
