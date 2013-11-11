function busca_servico_agendamento(IdServico,QtdMes,Erro,Local){
	if(IdServico == '' || IdServico == undefined){
		IdServico = 0;
	}
	if(QtdMes == '' || QtdMes == undefined){
		QtdMes = 0;
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
	
    url = "xml/servico_agendamento.php?IdServico="+IdServico+"&QtdMes="+QtdMes;
	xmlhttp.open("GET", url,true);
	xmlhttp.onreadystatechange = function(){
		// Carregando...
		carregando(true);
		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(Erro != false){
					document.formulario.Erro.value = 0;
					verificaErro();
				}
				
				if(xmlhttp.responseText == 'false'){
					document.formulario.IdStatus.value			= '';	
					document.formulario.IdQtdMeses.value		= '';
					document.formulario.QtdDias.value			= '';
					document.formulario.LoginCriacao.value		= '';
					document.formulario.DataCriacao.value		= '';
					document.formulario.LoginAlteracao.value	= '';
					document.formulario.DataAlteracao.value		= '';						
					document.formulario.Acao.value				= "inserir";
					
					busca_novo_status(0);
					verificar_status();
					
					document.formulario.IdQtdMeses.focus();
					verificaAcao();
					// Fim de Carregando
					carregando(false);
				} else{		
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMes")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var QtdMes = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdNovoStatus")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var IdNovoStatus = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("BaseDataStatusContratoOS")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var BaseDataStatusContratoOS = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginAlteracao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataAlteracao = nameTextNode.nodeValue;
						
						document.formulario.IdQtdMeses.value			= QtdMes;
						document.formulario.LoginCriacao.value			= LoginCriacao;
						document.formulario.QtdDias.value				= BaseDataStatusContratoOS;
						document.formulario.DataCriacao.value			= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value		= LoginAlteracao;
						document.formulario.DataAlteracao.value			= dateFormat(DataAlteracao);
						document.formulario.Acao.value 					= "alterar";
						
						busca_novo_status(IdStatus, IdNovoStatus, false);
						verificar_status(IdNovoStatus);
						
						document.formulario.IdQtdMeses.focus();
						verificaAcao();
					}
				}
				if(window.janela != undefined){
					window.janela.close();
				}		
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}