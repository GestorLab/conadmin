	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_visualizar_historico.disabled	= true;
				document.formulario.bt_imprimir.disabled				= true;
				document.formulario.bt_concluir.disabled				= true;
				document.formulario.bt_inserir.disabled					= false;
				document.formulario.bt_alterar.disabled					= true;
			}
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_visualizar_historico.disabled	= false;
				document.formulario.bt_imprimir.disabled				= false;
				
				if(document.formulario.LoginConclusao.value == ''){
					document.formulario.bt_concluir.disabled	= false;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_alterar.disabled		= false;
				} else{
					document.formulario.bt_concluir.disabled	= true;
					document.formulario.bt_inserir.disabled		= true;
					document.formulario.bt_alterar.disabled		= true;
				}
				
			}
		}	
	}
	function inicia(){
		status_inicial();
		document.formulario.IdProtocolo.focus();
	}
	function validar() {
		if(document.formulario.IdProtocoloTipo.value == '') {
			mensagens(1);
			document.formulario.IdProtocoloTipo.focus();
			return false;
		}
		/*
		if(document.formulario.IdTipoPessoa.value == 1){
			if(document.formulario.IdPessoa.value == '') {
				mensagens(1);
				document.formulario.IdPessoa.focus();
				return false;
			}
		} else {
			if(document.formulario.IdPessoa.value == '') {
				mensagens(1);
				document.formulario.IdPessoa.focus();
				return false;
			}
		}*/
		
		if(document.formulario.Assunto.value == '') {
			mensagens(1);
			document.formulario.Assunto.focus();
			return false;
		}
		
		if(document.formulario.Mensagem.value == '') {
			mensagens(1);
			document.formulario.Mensagem.focus();
			return false;
		}
		
		if(document.formulario.Data.value == '' && document.formulario.Hora.value != ''){
			mensagens(1);
			document.formulario.Data.focus();
			return false;
		}
		
		if(document.formulario.Erro.value == "27"){
			if(!validar_Data('titData', document.formulario.Data)){
				document.formulario.Data.focus();
				mensagens(27);
				return false;
			}
		}
		
		if(document.formulario.Hora.value == '' && document.formulario.Data.value != ''){
			mensagens(1);
			document.formulario.Hora.focus();
			return false;
		}
		
		if(document.formulario.Erro.value == "28"){
			if(!validar_Time('titHora', document.formulario.Hora)){
				mensagens(28);
				document.formulario.Hora.focus();
				return false;
			}
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
			case 'concluir':
				if(validar()){ 
					document.formulario.Concluir.value = 1;
					document.formulario.submit();
				}
				break;
			case 'imprimir':
				window.open("./protocolo/layout/1/imprimir_protocolo.php?IdProtocolo="+document.formulario.IdProtocolo.value);
				break;
			default:
				document.formulario.submit();
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
	function verificar_CPF_CNPJ(camp, tipo_pessoa){
		var valido = validar_CPF_CNPJ(camp, tipo_pessoa);
		CPF_CNPJ = camp.value;
		
		if(document.formulario.CPF_CNPJ_Duplicado.value == 1){
			if(CPF_CNPJ != '' && valido){
				var url = "./xml/pessoa.php?CPF_CNPJ="+CPF_CNPJ;
				
				call_ajax(url,function (xmlhttp){
					if(xmlhttp.responseText != 'false'){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var msg, IdPessoa = nameTextNode.nodeValue;
						
						if(document.formulario.IdTipoPessoa.value == 2){
							msg = "ATENÇÃO!\n\nCPF já utilizado.\nDeseja continuar?";
						} else{
							msg = "ATENÇÃO!\n\nCNPJ já utilizado.\nDeseja continuar?";
						}
						
						if(IdPessoa != ''){
							if(!confirm(msg)){
								busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
							}
						}
					}
				});
			}
		} else if(CPF_CNPJ != '' && (document.formulario.CPF_CNPJ_Obrigatorio.value == 1 || document.formulario.CPF_CNPJ_Duplicado.value == 2)){
			busca_pessoa(document.formulario.IdPessoa.value, 'false', document.formulario.Local.value, CPF_CNPJ);
		}
	}
	function validar_CPF_CNPJ(camp, tipo_pessoa){
		valor = camp.value.replace(/[\.\/-]/g, '');
		
		if(Number(tipo_pessoa) == 2) {
			inserir_mascara(camp,"cpf");
			
			if(!isCPF(valor) && valor != '') {
				document.getElementById("cp_CPF").innerHTML = "CPF - Inválido";
				document.getElementById("cp_CPF").style.backgroundColor = "#C10000";
				document.getElementById("cp_CPF").style.color = "#FFFFFF";
				camp.focus();
				return false;
			} else {
				document.getElementById("cp_CPF").innerHTML = "CPF";
				document.getElementById("cp_CPF").style.backgroundColor = "#FFFFFF";
				
				if(Number(document.formulario.CPF_CNPJ_Obrigatorio.value) == 1) {
					document.getElementById("cp_CPF").style.color = "#C10000";
				} else {
					document.getElementById("cp_CPF").style.color = "#000000";
				}
			}				
		} else {
			inserir_mascara(camp,"cnpj");
			
			if(!isCNPJ(valor) && valor != '') {
				document.getElementById("cp_CNPJ").innerHTML = "CNPJ - Inválido";
				document.getElementById("cp_CNPJ").style.backgroundColor = "#C10000";
				document.getElementById("cp_CNPJ").style.color = "#FFFFFF";
				camp.focus();
				return false;
			} else {
				document.getElementById("cp_CNPJ").innerHTML = "CNPJ";
				document.getElementById("cp_CNPJ").style.backgroundColor = "#FFFFFF";
				
				if(Number(document.formulario.CPF_CNPJ_Obrigatorio.value) == 1) {
					document.getElementById("cp_CNPJ").style.color = "#C10000";
				}else {
					document.getElementById("cp_CNPJ").style.color = "#000000";
				}
			}	
		}
		
		if(valor == '') {
			return false;
		} else {
			return true;
		}
	}
	function inserir_mascara(camp,tipo) {
		var retorno = camp.value.replace(/[\.\/-]/g, '');
		
		if(retorno == '') {
			return false;
		}
		
		if(tipo == 'cpf') {
			var cpf = retorno.substr(0,3);
			var temp = retorno.substr(3,3);
			
			if(temp != '') {
				cpf += '.' + temp;
				temp = retorno.substr(6,3);
				
				if(temp != '') {
					cpf += '.' + temp;
					temp = retorno.substr(9,2);
					
					if(temp != '') {
						cpf += '-' + temp;
					}
				}
			}
			
			camp.value = cpf;
		} else {
			cnpj = retorno.substr(0,2);
			var temp = retorno.substr(2,3);
			
			if(temp != '') {
				cnpj += '.' + temp;
				temp = retorno.substr(5,3);
				
				if(temp != '') {
					cnpj += '.' + temp;
					temp = retorno.substr(8,4);
					
					if(temp != '') {
						cnpj += '/' + temp;
						temp = retorno.substr(12,2);
						
						if(temp != '') {
							cnpj += '-' + temp;
						}
					}
				}
			}
			
			camp.value = cnpj;
		}
	}
	function protocolo_visualizar_historico(Visualizar,ScrollBottom){
		while(document.getElementById("tabelaProtocoloHistorico").rows.length > 1){
			document.getElementById("tabelaProtocoloHistorico").deleteRow(1);
		}
		
		if(Visualizar == undefined){
			Visualizar = true;
		} else if(Visualizar){
			document.getElementById("cp_protocolo_historico").style.display = "none";
		}
		
		if(ScrollBottom == undefined){
			ScrollBottom = true;
		}
		
		if(document.getElementById("cp_protocolo_historico").style.display == "none" && Visualizar){
			var url = "xml/protocolo_historico.php?IdProtocolo="+document.formulario.IdProtocolo.value;
			
			call_ajax(url,function (xmlhttp){
				if(xmlhttp.responseText != 'false'){
					document.formulario.bt_visualizar_historico.value = "Ocultar Histórico";
					document.getElementById("cp_protocolo_historico").style.display = "block";
					
					for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdProtocoloHistorico").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocoloHistorico")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdProtocoloHistorico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Mensagem")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Mensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Status = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var LoginCriacao = nameTextNode.nodeValue;
						var tam = document.getElementById('tabelaProtocoloHistorico').rows.length;
						var linha = document.getElementById('tabelaProtocoloHistorico').insertRow(tam-1);
						
						linha.accessKey = IdProtocoloHistorico+"_0"; 
						
						var c0 = linha.insertCell(0);
						c0.style.height	= "14px";
						
						tam = document.getElementById('tabelaProtocoloHistorico').rows.length;
						linha = document.getElementById('tabelaProtocoloHistorico').insertRow(tam-1);
						
						linha.accessKey = IdProtocoloHistorico+"_1"; 
						linha.style.backgroundColor = "#e2e7ed";
						
						Mensagem = Mensagem.replace(/  /g, "&nbsp;&nbsp;");
						Mensagem = Mensagem.replace(/\r\n|\n/g, "<br />");
						Mensagem = "<div><table style='width:100%;'><tr><td><b>Data:</b> "+dateFormat(DataCriacao)+"</td><td style='width:303px;'><b>Status:</b> "+Status+"</td></tr><tr><td colspan='2'>"+Mensagem+"</td></tr></table>";
						
						c0 = linha.insertCell(0);
						c0.innerHTML = Mensagem;
						c0.style.textAlign = "justify";
						c0.style.padding = "2px 3px 4px 3px";
					}
					
					if(ScrollBottom){
						scrollWindow('bottom');
					}
				}
			});
		} else{
			document.formulario.bt_visualizar_historico.value = "Visualizar Histórico";
			document.getElementById("cp_protocolo_historico").style.display = "none";
		}
	}
	function buscar_protocolo_tipo(IdProtocoloTipoDefault){
		if(IdProtocoloTipoDefault == undefined){
			IdProtocoloTipoDefault = "";
		}
		
	   	var url = "xml/protocolo_tipo.php?IdStatus=1&IdProtocoloTipoADD="+IdProtocoloTipoDefault+"&"+Math.random();
	   	
		call_ajax(url,function (xmlhttp){ 
			if(xmlhttp.responseText != "false"){
				while(document.formulario.IdProtocoloTipo.options.length > 0){
					document.formulario.IdProtocoloTipo.options[0] = null;
				}
				
				addOption(document.formulario.IdProtocoloTipo,"","");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdProtocoloTipo").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocoloTipo")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdProtocoloTipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProtocoloTipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoProtocoloTipo = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdProtocoloTipo,DescricaoProtocoloTipo,IdProtocoloTipo);
				}
				
				if(IdProtocoloTipoDefault != ""){
					for(i = 0; i < document.formulario.IdProtocoloTipo.length; i++){
						if(document.formulario.IdProtocoloTipo[i].value == IdProtocoloTipoDefault){
							document.formulario.IdProtocoloTipo[i].selected	= true;
							break;
						}
					}
				} else{
					document.formulario.IdProtocoloTipo[0].selected = true;
				}
			}
		});
	}
	function protocolo_codigo(){
		if(confirm("ATENÇÃO!\n\nVocê está prestes a gerar um novo protocolo.\nDeseja continuar?")){
			var url = "xml/protocolo_gera_codigo.php";
			
			call_ajax(url, function (xmlhttp) {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdProtocolo")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdProtocolo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Erro = nameTextNode.nodeValue;
				
				document.formulario.Erro.value = Erro;
				
				busca_protocolo(IdProtocolo, false, document.formulario.Local.value);
				verificaErro();
			});
		}
	}
	function verifica_previsao_etapa(){
		var Data = document.formulario.Data.value;
		var Hora = document.formulario.Hora.value;
		var DataAtual = parseInt(document.formulario.DataAtualTemp.value);
		var DataPrevisao = Data.substring(6,10)+Data.substring(3,5)+Data.substring(0,2)+Hora.replace(/:/g,"");
		
		if((DataAtual > parseInt(DataPrevisao) && DataPrevisao.length == 12) || document.formulario.Acao.value == "inserir"){
			document.formulario.bt_alterar.disabled = true;
		} else{
			document.formulario.bt_alterar.disabled = false;
		}
	}
	function validar_Data(id, campo){
		verifica_previsao_etapa();
	
		if(campo.value == ''){
			if(document.formulario.Hora.value != ''){
				document.getElementById('titData').style.color = "#c10000";
				document.getElementById('titData').style.backgroundColor='#fff';
			} else{
				document.getElementById('titData').style.color = "#000";
				document.getElementById('titData').style.backgroundColor='#fff';
				document.getElementById("titHora").style.color = "#000";
			}
			
			mensagens(0);
			
			return false;
		}
		
		if(isData(campo.value) == false){	
			document.getElementById('titData').style.backgroundColor = '#c10000';
			document.getElementById('titData').style.color='#fff';
			
			mensagens(27);
			
			document.formulario.Erro.value = 27;
			
			return false;
		}else{
			document.getElementById('titData').style.color = "#c10000";
			document.getElementById('titData').style.backgroundColor='#fff';
			
			if(isTime(document.formulario.Hora.value)){
				document.getElementById("titHora").style.color = "#c10000";
				document.getElementById("titHora").style.backgroundColor='#fff';
			} else{
				mensagens(28);
				
				document.formulario.Erro.value = 28;
				
				return false;
			}
			
			if(validar_data_hora(campo.value, '', id)){
				return true;
			} else{
				return false;
			}
		}
	}
	function validar_Time(id, campo){
		verifica_previsao_etapa();
		
		if(campo.value == ''){
			if(document.formulario.Data.value != ''){
				document.getElementById('titHora').style.color = "#c10000";
				document.getElementById('titHora').style.backgroundColor='#fff';
			} else{
				document.getElementById('titHora').style.color = "#000";
				document.getElementById('titHora').style.backgroundColor='#fff';
				document.getElementById("titData").style.color = "#000";
			}
			
			mensagens(0);
			return false;
		}
		
		if(isTime(campo.value) == false){
			document.getElementById('titHora').style.backgroundColor = '#c10000';
			document.getElementById('titHora').style.color='#fff';
			
			document.formulario.Erro.value = 28;
			
			mensagens(28);
			
			return false;
		}else{
			document.getElementById('titHora').style.color = "#c10000";
			document.getElementById('titHora').style.backgroundColor='#fff';
			
			if(isData(document.formulario.Data.value) || document.formulario.Data.value == ""){ 
				document.getElementById("titData").style.color = "#c10000";
				document.getElementById("titData").style.backgroundColor='#fff';
			} else{
				document.formulario.Erro.value = 27;
				
				mensagens(27);
				
				return false;
			}	
			
			if(validar_data_hora('', campo.value, id)){
				return true;
			} else{
				return false;
			}
		}
	}
	function validar_data_hora(Data, Hora, Id){
		var IdAux;
		var IdGrupoUsuario = document.formulario.IdGrupoUsuarioAtendimento.value;
		var LoginResponsavel = document.formulario.LoginAtendimento.value;
		
		if(Data == ''){
			Data = document.formulario.Data.value;
		}
		
		if(Hora == ''){
			Hora = document.formulario.Hora.value;
		}
		
		var url = "xml/validar_data_hora.php?Data="+Data+"&Hora="+Hora;
		
		call_ajax(url, function(xmlhttp){ 
			if(xmlhttp.responseText == 'true'){
				mensagens(0);
				
				document.getElementById(Id).style.color = "#c10000";
				document.getElementById(Id).style.backgroundColor = "#fff";
				document.formulario.Erro.value = "";
				
			} else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("Error")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Error = nameTextNode.nodeValue;
				
				if(Error != 0){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTemp")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdTemp = nameTextNode.nodeValue;
					
					document.getElementById(IdTemp).style.backgroundColor = '#c10000';
					document.getElementById(IdTemp).style.color='#fff';
				}
				
				document.formulario.Erro.value = Error;
				mensagens(Error);
			}
		});
		
		if(document.formulario.Erro.value != "" && document.formulario.Erro.value != 0){
			return false;
		} else{
			return true;
		}
	}