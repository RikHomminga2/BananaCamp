function fetchExam2(){
	let post = {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'request=getExam'
	}
	fetch('main.php', post).then(fstatus).then(json).then(getResultExam).catch(ferror);	
}
function getResultExam(obj){
	let id = document.querySelector('input#hidden1').value; //exam id
	let q_id = document.querySelector('input#hidden2').value;// array questions id's
	let inputs = [];
	let res = [];
	let qids = [];
	let questions = JSON.parse(obj[0].q_id);
	// get result
	for(let i=0; i < questions.length; i++){
		let val = document.querySelector(`input[name="${questions[i]}"]:checked`).value; 
		inputs.push(val);
	}
	// loop all qs
	for(let j=0; j < obj[1].length; j++){
		// loop exam qs
		for(let i=0; i < questions.length; i++){
			// compare all qs to exam qs
			if(questions[i] == obj[1][j].id){
				//console.log(obj[1][j].answers[0]);
			}
		}
	}		
	//loop results
	for(let j=0; j < inputs.length; j++){
		console.log(obj);
		//console.log(obj[1][j]);
		console.log(obj[1][j].answers);
		//console.log(inputs[j]);
		/*
		if(inputs[j] == obj[1][j].answers[0]){
			console.log(inputs[j]);
			console.log(obj[1][j].answers[0]);
			res.push(true);
		}else{
			console.log(inputs[j]);
			console.log(obj[1][j].answers[0]);
			res.push(false);	
		}*/
	}
	
	console.log(res);
	//storeExamResult(id, res);
	//console.log(id, res);
}