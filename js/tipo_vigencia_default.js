	function janela_busca_tipo_vigencia(){
		janelas('busca_tipo_vigencia.php',360,283,250,100,'');
	}
	function busca_tipo_vigencia(IdContratoTipoVigencia, Erro, Local){
		if(IdContratoTipoVigencia == ''){
			IdContratoTipoVigencia = 0;
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
		url = "xml/tipo_vigencia.php?IdContratoTipoVigencia="+IdContratoTipoVigencia;
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
						
						switch (Local){
							case "Vigencia":
								document.formulario.IdContratoTipoVigencia.value 			= '';
								document.formulario.DescricaoContratoTipoVigencia.value 	= '';								
								document.formulario.Isento.value 							= '';
								document.formulario.Valor.value								= '';	
								document.formulario.Valor.readOnly							= false;
								document.formulario.ValorDesconto.readOnly					= false;
								document.formulario.ValorPercentual.readOnly				= false;
								
								document.getElementById('titValor').style.color				= '#C10000';
								document.getElementById('titPercentual').style.color		= '#C10000';
								document.getElementById('titDesconto').style.color			= '#C10000';	
									
								break;
							default:
								addParmUrl("marTipoVigencia","IdContratoTipoVigencia","");
								
								document.formulario.IdContratoTipoVigencia.value 			= '';
								document.formulario.DescricaoContratoTipoVigencia.value 	= '';
								document.formulario.Isento.value 							= '';
								document.formulario.DataCriacao.value 						= '';
								document.formulario.LoginCriacao.value 						= '';
								document.formulario.DataAlteracao.value 					= '';
								document.formulario.LoginAlteracao.value					= '';
								document.formulario.Acao.value 								= 'inserir';
						}
						document.formulario.IdContratoTipoVigencia.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdContratoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdContratoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContratoTipoVigencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoContratoTipoVigencia = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Isento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Isento = nameTextNode.nodeValue;
						
						switch (Local){		
							case "Vigencia":
								document.formulario.IdContratoTipoVigencia.value		= IdContratoTipoVigencia;
								document.formulario.DescricaoContratoTipoVigencia.value = DescricaoContratoTipoVigencia;
								document.formulario.Isento.value 						= Isento;
								
								if(Isento == 1){
									document.formulario.Valor.value								= '0,00';	
									document.formulario.Valor.readOnly							= true;	
									document.formulario.ValorDesconto.readOnly					= true;
									document.formulario.ValorPercentual.readOnly				= true;
									document.getElementById('titValor').style.color				= '#000';
									document.getElementById('titPercentual').style.color		= '#000';
									document.getElementById('titDesconto').style.color			= '#000';
									document.formulario.ValorFinal.value						= '0,00';
								}else{
									document.formulario.Valor.readOnly							= false;
									document.formulario.ValorDesconto.readOnly					= false;
									document.formulario.ValorPercentual.readOnly				= false;
									document.getElementById('titValor').style.color				= '#C10000';	
									document.getElementById('titPercentual').style.color		= '#C10000';
									document.getElementById('titDesconto').style.color			= '#C10000';							
								}
								
								break;
							
							default:
								addParmUrl("marTipoVigencia","IdContratoTipoVigencia",IdContratoTipoVigencia);
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataCriacao = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginCriacao = nameTextNode.nodeValue;					
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataAlteracao = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginAlteracao = nameTextNode.nodeValue;
						
								document.formulario.IdContratoTipoVigencia.value		= IdContratoTipoVigencia;
								document.formulario.DescricaoContratoTipoVigencia.value = DescricaoContratoTipoVigencia;
								document.formulario.Isento.value 						= Isento;								
								document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value 					= LoginCriacao;
								document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
								document.formulario.LoginAlteracao.value				= LoginAlteracao;
								
								document.formulario.Acao.value 							= 'alterar';
						}
					}
					if(document.getElementById("quadroBuscaContratoTipoVigencia") != null){
						if(document.getElementById("quadroBuscaContratoTipoVigencia").style.display == "block"){
							document.getElementById("quadroBuscaContratoTipoVigencia").style.display =	"none";
						}
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
