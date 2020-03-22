<?php

/* LISTADO DE CATEGORÍAS */ 
/* Creando el Shortcode [list_cat] que podemos añadir a cualquier página.*/

function wpdlib_get_list_categories_shortcode(){
   wp_list_categories( array(
        'child_of'            => 0,  
        'current_category'    => __( 'Uncategorized', 'wpdlib' ),
        'depth'               => 0,
        'echo'                => 1,
        'exclude'             => '',
        'exclude_tree'        => '',
        'feed'                => '',
        'feed_image'          => '',
        'feed_type'           => '',
        'hide_empty'          => 1,
        'hide_title_if_empty' => false,
        'hierarchical'        => true,
        'order'               => 'ASC',
        'orderby'             => 'name',
        'separator'           => '<br />',
        'show_count'          => 1,
        'show_option_all'     => '',
        'show_option_none'    => __( 'No categories', 'wpdlib' ),
        'style'               => 'list',
        'taxonomy'            => 'thematic',
        'title_li'            => '',
        'use_desc_for_title'  => 1,
   ) );
}
add_shortcode('list_cat', 'wpdlib_get_list_categories_shortcode');


/* Creando página "Temáticas" */

if ( ! function_exists( 'wpdlib_crear_pagina_cat' )) {
  
	if( null == get_page_by_path ( 'thematics' ) ) {
	  
function wpdlib_crear_pagina_cat() {
            $post_data = array(
                'post_title'    => 'Thematics',
                'post_content'  => '[list_cat]',
                'post_status'   => 'publish',
                'post_type'     => 'page',
            );
            wp_insert_post( $post_data, $error_obj );
        }

            add_action( 'admin_init', 'wpdlib_crear_pagina_cat' );
         }
}

/* LISTADO DE DOCUMENTOS */ 
/* Creando el Shortcode [list_documents] que podemos añadir a cualquier página. */

function wpdlib_get_list_documents_shortcode(){

	$args = array (
	   'orderby' =>'title',
	   'order' =>'asc',
	   'posts_per_page' => '-1',
	   'post_type' => 'documents_post_type',
	   'post_status' => 'published',
	    
	);
	
	$the_query = new WP_Query ($args);
	
	if ($the_query-> have_posts()) {
	
		echo '<ul>';
	
		while ( $the_query->have_posts()) {
	
			$the_query->the_post();
	
			echo '<li><a href="' . get_the_permalink(). '">' .get_the_title(). '</a></li>';
		}
		echo '</ul>';
	 }

}
add_shortcode('list_documents', 'wpdlib_get_list_documents_shortcode');

/* Creando página "Documents" con buscador */

if ( ! function_exists( 'wpdlib_crear_documents_list' )) {
  
	if( null == get_page_by_path ( 'documents-list' ) ) {
	  
function wpdlib_crear_documents_list() {
            $post_data = array(
                'post_title'    => 'Documents list',
                'post_content'  => '[wpdlib_search][list_documents]',
                'post_status'   => 'publish',
                'post_type'     => 'page',
            );
            wp_insert_post( $post_data, $error_obj );
        }

            add_action( 'admin_init', 'wpdlib_crear_documents_list' );
         }
}


/* Shortcode buscador */

function wpdlib_searchform( $form ) {

     $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
     <div><label class="screen-reader-text" for="s">' . __('Search for:', 'wpdlib') . '</label>
     <input type="text" value="' . get_search_query() . '" name="s" id="s" />
     <input type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'wpdlib') .'" />
     </div>
     </form>';

     return $form;
 }

 add_shortcode('wpdlib_search', 'wpdlib_searchform');
