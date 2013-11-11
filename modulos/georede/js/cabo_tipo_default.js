	function busca_cabo_tipo(IdCaboTipo){
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		if(IdCaboTipo == ""){
			IdCaboTipo = 0;
		}
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
		
	   	url = "xml/cabo_tipo.php?IdCaboTipo="+IdCaboTipo;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaboTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaboTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCaboTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoCaboTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaCaboTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var SiglaCaboTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;				
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;		
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				document.formulario.IdCaboTipo.value	 	 = IdCaboTipo;
				document.formulario.Descricao.value	 		 = DescricaoCaboTipo;				
				document.formulario.SiglaCaboTipo.value	 	 = SiglaCaboTipo;				
				document.formulario.LoginCriacao.value	 	 = LoginCriacao;
				document.formulario.DataCriacao.value	 	 = DataCriacao;
				document.formulario.LoginAlteracao.value	 = LoginAlteracao;
				document.formulario.DataAlteracao.value		 = DataAlteracao;	
				
				document.formulario.Acao.value 				 = 'alterar';
				
				verificaAcao();			
				document.formulario.IdCaboTipo.focus();
			}else{
				document.formulario.IdCaboTipo.value	 	 = "";					
				document.formulario.Descricao.value	 		 = "";					
				document.formulario.SiglaCaboTipo.value	 	 = "";					
				document.formulario.LoginCriacao.value	 	 = "";
				document.formulario.DataCriacao.value	 	 = "";
				document.formulario.LoginAlteracao.value	 = "";
				document.formulario.DataAlteracao.value		 = "";	
				
				document.formulario.Acao.value 				 = 'inserir';							
				
				verificaAcao();
				document.formulario.IdCaboTipo.focus();
			}		
		});
	}