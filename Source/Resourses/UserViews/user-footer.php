<footer>
	<div class="footer-section-one">
		<div class="overlay-of-modal" style="display: none;"></div>
		<div class="header-logo footer-logo">
			<img width="30px" src="Source/Resourses/External/icons/logo-line&needle.svg">
			<h1 style="margin-right: 2%"><?php echo APP['appName'] ?></h1>
			<div class="footer-works-links">
				<ul class="links-options">
					<li><a>contact us</a></li>
					<li class="list-yellow-separator"></li>
					<li><a>be a partner</a></li>
					<li class="list-yellow-separator"></li>
					<li><a>announce here</a></li>
					<li class="list-yellow-separator"></li>
				</ul>
			</div>
		</div>
		<div class="contact-data">
			<div class="media-contacts">
				<a href="" class="contact-icon">
					<img width="30px" src="Source/Resourses/External/icons/email.svg">
				</a>
				<a href="" class="contact-icon">
					<img width="30px"  src="Source/Resourses/External/icons/whatsapp.svg">
				</a>
				<a href="" class="contact-icon">
					<img width="30px"  src="Source/Resourses/External/icons/instagram.svg">
				</a>
				<a href="" class="contact-icon">
					<img width="30px"  src="Source/Resourses/External/icons/facebook.svg">
				</a>
			</div>
			<div class="web-site-signature">
				© Copyright Cervo Digital. 2021
			</div>
		</div>
	</div>
</footer>

<script type="text/javascript">
	setOverlaySize();
	setFooterLinksSize();

	// set the overlay height
	function setOverlaySize(){
		var size = $('.footer-logo').height();
		$('.overlay-of-modal').height(size);
	}
	// set the links size
	function setFooterLinksSize(){
		var size = $('.overlay-of-modal').height();
		$('.footer-works-links').height(size);
	}
</script>

<style type="text/css">
	footer{
		margin-top: 20%;
	}
	.footer-logo{
		background-image: url('Source/Resourses/External/icons/gray-background.jpg');
		background-repeat: round;
	}
	.footer-works-links{
		background: rgb(159, 162, 57);
		opacity: 0.5;
		z-index: 1;
		margin-top: -5%;
		width: 25%;
	}
	.footer-works-links > ul{
		list-style: none;
	}
	.list-yellow-separator{
		background: rgba(250, 255, 0, 0.66);
		height: 1px;
		width: 50%;
	}
	.links-options{
		color: #ffffff;
		font-size: 20px;
		display: inline-flex;
		flex-direction: column;
		justify-content: space-around;
		height: 70%;
	}
	.links-options > li:hover{
		color: #000000;
		transition: 0.5s;
	}
	.overlay-of-modal{
		position: absolute;
		left: 0;
		background: #000;
		z-index: 100;
		right: 0;
		opacity: 0.5;
		width: 99%;
		margin: auto;
	}
	.contact-data{
		background: #5e5e5e;
		padding: 2%;
	}
	.media-contacts{
		display: flex;
		justify-content: space-around;
		width: 30%;
		margin: auto;
	}
	.contact-icon > img{
		width: 30px;
	}
	.web-site-signature{
		text-align: center;
		margin-top: 4%;
		color: rgba(255, 255, 255, 0.27);
	}
</style>