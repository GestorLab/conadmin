	function inicia(){	
		document.formulario.IdPoste.focus();
	}
	
	function busca_poste_pais(IdPais,Erro,Local,Endereco){
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(IdPais == ''){
			IdPais = 0;
		}		
		if(Endereco=='' || Endereco == undefined){
			return false;
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
	    
	   	url = "xml/pais.php?IdPais="+IdPais;
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
						document.formulario.IdPais.value 	= '';
						document.formulario.Pais.value 		= '';
						document.formulario.IdEstado.value 	= '';
						document.formulario.Estado.value 	= '';
						
						document.formulario.IdPais.focus();
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;		
						
						document.formulario.IdPais.value = IdPais;
						document.formulario.Pais.value	 = NomePais;
						
						document.formulario.IdEstado.focus();
						
					}
				/*	if(document.getElementById("quadroBuscaEstado").style.display	==	"block"){
						document.getElementById("quadroBuscaEstado").style.display	=	"none";
					}*/
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	
	function busca_poste_estado(IdPais,IdEstado,Erro,Local,Endereco){
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		if(IdPais == ''){
			IdPais = 0;
		}
		if(IdEstado == ''){
			IdEstado = 0;
		}
		if(Endereco=='' || Endereco == undefined){
			return false;
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
	    
	   	url = "xml/estado.php?IdPais="+IdPais+"&IdEstado="+IdEstado;
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
						document.formulario.IdEstado.value 		= '';
						document.formulario.Estado.value 		= '';
						
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
						
						if(document.formulario.IdPais.value == ""){
							document.formulario.IdPais.focus();
							return false;
						}
						
						document.formulario.IdEstado.value = IdEstado;
						document.formulario.Estado.value = NomeEstado;
						
						
					}
				/*	if(document.getElementById("quadroBuscaEstado").style.display	==	"block"){
						document.getElementById("quadroBuscaEstado").style.display	=	"none";
					}*/
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	
	function busca_poste_cidade(IdPais,IdEstado,IdCidade,Erro,Local,Endereco){
		if(IdPais == '')	IdPais = 0;
		if(IdEstado == '')	IdEstado = 0;
		if(IdCidade == '')	IdCidade = 0;
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		if(Endereco=='' || Endereco == undefined){
			return false;
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
	    
	   	url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade;
	   	
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
						document.formulario.IdCidade.value 		= '';
						document.formulario.Cidade.value 		= '';
						
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;					
							
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("SiglaEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var SiglaEstado = nameTextNode.nodeValue;
						
						if(document.formulario.IdPais.value == ""){
							document.formulario.IdPais.focus();
							return false;
						}
						
						if(document.formulario.IdEstado.value == ""){
							document.formulario.IdEstado.focus();
							return false;
						}
						
						document.formulario.IdCidade.value 	 = IdCidade;
						document.formulario.Cidade.value	 = NomeCidade;
						
						
					}
				/*	if(document.getElementById("quadroBuscaEstado").style.display	==	"block"){
						document.getElementById("quadroBuscaEstado").style.display	=	"none";
					}*/
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
		return true;
	}
	
	function validar(){
		if(document.formulario.IdTipoPoste.value==''){
			mensagens(1);
			document.formulario.IdTipoPoste.focus();
			return false;
		}
		
		if(document.formulario.NomePoste.value==''){
			mensagens(1);
			document.formulario.NomePoste.focus();
			return false;
		}	
		
		if(document.formulario.CEP.value==''){
			mensagens(1);
			document.formulario.CEP.focus();
			return false;
		}
		
		if(document.formulario.Endereco.value==''){
			mensagens(1);
			document.formulario.Endereco.focus();
			return false;
		}
		
		if(document.formulario.Bairro.value==''){
			mensagens(1);
			document.formulario.Bairro.focus();
			return false;
		}
		
		if(document.formulario.Latitude.value==''){
			mensagens(1);
			document.formulario.Latitude.focus();
			return false;
		}
		
		if(document.formulario.Longitude.value==''){
			mensagens(1);
			document.formulario.Longitude.focus();
			return false;
		}	
		return true;
	}
	
	function excluir(IdPoste,Local){
		if(excluir_registro() == true){
			var xmlhttp   = false;
			if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
		        if(xmlhttp.overrideMimeType){
		    //    	xmlhttp.overrideMimeType('text/xml');
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
			url = "files/excluir/excluir_poste.php?IdPoste="+IdPoste;
			call_ajax(url,function (xmlhttp){						
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";								
						url = "cadastro_poste.php?Erro=" + document.formulario.Erro.value;						
						window.location.replace(url);
					} else{
						verificaErro();
							
					}
				}else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					if(numMsg == 7){
						var aux = 0;
						for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
							if(IdPoste == document.getElementById('tableListar').rows[i].accessKey){
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar');
								break;
							}
						}								
						//document.getElementById('IdLoja'+IdLoja).bgColor = '#FF9D9D';
						if(aux=1){
							document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
						}
					}
				}		
			});
		}	
	} 
	