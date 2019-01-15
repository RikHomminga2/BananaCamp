const fstatus = (response) => {
	return (response.status == 200) ? Promise.resolve(response) : Promise.reject(new Error(response.statusText));
}
const json = (response) => {
	return response.json(); 
}
const ferror = (error) => {
	console.log('Request failed: ', error);
}
const fetchHTML = (url) => {
	fetch(url).then(fstatus).then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	}).catch(ferror);
}

function fetchQuestions() {
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=getQuestions'
	}
	fetch('main.php', post).then(fstatus).then(json).then(createQuestionsForm).catch(ferror);
}

function createQuestionsForm(obj) {
	let main = document.getElementById('getQuestions');
	let frm = document.createElement('form');
	frm.setAttribute('method', 'post');
	frm.setAttribute('action', 'main.php');
	main.appendChild(frm);
	for(x of obj){
		let ipt = document.createElement('input');
		let lbl = document.createElement('label');
		let br = document.createElement('br');
		ipt.setAttribute('type', 'checkbox');
		ipt.setAttribute('id', `question${x.id}`);
		lbl.setAttribute('for', `question${x.id}`);
		lbl.innerText = x.question +'?';
		frm.appendChild(ipt);
		frm.appendChild(lbl);
		frm.appendChild(br);
	}
	let ipt = document.createElement('input');
	ipt.setAttribute('type', 'submit');
	frm.appendChild(ipt);
}