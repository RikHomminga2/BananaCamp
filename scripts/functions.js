const emptyMain = () => {
	document.querySelector('main').innerText = '';
}

/**
 * fetch helper functions
 */
const fstatus = (response) => {
	return (response.status == 200) ? Promise.resolve(response) : Promise.reject(new Error(response.statusText));
}

const json = (response) => {
	return response.json(); 
}

const ferror = (error) => {
	console.log('Request failed: ', error);
}

/**
 * generic wrapper to fetch html/text file
 * @param {string} url
 * @param {function} handle
 */
const fetchHTML = (url) => {
	fetch(url).then(fstatus).then(function(response) {
		return response.text();
	}).then(function(data) {
		document.querySelector('main').innerHTML = data;
	}).catch(ferror);
}

/**
 * generic wrapper to fetch json data using http post requests
 * @param {string} body
 * @param {function} handle
 */
const fetchPostRequest = (body, handle, url='main.php') => {
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: body
	}
	fetch(url, post).then(fstatus).then(json).then(handle).catch(ferror);
}

const fetchQuestions = () => {
	let body = 'request=getQuestions';
	fetchPostRequest(body, createQuestionsForm);
}

const fetchAssesment = () => {
	let body = 'request=getAssesment';
	fetchPostRequest(body, createAssesmentForm);
}

const fetchAssesmentResultForUser = () => {
	let body = 'request=getAssesmentResultForUser';
	fetchPostRequest(body, displayResult);
}

const storeAssesmentResult = (id, res) => {
	let body = 'request=storeAssesmentResult&id='+id+'&result='+JSON.stringify(res)+'';
	fetchPostRequest(body, redirect);
}

const storeExamResult = (id, res) => {
	let body = 'request=storeExamResult&id='+id+'&result='+JSON.stringify(res)+'';
	fetchPostRequest(body, redirect);
}

const fetchUserInfo = () => {
	let body = 'request=getUserInfo';
	fetchPostRequest(body, populateUserInfo);
}

const fetchSignOut = () => {
	let body = 'request=logout';
	fetchPostRequest(body, redirect);
}

const redirect = (obj) => {
	location.href = obj.result ? obj.page : 'error.php';
}

function makeElement(type, attributes=[], innerText='') {
	let el = document.createElement(type);
	for(let attribute of attributes) {
		el.setAttribute(attribute[0], attribute[1]);
	}
	el.innerText = innerText;
	return el;
}

function populateProfile() {
	fetchAssesmentResultForUser();
	fetchUserInfo();
}

function populateUserInfo(obj) {
	console.log(obj);
	let section = document.querySelector('#main-top-left');
	let h2 = makeElement('h2', [], `${obj.firstname} ${obj.lastname}`);
	let hr = makeElement('hr', [['width', '50%'], ['color', '#202020']]);
	let github = makeElement('a', [['href', obj.github]]);
	let linkedin = makeElement('a', [['href', obj.linkedin]]);
	section.appendChild(h2);
	section.appendChild(hr);
	section.appendChild(github); github.appendChild(makeElement('span', [['class', 'fab fa-github']]));
	section.appendChild(linkedin); linkedin.appendChild(makeElement('span', [['class', 'fab fa-linkedin-in']]));	
	section = document.querySelector('#main-content-left');
	let bio = makeElement('p', [], `bio: ${obj.bio}`);
	section.appendChild(bio);
}

function createQuestionsForm(obj) {
	let elem = document.getElementById('getQuestions')
	let frm = makeElement('form', [['method', 'post'], ['action', 'main.php']]);
	let ipt = makeElement('input', [['type', 'text'], ['name', 'description'], ['placeholder', 'Description of Exam']]);
	let br = makeElement('br');
	elem.appendChild(frm);
	frm.appendChild(ipt); frm.appendChild(br);
	for(let q of obj) {
		let checkbx = makeElement('input', [['type', 'checkbox'], ['id', `question${q.id}`], ['name', `question${q.id}`], ['value', q.id]]);
		let lbl = makeElement('label', [['for', `question${q.id}`]], `${q.question}?`);
		let br = makeElement('br');
		frm.appendChild(checkbx); frm.appendChild(lbl); frm.appendChild(br);
	}
	let hide = makeElement('input', [['type', 'hidden'], ['name', 'request'], ['value', 'createExam']]);
	let subm = makeElement('input', [['type', 'submit']]);
	frm.appendChild(hide); frm.appendChild(subm);
}

