function getLoginForm(){
	fetch('login.html').then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	});				
}
function getRegisterForm(){
	fetch('register.html').then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	});				
}