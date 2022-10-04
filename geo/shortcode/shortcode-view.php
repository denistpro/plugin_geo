<?php

		echo "<table class='table' style='width:".$params['width-table']."'>";
		echo<<<EOL
		<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Positions</th>
			</tr>
		</thead>
		<tbody>
		EOL;
		$countPerson =0;
		foreach ($experts->posts as $person):
			if ($person->post_title)
			{
				$addr[] = str_replace(array('"',','),'',get_post_meta( $person->ID, '_my_meta_value_key', true ));
				$person_name[]=$person->post_title;
		
				echo '<tr>';
					echo '<td>'.$person->post_title.'</td>';
					echo '<td>'.get_post_meta( $person->ID, '_my_meta_value_key', true ).'</td>';
					echo '<td><a id="per'.$countPerson.'" onclick="clickPer('.$countPerson.')" style="cursor: pointer;" href="#map">Find it</a></td>';
				echo '</tr>';
			 }
		$countPerson++; 
		endforeach;
		echo <<<EOL
		</tbody>
		</table>
		EOL;
		$addressList = '[&quot;'.implode('&quot;,&quot;',$addr).'&quot;]';
		$personname = '[&quot;'.implode('&quot;,&quot;',$person_name).'&quot;]';
		echo '<div id="map" style="width:'.$params["width-map"].'; height:'.$params["height-map"].';" data-addresslist="'.$addressList.'" data-personname="'.$personname.'" zoom="'.$params["zoom"].'"></div>';

?>