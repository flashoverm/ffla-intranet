function showLoader(){
	  document.getElementById("overlay").style.display = "inline";		
}

function hideLoader(){
	  document.getElementById("overlay").style.display = "none";		
}


function closeOneModal(modalId) {

    const modal = document.getElementById(modalId);

    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    modal.setAttribute('style', 'display: none');

	const modalBackdrops = document.getElementsByClassName('modal-backdrop');
	if(modalBackdrops.length > 0){
		document.body.removeChild(modalBackdrops[0]);
	}
}