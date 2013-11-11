	function filtro_buscar_servico(IdServico,Local){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		var IdStatus = '';
		
		switch(Local){
			case 'ContratoCrescimentoPeriodo':
				IdStatus = '';
				break;
			default:
				IdStatus = 1;
				break;
		}
		
		
	    url = "xml/servico.php?IdServico="+IdServico+"&IdStatus="+IdStatus+"&IdTipoServico=1";

		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.IdServico.value				= '';
				document.filtro.DescricaoIdServico.value	= '';
			} else {
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.IdServico.value				= IdServico;
				document.filtro.DescricaoIdServico.value	= DescricaoServico;
				document.filtro.IdServicoTemp.value			= "";
			}
		});	
	}
	/*
	function manter_campo_filtro_selecionado(){
		var valor_campo			=  document.filtro.filtro_somar_contrato_cancelado.value;
		var valor_campo_migrado	=  document.filtro.filtro_somar_contrato_cancelado_migrado.value;
		
		document.filtro.submit();
		document.filtro.filtro_somar_contrato_cancelado.value			= valor_campo;
		document.filtro.filtro_somar_contrato_cancelado_migrado.value   = valor_campo_migrado;
		
	}*/