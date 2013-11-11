	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'MesEnvio':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'DataEnvio':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
				case 'DataCriacao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'IdContaReceber':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdPessoa':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdProcessoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdContrato':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdLancamentoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			default:
				campo.maxLength	=	100;
		}
	}
	function listar_visualizar(event){
		var keyCode = 0;
		
		if(event.keyCode){
			keyCode = event.keyCode;
		} else{
			keyCode = event.charCode;
		}
		
		if(keyCode == 13){
			buscar();
		}
	}
	
	function buscar(object, mouse, operacao){
		if(operacao){
			if(document.formulario != undefined) {
				if(document.filtro.filtro_valor.value == '' && object.value != "Buscar" && document.formulario.IdHistoricoMensagem.value != '') {
					document.filtro.filtro_campo.value = "IdHistoricoMensagem";
					document.filtro.filtro_valor.value = document.filtro.IdHistoricoMensagem.value
				}
			}
			
			object.href = "listar_reenvio_mensagem.php?filtro_nome="+document.filtro.filtro_nome.value+"&filtro_valor="+document.formulario.IdPessoa.value+"&filtro_campo="+document.formulario.IdPessoa.name+"&filtro_idstatus="+document.filtro.filtro_idstatus.value+"&filtro_tipo="+document.filtro.filtro_tipo.value+"&filtro_limit= "+document.filtro.filtro_limit.value;
			
			if(document.filtro.IdPessoa != undefined) {
				if(document.filtro.IdPessoa.value == ''){
					object.href	+=	"&IdContaReceber="+document.filtro.IdContaReceber.value;
				}
			}
		}
		
		if(document.filtro.keyCode != undefined) {
			if(document.filtro.keyCode.value == '' && mouse){
				parent.location.href = object.href;
			} else{
				if(navigator.userAgent.indexOf("Opera") != -1 && document.filtro.keyCode.value == 17){
					parent.location.href = object.href;
				}
				
				document.filtro.keyCode.value = null;
			}
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
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataInicio' || id=='DataPrimeiraCobranca'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000';
			}
			if(id == 'DataTermino'){
				if(document.formulario.DataUltimaCobranca.value == ''){
					document.formulario.DataUltimaCobranca.value = campo.value;
				}
			}
			mensagens(0);
			return true;
		}	
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}
	function busca_status(IdHistoricoMensagem){
		if(IdHistoricoMensagem == undefined){
			IdHistoricoMensagem = 0;
		}
		
		var nameNode, nameTextNode;
		
		url = "xml/reenvio_mensagem_status.php?IdHistoricoMensagem="+IdHistoricoMensagem;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorParametroSistema = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Cor = nameTextNode.nodeValue;

				document.getElementById('cp_Status').style.display		=	"block";		
				document.getElementById('cp_Status').style.color		=	Cor;		
				document.getElementById('cp_Status').innerHTML			=	ValorParametroSistema;
				document.getElementById('cp_Status').style.fontSize		=	"15px";
				document.getElementById('cp_Status').style.lineHeight	=	"11px";
			}
		});
	}
	