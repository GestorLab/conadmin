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
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataLancamento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimentoOriginal':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataVencimentoAtual':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'MesLancamento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimentoAtual':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimentoOriginal':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;
			case 'MesVencimento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
				break;	
			case 'IdProcessoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'NumeroDocumento':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'NumeroNF':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdReceibo':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdContaReceber':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdLancamentoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdArquivoRetorno':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			default:
				campo.maxLength	=	100;
		}
	}