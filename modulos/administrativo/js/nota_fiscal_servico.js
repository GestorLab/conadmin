	function cancelar(IdNotaFiscal, IdContaReceber, IdStatus){
		if(cancelar_registro() == true){
			if(IdStatus == 0){
				return false;
			}
			
			var url = "xml/nota_fiscal_cancelar.php?IdContaReceber="+IdContaReceber+"&IdNotaFiscal="+IdNotaFiscal;
			
			call_ajax(url,function (xmlhttp){
				var numMsg = parseInt(xmlhttp.responseText);
				
				if(numMsg == 67){ // registro cancelado com sucesso
					for(var i = 0; i < document.getElementById('tableListar').rows.length; i++){
						if(IdNotaFiscal == document.getElementById('tableListar').rows[i].cells[0].innerHTML){
							document.getElementById('tableListar').rows[i].cells[10].innerHTML = document.getElementById('tableListar').rows[i].cells[10].innerHTML.replace(/ico_del.gif/g, "ico_del_c.gif");
							document.getElementById('tableListar').rows[i].cells[9].innerHTML = 'Cancelado';
							tableMultColor('tableListar',document.filtro.corRegRand.value);
							break;
						}
					}
					
					mensagens(numMsg);
				} else{
					if(numMsg == 68){ // erro ao cancelar registro
						mensagens(numMsg);
					}
				}
			});
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
	
	function exportar_notas_fiscais(modelo,periodo,formato){
		if(periodo.value == ""){
			periodo.focus();
			mensagens(1);
			return false;
		}
		if(formato.value == ""){
			formato.focus();
			mensagens(1);
			return false;
		}
		window.location = "rotinas/exportar_nota_fiscal.php?IdLayoutNotaFiscal="+modelo.value+"&Periodo="+periodo.value+"&FormatoExportacao="+formato;
	}

	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
	    
		var url = "xml/servico.php?IdServico="+IdServico+"&IdTipoServico=1";
		
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
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}