<script type="text/javascript">
	function openToast(message = ''){
		var x = document.getElementById("messager");
		x.className = "show";
		x.innerText = message;
		x.style.display = 'block';
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
	}

	$('#messager').on('click', function(){
	    $('#messager').fadeOut();
	});
</script>

<style type="text/css">
	#messager {
	  cursor: pointer;
	  visibility: hidden;
	  min-width: 250px;
	  margin-left: -125px;
	  background-color: #333;
	  color: #fff;
	  text-align: center;
	  border-radius: 2px;
	  padding: 16px;
	  position: fixed;
	  z-index: 1000;
	  left: 50%;
	  top: 5%;
	  font-size: 17px;
	}

	#messager.show {
	  visibility: visible;
	  -webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
	  animation: fadein 0.5s, fadeout 0.5s 3.5s;
	}

	@-webkit-keyframes fadein {
	  from {top: 0; opacity: 0;} 
	  to {top: 5%; opacity: 1;}
	}

	@keyframes fadein {
	  from {top: 0; opacity: 0;}
	  to {top: 5%; opacity: 1;}
	}

	@-webkit-keyframes fadeout {
	  from {top: 5%; opacity: 1;} 
	  to {top: 0; opacity: 0;}
	}

	@keyframes fadeout {
	  from {top: 5%; opacity: 1;}
	  to {top: 0; opacity: 0;}
	}
</style>