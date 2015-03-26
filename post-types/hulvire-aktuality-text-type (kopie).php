<?php
function huu_register_init() { 
	
	$labels = array(
	'name'                => _x( 'hulvire_aktuality', 'post type general name' ),
	'singular_name'       => _x( 'Aktuality', 'post type singular name' ),
	'add_new'             => _x( 'Pridaj nový', 'aktuality' ),
	'add_new_item'        => __( 'Pridaj novú aktualitu' ),
	'menu_name'           => __( 'Hulvire Aktuality' ),
	'all_items'           => __( 'Všetky aktuality' ),
	'view_item'           => __( 'View Aktuality' ),
	'edit_item'           => __( 'Uprav Aktuality' ),
	'not_found_in_trash'  => __('No Aktuality found in Trash'),
	'parent_item_colon'   => '',
	    'menu_name' => __('Hulvire Aktuality') 
	);
		
	
    $args = array( 
		'labels' => $labels,
			'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'show_in_menu' => true, 
		    'query_var' => true,
		    'rewrite' => true,
		    'capability_type' => 'post',
		    'has_archive' => true, 
		    'hierarchical' => false, 
			'menu_position' => 5,
		'menu_icon' => plugin_dir_url( __FILE__ ). '../images/aktuality-icon-a.png',
        'supports' => array('title','thumbnail','editor') 
       ); 
   
    register_post_type('hulvire_aktuality' , $args ); 
} 
add_action('init', 'huu_register_init');
add_theme_support('post-thumbnails', array('hulvire_aktuality'));


/* Add Custom Columns */
function huu_edit_columns($columns)
{
	$columns = array(
		  "cb" => '<input type="checkbox" >',
		  "title" => __( 'Aktuality Title' ),		  
		  "thumb" => __( 'Thumbnail' ),		  		 
		  "aktualita_before_text" => __('Before text' ),
		  "aktualita_after_text" => __('After text' ),
		  "date" => __( 'Date' )
	);
	
	return $columns;
}
add_filter("manage_hulvire_aktuality_posts_columns", "huu_edit_columns");



function huu_custom_columns($column){
	global $post;
	switch ($column)
	{
		case 'thumb':
			if(has_post_thumbnail($post->ID))
			{
				the_post_thumbnail('aktuality-img-thumb',array( 'style' => 'width:180px;height:auto' ) );                   
			}
			else
			{
				_e('No Aktuality Image');
			}
			break;		
		case 'aktualita_before_text':
			$aktualita_before_text = get_post_meta($post->ID,'aktualita_before_text',true);
			if(!empty($aktualita_before_text))
			{
				$aktualita_before_text = substr($aktualita_before_text, 0, 200); echo $aktualita_before_text." ...";
			}
			else
			{
				_e('NA');
			}		
			break;
		case 'aktualita_after_text':
			$aktualita_after_text = get_post_meta($post->ID,'aktualita_after_text',true);
			if(!empty($aktualita_after_text))
			{
				$aktualita_after_text = substr($aktualita_after_text, 0, 200); echo $aktualita_after_text." ...";
			}
			else
			{
				_e('NA');
			}		
			break;
	}
}
add_action("manage_hulvire_aktuality_posts_custom_column", "huu_custom_columns");



