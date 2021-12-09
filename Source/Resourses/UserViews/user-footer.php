<footer>
	<div class="footer-section-one">
		<div class="overlay-of-modal" style="display: none;"></div>
		<div class="header-logo footer-logo">
			<img width="30px" src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/logo-line&needle.svg">
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
				<a href="mailto:<?= CONTACTS['email-link'] ?>" target="_blank" class="contact-icon">
					<img width="30px" src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/email.svg">
				</a>
				<a href="https://api.whatsapp.com/send?phone=<?= CONTACTS['whatsapp-number'] ?>&text=<?php echo urlencode(ucfirst(translate(CONTACTS['whatsapp-text']))); ?>" target="_blank" class="contact-icon">
					<img width="30px"  src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/whatsapp.svg">
				</a>
				<a href="<?= CONTACTS['instagram-page'] ?>" target="_blank" class="contact-icon">
					<img width="30px"  src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/instagram.svg">
				</a>
				<a href="<?= CONTACTS['facebook-page'] ?>" target="_blank" class="contact-icon">
					<img width="30px"  src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/facebook.svg">
				</a>
			</div>
			<div class="web-site-signature">
				© Copyright Cervo Digital. 2021
			</div>
		</div>
	</div>
</footer>

<script type="text/javascript">

</script>

<style type="text/css">
	footer{
		margin-top: 20%;
	}
	.footer-section-one{

	}
	.footer-logo{
		background-image: url('/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/gray-background.webp');
		background-repeat: round;
		width: 60%;
		margin: auto;
		border-radius: 15px 15px 0 0;
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
		cursor: pointer;
	}

	.footer-works-links{
		display: none;
	}
	.header-logo > img{
		width: 10%!important;
	}
	.header-logo > h1{
		margin-top: auto;
		margin-bottom: auto;
	}
	.header-logo{
		padding-top: 60px;
		padding-bottom: 60px;
		text-align: center;
		font-size: 20px;
	}
	.media-contacts{
		width: 90%;
	}
	.contact-icon:hover{
		opacity: 0.8;
	}

	/* improving footer */
    @media only screen and (max-width: 700px){
    	.header-logo{
    		width: 100%;
    		border-radius: unset;
    	}
    	.footer-works-links{
			display: none;
		}
		.header-logo{
			padding-top: 60px;
			padding-bottom: 60px;
			text-align: center;
			font-size: 10px;
		}
		.media-contacts{
			width: 90%;
		}
		.contact-icon:hover{
			opacity: 0.8;
		}
		.contact-icon > img{
			width: 22px;
			padding: 5px;
		}
		.web-site-signature{
			font-size: 0.8em;
		}
    }
</style>