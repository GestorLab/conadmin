	function busca_poste(IdPoste){
		var nameNode, nameTextNode, url;		
		var xmlhttp   = false;
		
		if(IdPoste == ""){
			IdPoste = 0;
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
		
	   	url = "xml/poste.php?IdPoste="+IdPoste;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPoste = nameTextNode.nodeValue;	
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoPoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdTipoPoste = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomePoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomePoste = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPoste")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoPoste = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdPais = nameTextNode.nodeValue;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomePais = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdEstado = nameTextNode.nodeValue;	
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeEstado = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCidade = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCidade = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Endereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Numero = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Complemento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Bairro = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cep")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cep = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Latitude")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Latitude = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Longitude")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Longitude = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Total")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Total = nameTextNode.nodeValue;
				
				document.formulario.IdPoste.value	 		 = IdPoste;
				document.formulario.IdTipoPoste.value		 = IdTipoPoste;
				document.formulario.NomePoste.value 		 = NomePoste;
				document.formulario.DescricaoPoste.value 	 = DescricaoPoste;
				document.formulario.Latitude.value 			 = Latitude;
				document.formulario.Longitude.value 		 = Longitude;
				document.formulario.Longitude.value 		 = Longitude;
				document.formulario.Endereco.value 			 = Endereco;
				document.formulario.Numero.value 			 = Numero;
				document.formulario.Complemento.value 		 = Complemento;
				document.formulario.Bairro.value 			 = Bairro;
				document.formulario.CEP.value 				 = Cep;
				
				document.formulario.IdPais.value 			 = IdPais;
				document.formulario.IdEstado.value 			 = IdEstado;
				document.formulario.IdCidade.value 			 = IdCidade;
				document.formulario.Pais.value 				 = NomePais;
				document.formulario.Estado.value 			 = NomeEstado;
				document.formulario.Cidade.value 			 = NomeCidade;

				
				document.formulario.LoginCriacao.value 		 = LoginCriacao;
				document.formulario.DataCriacao.value 		 = DataCriacao;
				document.formulario.LoginAlteracao.value 	 = LoginAlteracao;
				document.formulario.DataAlteracao.value 	 = DataAlteracao;
				document.formulario.Bairro.value 			 = Bairro;
				document.formulario.Acao.value 				 = 	'alterar';
			
				verificaAcao();
				
				document.formulario.IdPoste.focus();
			}else{
				document.formulario.IdPoste.value	 		 = "";
				document.formulario.IdTipoPoste.value		 = "";
				document.formulario.NomePoste.value 		 = "";
				document.formulario.DescricaoPoste.value 	 = "";
				document.formulario.Latitude.value 			 = "";
				document.formulario.Longitude.value 		 = "";
				document.formulario.Longitude.value 		 = "";
				document.formulario.Endereco.value 			 = "";
				document.formulario.Numero.value 			 = "";
				document.formulario.Complemento.value 		 = "";
				document.formulario.Bairro.value 			 = "";
				document.formulario.CEP.value 				 = "";
				
				document.formulario.IdPais.value 			 = "";
				document.formulario.IdEstado.value 			 = "";
				document.formulario.IdCidade.value 			 = "";
				document.formulario.Pais.value 				 = "";
				document.formulario.Estado.value 			 = "";
				document.formulario.Cidade.value 			 = "";
				
				document.formulario.LoginCriacao.value 		 = "";
				document.formulario.DataCriacao.value 		 = "";
				document.formulario.LoginAlteracao.value 	 = "";
				document.formulario.DataAlteracao.value 	 = "";
				document.formulario.Acao.value 				 = 	'inserir';
				
				document.formulario.IdPoste.focus();
				verificaAcao();
			}		
		});
	}