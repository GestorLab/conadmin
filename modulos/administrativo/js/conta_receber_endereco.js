	function excluir(IdContaReceber,IdStatus){
		if(IdStatus != 0 && IdStatus != 6){
			var url = 'cadastro_cancelar_conta_receber.php?IdContaReceber='+IdContaReceber;
			window.location.replace(url);
		}
	}
	function busca_filtro_cidade_estado(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		
		var url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){		
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}
				
				var nameNode, nameTextNode, NomeCidade;					
				
				addOption(document.filtro.filtro_cidade,"","");	
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeCidade = nameTextNode.nodeValue;
		
					addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
				}					
				
				if(IdCidadeTemp!=""){
					for(i=0;i<document.filtro.filtro_cidade.length;i++){
						if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
						    document.filtro.filtro_cidade[i].selected	=	true;
							break;
						}
					}
				}else{
					document.filtro.filtro_cidade[0].selected	=	true;
				}						
			}else{
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}						
			}
		});
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