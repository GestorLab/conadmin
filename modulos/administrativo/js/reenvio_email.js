
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
