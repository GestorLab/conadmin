	function busca_radippool(id,loja){
		if(id == '' || id == undefined){
			id = 0;
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

	   	url = "xml/radippool.php?id="+id;
	   	call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.formulario.IdRadIdPool.value			=	"";	
				document.formulario.PoolName.value				=	"";	
				document.formulario.FrameIpAddress.value		=	"";
				document.formulario.NasIpAddress.value 			= 	"";
				document.formulario.Acao.value 					= 	'inserir';				

				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("id")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var id = nameTextNode.nodeValue;	
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("pool_name")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var pool_name = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("framedipaddress")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var framedipaddress = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("nasipaddress")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var nasipaddress = nameTextNode.nodeValue;	
				
				document.formulario.IdRadIdPool.value			=	id;	
				document.formulario.PoolName.value				=	pool_name;	
				document.formulario.FrameIpAddress.value		=	framedipaddress;
				document.formulario.NasIpAddress.value 			= 	nasipaddress;
				document.formulario.Acao.value 					= 	'alterar';
				
				verificaAcao();
			}
			
		});		
	}

