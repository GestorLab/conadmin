	function atualizar_filtro_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		
		url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		
		call_ajax(url, function(xmlhttp){ 
			var nameNode, nameTextNode;
			if(xmlhttp.responseText != 'false'){
				while(document.filtro.filtro_sub_tipo.options.length > 0){
					document.filtro.filtro_sub_tipo.options[0] = null;
				}
				
				var nameNode, nameTextNode, IdSubTipoTicket;
				addOption(document.filtro.filtro_sub_tipo,"Todos","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[i];
					nameTextNode = nameNode.childNodes[0];
					var IdSubTipoHelpDesk = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoHelpDesk")[i];
					nameTextNode = nameNode.childNodes[0];
					var DescricaoSubTipoHelpDesk = nameTextNode.nodeValue;
					
					addOption(document.filtro.filtro_sub_tipo,DescricaoSubTipoHelpDesk,IdSubTipoHelpDesk);
				}
				
				if(IdSubTipoTicketTemp!=""){
					for(i=0;i<document.filtro.filtro_sub_tipo.length;i++){
						if(document.filtro.filtro_sub_tipo[i].value == IdSubTipoTicketTemp){
							document.filtro.filtro_sub_tipo[i].selected = true;
							break;
						}
					}
				}else{
					document.filtro.filtro_sub_tipo[0].selected = true;
				}
			}else{
				while(document.filtro.filtro_sub_tipo.options.length > 0){
					document.filtro.filtro_sub_tipo.options[0] = null;
				}
				addOption(document.filtro.filtro_sub_tipo,"Todos","");
			}
		});
	}