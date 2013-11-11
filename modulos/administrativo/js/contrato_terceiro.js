	function inicia(){
		if(document.filtro.filtro_agente_autorizado.value!=''){
			atualizar_filtro_carteira(document.filtro.filtro_agente_autorizado.value,document.filtro.filtro_carteira_temp.value);
		}
	}
	
	function atualizar_filtro_carteira(IdAgenteAutorizado, IdCarteiraTemp){
		if(IdAgenteAutorizado == ''){
			while(document.filtro.filtro_carteira.options.length > 0){
				document.filtro.filtro_carteira.options[0] = null;
			}
			addOption(document.filtro.filtro_carteira,"Todos","0");
			return false;
		}
		
		if(IdCarteiraTemp == undefined){
			IdCarteiraTemp = '';
		}
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	    //    	xmlhttp.overrideMimeType('text/xml');
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

		url = "xml/carteira.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdStatus=1";	
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.filtro.filtro_carteira.options.length > 0){
							document.filtro.filtro_carteira.options[0] = null;
						}
					}else{
						while(document.filtro.filtro_carteira.options.length > 0){
							document.filtro.filtro_carteira.options[0] = null;
						}
						addOption(document.filtro.filtro_carteira,"Todos","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCarteira").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCarteira")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCarteira = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Nome = nameTextNode.nodeValue;
							
							addOption(document.filtro.filtro_carteira,Nome,IdCarteira);
						}
						if(IdCarteiraTemp!=''){
							for(ii=0;ii<document.filtro.filtro_carteira.length;ii++){
								if(document.filtro.filtro_carteira[ii].value == IdCarteiraTemp){
									document.filtro.filtro_carteira[ii].selected = true;
									document.filtro.filtro_carteira_temp.value = document.filtro.filtro_carteira[ii].value;
									break;
								}
							}
						}else{
							document.filtro.filtro_carteira[0].selected = true;	
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
	
	function filtro_buscar_servico(IdServico,Local){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		var IdStatus = '';
		
		switch(Local){
			case 'ContratoAgenteAutorizado':
				IdStatus = '';
				break;
			default:
				IdStatus = 1;
				break;
		}
		
		
	    url = "xml/servico.php?IdServico="+IdServico+"&IdStatus="+IdStatus+"&IdTipoServico=1";

		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				document.filtro.IdServico.value = "";
			}
		});	
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