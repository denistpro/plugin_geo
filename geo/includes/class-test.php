<?php
class Test{
	public function __construct($name, $address) 
	{
		$post_data = array
		(
			  'post_name' => $name,
	          'post_title'    => $name,
	          'post_type'  => 'rg_experts',
			  'meta_input'     => [ '_my_meta_value_key'=>$address], 	
	          'post_status'   => 'publish'
        );
		wp_insert_post( wp_slash($post_data) );
		
	}
}
?>