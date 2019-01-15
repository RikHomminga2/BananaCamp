const emptyMain = () => {
	document.querySelector('main').innerText = '';
}
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

function makeElement(type, attributes=[], innerText='') {
	let el = document.createElement(type);
	for(let attribute of attributes) {
		el.setAttribute(attribute[0], attribute[1]);
	}
	el.innerText = innerText;
	return el;
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

function createAssesmentForm(obj) {
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let main = document.querySelector('main');
	let h1 = makeElement('h1', [], 'Self-Assesment');
	let p = makeElement('p', [], obj.question);
	let frm = makeElement('form', [['method', 'post'], ['action', 'main.php'], ['id', 'assesment']])
	let btn = makeElement('button', [['onclick', 'getResult()']], 'submit');
	main.appendChild(h1);
	main.appendChild(p);
	main.appendChild(frm)
	for(let category of obj.categories) {
		let hr = makeElement('hr');
		let h2 = makeElement('h2', [], category);
		let fs = makeElement('fieldset', [['id', category]])
		frm.appendChild(hr);
		frm.appendChild(h2);
		frm.appendChild(fs)
		for(let i = 0; i < 10; i++) {
			let stager = [['type', 'radio'], ['id', `${category}${i}`], ['name', category], ['value', i+1]];
			if(i == 0) { stager.push(['required', 'true']); }
			let iradio = makeElement('input', stager);
			fs.appendChild(iradio)
		}
	}
	main.appendChild(btn);
}

function fetchAssesment() {
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=getAssesment'
	}
	fetch('main.php', post).then(fstatus).then(json).then(createAssesmentForm).catch(ferror);
}

function getResult() {
	let res = [];
	let cats = ['html', 'css', 'js', 'php', 'sql'];
	for(let cat of cats) {
		let val = document.querySelector(`input[name="${cat}"]:checked`).value; 
		res.push((val == 'on') ? 0 : parseInt(val));
	}
	displayResult(res);
	storeAssesment(res);
}

function storeAssesment(res){
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=storeAssesment&result='+JSON.stringify(res)+''
	}
	fetch('main.php', post).catch(ferror);	
}

function displayResult(dataset) {
	emptyMain();
	const w = 500;
	const h = 100;
	const svg = d3.select("main")
		.append("svg")
		.attr("width", w)
		.attr("height", h);
	svg.selectAll("rect").data(dataset).enter()
		.append("rect")
		.attr("x", (d, i) => i * 30)
		.attr("y", (d, i) => h - 10 * d)
		.attr("width", 25)
		.attr("height", (d, i) => d * 100)
		.attr("fill", (d => (d < 6) ? "red" : "green"));
}