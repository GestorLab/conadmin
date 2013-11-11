function busca_processo(IdProcesso,Erro,Local){
	if(IdProcesso == '' || IdProcesso == undefined){
		IdProcesso = 0;
	}
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
	}
	var nameNode, nameTextNode, url;
	
	var xmlhttp   = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
    	xmlhttp = new XMLHttpRequest();
        if(xmlhttp.overrideMimeType){
        	xmlhttp.overrideMimeType('text/xml');
		}
	}else if (window.ActiveXObject){ // IE
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }

   	url = "xml/processo.php";
   	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){

				if(Erro != false){
					document.formulario.Erro.value = 0;
					verificaErro();
				}

				if(xmlhttp.responseText == 'false'){
					window.location.replace("listar_processo.php");
					
					// Fim de Carregando
					carregando(false);
				}else{
					var temp = 0;
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdProcesso").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcesso")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdProcessoTemp = nameTextNode.nodeValue;	
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("User")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var User = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Host")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Host = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DB")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DB = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Command")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Command = nameTextNode.nodeValue;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Time")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Time = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("State")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var State = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Info")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Info = nameTextNode.nodeValue;
						
						if(IdProcesso == IdProcessoTemp){
						
							document.formulario.IdProcesso.value			=	IdProcessoTemp;	
							document.formulario.User.value					=	User;
							document.formulario.Host.value					=   Host;
							document.formulario.DB.value 					= 	DB;
							document.formulario.Command.value 				= 	Command;
							document.formulario.Time.value 					= 	Time;
							document.formulario.State.value					= 	State;
							document.formulario.Info.value 					= 	Info;
							temp++;
							break;
							
						}
					}
					if(temp == 0){
						window.location.replace("listar_processo.php");
					}
				}	
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}
function voltar(){
	window.location.replace("listar_processo.php");
}
function inicia(){
	document.formulario.IdProcesso.focus();
}