function createAssesmentForm(obj) {
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }	
	let main = document.querySelector('main');
	let h1 = makeElement('h1', [], obj.title);
	let h2 = makeElement('h2', [], obj.description);
	let section = document.createElement('section');
	section.setAttribute('class', 'sliders');
	let datalist = makeElement('datalist', [['id', 'tickmarks'], ['style', 'display: visible']]);
	let hide = makeElement('input', [['type', 'hidden'], ['value', `${obj.id}`], ['id', 'hidden']]);
	let btn = makeElement('button', [['onclick', 'getResult()'], ['class', 'btn']], 'Submit');
	main.appendChild(h1);
	main.appendChild(h2);
	section.appendChild(datalist);
	section.appendChild(hide);
	for(let i = 0; i < 101; i+=10) {
		let opt = makeElement('option', [['value', `${i}`]]);
		datalist.appendChild(opt);
	}
	for(let category of JSON.parse(obj.assesment)) {
		let span = makeElement('span');
		let h3 = makeElement('h3', [], category);
		section.appendChild(h3);
		span.innerText = 'bad';
		section.appendChild(span);
		ipt = makeElement('input', [['type', 'range'], ['list', 'tickmarks'], ['min', '0'], ['max', '100'], ['step', '10'], ['name', `${category}`]]);
		span = makeElement('span');
		span.innerText = 'excellent';
		section.appendChild(ipt);
		section.appendChild(span);
		section.appendChild(makeElement('br'));
	}
	main.appendChild(section);
	main.appendChild(btn);
}

function getResult() {
	let res = [];
	let id = false;
	let ipts = document.querySelectorAll('input');
	for(let ipt of ipts) {
		if(ipt.id != 'hidden') { res.push(ipt.value); }
		else { id = ipt.value; }
	}
	storeAssesmentResult(id, res);
}

function displayResult(obj) {
	let title =makeElement('h2', [], obj.title);
	let main = document.getElementById("main-content-center");
	main.appendChild(title);
	const dataset =  obj.results.map(x => parseInt(x));
	const w = 600;
    const h = 150;
	const barPadding = 5;
	const svg = d3.select("#main-content-center")
                .append("svg")
                .attr("width", w)
                .attr("height", h + 35)
				.attr("viewBox", "0 0 " + w + " " + h )
				.attr("preserveAspectRatio", "xMidYMid")
				.attr("id", "chart");			
	// create bars
    svg.selectAll("rect")
       .data(dataset)
       .enter()
       .append("rect")
       .attr("x", (d, i) => i * w / dataset.length)
       .attr("y", (d, i) => h - d)
       .attr("width", w / dataset.length - barPadding)
	   .attr("height", (d, i) => d)
       .attr("fill", (d => (d < 60) ? "red" : "green"))
       .attr("class", "bar")
		// create tooltip
       .append("title")
	   .attr("class", "tooltip")
       .text((d, i) => (obj.assesment,obj.assesment[i]));  
	// add text
	svg.selectAll("text")
       .data(dataset)
       .enter()
       .append("text")
	   .attr("font-family", "Roboto Condensed", "sans-serif")
	   .attr("font-size", "16px")
	   .attr("font-weight", "bold")
	   .attr("fill", "#000")
	   .attr("text-anchor", "middle")
       .text((d) => d)
       .attr("x", (d, i) => i * (w / dataset.length) + (w / dataset.length - barPadding) / 2)
       .attr("y", (d, i) => h - (d  + 4) );
	// resize
	let chart = $("#chart");
	let aspect = chart.width() / chart.height();
	let container = chart.parent();	
	$(window).on("resize", function(){
		let targetWidth = container.width();
		chart.attr("width", targetWidth);
		chart.attr("height", Math.round(targetWidth / aspect));
	}).trigger("resize");    
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
	let hide1 = makeElement('input', [['type', 'hidden'], ['value', `${obj[0].id}`], ['id', 'hidden1']]);
	let hide2 = makeElement('input', [['type', 'hidden'], ['value', `${obj[0].q_id}`], ['id', 'hidden2']]);
	let btn = makeElement('button', [['onclick', 'getResultExam()'], ['class', 'btn']], 'Submit');
	main.appendChild(title);
	main.appendChild(frm);
	main.appendChild(hide1);
	main.appendChild(hide2);
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
					let stager = [['type', 'radio'], ['id', `${obj[1][q].question}`], ['name', obj[1][q].id], ['value', i+1]];
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

function getResultExam(obj) {
	let id = document.querySelector('input#hidden1').value;
	let q_id = document.querySelector('input#hidden2').value;
	let q_ids = JSON.parse(q_id);
	let res = [];
	for(let i=0; i < q_ids.length; i++){
		let val = document.querySelector(`input[name="${q_ids[i]}"]:checked`).value; 
		res.push(val);
	}
	storeExamResult(id, res);
}