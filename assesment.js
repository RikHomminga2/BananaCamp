/*
let assesment = {
	question: "What is your skill level on a scale from 1 - 10",
	categories: ['html', 'css', 'javascript', 'php', 'sql']
};
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

function createAssesmentForm(obj) {
	if(!obj || typeof obj != 'object') { throw new Error('not an object'); }
	let main = document.querySelector('main');
	let h1 = document.createElement('h1');
	let p = document.createElement('p')
	let frm = document.createElement('form');
	let btn = document.createElement('button');
	frm.setAttribute('method', 'post');
	frm.setAttribute('action', 'main.php');
	frm.setAttribute('id', 'assesment');
	h1.innerText = 'Self-Assesment';
	p.innerText = obj.question;
	btn.setAttribute('onclick', 'getResult()');
	btn.innerText = 'submit';
	main.appendChild(h1);
	main.appendChild(p);
	main.appendChild(frm)
	for(let category of obj.categories) {
		let hr = document.createElement('hr');
		let h2 = document.createElement('h3');
		let fs = document.createElement('fieldset');
		fs.setAttribute('id', category);
		h2.innerText = category;
		frm.appendChild(hr);
		frm.appendChild(h2);
		frm.appendChild(fs)
		for(let i = 0; i < 10; i++) {
			let iradio = document.createElement('input');
			if(i == 0) { iradio.setAttribute('required', 'true'); }
			iradio.setAttribute('type', 'radio');
			iradio.setAttribute('id', `${category}${i}`);
			iradio.setAttribute('name', category);
			iradio.setAttribute('value', i+1);
			fs.appendChild(iradio)
		}
	}	main.appendChild(btn);
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
	//fetch('main.php', post).then(json).then(console.log);
}

function getResult() {
	let res = [];
	let cats = ['html', 'css', 'js', 'php', 'sql'];
	for(let cat of cats) {
		let val = document.querySelector(`input[name="${cat}"]:checked`).value; 
		res.push((val == 'on') ? 0 : parseInt(val));
	}
	displayResult(res);
}

function displayResult(dataset) {
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
					
	

			
	
	
	
	