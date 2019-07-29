function check_content(){
	let file = document.getElementById("lif_file").value;
	let = danger_box = document.getElementsByClassName("alert");
	if(file != ""){		
		if( ".lif" == getFileExtension(file)){
			document.getElementById("load_file").submit();
		}else{
			danger_box[0].style.display = "block";
			danger_box[0].innerHTML = "Csak '.lif' kiterjesztésű fájl megengedett!";
		}
	}else{
		danger_box[0].style.display = "block";
		danger_box[0].innerHTML = "Nincs kiválasztva fájl!";
	}	
}

function getFileExtension( fileName ){
	let pos = -1;
	let extension = "";
	if((pos = fileName.lastIndexOf(".")) != -1){
		extension = fileName.substr(pos);
	}
	return extension;
}