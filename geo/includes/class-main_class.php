<?php
class mainClass {
	/**
	 * We install hooks at the time of class initialization.
	 */
    public function __construct() 
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adding an extra block.
	 */
	public function add_meta_box( $post_type )
	{
		// Set the types of posts to which the block will be added
		$post_types = array('rg_experts');
		if ( in_array( $post_type, $post_types )) 
		{
			add_meta_box
			(
				'some_meta_box_name',
				__( 'Address', 'geo_textdomain' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'advanced',
				'high',
			);
		}
	}
	/**
	 * We save data when saving a post.
	 *
	 * @param int $post_id The ID of the post that is being saved.
	 */
	public function save( $post_id ) 
	{
		/*
		 * We need to do a check to make sure the request came from our page,
		 * because save_post can be called anywhere else.
		 */
		// Check if nonce is set.
		if ( ! isset( $_POST['geo_inner_custom_box_nonce'] ) ) return $post_id;
		$nonce = $_POST['geo_inner_custom_box_nonce'];
		
		// Check if the nonce is correct.
		if ( ! wp_verify_nonce( $nonce, 'geo_inner_custom_box' ) )
			return $post_id;
		
		// If it's autosave we don't do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Checking user rights.
		if ( 'rg_experts' == $_POST['post_type'] ) 
		{

		if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;

		} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		// OK, everything is clean, we can save the data.

		// Clearing the input field.
		$mydata = sanitize_text_field( $_POST['geo_new_field'] );

		// We update the data.
		
		update_post_meta( $post_id, '_my_meta_value_key', $mydata );
		
	}

	/**
	 * Additional block code.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function render_meta_box_content( $post ) 
	{

		// We add a nonce field that we will check when saving.
		wp_nonce_field( 'geo_inner_custom_box', 'geo_inner_custom_box_nonce' );

		// We get the existing data from the database.
		$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

		// We display the form fields using the received data.
		echo '<label for="geo_new_field">';
		echo __( 'Expert address', 'geo_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="geo_new_field" name="geo_new_field"';
		echo ' value="' . esc_attr( $value ) . '" size="25" />';		
	}
}
?>