	function busca_cabo(IdCabo){
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		if(IdCabo == ""){
			IdCabo = 0;
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
		
	   	url = "xml/cabo.php?IdCabo="+IdCabo;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCabo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCabo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoCabo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoCabo = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCabo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCabo = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Especificacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Especificacao = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("EspessuraVisual")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var EspessuraVisual = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Opacidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Opacidade = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Oculto")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Oculto = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdFibra")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdFibra = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataInstalacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataInstalacao = nameTextNode.nodeValue;	
				
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
				
				document.formulario.IdCabo.value	 		 = IdCabo;
				document.formulario.TipoCabo.value	 		 = IdTipoCabo;
				document.formulario.Especificacao.value	 	 = Especificacao;
				document.formulario.NomeCabo.value	 		 = NomeCabo;
				document.formulario.Cor.value	 			 = Cor;
				document.formulario.EspessuraVisual.value	 = EspessuraVisual;
				document.formulario.Opacidade.value			 = Opacidade;
				document.formulario.Oculto.value			 = Oculto;
				document.formulario.QtdFibra.value	 		 = QtdFibra;
				document.formulario.DataInstalacao.value	 = DataInstalacao;
				document.formulario.LoginCriacao.value	 	 = LoginCriacao;
				document.formulario.DataCriacao.value	 	 = DataCriacao;
				document.formulario.LoginAlteracao.value	 = LoginAlteracao;
				document.formulario.DataAlteracao.value		 = DataAlteracao;				
				document.formulario.Acao.value 				 = 	'alterar';
				
				document.formulario.IdCabo.focus();
				verificaAcao();			
			}else{
				document.formulario.IdCabo.value	 		 = "";
				document.formulario.TipoCabo.value	 		 = "";
				document.formulario.Especificacao.value	 	 = "";
				document.formulario.NomeCabo.value	 		 = "";
				document.formulario.Cor.value	 			 = "";
				document.formulario.EspessuraVisual.value	 = "";
				document.formulario.Opacidade.value	 		 = "";
				document.formulario.Oculto.value	 		 = "";
				document.formulario.QtdFibra.value	 		 = "";
				document.formulario.DataInstalacao.value	 = "";
				document.formulario.LoginCriacao.value	 	 = "";
				document.formulario.DataCriacao.value	 	 = "";
				document.formulario.LoginAlteracao.value	 = "";
				document.formulario.DataAlteracao.value		 = "";				
				document.formulario.Acao.value 				 = 	'inserir';
				
				verificaAcao();
				document.formulario.IdCabo.focus();
			}		
		});
	}