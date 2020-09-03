function showLoader(){
	var overlay = document.getElementById("overlay");
	  overlay.style.display = "inline";		
}

function hideLoader(){
	var overlay = document.getElementById("overlay");
	overlay.style.display = "none";		
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

function showHideElement(id, show){
	var elem = document.getElementById(id);
	if(elem != null){
		if(show){
			elem.style.display = "inline";
		} else {
			elem.style.display = "none";
		}		
		
	}
}

function generatePassword(){
	return Math.random().toString(36).slice(-8);
}

function tooglePassword(id){
	var element = document.getElementById(id);
	if(element != null){
		if(element.type === 'password'){
			element.type = 'text';
		} else {
			element.type = "password";
		}
	}
}