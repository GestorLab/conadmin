	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_inserir.disabled	= false;
				document.formulario.bt_alterar.disabled	= true;
				document.formulario.bt_excluir.disabled	= true;
			}
			
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_inserir.disabled	= true;
				document.formulario.bt_alterar.disabled	= false;
				document.formulario.bt_excluir.disabled	= true;
				
				if(Number(document.formulario.IdProtocoloTipo.value) > 9999){
					document.formulario.bt_excluir.disabled = false;
				}
			}
		}	
	}
	function inicia(){
		document.formulario.IdProtocoloTipo.focus();
	}
	function validar() {
		if(document.formulario.DescricaoProtocoloTipo.value == '') {
			mensagens(1);
			document.formulario.DescricaoProtocoloTipo.focus();
			return false;
		}
		
		if(document.formulario.AberturaCDA.value == '') {
			mensagens(1);
			document.formulario.AberturaCDA.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == '') {
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		
		mensagens(0);
		return true;
	}
	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case 'inserir':
				if(validar()){ 
					document.formulario.submit();
				}
				break;
			case 'alterar':
				if(validar()){ 
					document.formulario.submit();
				}
				break;
			default:
				document.formulario.submit();
				break;
		}
	}
	function busca_login_usuario(IdGrupoUsuario,campo,IdUsuarioTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			} else{
				addOption(campo," ","");
			}
			return false;
		}
		
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
		}

		var url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				addOption(campo," ","");
			}else{
				while(campo.options.length > 0){
					campo.options[0] = null;
				}
				
				if(document.filtro.filtro_usuario != undefined){
					addOption(campo,"Todos","");
				}else{
					addOption(campo," ","");
				}
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						
					nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Login = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeUsuario = nameTextNode.nodeValue;
					
					var Descricao	=	NomeUsuario.substr(0,50);	
					
					addOption(campo,Descricao,Login);
				}
				if(IdUsuarioTemp!=''){
					for(ii=0;ii<campo.length;ii++){
						if(campo[ii].value == IdUsuarioTemp){
							campo[ii].selected = true;
							break;
						}
					}
				}else{
					campo[0].selected = true;
				}
			}
		});
	}
	function excluir(IdProtocoloTipo){
		if(IdProtocoloTipo == '' || IdProtocoloTipo == undefined){
			IdProtocoloTipo = document.formulario.IdProtocoloTipo.value;
		}
		
		if(excluir_registro()){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return false;
				}
			}
			
			var url = "./files/excluir/excluir_protocolo_tipo.php?IdProtocoloTipo="+IdProtocoloTipo;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_protocolo_tipo.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(IdProtocoloTipo == document.getElementById("tableListar").rows[i].accessKey){
								document.getElementById("tableListar").deleteRow(i);
								tableMultColor("tableListar", document.filtro.corRegRand.value);
								document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById("tableListar").rows.length-2);
								break;
							}
						}							
					}
				}
			});
		}
	}