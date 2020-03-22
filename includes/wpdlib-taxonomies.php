<?php

// Creamos la taxonomía temáticas

function wpdlib_crear_taxonomias() {
        // Añadimos nueva taxonomía y la hacemos jerárquica (como las categorías por defecto)
    $labels = array(
        'name'              => _x( 'Thematics', 'Taxonomy General Name', 'wpdlib' ),
        'singular_name'     => _x( 'Thematic', 'Taxonomy Singular Name','wpdlib' ),
		'menu_name'         => __( 'Thematics', 'wpdlib' ),        
        'search_items'      => __( 'Search_Thematics', 'wpdlib' ),
        'all_items'         => __( 'All Thematics', 'wpdlib' ),
        'parent_item'       => __( 'Parent Thematic', 'wpdlib' ),
        'parent_item_colon' => __( 'Parent Thematic:', 'wpdlib' ),
        'edit_item'         => __( 'Edit Thematic', 'wpdlib' ),
        'update_item'       => __( 'Update Thematic', 'wpdlib' ),
        'add_new_item'      => __( 'Add New Thematic', 'wpdlib' ),
        'new_item_name'     => __( 'New Thematic Name', 'wpdlib'  ),		
		'view_item'                  => __( 'View Thematic', 'wpdlib' ),
		'separate_items_with_commas' => __( 'Separate thematics with commas', 'wpdlib' ),
		'add_or_remove_items'        => __( 'Add or remove thematics', 'wpdlib' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wpdlib' ),
		'popular_items'              => __( 'Popular thematics', 'wpdlib' ),
		'not_found'                  => __( 'Not Found', 'wpdlib' ),
		'no_terms'                   => __( 'No thematics', 'wpdlib' ),
		'Thematics_list'             => __( 'Thematics list', 'wpdlib' ),
		'items_list_navigation'      => __( 'Thematics list navigation', 'wpdlib' ),		  
    );
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,		
		'show_in_nav_menus'          => true,
		'show_in_rest'               => true,
		'show_tagcloud'              => true,
		'query_var'                  => true,
	);
    register_taxonomy( 'thematic', array( 'documents_post_type' ), $args);
}
add_action( 'init', 'wpdlib_crear_taxonomias', 0 );


// Creamos etiquetas

function wpdlib_crear_etiquetas() {
        // Añadimos nueva taxonomía y la hacemos jerárquica (como las categorías por defecto)
    $labels = array(
        'name'              => _x( 'Tags', 'Taxonomy General Name', 'wpdlib' ),
        'singular_name'     => _x( 'Tag', 'Taxonomy Singular Name','wpdlib' ),
		'menu_name'         => __( 'Tags', 'wpdlib' ),        
        'search_items'      => __( 'Search_Tags', 'wpdlib' ),
        'all_items'         => __( 'All Tags', 'wpdlib' ),
        'parent_item'       => null,
        'parent_item_colon' => null,
        'edit_item'         => __( 'Edit Tag', 'wpdlib' ),
        'update_item'       => __( 'Update Tag', 'wpdlib' ),
        'add_new_item'      => __( 'Add New Tag', 'wpdlib' ),
        'new_item_name'     => __( 'New Tag Name', 'wpdlib'  ),		
		'view_item'                  => __( 'View Tag', 'wpdlib' ),
		'separate_items_with_commas' => __( 'Separate tags with commas', 'wpdlib' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'wpdlib' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wpdlib' ),
		'popular_items'              => __( 'Popular tags', 'wpdlib' ),
		'not_found'                  => __( 'Not Found', 'wpdlib' ),
		'no_terms'                   => __( 'No tags', 'wpdlib' ),
		'Thematics_list'             => __( 'Tags list', 'wpdlib' ),
		'items_list_navigation'      => __( 'Tags list navigation', 'wpdlib' ),		  
    );
    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tag' ),
    ); 
    register_taxonomy( 'tag', array( 'documents_post_type' ), $args);
}
add_action( 'init', 'wpdlib_crear_etiquetas', 0 );


// Creamos una taxonomia por defecto


function wpdlib_set_default_taxonomies( $post_id, $post ) {
	if ( 'publish' === $post->post_status && $post->post_type === 'documents_post_type' ) {
		$defaults = array(
			'thematic' => __( 'Uncategorized', 'wpdlib'  ),
		);
		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach ( (array) $taxonomies as $taxonomy ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy );
			if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
				wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
			}
		}
	}
}
add_action( 'save_post', 'wpdlib_set_default_taxonomies', 100, 2 );

// Creamos una etiqueta por defecto


function wpdlib_set_default_tags( $post_id, $post ) {
	if ( 'publish' === $post->post_status && $post->post_type === 'documents_post_type' ) {
		$defaults = array(
			'tag' => __( 'Document', 'wpdlib'  ),
		);
		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach ( (array) $taxonomies as $taxonomy ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy );
			if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
				wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
			}
		}
	}
}
add_action( 'save_post', 'wpdlib_set_default_tags', 100, 2 );



 
