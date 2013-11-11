	function busca_template_mensagem(IdTemplate, Erro, Local){
		if(IdTemplate == '' || IdTemplate == undefined){
			IdTemplate = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/template_mensagem.php?IdTemplate="+IdTemplate;
		
		call_ajax(url, function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "TemplateMensagem":
						document.formulario.IdTemplate.value		= '';
						document.formulario.DescricaoTemplate.value	= '';
						document.formulario.Estrutura.value			= '';
						document.formulario.LoginCriacao.value		= '';
						document.formulario.DataCriacao.value		= '';
						document.formulario.LoginAlteracao.value	= '';
						document.formulario.DataAlteracao.value		= '';
						document.formulario.Acao.value				= "inserir";
						
						listar_template_mensagem();
						verificaAcao();
						
						document.formulario.IdTemplate.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemplate")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdTemplate = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoTemplate")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoTemplate = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Estrutura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Estrutura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				switch(Local){
					case "TemplateMensagem":
						document.formulario.IdTemplate.value		= IdTemplate;
						document.formulario.DescricaoTemplate.value	= DescricaoTemplate;
						document.formulario.Estrutura.value			= Estrutura;
						document.formulario.LoginCriacao.value		= LoginCriacao;
						document.formulario.DataCriacao.value		= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.DataAlteracao.value		= dateFormat(DataAlteracao);
						document.formulario.Acao.value				= "alterar";
						
						listar_template_mensagem();
						verificaAcao();
						
						document.formulario.IdTemplate.focus();
						break;
				}
			}
		});
	}