$('.close').on('click', function(){
	closeModal();
});
$('.confirm-modal').on('click', function(){
	closeModal();

});
$('.cancel-modal').on('click', function(){
	closeModal();
});

// Opens the modal
function openModal(elementId = 'modal1'){
	$('#'+elementId).show();
	$('#'+elementId).addClass('modal-opened');
	$('body').css('overflow', 'hidden');
}
// closes the modal
function closeModal(){
	$('.modal').hide();
	$('.modal').removeClass('modal-opened');
	$('body').css('overflow', 'auto');
}