const fetchQuestions = (sAuthor='', sCategory='', sSearch='') => {
	const fstatus = (response) => { return (response.status == 200) ? Promise.resolve(response) : Promise.reject(new Error(response.statusText)) }
	const json = (response) => { return response.json() }
	const handle = (data) => {
		let body = document.querySelector('body');
		let qform = document.createElement('form');
		qform.setAttribute('id', 'qform');
		body.appendChild(qform);
		for(let i = 0; i < data.length; i++) {
			let qf = document.getElementById('qform');
			let p = document.createElement('p');
			p.innerText = data[i][0];
			qf.appendChild(p);
			let fs = document.createElement('fieldset');
			qf.appendChild(fs);
			for(let j = 1; j < data[i].length; j++) {
				let iradio = document.createElement('input');
				iradio.setAttribute('type', 'radio');
				iradio.setAttribute('name', `answer${i}`);
				let lbl = document.createElement('label');
				lbl.setAttribute('for', `answer${i}`);
				lbl.innerText = data[i][j];
				
				//document.write(data[i][j]);
				fs.appendChild(iradio);
				fs.appendChild(lbl);
				fs.appendChild(document.createElement('br'));
			}
			

		}
		console.log(data);
	}
	const ferror = (error) => { console.log('Request failed: ', error) ; }
	fetch('create_questions_json.php').then(fstatus).then(json).then(handle).catch(ferror);
}