<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
	<title><?php echo $title ?></title>

	<!-- importing jquery -->
	<script src="Source/Resourses/External/jquery/jquery-3.5.1.js"></script>
	<!-- importing FontAwesome -->
	<link href="Source/Resourses/External/fontawesome/css/all.css" rel="stylesheet"></link>
</head>
<header>
	<nav id="top-navigation-bar">
		<div class="user-menu-options">
			<div class="banner-option"  title="<?php echo ucfirst(translate('select your language')) ?>">
			<img src="Source/Resourses/External/icons/<?php echo $_SESSION['userLanguage'] ?>.svg">
			<select class="language-selector">
				<option id="pt"><?php echo ucfirst(translate('portuguese')); ?></option>
				<option id="en"><?php echo ucfirst(translate('english')); ?></option>
				<option id="es"><?php echo ucfirst(translate('spanish')); ?></option>
			</select>
			</div>
			<a class="homepage-icon" href="<?php echo '/'.URL['urlDomain']; ?>" title="<?php echo ucfirst(translate('go to homepage')); ?>">
	    		<i class="fas fa-home"></i>
	    	</a>
		</div>

		<div class="header-logo">
			<img src="Source/Resourses/External/icons/logo-line&needle.svg">
			<h1><?php echo APP['appName'] ?></h1>
		</div>
	</nav>
	<div class="divider"></div>
</header>
<body>
	<section class="login-form-section">
		<h2><?php echo ucfirst(translate('enter in you account')); ?></h2>
		<div class="login-wrapper">
			<form class="login-form" method="post" action="login">
				<input type="text" name="email" placeholder="<?php echo ucfirst(translate('email')) ?>">
				<input type="password" name="password" placeholder="<?php echo ucfirst(translate('password')) ?>">
				<input class="submit-btn" type="submit" value="<?php echo ucfirst(translate('enter')) ?>">
			</form>
			<div class="login-additional-options">
				<a href="login/retrievepassword"><?php echo ucfirst(translate('i forgot my password')) ?></a>
				<a href="newaccount"><?php echo ucfirst(translate('create new account')) ?></a>
			</div>
		</div>
	</section>
	<br><br>
	<?php $this->insert('UserViews/user-footer') ?>

</body>

<script type="text/javascript">
	$(document).ready(function() {
	    var messages = "<?= isset($_SESSION['messages']) ? $_SESSION['messages'] : '' ?>";
		if(messages !== ''){
			alert(messages);
			cleanSystemMessages();
		}
	});
	setLanguageOptions();
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
	// sets language at banner to the correspondent selected language
	function cleanSystemMessages(){
		$.ajax({
			url: "systemmessages/clean",
			type: 'post'
  		});
	}

</script>

<style type="text/css">
	body{
		background: #F0F0F0;
	}
	a, select, img{
		cursor: pointer;
	}
	*{
		font-family: Sen;
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
		background: #F5F4F4;
	}
	.language-selector {
	  -moz-appearance: none; /* for Firefox */
	  -webkit-appearance: none;/* for Chrome */
	}
	.language-selector::-ms-expand {
	  display: none;/* For IE10 */
	}
	.homepage-icon{
		font-size: 50px;
		color: rgba(0, 0, 0, 0.33);
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
	.divider{
		height: 1px;
		width: 100%;
		background: #C4C4C4;
	}

	/* login form related CSS */
	.login-form-section{
		text-align: center;
	}
	.login-wrapper{
		padding: 5%;
		margin: 5%;
		background: #ffffff;
		border: 0.5px solid rgba(0, 0, 0, 0.5);
		border-radius: 5px;
	}
	.login-form{
		display: grid;
		width: 50%;
		margin: auto;
		grid-row-gap: 20px;	
		margin-bottom: 5%;
	}
	.login-form > input{
		border-radius: 5px;
		background: #F5F4F4;
		height: 30px;
		border: 0.5px solid rgba(0, 0, 0, 0.5);
		text-indent: 10px;
	}
	.login-form > input::placeholder{
		margin: 5px;
		color: rgba(0, 0, 0, 0.25);
	}
	.login-form > input::::-webkit-input-placeholder { /* Edge */
	    color: rgba(0, 0, 0, 0.25);
	}

	.login-form > input:::-ms-input-placeholder { /* Internet Explorer 10-11 */
	    color: rgba(0, 0, 0, 0.25);
	}
	.submit-btn{
		width: 30%;
		margin: auto;
		background: rgba(0, 0, 0, 0.5)!important;
		text-indent: unset!important;
		color: #ffffff;
		padding: 4%!important;
		border: 1px solid #ffffff!important;
		height: unset!important;
	}
	.submit-btn:hover{
		transition: 0.5s;
		color: #ffffff!important;
		background: rgba(218, 221, 90, 0.78)!important;
	}
	.login-additional-options>a{
		text-decoration: none;
		color: #000000;
		margin: 5%;
		padding: 2%;
		border-radius: 5px;
	}
	.login-additional-options>a:hover{
		transition: 0.5s;
		color: #ffffff;
		background: rgba(218, 221, 90, 0.78);
	}
	input, a{
		cursor: pointer;
	}
</style>