<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
	<title>Trapos e Retalhos - <?php echo $title ?></title>
	<link rel="icon" type="image/svg" href="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/favicon.svg"/>

	<!-- importing jquery -->
	<script src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/jquery/jquery-3.5.1.js"></script>
	<!-- importing FontAwesome -->
	<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/External/fontawesome/css/all.css" rel="stylesheet"></link>
	<!-- importing CSS -->
	<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/general.css" rel="stylesheet"></link>
	<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/tips-view.css" rel="stylesheet"></link>
	<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/modal.css" rel="stylesheet"></link>
	<link href="/<?= URL['urlDomain'] ?>/Source/Resourses/CSS/alert.css" rel="stylesheet"></link>
</head>

<header>
	<nav id="top-navigation-bar">
		<div class="user-menu-options">
			<div class="sorting" title="<?php echo ucfirst(translate('select your language')) ?>">
				<div class="sort right">
					<label>
				    <select class="language-selector">
							<option id="pt"><?php echo ucfirst(translate('portuguese')); ?></option>
							<option id="en"><?php echo ucfirst(translate('english')); ?></option>
							<option id="es"><?php echo ucfirst(translate('spanish')); ?></option>
						</select>
	    	    <span class="pointer"><img width="30px" src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/<?php echo $_SESSION['userLanguage'] ?>.svg"></span>
    			</label>
    		</div>
    	</div>
			<ul class="user-menu-list">
				<?php if(isset($_SESSION['personId'])){ ?>
				<li title="<?php echo ucfirst(translate('my favorites menu')) ?>">
					<img src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/star.svg">
					<a href="favorites"><?php echo ucfirst(translate('favorites')); ?></a>
				</li>
				<?php } ?>
				<?php if(isset($_SESSION['personId'])){ ?>
					<li title="<?php echo ucfirst(translate('my account information')) ?>">
						<img src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/account.svg">
						<a href="myaccount"><?php echo ucfirst(translate('my account')); ?></a>
					</li>
				<?php } ?>
				<li>
					<a href="<?php if(isset($_SESSION['personId'])){ echo 'logout';}else{ echo 'login';}?>" title="<?php if(isset($_SESSION['personId'])){ echo ucfirst(translate('exit your account'));}else{echo ucfirst(translate('enter in your account'));}?>">
						<?php
							if(isset($_SESSION['personId'])){
								echo ucfirst(translate('exit'));
							}else{
								echo ucfirst(translate('enter'));
							}
						?>
					</a>
				</li>
			</ul>
		</div>

		<div class="header-logo">
			<img width="15%"  src="/<?= URL['urlDomain'] ?>/Source/Resourses/External/icons/logo-line&needle.svg">
			<h1><?php echo APP['appName'] ?></h1>
		</div>

		<div class="header-items">
			<div id="menu-icon-bar" style="display: none;"><i class="fas fa-bars"></i></div>
		    <ul class="top-menu-options dropdown-menu">
			    <li id="homepage" title="<?php echo ucfirst(translate('go to the home page')) ?>">
			    	<a class="homepage-icon" href="<?php echo '/'.URL['urlDomain']; ?>">
			    		<i class="fas fa-home"></i>
			    	</a>
			    </li>
			    <li id="new" title="<?php echo ucfirst(translate('see the latest posts')) ?>">
			    	<a href="news"><?php echo ucfirst(translate('news')); ?></a>
			    </li>
			    <li id="map" title="<?php echo ucfirst(translate('search on the real time map')) ?>">
			    	<a href="map"><?php echo ucfirst(translate('map')); ?></a>
			    </li>
			    <li id="tips" title="<?php echo ucfirst(translate('know the best of the art')) ?>">
			    	<a href="tips"><?php echo ucfirst(translate('tips')); ?></a>
			    </li>
			    <li id="courses" title="<?php echo ucfirst(translate('see your courses')) ?>">
			    	<a href="courses"><?php echo ucfirst(translate('courses')); ?></a>
			    </li>
			    <?php if(isset($_SESSION['userRole']) && !is_null($_SESSION['userRole'])){ ?>
				    <li id="posts" title="<?php echo ucfirst(translate('manage your posts')) ?>">
				    	<a href="posts"><?php echo ucfirst(translate('posts')); ?></a>
				    </li>
				<?php } ?>
		    </ul>
	    </div>
	</nav>
</header>

<div class="loader"></div>
<div class="messager"></div>

<?php include "Source/Resourses/Components/confirmation-alert.php" ?>

<script type="text/javascript">
	var pageTitle = "<?php echo $title ?>";
	pageTitle = pageTitle.toLowerCase();
	setMenuSelectedOption();
	setLanguageOptions();

	// sets the selected option by the page title, showing which page that is
	function setMenuSelectedOption(){
		$('.header-items').find('li').removeClass('selected-on-menu');
		$('#'+pageTitle).addClass('selected-on-menu');
	}

	// sets the language to user choice
	$('.language-selector').on('change', function(){
		var languageISO = $('.language-selector').children("option:selected").attr('id');
		$.ajax({
			url: "language/changeuserlanguage",
			type: 'post',
			data: {languageISO: languageISO},
			success: function(){
    			location.reload();
  			}
  		});
	});

	// sets language at banner to the correspondent selected language
	function setLanguageOptions(){
		var userLanguage = "<?php echo $_SESSION['userLanguage']; ?>";
		$('#'+userLanguage).attr('select', 'selected');
		var languageToBeSelected = $('#'+userLanguage).text();
		$('#'+userLanguage).parent().val(languageToBeSelected).change();
	}

	
	  $('#menu-icon-bar').on('click', function(){
	  	  $(this).toggleClass('menu-bar-oppened');
	  	  $('.dropdown-menu').toggleClass('opened-dropdown-menu');
	  });
