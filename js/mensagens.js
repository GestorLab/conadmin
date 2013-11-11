	function mensagens(n,Local,VarAux){
		var msg='';
		var prioridade='';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		if(VarAux == undefined){
			VarAux = '';
		}
		if(n == 0){
			return help(msg,prioridade);
		}
		
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
	    switch(Local){
	    	case 'index':
				url = "xml/mensagens.xml";
				break;
	    	case 'helpdesk':
				url = "xml/mensagens.xml";
				break;
	    	case 'etiqueta':
				url = "../../../../xml/mensagens.xml";
				break;
	    	case 'rotinas':
				url = "../../../xml/mensagens.xml";
				break;
			default:
				url = "../../xml/mensagens.xml";
   		}
   		xmlhttp.open("GET", url,true);
   		xmlhttp.onreadystatechange = function(){ 
   			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						msg = nameTextNode.nodeValue;
						
						if(VarAux!=""){
							msg	=	msg.replace('Valor',VarAux);
						}
					}else{
						msg = '';
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						prioridade = nameTextNode.nodeValue;
					}else{
						prioridade = '';
					}
					
					return help(msg,prioridade);
				}
			}
		}
		xmlhttp.send(null);
	}
	
	function scrollWindow(pos){
		switch(pos){
			case 'bottom':
				window.scrollTo(0,9999999);
				break;
			case 'top':
				window.scrollTo(0,0);
				break;
		}
	}
	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens(nerro,document.formulario.Local.value);
	}
	function help(msg,prioridade){
		if(msg!=''){
			scrollWindow("bottom");
		}

		document.getElementById('helpText').innerHTML = msg;
		document.getElementById('helpText').innerHTML = msg;
		document.getElementById('helpText').style.display = "block";
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText').style.color = "#004975";
				return true;
		}
	}
