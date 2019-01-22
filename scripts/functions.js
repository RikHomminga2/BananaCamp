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
	let inp = document.createElement('input');
	inp.setAttribute('type', 'text');
	inp.setAttribute('name', 'description');
	inp.setAttribute('placeholder', 'Description of Exam');
	let br = document.createElement('br');
	frm.appendChild(inp);
	frm.appendChild(br);
	for(x of obj) {
		let ipt = document.createElement('input');
		let lbl = document.createElement('label');
		let br = document.createElement('br');
		ipt.setAttribute('type', 'checkbox');
		ipt.setAttribute('id', `question${x.id}`);
		ipt.setAttribute('name', `question${x.id}`);
		ipt.setAttribute('value', x.id);
		lbl.setAttribute('for', `question${x.id}`);
		lbl.innerText = x.question +'?';
		frm.appendChild(ipt);
		frm.appendChild(lbl);
		frm.appendChild(br);
	}
	let hidden = document.createElement('input');
	hidden.setAttribute('type', 'hidden');
	hidden.setAttribute('name', 'request');
	hidden.setAttribute('value', 'createExam');
	frm.appendChild(hidden);
	let ipt = document.createElement('input');
	ipt.setAttribute('type', 'Submit');
	frm.appendChild(ipt);
}

function createAssesmentForm(obj) {
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let main = document.querySelector('main');
	let h1 = makeElement('h1', [], obj.title);
	let p = makeElement('p', [], obj.description);
	let frm = makeElement('form', [['method', 'post'], ['action', 'main.php'], ['id', 'assesment']]);
	let btn = makeElement('button', [['onclick', 'getResult('+obj.id+','+obj.assesment+')']], 'submit');
	main.appendChild(h1);
	main.appendChild(p);
	main.appendChild(frm);
	for(let category of JSON.parse(obj.assesment)) {
		let hr = makeElement('hr');
		let h2 = makeElement('h2', [], category);
		let fs = makeElement('fieldset', [['id', category]]);
		frm.appendChild(hr);
		frm.appendChild(h2);
		frm.appendChild(fs);
		for(let i = 0; i < 10; i++) {
			let stager = [['type', 'radio'], ['id', `${category}${i}`], ['name', category], ['value', i+1]];
			if(i == 0) { stager.push(['required', 'true']); }
			let iradio = makeElement('input', stager);
			fs.appendChild(iradio);
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

function getResult(id, assesments) {
	let res = [];
	let cats = assesments;
	for(let cat of cats) {
		let val = document.querySelector(`input[name="${cat}"]:checked`).value; 
		res.push((val == 'on') ? 0 : parseInt(val));
	}
	//displayResult(res);
	storeAssesmentResult(id, res);
}

function storeAssesmentResult(id, res){
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=storeAssesmentResult&id='+id+'&result='+JSON.stringify(res)+''
	}
	fetch('main.php', post).catch(ferror);	
}

function getUserResultsAssesments() {
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=getUserResultsAssesments'
	}
	fetch('main.php', post).then(fstatus).then(json).then(displayResult).catch(ferror);
	
}

function displayResult(obj) {
	let res = obj;
	let dataset = JSON.parse(res.results);
	//emptyMain();
	let title =makeElement('h2', [], res.title);
	let main = document.querySelector("main");
	main.appendChild(title);
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

function fetchExam(){
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=getExam'
	}
	fetch('main.php', post).then(fstatus).then(json).then(displayExam).catch(ferror);	
}

function displayExam(obj){
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let main = document.querySelector('main');
	let title = makeElement("h1",[], obj[0].description);
	let frm = makeElement('form', [['method', 'post'], ['action', 'main.php'], ['id', 'exam']]);
	let btn = makeElement('button', [['onclick', 'getResultExam('+obj[0].id+','+obj[0].description+')']], 'submit');
	main.appendChild(title);
	main.appendChild(frm);
	for(let num of JSON.parse(obj[0].q_id)){
		for (let q in obj[1]){
			if(num == obj[1][q].id){
				let h2 = makeElement('h2', [], obj[1][q].question);
				let fs = makeElement('fieldset', [['id', obj[1][q].question]]);
				let br = makeElement('br');
				frm.appendChild(br);
				frm.appendChild(h2);
				frm.appendChild(fs);		
				for(let i=0; i < obj[1][q].answers.length; i++ ){
					let h4 = makeElement('span', [], obj[1][q].answers[i]);
					let stager = [['type', 'radio'], ['id', `${obj[1][q].question}`], ['name', obj[1][q].question], ['value', i+1]];
					if(i == 0) { stager.push(['required', 'true']); }
					let iradio = makeElement('input', stager);
					fs.appendChild(h4);
					fs.appendChild(iradio);
				}
			}
		}
	}
	main.appendChild(btn);
}
