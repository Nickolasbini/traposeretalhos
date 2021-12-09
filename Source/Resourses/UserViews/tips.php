<?php $this->insert('user-header', ['title' => ucfirst(translate('tips'))]) ?>

<body>
	<section class="top-horizontol text">
		<h1><?php echo ucfirst(translate('steps to starting learning the art')); ?>:</h1>
		<div class="text-wrapper">
			<h2 class="text-title"><?php echo ucfirst(translate('acquire a swewing machine')); ?></h2>
			<p>
				Maybe you already have your sewing machine, but if you’re in the market for one still, it can be quite mind-boggling to try to figure out what machine to choose. They can cost anywhere from $99-$2,000 (and above) and how are you supposed to know which one will work best for you?
				<h4>Have in mind before purchasing a machine</h4>
				<ul class="text-list-with-markers">
					<li>
						What’s your budget? Do you want something on the very low end of the price scale or do you have a little bit more money to work with?
					</li>
					<li>
						Do you want to buy it locally or do you want to order it online? Some companies will include free sewing lessons when you purchase in store. If that interests you, you might choose to buy locally. You’re likely to find better prices online though, so if you are comfortable learning to sew online, that’s a great route to go.
					</li>
					<li>
						Do you want a lot of bells and whistles, or do you prefer something simple that might be easier to get started on? Both can be good options-sometimes simple is great. Or maybe you have some specific options that you want in a machine? Think about some of the things that matter to you.				
					</li>
				</ul>
			</p>
			
			<h2 class="text-title">Start sewing</h2>
			<p>
				Your skills need to build gradually over time, and the best way to do that is to focus on learning something new with each project you try. Think of it as giving yourself little assignments.
			</p>
			<h4>Some projects for you to do in order to enhance your skills:</h4>
			<ul class="text-list-with-markers">
				<li>
					Make a pillow. So easy, and you don’t need a pattern. You’ll learn how to cut and sew with seam allowances. And you can use almost any fabric.
				</li>
				<li>
					Make a Sorbetto top. This free pattern has only two pattern pieces and will help you learn to use bias tape, a very good skill to have. No zippers or other closures are needed.
				</li>
				<li>
					Make another Sorbetto. This time, try making your own bias tape, if you’re feeling adventurous.
				</li>
				<li>
					Laurel. Now you’ll use those bias tape skills once again, while also installing a zipper.
				</li>
				<li>
					Ginger. With this aline skirt, you’ll be putting in a zipper once again, and also installing a waistband.
				</li>
				<li>
					Macaron. Try installing pockets, facings, and doing a bit of topstitching.
				</li>
			</ul>
		</div>

	</section>

	<section class="left-vertical text small">
		
	</section>

	<section class="rigth-vertical text big">
		
	</section>

	<section class="bottom-horizontol text">
		
	</section>

	<?php $this->insert('user-footer') ?>
</body>

<style type="text/css">
	.text-list-with-markers{

	}
	.text-title{
		padding: 1%;
		background: rgba(0, 0, 0, 0.67);
		border-radius: 5px;
		color: #ffffff;
		margin: unset;
		font-size: 20px;
	}
	.top-horizontol{
		width: 95%;
		margin: auto;
		padding: 1%;
	}
	.top-horizontol > h1{
		padding: 1%;
		background: rgba(0, 0, 0, 0.67);
		border-radius: 5px;
		margin: 2%;
		color: #ffffff;
		font-size: 35px;
	}
	.bottom-horizontol{

	}
	.left-vertical{

	}
	.rigth-vertical{

	}
	.text{
		text-align: left;
		font-size: 20px;
	}
	.text-wrapper{
		background: #fff;
		padding: 1%;
		margin: 2%;
	}
	.big{
		width: 80%;
	}
	.small{
		width: 20%;
	}
	@media only screen and (max-width: 600px){
		.top-horizontol > h1{
			font-size: 1.2em;
		}
	}
</style>
