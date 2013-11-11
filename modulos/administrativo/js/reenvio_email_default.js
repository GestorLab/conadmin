	function busca_reenvio_email(IdEmail,Erro,Local){
		if(IdEmail == '' || IdEmail == undefined){
			IdEmail = 0;
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
	
	   	url = "xml/reenvio_email.php?IdEmail="+IdEmail;
		xmlhttp.open("GET", url,true);
	
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						switch(Local){
							case 'EnviarEmail':
								document.formulario.IdEmail.value				= 	"";
								document.formulario.Email.value					= 	"";
								document.formulario.Email.focus();
								break;
							default:
								document.formulario.IdPessoa.value				=	"";		
								document.filtro.IdPessoa.value					=	"";
								document.formulario.Nome.value					=	"";
								document.formulario.IdEmail.value 				= 	"";
								document.filtro.IdEmail.value 					= 	"";
								document.formulario.IdContaReceber.value 		= 	"";
								document.filtro.IdContaReceber.value 			= 	"";
								document.formulario.IdTipoEmail.value 			= 	"";
								document.formulario.Email.value					= 	"";
								document.formulario.DataEnvio.value 			= 	"";
								document.formulario.HoraEnvio.value 			= 	"";
								document.formulario.IdEmailReEnvio.value 		= 	"";
								
								window.parent.email.location.replace("../../visualizar_email.php");
								document.formulario.IdEmail.focus();
								break;
								
						}
						
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;	
					
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
	
						nameNode = xmlhttp.responseXML.getElementsByTagName("Email")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Email = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEmail")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEmail = nameTextNode.nodeValue;
						
						switch(Local){
							case 'EnviarEmail':
								document.formulario.IdEmail.value 				= 	IdEmail;
								document.formulario.Email.value 				= 	Email;
								break;
							default:
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdContaReceber = nameTextNode.nodeValue;					
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoEmail")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdTipoEmail = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataEnvio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataHoraEnvio = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdEmailReEnvio")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdEmailReEnvio = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdPessoa = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLoja = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdProcessoFinanceiro")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdProcessoFinanceiro = nameTextNode.nodeValue;
								
//								addParmUrl("marReenvioEmail","IdEmail",IdEmail);
//								addParmUrl("marReenvioEmail","IdPessoa",IdPessoa);
								addParmUrl("marPessoa","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdPessoa",IdPessoa);
								addParmUrl("marContrato","IdContaReceber",IdContaReceber);
								addParmUrl("marContratoNovo","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdPessoa",IdPessoa);
								addParmUrl("marContasReceber","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdContaReceber",IdContaReceber);
								addParmUrl("marLancamentoFinanceiro","IdPessoa",IdPessoa);
								addParmUrl("marProcessoFinanceiro","IdProcessoFinanceiro",IdProcessoFinanceiro);
								addParmUrl("marContaEventual","IdContaReceber",IdContaReceber);
								addParmUrl("marContaEventualNovo","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServicoNovo","IdPessoa",IdPessoa);
								addParmUrl("marOrdemServico","IdContaReceber",IdContaReceber);
								
								document.formulario.IdPessoa.value				=	IdPessoa;	
								document.filtro.IdPessoa.value					=	IdPessoa;	
								document.formulario.Nome.value					=	Nome;	
								document.formulario.IdEmail.value 				= 	IdEmail;
								document.filtro.IdEmail.value 					= 	IdEmail;
								document.formulario.IdContaReceber.value 		= 	IdContaReceber;
								document.filtro.IdContaReceber.value 			= 	IdContaReceber;
								document.formulario.IdTipoEmail.value 			= 	IdTipoEmail;
								document.formulario.Email.value 				= 	Email;
								document.formulario.IdEmailReEnvio.value		= 	IdEmailReEnvio;
								document.formulario.DataEnvio.value				= 	dateFormat(DataHoraEnvio.substring(0,10));
								document.formulario.HoraEnvio.value				= 	DataHoraEnvio.substring(11,16);
								
								window.parent.email.location.replace("../../visualizar_email.php?IdLoja="+IdLoja+"&IdEmail="+IdEmail);
								document.formulario.IdEmail.focus();
								break;
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function busca_email_enviado(IdLoja,IdEmailReEnvio){
		if(IdEmailReEnvio == ''){
			return false;
		}
		busca_reenvio_email(IdEmailReEnvio,false);
		window.parent.email.location.replace("../../visualizar_email.php?IdLoja="+IdLoja+"&IdEmail="+IdEmailReEnvio);
	}
	function reenviar_email(IdEmail){
		parent.location = "cadastro_enviar_email.php?IdEmail="+IdEmail;
	}
	function buscar(object, mause, operacao){
		if(operacao){
			if(document.filtro.filtro_valor.value == '' && document.formulario.IdEmail.value != '' && object.value != "Buscar"){
				document.filtro.filtro_campo.value = "IdEmail";
				document.filtro.filtro_valor.value = document.filtro.IdEmail.value
			}
			
			object.href = "listar_reenvio_email.php?filtro_nome="+document.filtro.filtro_nome.value+"&filtro_valor="+document.filtro.filtro_valor.value+"&filtro_campo="+document.filtro.filtro_campo.value+"&filtro_idstatus="+document.filtro.filtro_idstatus.value+"&filtro_tipo="+document.filtro.filtro_tipo.value+"&filtro_limit="+document.filtro.filtro_limit.value;
			
			if(document.filtro.IdPessoa.value == ''){
				object.href	+=	"&IdContaReceber="+document.filtro.IdContaReceber.value;
			}
		}
		
		if(document.filtro.keyCode.value == '' && mause){
			parent.location.href = object.href;
		} else{
			if(navigator.userAgent.indexOf("Opera") != -1 && document.filtro.keyCode.value == 17){
				parent.location.href = object.href;
			}
			
			document.filtro.keyCode.value = null;
		}
	}
	function code(event){
		if(event != null){
			var key_code;
			
			if(event.keyCode){
				key_code = event.keyCode;
			} else if(event.which){
				key_code = event.which;
			} else{
				key_code = event.charCode;
			}
			
			if(key_code > 15 && key_code < 18){
				document.filtro.keyCode.value = key_code;
			}
		} else{
			document.filtro.keyCode.value = null;
		}
	}
