<?php
/* AÑADIR CPT A CONSULTA */

add_action( 'pre_get_posts', 'wpdlib_add_my_post_types_to_query' );

function wpdlib_add_my_post_types_to_query( $query ) {
	if ( (is_single() || is_category() ) && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'documents_post_type' ) );

	return $query;
}

function wpdlib_metabox() {
  add_meta_box( 'documents-metabox', __('Details Documents', 'wpdlib'), 'wpdlib_campos_documentos', 'documents_post_type', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'wpdlib_metabox' );

function wpdlib_campos_documentos($post) {
  //si existen se recuperan los valores de los metadatos
  $title = get_post_meta( $post->ID, 'title', true );
  $author =  get_post_meta( $post->ID, 'author', true ); 
  $description = get_post_meta( $post->ID, 'description', true );
  $format = get_post_meta( $post->ID, 'format', true );
  $pages = get_post_meta( $post->ID, 'pages', true ); 
  $archive = get_post_meta( $post->ID, 'archive', true );
  $image = get_post_meta( $post->ID, 'image', true );  
  
    //Almacenamos los formatos en un array para montar un select

  $array_format = array(__('Select a format', 'wpdlib'), 
						 
		'CSS'  => 'CSS', 
		'Doc'  => 'Doc', 
		'HTML' => 'HTML', 				 
        'Img'  => 'Img', 
		'JS'   => 'JS', 				 
        'Pdf'  => 'Pdf',
		'PHP'  => 'PHP',				 
        'PP'   => 'PP', 
        'Txt'  => 'Txt', 
        'Zip'  => 'Zip',
						 
   );  

 
   // Se añade un campo nonce para probarlo más adelante cuando validemos
  wp_nonce_field( 'campos_documentos_metabox', 'campos_documentos_metabox_nonce' );?>
  
  <table width="100%" cellpadding="1" cellspacing="1" border="0">
    <tr>
      <td width="20%"><strong><?php _e('Title: ', 'wpdlib');?> </strong><br /></td>
      <td width="80%"><input type="text" name="title" value="<?php echo sanitize_text_field($title);?>" class="large-text" placeholder="<?php _e('Title');?>" /></td>
    </tr>
    <tr>
      <td width="20%"><strong><?php _e('Author: ', 'wpdlib');?></strong><br /></td>
      <td width="80%"><input type="text" name="author" value="<?php echo sanitize_text_field($author);?>" class="large-text" placeholder="<?php _e('Author');?>" /></td>
    </tr>
    <tr>
      <td><strong><?php _e('Description: ', 'wpdlib');?></strong></td>
      <td><input type="text" name="description" value="<?php echo sanitize_text_field($description);?>" class="large-text" placeholder="<?php _e('Description');?>" /></td>
    </tr>
    <tr>
      <td><strong><?php _e('Format: ', 'wpdlib');?></strong><br /></td>
      <td><select name="format" class="postform">
        <?php foreach ($array_format as $key => $format) {?>
          <option value="<?php echo ($key);?>" <?php if ($format == $key){?>selected="selected"<?php }?>><?php echo $format;?></option>
        <?php }?>
      </select></td>
    </tr>
    <tr>
      <td width="20%"><strong><?php _e('Pages: ', 'wpdlib');?></strong><br /></td>
      <td width="80%"><input type="text" name="pages" value="<?php echo sanitize_text_field($pages);?>" class="large-text" placeholder="<?php _e('Pages');?>" /></td>
    </tr>
	<tr>
      <td width="20%"><strong><?php _e('Archive: ', 'wpdlib');?> </strong></td>
      <td width="80%" ><input type="text" name="archive" value="<?php echo sanitize_text_field($archive);?>" class="large-text" placeholder="<?php _e('Archive URL');?>" /></td>
    </tr>	
    <tr>
      <td width="20%"><strong><?php _e('Image: ', 'wpdlib');?></strong></td>
      <td width="80%"><input type="text" name="image" value="<?php echo sanitize_text_field($image);?>" class="large-text" placeholder="<?php _e('Image URL');?>" /></td>
    </tr> 
    <tr>
      <label for="upload_image">
	  <td width="20%"><strong><?php _e('Copy and paste this URL in archive or image: ', 'wpdlib');?></strong></td>
	  <td width="80%"><input id="upload_image" type="text" size="100" name="add_image" value="http://" />
	  <input id="upload_image_button" class="button" type="button" value="<?php _e('Add Media');?>" /></td>
      </label>
	</tr>
  </table>

   <?php
}


/* METABOX */

function wpdlib_campos_documentos_save_data($post_id) {
  // Comprobamos si se ha definido el nonce.
  if ( ! isset( $_POST['campos_documentos_metabox_nonce'] ) ) {
    return $post_id;
  }
  $nonce = $_POST['campos_documentos_metabox_nonce'];
 
  // Verificamos que el nonce es válido.
  if ( !wp_verify_nonce( $nonce, 'campos_documentos_metabox' ) ) {
    return $post_id;
  }
 
  // Si es un autoguardado nuestro formulario no se enviará, ya que aún no queremos hacer nada.
  if ( defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
    return $post_id;
  }
 
  // Comprobamos los permisos de usuario.
  if ( $_POST['post_type'] == 'page' ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }
 
  // Vale, ya es seguro que guardemos los datos.
 
  // Si existen entradas antiguas las recuperamos
  $old_title = get_post_meta( $post_id, 'title', true );
  $old_author = get_post_meta( $post_id, 'author', true );
  $old_description = get_post_meta( $post_id, 'description', true );
  $old_format = get_post_meta( $post_id, 'format', true );
  $old_pages = get_post_meta( $post_id, 'pages', true );
  $old_archive= get_post_meta( $post_id, 'archive', true );
  $old_image= get_post_meta( $post_id, 'image', true );
 
  // Saneamos lo introducido por el usuario.
  $title = sanitize_text_field( $_POST['title'] );
  $author = sanitize_text_field( $_POST['author'] );
  $description = sanitize_text_field( $_POST['description'] );
  $format = sanitize_text_field( $_POST['format'] );
  $pages = sanitize_text_field( $_POST['pages'] );	
  $archive = sanitize_text_field( $_POST['archive'] );
  $image = sanitize_text_field( $_POST['image'] );
 
  // Actualizamos el campo meta en la base de datos.
  update_post_meta( $post_id, 'title', $title, $old_title );
  update_post_meta( $post_id, 'author', $author, $old_author );	
  update_post_meta( $post_id, 'description', $description, $old_description );
  update_post_meta( $post_id, 'format', $format, $old_format );
  update_post_meta( $post_id, 'pages', $pages, $old_pages );
  update_post_meta( $post_id, 'archive', $archive, $old_archive );
  update_post_meta( $post_id, 'image', $image, $old_image );
}

add_action( 'save_post', 'wpdlib_campos_documentos_save_data' );

/* MOSTRAR LOS CAMPOS */

add_filter( 'the_content', 'wpdlib_add_custom_fields_to_content' );
function wpdlib_add_custom_fields_to_content( $content ) {
 
    $custom_fields = get_post_custom();

    $content .= "<div class='campos'>";	

    if( isset( $custom_fields['image'] ) ) {
         $content .= '<img class="image" width="150px; height="auto" src="'. $custom_fields['image'][0] .'" >';
	}	
    if( isset( $custom_fields['title'] ) ) {
         $content .= '<li>'.__('Title', 'wpdlib').': <span class="red"><strong>' . $custom_fields['title'][0] . '</span></strong></li>';
    }
	 if( isset( $custom_fields['author'] ) ) {
         $content .= '<li>'.__('Author', 'wpdlib').': <span class="blue">' . $custom_fields['author'][0] . '</span></strong></li>';
    }
    if( isset( $custom_fields['description'] ) ) {
         $content .= '<li>'.__('Description', 'wpdlib').': <span class="blue">' . $custom_fields['description'][0] . '</span></li>';
    }
    if( isset( $custom_fields['format'] ) ) {
         $content .= '<li>'.__('Format', 'wpdlib').': <span class="blue">'  . $custom_fields['format'][0] . '</li>'; 
	}  
    if( isset( $custom_fields['pages'] ) ) {
         $content .= '<li>'.__('Pages', 'wpdlib').': <span class="blue">'  .  $custom_fields['pages'][0] . '</li><br>';
	}	
     if( isset( $custom_fields['archive'] ) ) {
  	     $content .= '<a class="boton" href="'. $custom_fields['archive'][0] . '">'.__('Download document', 'wpdlib').' </a>'; 

    $content .= '</div>';
	
	}
    return $content;

}

/* ARCHIVOS PERMITIDOS */

add_filter('upload_mimes', 'wpdlib_mas_extensiones');

function wpdlib_mas_extensiones ( $existing_mimes=array() ) {
// Añadimos las extensiones que queremos permitir junto con su MIME type:
$existing_mimes['php']  = 'application/php';
$existing_mimes['zip']  = 'application/zip';
$existing_mimes['css']  = 'application/css';
$existing_mimes['txt']  = 'application/txt';
$existing_mimes['js']   = 'application/js';
$existing_mimes['html'] = 'application/html';

//Pueden agregarse tantas líneas como se desee
return $existing_mimes;
}

