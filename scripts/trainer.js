//window.load = fetchQuestions();
function changeTitle(s) {
	document.querySelector('title').innerText = s;
	return true;
}

function toggle(id) {
	for(let section of document.querySelectorAll('section')) {
		if(section.id == id) {
			section.style.display = 'block';
		} else {
			section.style.display = 'none';
		}
	}
	if(id == 'getQuestions') { document.getElementById(id).innerText = ''; fetchQuestions(); }
}
