	function busca_nas(id,Erro, Local){
		if(id == ''){
			id = document.formulario.id.value;
		}
		var nameNode, nameTextNode, url;
		var xmlhttp   = false;
		if (window.XMLHttpRequest) {
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject){
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch (e) {}
	        }
	    }
	    
	   	url = "xml/nas.php?id="+id;
		call_ajax(url,function (xmlhttp){	
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}			
			if(xmlhttp.responseText == 'false'){						
					document.formulario.id.value				=	"";	
					document.formulario.nasname.value			=	"";	
					document.formulario.shortname.value			=	"";
					document.formulario.type.value 				= 	"";
					document.formulario.ports.value 			= 	"";
					document.formulario.secret.value 			= 	"";
					document.formulario.server.value			= 	"";
					document.formulario.community.value			= 	"";
					document.formulario.description.value		= 	"";
					document.formulario.Acao.value 				= 	'inserir';
					verificaAcao();
					document.formulario.id.focus();	
				// Fim de Carregando
				carregando(false);
			}else{			
				nameNode = xmlhttp.responseXML.getElementsByTagName("id")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var id = nameTextNode.nodeValue;	
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("nasname")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var nasname = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("shortname")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var shortname = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("type")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var type = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ports")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ports = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("secret")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var secret = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("server")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var server = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("community")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var community = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("description")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var description = nameTextNode.nodeValue;	
				
				document.formulario.id.value				=	id;
				document.formulario.nasname.value			=	nasname;
				document.formulario.shortname.value			=	shortname;
				document.formulario.type.value 				= 	type;
				document.formulario.ports.value 			= 	ports;
				document.formulario.secret.value 			= 	secret;
				document.formulario.server.value			= 	server;
				document.formulario.community.value			= 	community;
				document.formulario.description.value		= 	description;
				document.formulario.Acao.value 				= 	'alterar';

			}
			verificaAcao();
		})
	}