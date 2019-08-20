window.onload = function() {

	var urls = document.getElementById("links").value;
	var readyCommand = document.getElementById('readyCommand');
	var mirrorInput = function(){
//		readyCommand.innerHTML = e.value;

		var urls = document.getElementById("links").value;
		urls = urls.replace(/\n/g, " ");
		urls = urls.replace(/,/g, " ");
		var output = "youtube-dl ";
		var asMp3 = document.getElementById("asMp3");
		if(asMp3.checked) {
			output = output.concat(" ", "-x --audio-format mp3");
		}
		var additionalArguments = document.getElementById("additionalArguments").value;
		output = output.concat(" ", additionalArguments);
		output = output.concat(" ", urls);
		//document.getElementById("ready").innerHTML = output;
		readyCommand.innerHTML = output;
	}

	var l = document.getElementById('links');
	var m = document.getElementById('asMp3');
	var a = document.getElementById('additionalArguments');
	l.oninput = mirrorInput;
	m.onchange = mirrorInput;
	a.oninput = mirrorInput;
	e.onpropertychange = e.oninput; // for IE8
	// e.onchange = e.oninput; // FF needs this in <select><option>...
	// other things for onload()

}