/*-----------------------------------------------------------------------------------*/
/*	Add Metabox to Aktuality
/*-----------------------------------------------------------------------------------*/	


	function huu_add_meta_boxes() {
	    add_meta_box('huu_meta_id', 'Aktuality text', 'aktualita_meta_box', 'hulvire_aktuality', 'normal');
	    add_meta_box('huu_meta_id2', 'Aktuality obrázky', 'aktualita_img_meta_box', 'hulvire_aktuality', 'normal');
	}
	add_action('add_meta_boxes', 'huu_add_meta_boxes');
	
	function aktualita_meta_box( $post )
	{
		$values = get_post_custom( $post->ID );
		
		$aktualita_before_text = isset( $values['aktualita_before_text'] ) ? esc_attr( $values['aktualita_before_text'][0] ) : '';
		$aktualita_after_text = isset( $values['aktualita_after_text'] ) ? esc_attr( $values['aktualita_after_text'][0] ) : '';
		
		wp_nonce_field( 'aktualita_meta_box_nonce', 'meta_box_nonce_aktualita' );
		?>
		<table style="width:100%;">			
        	<tr>
				<td style="width:25%;">
					<label for="aktualita_before_text"><strong><?php _e('Before text','HuuA');?></strong></label>					
				</td>
				<td style="width:75%;">
					<textarea type="text" name="aktualita_before_text" id="aktualita_before_text" value="" style="width:60%; margin-right:4%;" ><?php echo $aktualita_before_text; ?></textarea>
                    <span style="color:#999; display:block;"><?php _e('Toto je text pod nadpisom, ktory je zobrazeny stale','HuuA'); ?></span>
				</td>
			</tr>
        	<tr>
				<td style="width:25%;">
					<label for="aktualita_after_text"><strong><?php _e('After text','HuuA');?></strong></label>					
				</td>
				<td style="width:75%;">
					<textarea type="text" name="aktualita_after_text" id="aktualita_after_text" value="" style="width:60%; margin-right:4%;" ><?php echo $aktualita_after_text; ?></textarea>
                    <span style="color:#999; display:block;"><?php _e('Toto je text ktory sa zobrazi po stlaceni tlacitka Zobraz viacej','HuuA'); ?></span>
				</td>
			</tr>	
		</table>		        		
		<?php
	}
	
	function aktualita_img_meta_box( $post )
	{
		global $post;
 
		$image_src = '';
		$image_id = get_post_meta( $post->ID, '_image_id', true );
		$image_src = wp_get_attachment_url( $image_id );
 
		?>
		  <img id="book_image" src="<?php echo $image_src ?>" style="max-width:100%;" />
		  <input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo $image_id; ?>" />
		  <p>
		    <a title="<?php esc_attr_e( 'Set book image' ) ?>" href="#" id="set-book-image"><?php _e( 'Set book image' ) ?></a>
		    <a title="<?php esc_attr_e( 'Remove book image' ) ?>" href="#" id="remove-book-image" style="<?php echo ( ! $image_id ? 'display:none;' : '' ); ?>"><?php _e( 'Remove book image' ) ?></a>
		  </p>	        		
		<?php
	}
	
	
	//Pridá Metabox Media Library
    define('MY_POST_TYPE', 'hulvire_aktuality');
    define('MY_POST_SLUG', 'gallery');

    function my_register_post_type () {
    	$args = array (
    		'label' => 'Gallery',
    		'supports' => array( 'title', 'excerpt' ),
    		'register_meta_box_cb' => 'my_meta_box_cb',
    		'show_ui' => true,
    		'query_var' => true
    	);
    	register_post_type( MY_POST_TYPE , $args );
    }
    add_action( 'init', 'my_register_post_type' );

    function my_meta_box_cb () {
    	add_meta_box( MY_POST_TYPE . '_details' , 'Media Library', 'my_meta_box_details', MY_POST_TYPE, 'normal', 'high' );
    }

    function my_meta_box_details () {
    	global $post;
      $post_ID = $post->ID; // global used by get_upload_iframe_src
    	printf( "<iframe frameborder='0' src=' %s ' style='width: 100%%; height: 400px;'> </iframe>", get_upload_iframe_src('media') );
    }
	//Metabox Media Library
	
	
		
	
	
	
	
	add_action( 'save_post', 'aktualita_meta_box_save' );
	
	function aktualita_meta_box_save( $post_id )
	{
		
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		if( !isset( $_POST['meta_box_nonce_aktualita'] ) || !wp_verify_nonce( $_POST['meta_box_nonce_aktualita'], 'aktualita_meta_box_nonce' ) ) return;
		
		if( !current_user_can( 'edit_post' ) ) return;				
		
		if( isset( $_POST['aktualita_before_text'] ) )
			update_post_meta( $post_id, 'aktualita_before_text', $_POST['aktualita_before_text']  );
		
		if( isset( $_POST['aktualita_after_text'] ) )
			update_post_meta( $post_id, 'aktualita_after_text', $_POST['aktualita_after_text']  );
		
		if( isset( $_POST['aktualita_viac_img'] ) )
			update_post_meta( $post_id, 'aktualita_viac_img', $_POST['aktualita_viac_img']  );
		
		if ( $post->post_type != 'book' ) return;
			
		$this->process_book_meta( $post_id, $post );
		
		

	}
	