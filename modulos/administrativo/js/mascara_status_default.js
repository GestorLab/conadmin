function busca_mascara_status(IdServico, IdStatus, Erro, Local) {
	if(IdServico == '' || IdServico == undefined) {
		IdServico = 0;
	}
	
	if(IdStatus == '' || IdStatus == undefined) {
		IdStatus = 0;
	}
	
	if(Local == '' || Local == undefined) {
		Local = document.formulario.Local.value;
	}
	
   	var url = "./xml/mascara_status.php?IdServico=" + IdServico + "&IdStatus=" + IdStatus;
	
	call_ajax(url, function (xmlhttp) { 
		var nameNode, nameTextNode;
		
		if(Erro != false) {
			document.formulario.Erro.value = 0;
			verificaErro();
		}

		if(xmlhttp.responseText == 'false') {
			document.formulario.PercentualDesconto.value	= '';
			document.formulario.TaxaMudancaStatus.value		= '';
			document.formulario.QtdMinimaDia.value			= '';
			document.formulario.DataCriacao.value			= '';
			document.formulario.LoginCriacao.value			= '';
			document.formulario.DataAlteracao.value			= '';
			document.formulario.LoginAlteracao.value		= '';
			document.formulario.Acao.value					= "inserir";
			
			verificaAcao();
			document.formulario.IdStatus.focus();
		} else {
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdTipoServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DescricaoServico = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var IdStatus = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualDesconto")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var PercentualDesconto = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("TaxaMudancaStatus")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var TaxaMudancaStatus = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMinimaDia")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var QtdMinimaDia = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DataCriacao = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var LoginCriacao = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DataAlteracao = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var LoginAlteracao = nameTextNode.nodeValue;
			
			document.formulario.IdServico.value				= IdServico;
			document.formulario.DescricaoServico.value		= DescricaoServico;
			document.formulario.IdTipoServico.value			= IdTipoServico;
			document.formulario.IdStatus.value				= IdStatus;
			document.formulario.QtdMinimaDia.value			= QtdMinimaDia;
			document.formulario.PercentualDesconto.value	= formata_float(Arredonda(PercentualDesconto, 2), 2).replace(/\./, ',');
			document.formulario.TaxaMudancaStatus.value		= formata_float(Arredonda(TaxaMudancaStatus, 2), 2).replace(/\./, ',');
			document.formulario.DataCriacao.value			= dateFormat(DataCriacao);
			document.formulario.LoginCriacao.value			= LoginCriacao;
			document.formulario.DataAlteracao.value			= dateFormat(DataAlteracao);
			document.formulario.LoginAlteracao.value		= LoginAlteracao;
			document.formulario.Acao.value					= 'alterar';
			
			listar_mascara_status(IdServico);
			verificaAcao();
		}
		
		if(window.janela != undefined) {
			window.janela.close();
		}
	});
}