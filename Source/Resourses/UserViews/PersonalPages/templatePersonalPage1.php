<!DOCTYPE html>
<html>
<head>
	<title>PersonalPage Default number 1</title>
</head>
<body>
	<div>
		Be welcome <?=$this->e($person['name'])?>
	</div>

	<div>
		Your data: 
		<?php 
			foreach($person as $keyName => $value){
				if($keyName == 'name' || $keyName == 'id')
					continue;
				if(is_array($value)){
					continue;
				}
				echo '<p>Your '.$keyName.' is '.$value.'</p>';
			}
		?>
	</div>
</body>
</html>