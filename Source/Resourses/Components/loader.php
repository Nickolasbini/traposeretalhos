<script type="text/javascript">
    function openLoader(open = true, scrolToThis = 0){
        if(open == true){
            var marginTop = $(document).scrollTop();
            $('#loader-overlay').addClass('overlay-open');
            $('#loader-overlay').show();
            //$('#loader').css('margin-top', marginTop+'px');
            $('#loader').show();
        }else{
            $('#loader-overlay').removeClass('overlay-open');
            $('#loader-overlay').hide();
            $('#loader').hide();
        }
    }
</script>
<style>
  #loader-overlay{
    display: none;
    z-index: 999;
  }
  #loader {
    border: 16px solid #fff;
    border-radius: 50%;
    border-top: 16px solid rgba(218, 221, 90, 0.78);
    width: 200px;
    height: 200px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
    display: block;
    margin: auto;
    margin-top: auto;
    margin-top: 5%;
  }

  /* Safari */
  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .overlay-open{
    position: fixed;
    background: rgba(0,0,0,0.4);
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
  }

  @media only screen and (max-width: 600px) {
    #loader {
        width: 120px;
        height: 120px;
    }
  }
</style>