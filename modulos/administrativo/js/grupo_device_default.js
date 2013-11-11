	function busca_grupo_device(IdGrupoDevice, Erro, Local){
		if(IdGrupoDevice == ''){
			IdGrupoDevice = '-1';
		}
		
		if(Local == undefined || Local == ''){
			Local = document.formulario.Local.value;
		}
		
		var url = "xml/grupo_device.php?IdGrupoDevice="+IdGrupoDevice;
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				if(Local == 'GrupoDevice'){
					document.formulario.Erro.value = 0;
					
					verificaErro();
				}
				
			}
			
			if(xmlhttp.responseText == 'false'){
				if(Local == 'GrupoDevice'){
					document.formulario.IdGrupoDevice.value			= '';
					document.formulario.DescricaoGrupoDevice.value	= '';
					document.formulario.LoginCriacao.value			= '';
					document.formulario.DataCriacao.value			= '';
					document.formulario.LoginAlteracao.value		= '';
					document.formulario.DataAlteracao.value			= '';
					document.formulario.Acao.value					= 'inserir';
				}
				
				if(Local == 'Device'){
					document.formulario.IdGrupoDevice.value			= '';
					document.formulario.DescricaoGrupoDevice.value	= '';
				}
				
				document.formulario.IdGrupoDevice.focus();
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoDevice")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdGrupoDevice = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoDevice")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoGrupoDevice = nameTextNode.nodeValue;
				
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
				
				if(Local == 'GrupoDevice'){
					document.formulario.IdGrupoDevice.value			= IdGrupoDevice;
					document.formulario.DescricaoGrupoDevice.value	= DescricaoGrupoDevice;
					document.formulario.LoginCriacao.value			= LoginCriacao;
					document.formulario.DataCriacao.value			= DataCriacao;
					document.formulario.LoginAlteracao.value		= LoginAlteracao;
					document.formulario.DataAlteracao.value			= DataAlteracao;
					document.formulario.Acao.value					= 'alterar';
				}
				
				if(Local == 'Device'){
					document.formulario.IdGrupoDevice.value			= IdGrupoDevice;
					document.formulario.DescricaoGrupoDevice.value	= DescricaoGrupoDevice;
				}
			}
			
			if(document.getElementById("quadroBuscaGrupoDevice") != null){
				if(document.getElementById("quadroBuscaGrupoDevice").style.display == "block"){
					document.getElementById("quadroBuscaGrupoDevice").style.display = "none";
				}
			}
			
			verificaAcao();
		});
	}