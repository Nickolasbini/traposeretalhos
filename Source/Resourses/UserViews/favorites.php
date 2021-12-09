<?php $this->insert('user-header', ['title' => ucfirst(translate('favorites'))]) ?>

<section>
	<div class="post-title">
		<?php echo ucfirst(translate('favorites')) ?>
	</div>
	<div class="type-of-favorite">
		<select class="favoriteCategory">
			<option value="1" selected="selected">
				<?php echo ucfirst(translate('posts')); ?>
			</option>
			<option style="display: none" value="2">
				<?php echo ucfirst(translate('comments')); ?>
			</option>
		</select>
	</div>
	<div class="favorite-contents">
		<?php foreach($favorites as $content){ ?>
			<div class="wrapper">
				<?php foreach($content as $key => $val){ ?>
					<?php if($key == 'numberOfComments' || $key == 'numberOfClicks' || $key == 'numberOfInFavoriteList' || $key == 'categoryId' || $key == 'dateOfUpdate'){continue;} ?>
					<?php if($key == 'id'){ ?>
						<div class="id" data-id="<?= $val ?>"></div>
					<?php continue; }else{ ?>
						<div class="value">
							<label><?= $key ?></label>
							<h4><?= $val ?></h4>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</section>

<?php $this->insert('user-footer') ?>
<script type="text/javascript">
	
</script>
<style type="text/css">
	.post-title{
		padding: 2%;
		width: 90%;
		background: rgba(0, 0, 0, 0.67);
		margin: auto;
		border-radius: 5px;
		color: #fff;
		text-align: center;
		font-size: 25px;
		margin-bottom: 5%;
	}
	.wrapper{
		margin: auto;
		display: flex;
		flex-direction: column;
		text-align: center;
		width: 60%;
		background: #fff;
		padding-top: 5%;
		padding-bottom: 5%;
		border-radius: 5px;
	}
	.type-of-favorite{
		width: 50%;
		margin: auto;
	    margin-bottom: auto;
		text-align: center;
		margin-bottom: 10%;
	}
	.favoriteCategory{
		width: 50%;
	}
</style>