</script>

<style type="text/css">
	body{
		background: #F0F0F0;
	}
	a, select, img{
		cursor: pointer;
	}
	*{
		font-family: Arial, Helvetica, sans-serif;
	}
	header{
		margin-bottom: 5%;
	}

	/* The user option to click */
	.user-menu-options{
		display: flex;
		width: 100%;
		justify-content: space-between;
		background: #F5F4F4;
		border-bottom: 1px solid #C4C4C4;
	}
	/* The banner and language selectors */
	.banner-option{
		display: flex;
	}
	.banner-option>img{
		width: 50px;
	}
	.banner-option>a{
		margin: auto;
		margin-left: 5px;
	}
	/* The selector related CSS */
	.language-selector{
		border: none;
	}
	.language-selector {
	  -moz-appearance: none; /* for Firefox */
	  -webkit-appearance: none;/* for Chrome */
	}
	.language-selector::-ms-expand {
	  display: none;/* For IE10 */
	}
	/* The option of the USER at the top menu */
	.user-menu-options>ul{
		display: flex;
	}
	.user-menu-options>ul>li{
		list-style: none;
		margin: 0 10px 0 10px;
		display: flex;
	}
	.user-menu-options>ul>li>a{
		text-decoration: none;
		color: #000000;
		margin: auto;
	}
	.user-menu-options>ul>li>img{
		width: 30px;
		height: 30px;
		margin-right: 10px;
	}

	/* The logo */
	.header-logo{
		display: flex;
		justify-content: center;
		padding: 5%;
	}
	.header-logo>img{
		cursor: default!important;
		width: 15%;
	}
	.header-logo>h1{
		color: rgba(0, 0, 0, 0.33);
	}

	/* The clicable option to navigate the system*/
	.header-items{
		background: rgba(0, 0, 0, 0.67);
	}
	.header-items>ul{
		display: flex;
		justify-content: space-evenly;
		height: 70px;
	}
	.header-items>ul>li{
		list-style: none;
		margin: auto;
	}
	.header-items>ul>li>a{
		text-decoration: none;
		color: #FFFFFF;
		font-size: 25px;
	}
	.header-items>ul>li>a:hover:not(.selected-on-menu>a){
		text-decoration: underline;
	}
	.homepage-icon>i{
		font-size: 40px;
	}
	/* The selected item on menu CSS */
	.selected-on-menu{
		display: flex;
		height: 70px;
		text-align: center;
		background: rgba(218, 221, 90, 0.78);
		padding: 0 15px;
	}
	.selected-on-menu>li{
		width: 30%;
	}
	.selected-on-menu>a{
		color: #ffffff!important;
		width: 100%;
		margin: auto;
	}
	/* Custom hover effect of the HomePage */
	@media only screen and (min-width: 600px){
		#homepage:hover{
			font-size: 60px;
		}
	}

	/* removing arrows from Number input*/
	input[type=number] { 
	    -moz-appearance: textfield;
	    appearance: textfield;
	    margin: 0; 
	}
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
	      -webkit-appearance: none; 
	      margin: 0; 
	}

	/* CSS related to default buttons */
	.comments-button{
		text-decoration: none;
		width: auto;
		margin: auto;
		border: 1px solid #dddd;
		border-radius: 5px;
		padding: 2%;
		font-size: 20px;
	}
	.comments-button:hover{
		transition: 0.5s;
		color: #ffffff;
		background: rgba(218, 221, 90, 0.78);
	}

	/* Language selector icon */
	select{
	    -webkit-appearance:none;
	    appearance:none;
      -moz-appearance:none;
	}
	.sorting{
			padding:5px 10px;
			clear:both;
	}
	.sorting h4{
			padding:4px 0 0;
			margin:0;
	}
	.sort{
			position:relative;	
			padding-left:10px;
		  float:left;
	}
	.sort>label{
			font-weight:normal !important
	}
	.sort span.pointer{
			height:30px;
			width:30px;
			position:absolute;
			right:0;
			top:0;
			text-align:center;
			color:#c49633;
			font-size:20px;
			z-index:1;
	}
	.sort span.pointer i{
			margin-top:6px;
	}
	.sorting select{
			padding:5px 40px 5px 10px !important;
			background:none;
			height:30px;
			position:relative;
			z-index:2;
	}

	@media only screen and (max-width: 600px){
	  .sort{
	  	padding-left: 0px;
	  }
	  .sorting{
	  	margin-top: auto;
			margin-bottom: auto;
	  }
	  .user-menu-list{
	  	margin-right: 5%;
	  }
	  .dropdown-menu li{
	  	display: none;
	  	height: 50px;
	  }
	  .opened-dropdown-menu{
	  	height: 250px!important;
	  	flex-direction: column!important;
	  	justify-content: none;
	  	height: none;
	  	padding: 5%;
	  }
	  .opened-dropdown-menu li{
	  	display: block;
	  }
	  #menu-icon-bar{
	  	display: block!important;
	  	position: absolute;
			right: 10%;
			margin-top: 5%;
	  }
	  .menu-bar-oppened{
	  	color: #fff;
	  	transform: rotate(90deg);
			transition: 0.2;
	  }
	  #menu-icon-bar:hover{
	  	color: #fff;
	  	cursor: pointer;
	  	transition: 0.5s;
	  }
	  .homepage-icon > i{
	  	font-size: 1.5em;
	  }
	  .dropdown-menu a{
	  	font-size: 1.2em !important;
	  }
	}
</style>