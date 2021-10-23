<?php $this->insert('user-header', ['title' => ucfirst(translate('new'))]) ?>
<section>
	<div class="main-data-wrapper">
		<div class="background-picture">
			<div class="profile-photo">
				<img src="">
			</div>
		</div>
		<div class="centralized">
			<h5 class="score"><?= $person['role']['personScoreStars'] ?></h5>
			<h4 class="profile-name"><?= $person['fullName'] ?></h4>
		</div>
		<div class="role-icon">
			<?php 
				foreach(json_decode($_SESSION['roles'], true) as $role){ 
					if($role['id'] == $person['role']['roleId']){
						echo '<img class="role-icon" src="/'.URL['urlDomain'].'/Source/Resourses/External/icons/'.$person['role']['roleIconURL'].'">';
						break;
					}
				} ?>
		</div>
	</div>


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
</section>
<?php $this->insert('user-footer') ?>