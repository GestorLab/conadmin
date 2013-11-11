	function busca_protocolo_tipo(IdProtocoloTipo, Erro, Local){
		if(IdProtocoloTipo == '' || IdProtocoloTipo == undefined){
			IdProtocoloTipo = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/protocolo_tipo.php?IdProtocoloTipo="+IdProtocoloTipo;
		
		call_ajax(url, function (xmlhttp){
			if(Erro){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "ProtocoloTipo":
						addParmUrl("marProtocolo","IdProtocoloTipo","");
						addParmUrl("marProtocoloNovo","IdProtocoloTipo","");
						
						document.formulario.IdProtocoloTipo.value			= '';
						document.formulario.DescricaoProtocoloTipo.value	= '';
						document.formulario.AberturaCDA.value				= '';
						document.formulario.IdStatus.value 					= '';
						document.formulario.IdGrupoUsuarioAbertura.value	= '';
						document.formulario.LoginCriacao.value				= '';
						document.formulario.DataCriacao.value				= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.DataAlteracao.value				= '';
						document.formulario.Acao.value						= "inserir";
						
						busca_login_usuario(document.formulario.IdGrupoUsuarioAbertura.value,document.formulario.LoginAbertura);
						verificaAcao();
						
						document.formulario.IdProtocoloTipo.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocoloTipo")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdProtocoloTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProtocoloTipo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoProtocoloTipo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("AberturaCDA")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var AberturaCDA = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuarioAbertura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdGrupoUsuarioAbertura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAbertura")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAbertura = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
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
					case "ProtocoloTipo":
						addParmUrl("marProtocolo","IdProtocoloTipo",IdProtocoloTipo);
						addParmUrl("marProtocoloNovo","IdProtocoloTipo",IdProtocoloTipo);
						
						document.formulario.IdProtocoloTipo.value			= IdProtocoloTipo;
						document.formulario.DescricaoProtocoloTipo.value	= DescricaoProtocoloTipo;
						document.formulario.AberturaCDA.value				= AberturaCDA;
						document.formulario.IdStatus.value 					= IdStatus;
						document.formulario.IdGrupoUsuarioAbertura.value	= IdGrupoUsuarioAbertura;
						document.formulario.LoginCriacao.value				= LoginCriacao;
						document.formulario.DataCriacao.value				= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value			= LoginAlteracao;
						document.formulario.DataAlteracao.value				= dateFormat(DataAlteracao);
						document.formulario.Acao.value						= "alterar";
						
						busca_login_usuario(IdGrupoUsuarioAbertura,document.formulario.LoginAbertura,LoginAbertura);
						verificaAcao();
						
						document.formulario.IdProtocoloTipo.focus();
						break;
				}
			}
		});
	}