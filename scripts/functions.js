const emptyMain = () => {
	document.querySelector('main').innerText = '';
}

const getRandomInt = (max=1000) => {
	return Math.floor(Math.random() * max) + 1;
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

const fetchFilterQuestions = (cat) => {
	let body = 'request=getFilterQuestions&category='+cat+'';
	fetchPostRequest(body, createQuestionsForm);
}

const fetchAssesment = () => {
	let body = 'request=getAssesment';
	fetchPostRequest(body, createAssesmentForm);
}

const fetchAllAssesments = () => {
	let body = 'request=getAllAssesments';
	fetchPostRequest(body, createAssesmentForm2);	
}

const fetchAssesmentResultForUser = () => {
	let body = 'request=getAssesmentResultForUser';
	fetchPostRequest(body, displayResult);
}

const fetchExamResultForUser = () => {
	let body = 'request=getExamResultForUser';
	fetchPostRequest(body, displayResultExam);
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

function fetchExam(id){
	let body = 'request=getExam&id='+id+'';
	fetchPostRequest(body, displayExam);
}

function fetchHtmlExams (){
	let body = 'request=getHtmlExams';
	fetchPostRequest(body, populateExamsSectionHtml);	
}

function fetchCssExams (){
	let body = 'request=getCssExams';
	fetchPostRequest(body, populateExamsSectionCss);	
}

function fetchJavascriptExams (){
	let body = 'request=getJavascriptExams';
	fetchPostRequest(body, populateExamsSectionJavascript);	
}

function fetchPhpExams (){
	let body = 'request=getPhpExams';
	fetchPostRequest(body, populateExamsSectionPhp);	
}

function fetchSqlExams (){
	let body = 'request=getSqlExams';
	fetchPostRequest(body, populateExamsSectionSql);	
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
	fetchUserInfo();
	populateUserBadges();
	fetchHtmlExams();
	fetchCssExams();
	fetchJavascriptExams();
	fetchPhpExams();
	fetchSqlExams();
	fetchDisplayResults();
	displayOverlay();
	populateAssesment();
}

function fetchDisplayResults(){
	let section = document.querySelector('#main-content-center');
	let nav = makeElement('nav');
	let btn_exams = makeElement('button',[['class', 'navbtn'], ['onclick', 'showExamsResults()']], 'Exam results');
	let btn_asses = makeElement('button',[['class', 'navbtn'],['onclick', 'showAssesmentsResults()']], 'Assesment results');
	section.appendChild(nav);
	nav.appendChild(btn_exams);
	nav.appendChild(btn_asses);
}

function showExamsResults(){
	document.querySelector('#main-content-center').innerText = '';
	fetchDisplayResults();
	fetchExamResultForUser();	
}
function showAssesmentsResults(){
	document.querySelector('#main-content-center').innerText = '';
	fetchDisplayResults();
	fetchAssesmentResultForUser();	
}

function displayResultExam(obj){
	let section = document.querySelector('#main-content-center');
	for(let i = 0; i < obj.length; i++){
		let dataSet = obj[i].results;
		let examResultContainer = makeElement('section',[['id', 'examResultContainer'+obj[i].id+'']]);
		section.appendChild(examResultContainer);
		let examData = makeElement('section',[['id', 'examData'+obj[i].id+'']]);
		examResultContainer.appendChild(examData);
		let title = makeElement('h2',[], obj[i].description);
		let cat = makeElement('h4',[], obj[i].category);
		let lvl = makeElement('h4',[], obj[i].level);
		let amount = makeElement('h4',[], obj[i].results.length+' questions');
		examData.appendChild(title);
		examData.appendChild(cat);
		examData.appendChild(lvl);	
		examData.appendChild(amount);
		let examDoughnut = makeElement('section',[['id', 'examDoughnut'+obj[i].id+'']]);
		examResultContainer.appendChild(examDoughnut);
		let count = 0;
		for(let i = 0; i < dataSet.length; i++){
			if(dataSet[i] == true){
				count++;
			}
		}
		let data = [{ "answer": "fout",   "count": dataSet.length-count}, { "answer": "goed",  "count": count}];
		let precentage = count / dataSet.length * 100;
		let res = Math.round(precentage);
		let stroke = '5px';
		let size = '26px'
		if(count == 0 || (count == dataSet.length)){
			stroke = '0px';
			size = '0px';
		}
		let margin = {top:20, right:20, bottom:20, left:20};
		let width  = 400 - margin.right - margin.left;
		let height = 400 - margin.top - margin.bottom;
		let radius = width/2;
		let color = d3.scaleOrdinal()
					.range(['#B80F0A' , '#228B22']);
		let arc = d3.arc()
					.outerRadius(radius - 10)
					.innerRadius(radius - 100);		
		let labelArc = d3.arc()
					.outerRadius(radius)
					.innerRadius(radius - 117);
		let pie = d3.pie()
					.sort(null)
					.value((d) => d.count);		
		let svg = d3.select('#examDoughnut'+obj[i].id+'').append('svg')
					.attr('width', width)
					.attr('height', height)
					.attr('id', 'doughnut'+obj[i].id)
					.append('g')
					.attr('transform', 'translate('+ width/2 +', '+height/2+')')			
		let g = svg.selectAll('.arc')
					.data(pie(data))
					.enter().append('g')
					.attr('class', 'arc');
		g.append('path')
					.attr('d', arc)
					.style('fill', (d) => color(d.data.answer))
					.attr('stroke', '#fff')
					.attr('stroke-width', stroke);
		g.append('text')
					.attr('transform',((d) => 'translate('+labelArc.centroid(d) +')'))	
					.attr("text-anchor", "middle")
					.style("fill", "#fff")  
					.attr("font-family", "Roboto Condensed", "sans-serif")
					.attr("font-size", size)
					.attr("font-weight", "bold")
					.text((d) => d.data.count);	
		g.append("text")
					.attr("text-anchor", "middle")
					.attr('font-size', '3em')
					.attr("font-family", "Roboto Condensed", "sans-serif")
					.attr("font-weight", "bold")
					.attr('y', 18)
					.text(res + '%');	
	}
}

function populateAssesment() {
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2', [], 'Assesment');
	let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchAssesment()']], 'Assesment');
	section.appendChild(h2);
	section.appendChild(btns);
}

function populateExamsSectionHtml(obj){
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2',[], 'Exams Html');
	section.appendChild(h2);
	for(let i = 0; i < obj.length; i++){
		let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchExam('+obj[i].id+')']], obj[i].description);
		section.appendChild(btns);
	}
}
	
function populateExamsSectionCss(obj){
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2',[], 'Exams Css');
	section.appendChild(h2);
	for(let i = 0; i < obj.length; i++){
		let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchExam('+obj[i].id+')']], obj[i].description);
		section.appendChild(btns);
	}
}

function populateExamsSectionJavascript(obj){
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2',[], 'Exams Javascript');
	section.appendChild(h2);
	for(let i = 0; i < obj.length; i++){
		let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchExam('+obj[i].id+')']], obj[i].description);
		section.appendChild(btns);
	}
}

function populateExamsSectionPhp(obj){
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2',[], 'Exams Php');
	section.appendChild(h2);
	for(let i = 0; i < obj.length; i++){
		let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchExam('+obj[i].id+')']], obj[i].description);
		section.appendChild(btns);
	}
}	

function populateExamsSectionSql(obj){
	let section = document.querySelector('#main-content-right');
	let h2 = makeElement('h2',[], 'Exams Sql');
	section.appendChild(h2);
	for(let i = 0; i < obj.length; i++){
		let btns = makeElement('button', [['class', 'navbtn'], ['onclick', 'fetchExam('+obj[i].id+')']], obj[i].description);
		section.appendChild(btns);
	}
}		

function populateUserBadges(obj) {
	let section = document.querySelector('#main-top-right');
	let figs = [];
	let img_urls = [['https://i.imgur.com/on2dxR0.png', 'HTML'], ['https://i.imgur.com/EOpWsok.png', 'CSS'], ['https://i.imgur.com/hRhGkUH.png', 'SQL'], ['https://i.imgur.com/OH1mR5y.png', 'PHP'], ['https://i.imgur.com/eHDzKet.png', 'Javascript']];
	let imgs = [];
	for(let i = 0; i < 5; i++) {
		figs.push(makeElement('figure'));
	}
	for(img_url of img_urls) {
		imgs.push(makeElement('img', [['src', img_url[0]], ['alt', `${img_url[1]} Badge`], ['title', `${img_url[1]} Badge`]]));
	}
	for(let i = 0; i < 5; i++) {
		section.append(figs[i]);
		figs[i].append(imgs[i]);
	}
	for(let i = 0; i < 5; i++) {
		let figcaption = makeElement('figcaption');
		for(let j = 0; j < 3; j++) {
			figcaption.appendChild(makeElement('i', [['class', 'far fa-star']]));
		}
		section.appendChild(figcaption);
	}
}

function displayOverlay() {
		let section = document.querySelector('#main-content-left');
		let edit = makeElement('i', [['class', 'far fa-edit'], ['id', 'show'] , ['onclick', 'on()']]);
		let br = makeElement('br');
		section.appendChild(edit);
		section.appendChild(br);
		let frm = makeElement('form', [['id', 'overlay'], ['method', 'post']]);
		let cross = makeElement('i', [['class', 'fas fa-times'], ['onclick', 'off()']]);
		let brk = makeElement('br');
		let h1 = makeElement('h1', [], 'Edit Bio');
		let txtarea = makeElement('textarea', [['rows', '10'], ['cols', '2'], ['class', 'iptfield'], ['name', 'bio'], ['id', 'bio']], 'Short description');
		let bk = makeElement('br');
		let github = makeElement('input', [['type', 'text'], ['class', 'iptfield'], ['name', 'github'], ['id', 'github'], ['pattern', 'https?://.+'], ['placeholder', 'Add GitHub URL']]);
		let linebr = makeElement('br');
		let linkedin = makeElement('input', [['type', 'text'], ['class', 'iptfield'], ['name', 'linkedin'], ['id', 'linkedin'], ['pattern', 'https?://.+'], ['placeholder', 'Add LinkedIn URL']]);
		let linebk = makeElement('br');
		let ipt = makeElement('input', [['type', 'submit'], ['value', 'Save'], ['class', 'formbtn'], ['onclick', 'updateUserInfo()']]);
		frm.appendChild(cross);
		frm.appendChild(brk);
		frm.appendChild(h1);
		frm.appendChild(txtarea);
		frm.appendChild(bk);
		frm.appendChild(github);
		frm.appendChild(linebr);
		frm.appendChild(linkedin);
		frm.appendChild(linebk);
		frm.appendChild(ipt);
		section.appendChild(frm);
}

function on() {
  document.getElementById("overlay").style.display = "block";
}

function off() {
  document.getElementById("overlay").style.display = "none";
}

function populateUserInfo(obj) {
	let section = document.querySelector('#main-top-left');
	let h2 = makeElement('h2', [], `${obj.firstname} ${obj.lastname}`);
	let hr = makeElement('hr', [['width', '50%'], ['color', '#202020']]);
	let github = makeElement('a', [['href', obj.github], ['target', '_blank']]);
	let linkedin = makeElement('a', [['href', obj.linkedin], ['target', '_blank']]);
	section.appendChild(h2);
	section.appendChild(hr);
	section.appendChild(github); github.appendChild(makeElement('span', [['class', 'fab fa-github']]));
	section.appendChild(linkedin); linkedin.appendChild(makeElement('span', [['class', 'fab fa-linkedin-in']]));	
	section = document.querySelector('#main-content-left');
	obj.bio = obj.bio == null ? '' : obj.bio;
	let bio = makeElement('p', [], `About me: ${obj.bio}`);
	section.appendChild(bio);
}

function updateUserInfo() {
	let bio = document.getElementById("bio").value;
	let github = document.getElementById("github").value; if(!github.startsWith('https://')) { github = `https://${github}`; }
	let linkedin = document.getElementById("linkedin").value; if(!linkedin.startsWith('https://')) { linkedin = `https://${github}`; }
	let body = `request=updateUserInfo&bio=${bio}&linkedin=${linkedin}&github=${github}`;
	fetchPostRequest(body, redirect);
}

function filterQuestions(){
	let cat = document.querySelector('#filter').value;
	fetchFilterQuestions(cat);
}

function createQuestionsForm(obj) {
	document.querySelector('#get-questions').innerHTML = '';
	let elem = document.getElementById('get-questions');
	let frm = makeElement('form', [['method', 'post'], ['action', 'main.php'], ['id', 'create-exam']]);
	let ipt = makeElement('input', [['type', 'text'], ['name', 'description'], ['class', 'iptfield'], ['placeholder', 'Description of Exam'], ['required', true]]);
	let br = makeElement('br');
	elem.appendChild(frm);
	frm.appendChild(ipt); 
	let select = makeElement('select',[['class', 'formbtn'],['onchange', 'filterQuestions()'],['id', 'filter']]);
	frm.appendChild(select);
	let cat = document.createElement('option');
	cat.setAttribute("value", "");
	cat.setAttribute("disabled", "");
	cat.setAttribute("selected", "");
	cat.innerText = 'Category';
	select.appendChild(cat);
	let options = ['html', 'css', 'javascript', 'php', 'sql'];
	for(let i = 0; i < options.length; i++){
		let option = makeElement('option',[['value', options[i]]], options[i])
		select.appendChild(option);	
	}
	frm.appendChild(br);
	for(let q of obj) {
		let checkbx = makeElement('input', [['type', 'checkbox'], ['id', `question${q.id}`], ['name', `question${q.id}`], ['value', q.id]]);
		let lbl = makeElement('label', [['class', 'lbl'], ['for', `question${q.id}`]], `${q.question}?`);
		let spn = makeElement('span', [['class', 'checkbox']]);
		let br = makeElement('br');
		lbl.appendChild(checkbx); frm.appendChild(lbl); lbl.appendChild(spn); lbl.appendChild(br);
	}	
	let selectLevel = makeElement('select', [['form', 'create-exam'], ['name', 'level'], ['class', 'formbtn']]);
	let optgroupLevel = makeElement('optgroup', [['label', 'level']]);
	selectLevel.appendChild(optgroupLevel);
	for(let lvl of ['easy', 'moderate', 'hard']) {
		opti = makeElement('option', [['value', lvl]], lvl);
		optgroupLevel.appendChild(opti);
	}
	let selectCategory = makeElement('select', [['form', 'create-exam'], ['name', 'category'], ['class', 'formbtn']]);
	let optgroupCategory = makeElement('optgroup', [['label', 'category']]);
	selectCategory.appendChild(optgroupCategory);
	for(let cat of ['html', 'css', 'javascript', 'php', 'sql']) {
		opti = makeElement('option', [['value', cat]], cat);
		optgroupCategory.appendChild(opti);
	}
	let brk = makeElement ('br');
	let hide = makeElement('input', [['type', 'hidden'], ['name', 'request'], ['value', 'createExam']]);
	let subm = makeElement('input', [['type', 'submit'], ['class', 'formbtn']]);
	frm.appendChild(selectCategory); frm.appendChild(selectLevel); frm.appendChild(brk);
	frm.appendChild(hide); frm.appendChild(subm);
}

function createAssesmentForm(obj) {
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let main = document.querySelector('main');
	main.setAttribute('class', 'main-assesment');
	let h1 = makeElement('h1', [], obj.title);
	let h2 = makeElement('h2', [], obj.description);
	let section = document.createElement('section');
	section.setAttribute('class', 'sliders');
	let datalist = makeElement('datalist', [['id', 'tickmarks'], ['style', 'display: visible']]);
	let hide = makeElement('input', [['type', 'hidden'], ['value', `${obj.id}`], ['id', 'hidden']]);
	let btn = makeElement('button', [['onclick', 'getResult()'], ['class', 'formbtn']], 'Submit');
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
	section.appendChild(btn);
}

function createAssesmentForm2(obj){
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }	
	let main = document.querySelector('main');
	let h1 = makeElement('h1', [], obj[0].title);
	let h2 = makeElement('h2', [], obj[0].description);
	let section = document.createElement('section');
	section.setAttribute('class', 'sliders');
	let datalist = makeElement('datalist', [['id', 'tickmarks'], ['style', 'display: visible']]);
	let hide = makeElement('input', [['type', 'hidden'], ['value', `${obj[0].id}`], ['id', 'hidden']]);
	let btn = makeElement('button', [['onclick', 'getResult()'], ['class', 'formbtn']], 'Submit');
	main.appendChild(h1);
	main.appendChild(h2);
	section.appendChild(datalist);
	section.appendChild(hide);
	for(let i = 0; i < 101; i+=10) {
		let opt = makeElement('option', [['value', `${i}`]]);
		datalist.appendChild(opt);
	}
	for(let category of JSON.parse(obj[0].assesment)) {
		let span = makeElement('span', [['class', 'skill']]);
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
	section.appendChild(btn);
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

function createQuestionElementsArray(question) {
	let stager = [];
	let fs = makeElement('fieldset', [['id', question.id], ['class', 'fieldset']]);
	let legend = makeElement('legend', [], `${question.question}?`);
	fs.appendChild(legend);
	for(let i = 0; i < question.answers.length; i++) {
		let iradio = makeElement('input', [['type', 'radio'], ['value', `${question.id}_${i}`], ['name', `${question.id}`], ['id', `${question.id}_${i}`]]);
		let lbl = makeElement('label', [['class', 'label'], ['for', `${question.id}_${i}`]], question.answers[i]);
		let spn = makeElement('span', [['class', 'checkmark']]);
		lbl.appendChild(iradio); lbl.appendChild(spn); 
		getRandomInt(2) === 1 ? fs.appendChild(lbl) : fs.prepend(lbl);
	}
	return[fs];
}

function displayExam(obj) {
	emptyMain();
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let exam = obj[0];
	let questions = obj[1];
	let main = document.querySelector('main');
	main.setAttribute('class', 'main-exam');
	let section = makeElement('section', [['id', 'exam']])
	let btn = makeElement('button', [['onclick', 'getResultExam()'], ['class', 'formbtn']], 'Submit');
	let hide = makeElement('input', [['type', 'hidden'], ['value', `${exam.id}`], ['id', 'exams_id']]);
	main.appendChild(section);
	section.appendChild(hide);
	
	let question_ids = exam.question_ids;
	for(let i = 0; i < question_ids.length; i++) {
		for(let question of questions) {
			if(question.id == question_ids[i]) {
				for(let elem of createQuestionElementsArray(question)) { 
					section.appendChild(elem); 
				}
			}
		}
	}
	section.appendChild(btn);
}

function getResultExam() {
	let id = document.getElementById('exams_id').value;
	let res = [];
	for(let fs of document.querySelectorAll('fieldset')) {
		let elem = document.getElementById(fs.id + '_0');
		res.push(elem.checked);
	}
	storeExamResult(id, res);
}