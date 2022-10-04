<?php
class Test{
	private $arr_test = 
	[
		['Den-test','g.k. Lyulin 5 512В, 1359 zh.k. Lyulin 5, Sofia'],
		['Bob-test','ul. Nikola Belovezhdov 1, 1359 zh.k. Lyulin 4, Sofia'],
		['John-test','bul. Dobrinova skala 546В, 1359 zh.k. Lyulin 5, Sofia'],
		['Mike-test','D-r, bul. "Doctor Peter Dertliev" 104, 1336 zh.k. Lyulin 5, Sofia']
	];
	public function __construct() 
	{
		$arr_test = $this->arr_test;
		foreach ($arr_test as $item)
			{
				$this->add_test_posts($item[0],$item[1]);
			}
			
	}
	private function add_test_posts($name, $address)
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