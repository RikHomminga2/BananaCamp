const fstatus = (response) => {
		return (response.status == 200) ? Promise.resolve(response) : Promise.reject(new Error(response.statusText));
	}
	
const json = (response) => {
	return response.json(); 
}

const ferror = (error) => {
	console.log('Request failed: ', error);
}
const fetchForm = (inputForm) => {
	fetch(inputForm).then(fstatus).then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	}).catch(ferror);
}
const getLoginForm = () => {
	let inputForm = 'login.html';
	fetchForm(inputForm);
}
const getRegisterForm = () => {
	let inputForm = 'register.html';
	fetchForm(inputForm);
}

