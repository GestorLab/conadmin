 function busca_conta_sms(IdContaSMS,Erro,Local){
	if(IdContaSMS == '' || IdContaSMS == undefined){
		IdContaSMS = 0;
	}
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
	}
	
	var nameNode, nameTextNode, url;
	url = "xml/conta_sms.php?IdContaSMS="+IdContaSMS;	
	
	call_ajax(url, function (xmlhttp) {
		if(xmlhttp.responseText != "false") {
			for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdContaSMS").length; i++) {			
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaSMS")[i]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaSMS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoContaSMS")[i]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoContaSMS = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdOperadora")[i]; 
				nameTextNode = nameNode.childNodes[0];
				var IdOperadora = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
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
				
				switch(Local){
					case 'ContaSMS':
						document.formulario.IdContaSMS.value	 	= IdContaSMS;
						document.formulario.Descricao.value		 	= DescricaoContaSMS;
						document.formulario.IdOperadora.value		= IdOperadora;
						document.formulario.IdStatus.value 			= IdStatus;
						document.formulario.LoginCriacao.value		= LoginCriacao;
						document.formulario.DataCriacao.value		= DataCriacao;
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.DataAlteracao.value		= DataAlteracao;
						
						if(IdOperadora != ""){
							verifica_operadora(IdOperadora,IdContaSMS);
						}
						document.formulario.Acao.value = "alterar";
						verificaAcao();
					break;
				}
			}
		}else{
			switch(Local){
				case 'ContaSMS':
					document.formulario.IdContaSMS.value	 			= "";
					document.formulario.Descricao.value		 			= "";
					document.formulario.IdOperadora.value				= "";
					document.formulario.IdStatus.value 					= "";
					document.formulario.LoginCriacao.value				= "";
					document.formulario.DataCriacao.value				= "";
					document.formulario.LoginAlteracao.value			= "";
					document.formulario.DataAlteracao.value				= "";
					verifica_operadora(0);
					document.formulario.Acao.value = "inserir";
					verificaAcao();
				break;
			}
		}		
	},true);
}