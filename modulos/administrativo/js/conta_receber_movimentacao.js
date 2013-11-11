	function excluir(IdContaReceber,IdStatus){
		if(IdStatus != 0){
			var url = 'cadastro_cancelar_conta_receber.php?IdContaReceber='+IdContaReceber;
			window.location.replace(url);
		}
	}
	function listar_filtro(e){
		var e = e || event;
		var k = e.keyCode || e.which;
		if (k==13){
			if(validar_filtro() == true){
			 	document.filtro.submit();
			}
		}
	}
	function validar_filtro(){
		if(document.filtro.chNome.checked  == true)		document.filtro.chNome.value  = 1; 		else	document.filtro.chNome.value	=	0;
		if(document.filtro.chRazao.checked == true) 	document.filtro.chRazao.value = 1; 		else  	document.filtro.chRazao.value	=	0;
		if(document.filtro.chFone1.checked == true) 	document.filtro.chFone1.value = 1; 		else  	document.filtro.chFone1.value	=	0;
		if(document.filtro.chFone2.checked == true) 	document.filtro.chFone2.value = 1; 		else  	document.filtro.chFone2.value	=	0;
		if(document.filtro.chFone3.checked == true) 	document.filtro.chFone3.value = 1; 		else  	document.filtro.chFone3.value	=	0;
		if(document.filtro.chCel.checked   == true)		document.filtro.chCel.value   = 1; 		else  	document.filtro.chCel.value		=	0;
		if(document.filtro.chFax.checked   == true)	 	document.filtro.chFax.value   = 1; 		else  	document.filtro.chFax.value		=	0;
		if(document.filtro.chCompF.checked == true) 	document.filtro.chCompF.value = 1; 		else  	document.filtro.chCompF.value	=	0;
		if(document.filtro.chEmail.checked == true)		document.filtro.chEmail.value = 1; 		else  	document.filtro.chEmail.value	=	0;
		if(document.filtro.chNumD.checked  == true)	 	document.filtro.chNumD.value  = 1; 		else  	document.filtro.chNumD.value	=	0;
		if(document.filtro.chNumNF.checked == true) 	document.filtro.chNumNF.value = 1; 		else  	document.filtro.chNumNF.value	=	0;
		if(document.filtro.chDataF.checked == true) 	document.filtro.chDataF.value = 1; 		else  	document.filtro.chDataF.value	=	0;
		if(document.filtro.chLCob.checked  == true) 	document.filtro.chLCob.value  = 1; 		else  	document.filtro.chLCob.value	=	0;
		if(document.filtro.chDataL.checked == true)		document.filtro.chDataL.value = 1; 		else  	document.filtro.chDataL.value	=	0;
		if(document.filtro.chDataV.checked == true)	 	document.filtro.chDataV.value = 1;		else  	document.filtro.chDataV.value	=	0;
		if(document.filtro.chDataP.checked == true) 	document.filtro.chDataP.value = 1; 		else  	document.filtro.chDataP.value	=	0;
		if(document.filtro.chValor.checked == true) 	document.filtro.chValor.value = 1;		else  	document.filtro.chValor.value	=	0;
		if(document.filtro.chValDp.checked == true)		document.filtro.chValDp.value = 1; 		else  	document.filtro.chValDp.value	=	0;
		if(document.filtro.chValDe.checked == true)	 	document.filtro.chValDe.value = 1;		else  	document.filtro.chValDe.value	=	0;
		if(document.filtro.chValF.checked  == true)	 	document.filtro.chValF.value  = 1; 		else  	document.filtro.chValF.value	=	0;
		if(document.filtro.chLRec.checked  == true)		document.filtro.chLRec.value  = 1; 		else  	document.filtro.chLRec.value	=	0;
		if(document.filtro.chStat.checked  == true)	 	document.filtro.chStat.value  = 1;		else  	document.filtro.chStat.value	=	0;
		if(document.filtro.chObs.checked   == true)	 	document.filtro.chObs.value   = 1; 		else  	document.filtro.chObs.value		=	0;
		
		ativaNome('Contas a Receber');
		
		return true;
	}
	function filtro_ordenar_filtro(valor,typeDate){
		if(typeDate == undefined){
			typeDate = 'text';
		}
		
		if(document.filtro.filtro_tipoDado != undefined){
			document.filtro.filtro_tipoDado.value = typeDate;
		}
	
		if(document.filtro.filtro_ordem.value == valor){
			if(document.filtro.filtro_ordem_direcao.value == "ascending"){
				document.filtro.filtro_ordem_direcao.value = "descending";
			}else{
				document.filtro.filtro_ordem_direcao.value = "ascending";
			}
		}else{
			document.filtro.filtro_ordem.value = valor;
		}
		if(validar_filtro() == true){
			document.filtro.submit();
		}
	}
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataVencimento':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			case 'DataMovimentacao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			default:
				campo.maxLength	=	100;
		}
	}
	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}
						
						var nameNode, nameTextNode, NomeCidade;					
											
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
					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
	    var url = "xml/servico.php?IdServico="+IdServico;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico == undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}
