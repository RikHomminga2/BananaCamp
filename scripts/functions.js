function fetchForm(inputForm){
	fetch(inputForm).then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	});		
}
function getLoginForm(){
	let inputForm = 'login.html';
	fetchForm(inputForm);
}
function getRegisterForm(){
	let inputForm = 'register.html';
	fetchForm(inputForm);	
}

