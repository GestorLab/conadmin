	function excluir(IdOrdemServico,IdStatus){
		if(IdOrdemServico== '' || undefined){
			IdOrdemServico = document.formulario.IdOrdemServico.value;
		}
		if(IdStatus != 0){
			return false;
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
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
    
   			url = "files/excluir/excluir_ordem_servico.php?IdOrdemServico="+IdOrdemServico;
			xmlhttp.open("GET", url,true);

			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_ordem_servico.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdOrdemServico == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));;
									}
									document.getElementById('tableListarValor').innerHTML			=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}								
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
	} 
	function validar(){
		if(document.formulario.IdTipoOrdemServico.value == ""){
			mensagens(1);
			document.formulario.IdTipoOrdemServico.focus();
			return false;
		}
		if(document.formulario.IdTipoOrdemServico.value != '2'){
			if(document.formulario.IdPessoa.value==""){
				mensagens(1);
				document.formulario.IdPessoa.focus();
				return false;
			}
			if(document.formulario.IdServico.value==''){
				mensagens(1);
				document.formulario.IdServico.focus();
				return false;
			}
			if(document.formulario.Acao.value == 'inserir' || document.formulario.Login.value == document.formulario.LoginCriacao.value){	
				if(document.formulario.Valor.value==""){
					mensagens(1);
					document.formulario.Valor.focus();
					return false;
				}
				if(document.formulario.DescricaoOS.value==""){
					mensagens(1);
					document.formulario.DescricaoOS.focus();
					return false;
				}
				var posInicial=0,posFinal=0,temp=0;
				if(document.formulario.IdServico.value!=''){
					for(i = 0; i<document.formulario.length; i++){
						if(document.formulario[i].name != undefined){
							if(document.formulario[i].name.substring(0,6) == 'Valor_'){
								if(posInicial == 0){
									posInicial = i;
								}
								posFinal = i;
							}
						}
					}
					if(posInicial != 0){
						for(i = posInicial; i<=posFinal; i=i+3){
							if(document.formulario[i+1].value == 1){
								if(document.formulario[i].type == 'text'){
								 	if(document.formulario[i].value == '' && document.formulario[i+2].value == '1'){
										mensagens(1);
										document.formulario[i].focus();
										return false;
									}
								}
								if(document.formulario[i].type == 'select-one'){
									var cont = 0;
									for(j=0;j<document.formulario[i].options.length;j++){
										if(document.formulario[i][j].selected == true && document.formulario[i][j].value != ""){
											cont++;
											j = document.formulario[i].options.length;
										}
									}
									if(cont == 0 && document.formulario[i+2].value == '1'){
										mensagens(1);
										document.formulario[i].focus();
										return false;
									}
								}
							}
						}
					}
				}
			}else{
				if(document.formulario.DescricaoOS.value==""){
					mensagens(1);
					document.formulario.DescricaoOS.focus();
					return false;
				}
			}
		}else{
			if(document.formulario.DescricaoOSInterna.value==""){
				mensagens(1);
				document.formulario.DescricaoOSInterna.focus();
				return false;
			}
		}
		if(document.formulario.IdStatusNovo.value=="" && document.formulario.Acao.value == "inserir"){
			if(document.formulario.IdStatusNovo.value==""){
				mensagens(1);
				document.formulario.IdStatusNovo.focus();
				return false;
			}
		}
		if(document.formulario.IdStatusNovo.value!=""){
			switch(document.formulario.IdStatusNovo.value){
				case '0': //Cancelado
					if(document.formulario.Obs.value==""){
						mensagens(1);
						document.formulario.Obs.focus();
						return false;
					}
					break;
				case '1': //Em Aberto
					if(document.formulario.IdGrupoUsuarioAtendimento.value==""){
						mensagens(1);
						document.formulario.IdGrupoUsuarioAtendimento.focus();
						return false;
					}
					break;
				case '3': //Pendente
					if(document.formulario.Obs.value==""){
						mensagens(1);
						document.formulario.Obs.focus();
						return false;
					}
					break;
				case '2': //Concluido
					if(document.formulario.IdTipoOrdemServico.value != 2){
						valor	=	document.formulario.Valor.value;
						valor	=	new String(valor);
						valor	=	valor.replace('.','');
						valor	=	valor.replace('.','');
						valor	=	valor.replace(',','.');
						
						if(valor != 0){
							valorF	=	document.formulario.ValorFinal.value;
							valorF	=	new String(valorF);
							valorF	=	valorF.replace('.','');
							valorF	=	valorF.replace('.','');
							valorF	=	valorF.replace(',','.');	
							
							if(valor != valorF){
								mensagens(105);
								document.formulario.ValorFinal.focus();
								return false;
							}
							if(document.formulario.FormaCobranca.value==""){
								mensagens(1);
								document.formulario.FormaCobranca.focus();
								return false;
							}
							if(document.formulario.IdLocalCobranca.value=="" && document.formulario.IdLocalCobranca.readOnly == false){
								mensagens(1);
								document.formulario.IdLocalCobranca.focus();
								return false;
							}
						}
					}
					break;
			}
			if(document.formulario.Data.value!="" && isData(document.formulario.Data.value) == false){
				mensagens(27);
				document.formulario.Data.focus();
				return false;
			}
			if(document.formulario.Hora.value!="" && isTime(document.formulario.Hora.value)==false){
				mensagens(28);
				document.formulario.Hora.focus();
				return false;
			}
			if(document.formulario.Data.value=="" && document.formulario.Hora.value!=""){
				mensagens(1);
				document.formulario.Data.focus();
				return false;
			}
		}
		mensagens(0);
		return true;
	}
	
	function atualiza_tipo_servico(IdTipoOrdemServico){
		switch(IdTipoOrdemServico){
			case '2':
				document.getElementById('cp_dadosCliente').style.display		=	'none';
				document.getElementById('cpDadosServico').style.display			=	'none';
				document.getElementById('cp_dadosContrato').style.display		=	'none';
				document.getElementById('cpDescricaoOSInterna').style.display	=	'block';
				break;
			default:
				document.getElementById('cp_dadosCliente').style.display		=	'block';
				document.getElementById('cpDadosServico').style.display			=	'block';
				document.getElementById('cp_dadosContrato').style.display		=	'block';
				document.getElementById('cpDescricaoOSInterna').style.display	=	'none';
		}
	}
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function validar_Time(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return false;
		}
		if(isTime(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#000';
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_imprimir.disabled	= true;	
				document.formulario.bt_fatura.disabled		= true;			
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			else{			
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_imprimir.disabled	= false;
				document.formulario.bt_excluir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				
				if(document.formulario.IdStatus.value == '0' || document.formulario.IdStatus.value == '5'){
					document.formulario.bt_alterar.disabled 	= true;
				}else{
					document.formulario.bt_alterar.disabled 	= false;
					
					if(document.formulario.IdStatus.value >= 4 || document.formulario.IdStatus.value){
						document.formulario.bt_fatura.disabled		= false;
					}else{
						document.formulario.bt_fatura.disabled		= true;	
					}
				}	
				
				if(document.formulario.Login.value == document.formulario.LoginCriacao.value || document.formulario.Login.value == document.formulario.LoginAtendimento.value){
					document.formulario.bt_alterar.disabled 	= false;
					
					if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
						
						if(document.formulario.IdStatus.value == '0' || document.formulario.IdStatus.value == '5'){
							document.formulario.bt_alterar.disabled 	= true;
							
							if(document.formulario.IdStatus.value == '2'){
								document.formulario.bt_excluir.disabled 	= true;
							}else{
								document.formulario.bt_excluir.disabled 	= false;
							}
						}else{
							document.formulario.bt_alterar.disabled 	= false;
							document.formulario.bt_excluir.disabled 	= true;
						}
						
						if(document.formulario.IdStatus.value == '4'){
							document.formulario.bt_excluir.disabled 	= true;	
							document.formulario.bt_imprimir.disabled 	= false;					
						}
					}else{
						document.formulario.bt_excluir.disabled 	= true;					
					}	
				}else{ //verificar LoginAtendimento no GrupoUsuarioAtendimento
					if(document.formulario.LoginAtendimento.value == ''){
						var IdGrupoUsuarioAtendimento = document.formulario.IdGrupoUsuarioAtendimento.value;
					
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
				
						url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
						xmlhttp.open("GET", url,true);
				
						xmlhttp.onreadystatechange = function(){ 
				
							// Carregando...
							carregando(true);
				
							if(xmlhttp.readyState == 4){ 
								if(xmlhttp.status == 200){
									if(xmlhttp.responseText == 'false'){
										document.formulario.bt_alterar.disabled 	= true;
									}else{
										var update	=	false;
										var Login	=	document.formulario.Login.value;
										for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
									
											nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
											nameTextNode = nameNode.childNodes[0];
											LoginAtendimento = nameTextNode.nodeValue;
											
											if(LoginAtendimento == Login){
												if(document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 2){
													document.formulario.bt_alterar.disabled 	= true;
													
													if(document.formulario.IdStatus.value == 2){
														document.formulario.bt_excluir.disabled 	= true;
													}else{
														document.formulario.bt_excluir.disabled 	= false;
													}
												}else{
													document.formulario.bt_alterar.disabled 	= false;
													document.formulario.bt_excluir.disabled 	= true;
												}
												update = true;
												break;
											}
										}
										if(update == false){
											document.formulario.bt_alterar.disabled 	= true;
											document.formulario.bt_excluir.disabled 	= true;
										}
									}
								}
								// Fim de Carregando
								carregando(false);
							}
							return true;
						}
						xmlhttp.send(null);
					}else{
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_excluir.disabled 	= true;
					}					
				}
			}
		}	
	}
	
	function busca_login_usuario(IdGrupoUsuario,campo,LoginTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			return false;
		}
		if(LoginTemp == undefined){
			LoginTemp = '';
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

		url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
					}else{
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						addOption(campo,"","");
							
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
						if(LoginTemp!=''){
							for(ii=0;ii<campo.length;ii++){
								if(campo[ii].value == LoginTemp){
									campo[ii].selected = true;
									break;
								}
							}
						}else{
							campo[0].selected = true;
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
	function addStatus(IdStatus){
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

		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=40&IdParametroSistemaFalse=4";
		
		if(IdStatus!="" && IdStatus!=undefined){
			url	+= "&IdParametroSistema="+IdStatus;
		}
		
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdStatusNovo.options.length > 0){
							document.formulario.IdStatusNovo.options[0] = null;
						}
					}else{
						while(document.formulario.IdStatusNovo.options.length > 0){
							document.formulario.IdStatusNovo.options[0] = null;
						}
						addOption(document.formulario.IdStatusNovo,"","");
							
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorParametroSistema = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdStatusNovo,ValorParametroSistema,IdParametroSistema);
						}
						document.formulario.IdStatusNovo[0].selected = true;
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function verifica_permissao_update(IdGrupoUsuarioAtendimento){
		if(IdGrupoUsuarioAtendimento != ''){
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
	
			url = "xml/usuario_grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuarioAtendimento;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
	
				// Carregando...
				carregando(true);
	
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){
							document.formulario.bt_alterar.disabled 	= true;
						}else{
							var Login	=	document.formulario.Login.value;
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
						
								nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
								nameTextNode = nameNode.childNodes[0];
								LoginAtendimento = nameTextNode.nodeValue;
								
								if(LoginAtendimento == Login || document.formulario.Login.value == document.formulario.LoginCriacao.value){
									document.formulario.IdStatusNovo.disabled				=	false;
									document.formulario.Data.readOnly						=	false;
									document.formulario.Hora.readOnly						=	false;
									document.formulario.IdGrupoUsuarioAtendimento.disabled	=	false;
									document.formulario.LoginAtendimento.disabled			=	false;
									document.formulario.Obs.readOnly						=	false;
									document.formulario.DescricaoOS.readOnly				= 	false;
									document.formulario.Valor.readOnly						=	false;
									break;
								}
							}
						}
					}
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function busca_status(IdStatusTemp){
		if(IdStatusTemp == undefined){
			IdStatusTemp = 0;
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

		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=40&IdParametroSistema="+IdStatusTemp;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorParametroSistema = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
						document.getElementById('cp_Status').style.display	=	"block";		
						document.getElementById('cp_Status').style.color	=	Cor;		
						document.getElementById('cp_Status').innerHTML		=	ValorParametroSistema;
						
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function cadastrar(acao){
		if(acao == 'cancelar' || acao=='imprimir'){
			document.formulario.Acao.value = acao;	
			if(acao == 'cancelar'){
				if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
					document.formulario.submit();
				}else{
					mensagens(2);
				}
			}else{
				document.formulario.submit();
			}
		}else{
			if(validar()==true){
				document.formulario.submit();
			}
		}
	}
	function visualiza_campo(IdStatus){
		switch(IdStatus){
			case '0':	//Cancelado
				document.getElementById('titGrupoAtendimento').style.display	= 'none';
				document.getElementById('titUsuarioAtendimento').style.display	= 'none';
				document.getElementById('titDataAgendamento').style.display		= 'none';
				document.getElementById('titHoraAgendamento').style.display		= 'none';
				document.getElementById('cpGrupoAtendimento').style.display		= 'none';
				document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
				document.getElementById('cpData').style.display					= 'none';
				document.getElementById('cpDataIco').style.display				= 'none';
				document.getElementById('cpHora').style.display					= 'none';
				document.getElementById('cpFaturamento').style.display			= 'none';
				
				document.getElementById('titObs').style.color					= '#C10000';
				break;
			case '1':	//Em Aberto
				document.getElementById('titGrupoAtendimento').style.display	= 'block';
				document.getElementById('titUsuarioAtendimento').style.display	= 'block';
				document.getElementById('titDataAgendamento').style.display		= 'block';
				document.getElementById('titHoraAgendamento').style.display		= 'block';
				document.getElementById('cpGrupoAtendimento').style.display		= 'block';
				document.getElementById('cpUsuarioAtendimento').style.display	= 'block';
				document.getElementById('cpData').style.display					= 'block';
				document.getElementById('cpDataIco').style.display				= 'block';
				document.getElementById('cpHora').style.display					= 'block';
				document.getElementById('cpFaturamento').style.display			= 'none';
				
				document.getElementById('titGrupoAtendimento').style.color		= '#C10000';
				document.getElementById('titObs').style.color					= '#000';
				break;
			case '2':	//Concluido
				document.getElementById('titGrupoAtendimento').style.display	= 'none';
				document.getElementById('titUsuarioAtendimento').style.display	= 'none';
				document.getElementById('titDataAgendamento').style.display		= 'none';
				document.getElementById('titHoraAgendamento').style.display		= 'none';
				document.getElementById('cpGrupoAtendimento').style.display		= 'none';
				document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
				document.getElementById('cpData').style.display					= 'none';
				document.getElementById('cpDataIco').style.display				= 'none';
				document.getElementById('cpHora').style.display					= 'none';
				
				document.getElementById('titObs').style.color					= '#000';
				
				valor	=	document.formulario.Valor.value;
				valor	=	new String(valor);
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace(',','.');	
				
				if(valor > 0){
					document.getElementById('cpFaturamento').style.display			= 'block';
					
					if(document.formulario.IdContrato.value == ""){
						document.formulario.FormaCobranca.value						= 2; //Individual
						document.formulario.FormaCobrancaTemp.value					= 2; //Individual
						document.formulario.IdLocalCobranca.disabled				= false;	
						document.getElementById('titLocalCobranca').style.color		= '#C10000';
						
					}else{
						document.formulario.FormaCobranca.value						= ""; 
						document.formulario.FormaCobrancaTemp.value					= ""; 
						document.formulario.IdLocalCobranca.disabled				= true;	
						document.getElementById('titLocalCobranca').style.color		= '#000';
					}
					verifica_local_cobranca(document.formulario.FormaCobranca.value);
				}else{
					document.getElementById('cpFaturamento').style.display			= 'none';
				}
				
				break;
			case '3':	//Pendente
				document.getElementById('titGrupoAtendimento').style.display	= 'none';
				document.getElementById('titUsuarioAtendimento').style.display	= 'none';
				document.getElementById('titDataAgendamento').style.display		= 'none';
				document.getElementById('titHoraAgendamento').style.display		= 'none';
				document.getElementById('cpGrupoAtendimento').style.display		= 'none';
				document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
				document.getElementById('cpData').style.display					= 'none';
				document.getElementById('cpDataIco').style.display				= 'none';
				document.getElementById('cpHora').style.display					= 'none';
				document.getElementById('cpFaturamento').style.display			= 'none';
				
				document.getElementById('titObs').style.color					= '#C10000';
				break;
			default:
				document.getElementById('titGrupoAtendimento').style.display	= 'none';
				document.getElementById('titUsuarioAtendimento').style.display	= 'none';
				document.getElementById('titDataAgendamento').style.display		= 'none';
				document.getElementById('titHoraAgendamento').style.display		= 'none';
				document.getElementById('cpGrupoAtendimento').style.display		= 'none';
				document.getElementById('cpUsuarioAtendimento').style.display	= 'none';
				document.getElementById('cpData').style.display					= 'none';
				document.getElementById('cpDataIco').style.display				= 'none';
				document.getElementById('cpHora').style.display					= 'none';
				document.getElementById('cpFaturamento').style.display			= 'none';
				
				document.getElementById('titObs').style.color					= '#000';
				break;
		}
	}
	function listarParametro(IdOrdemServico,IdServico,Erro){
		while(document.getElementById('tabelaParametro').rows.length > 1){
			document.getElementById('tabelaParametro').deleteRow(0);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/ordem_servico_parametro.php?IdServico="+IdServico+"&IdOrdemServico="+IdOrdemServico+"&IdStatus=1";
 		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						carregando(false);
					}else{
						document.getElementById('cp_parametrosServico').style.display	=	'none';
						var obsTemp = new Array(), invisivel="",cont=0;
						var readOnly;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoOrdemServicoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoOrdemServicoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorDefault = nameTextNode.nodeValue;	
														
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("VisivelOS")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var VisivelOS = nameTextNode.nodeValue;
							
							if(Valor == '' && ValorDefault != ''){
								Valor	=	ValorDefault;
							}
							
							if(VisivelOS == '1'){
							
								obsTemp[cont]	=	Obs;
								
								tam 	 = document.getElementById('tabelaParametro').rows.length;
	
								if(cont%2 == 0){
									linha	 = document.getElementById('tabelaParametro').insertRow(tam);
									tabindex = 7 + cont + 1;
									pos		 = 0;
									padding	 = 22;
								}else{	
									padding	 = 10;
									tabindex = 7 + cont;
									pos		 = 1;
									if(obsTemp[(cont-1)]!= undefined && obsTemp[(cont-1)]!= ''){
										if(Obs	==	'')	Obs	=	'<BR>';
									}
								}
								
								if((cont+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && cont%2 == 0){
									padding	 = 22;	
								}
								
								if(Obrigatorio == 1){
									color = "#C10000";
								}else{
									color = "#000000";
								}
								
								if(document.formulario.Login.value == document.formulario.LoginCriacao.value){
									readOnly	=	'';
								}else{
									readOnly	=	"readOnly='true'";
								}
								
								linha.accessKey = IdParametroServico; 
	
								c0	= linha.insertCell(pos);
								
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'date')\" maxlength='10' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><BR>"+Obs+"</p>";
											break;
										case '2':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'int')\" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><BR>"+Obs+"</p>";
											break;
										case '3':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'float')\" maxlength='12' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><BR>"+Obs+"</p>";
											break;
										case '5':
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" onkeypress=\"mascara(this,event,'mac')\" maxlength='17' tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><BR>"+Obs+"</p>";
											break;
										default:
											c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='Valor_"+IdParametroServico+"' value='"+Valor+"' style='width:399px;' autocomplete='off' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" tabindex="+tabindex+"><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'><BR>"+Obs+"</p>";
									}
								}else{
									campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
									campo +=	"<B style='color:"+color+";'>"+DescricaoOrdemServicoParametro+"</B></p>";
									campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
									
									if(readOnly == "readOnly='true'"){
										readOnly	=	"disabled='true'";
									}
									
									campo +=	"<select type='select' name='Valor_"+IdParametroServico+"'  style='width:406px;' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out');\" "+readOnly+" tabindex="+tabindex+">";
									campo += "<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(Valor == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'>";
									campo +=	"<BR>"+Obs+"</p>";
									
									c0.innerHTML	=	campo;
								}
								
								cont++;
							}else{
								invisivel	+=	"<div style='display:none'>";
								if(IdTipoParametro == 1){
									switch(IdMascaraCampo){
										case '1':
											invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'></p>";
											break;
										case '2':
											invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'></p>";
											break;
										case '3':
											invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'></p>";
											break;
										case '5':
											invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'></p>";
											break;
										default:
											invisivel += "<input type='text' name='Valor_"+IdParametroServico+"' value='"+ValorDefault+"' style='width:399px;' autocomplete='off'><input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'></p>";
									}
								}else{
									campo = 	"";
									campo +=	"<select type='select' name='Valor_"+IdParametroServico+"'  style='width:406px;'>";
									campo += 	"<option value=''></option>";
												
									valor	=	OpcaoValor.split("\n");
									for(var ii=0; ii<valor.length; ii++){
										selecionado = "";
										if(ValorDefault == valor[ii]){
											selecionado	=	"selected=true";
										}
										campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
									}
									campo += "</select>";
									campo +=	"<input type='hidden' name='Obrigatorio_"+IdParametroServico+"' value='"+Obrigatorio+"'>";
									
									invisivel	+=	campo;
								}
							}
						}
						
						if(cont > 0){
							document.getElementById('cp_parametrosServico').style.display	=	'block';	
						}
						
						if(invisivel !=""){
							tam 	 = document.getElementById('tabelaParametro').rows.length;
							linha	 = document.getElementById('tabelaParametro').insertRow(tam);
							
							linha.accessKey = IdParametroServico; 
							
							c0	= linha.insertCell(0);
							c0.innerHTML	=	invisivel;
						}	
						
						if(document.formulario.Erro.value != '' && document.formulario.Erro.value != false){
							scrollWindow('bottom');
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
	function faturamento(){
		if(document.formulario.IdOrdemServico.value!="" ){
			window.location.replace("cadastro_ordem_servico_fatura.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
		}
	}
	function listarParametroContrato(IdServico,Erro,IdContrato){
		while(document.getElementById('tabelaParametroContrato').rows.length > 0){
			document.getElementById('tabelaParametroContrato').deleteRow(0);
		}		
	
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == ''){
			IdServico = 0;
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
	    url = "xml/contrato_parametro.php?IdServico="+IdServico+"&IdContrato="+IdContrato+"&IdStatus=1&VisivelOS=1";

		xmlhttp.open("GET", url,true);
 		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						document.getElementById('cp_parametrosContrato').style.display	 =	'none';	
						document.getElementById('tabelaParametroContrato').style.display =	'block';
						carregando(false);
					}else{
						var padding, visivel,DescricaoParametroServico, Obrigatorio, ValorDefault, Valor, IdParametroServico, color, salvar;
						
						var obsTemp = new Array();
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							if(i==0){
								document.getElementById('cp_parametrosContrato').style.display	 =	'block';	
								document.getElementById('tabelaParametroContrato').style.display =	'block';	
							}
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Valor = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoParametro")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoParametro = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdMascaraCampo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdMascaraCampo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("OpcaoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var OpcaoValor = nameTextNode.nodeValue;
							
							tam 	 = document.getElementById('tabelaParametroContrato').rows.length;
							
							obsTemp[i]	=	Obs;
							
							if(i%2 == 0){
								linha	 = document.getElementById('tabelaParametroContrato').insertRow(tam);
								padding	 = 22;	
								pos		 = 0;
							}else{
								padding	 = 10;	
								pos		 = 1;
								if(obsTemp[(i-1)]!= undefined && obsTemp[(i-1)]!= ''){
									if(Obs	==	'')	Obs	=	'<BR>';
								}
							}
							
							if((i+1) == xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length && i%2 == 0){
								padding	 = 22;	
							}
								
							linha.accessKey = IdParametroServico; 
							
							c0	= linha.insertCell(pos);
							
							if(IdTipoParametro == 1){
								switch(IdMascaraCampo){
									case '1':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" readOnly maxlength='10'><BR>"+Obs+"</p>";
										break;
									case '2':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" readOnly><BR>"+Obs+"</p>";
										break;
									case '3':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" maxlength='12' readOnly><BR>"+Obs+"</p>";
										break;
									case '5':
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' autocomplete='off' "+visivel+" maxlength='17' readOnly><BR>"+Obs+"</p>";
										break;
									default:
										c0.innerHTML = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'><B style='color:#000;'>"+DescricaoParametroServico+"</B></p><p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'><input type='text' name='ValorContrato_"+(i+1)+"' value='"+Valor+"' style='width:399px;' "+visivel+" readOnly><BR>"+Obs+"</p>";
								}
							}else{
								campo  = "<p style='margin:0; padding-bottom:1px; padding-left:"+padding+"px'>";
								campo +=	"<B style='color:"+color+";'>"+DescricaoParametroServico+"</B></p>";
								campo += "<p style='padding-bottom:6px; padding-left:"+padding+"px; margin:0;'>";
								campo +=	"<select name='ValorContrato_"+(i+1)+"'  style='width:406px;' "+visivel+" disabled>";
								campo += "<option value=''></option>";
											
								valor	=	OpcaoValor.split("\n");
								for(var ii=0; ii<valor.length; ii++){
									selecionado = "";
									if(Valor == valor[ii]){
										selecionado	=	"selected=true";
									}
									campo +=	"<option value='"+valor[ii]+"' "+selecionado+">"+valor[ii]+"</option>";
								}
								campo += "</select>";
								campo +=	"<BR>"+Obs+"</p>";
								
								c0.innerHTML	=	campo;
							}
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
	function verifica_local_cobranca(FormaCobranca){
		if(document.formulario.IdContrato.value == ""){
			if(FormaCobranca == 1){	//Contrato
				var IdPessoa = document.formulario.IdPessoa.value;
				seleciona_local_cobranca(IdPessoa);
			}else{ //Individual
				seleciona_local_cobranca('');
			}
		}
	}
	function seleciona_local_cobranca(IdPessoa,IdLocalCobrancaTemp){
		if(IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = '';
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
		if(IdPessoa != ""){
			url = "xml/local_cobranca_contrato.php?IdPessoa="+IdPessoa;
		}else{
			url = "xml/local_cobranca.php";
		}
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
					}else{
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						addOption(document.formulario.IdLocalCobranca,"","");
							
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
						}
						if(IdLocalCobrancaTemp!=''){
							for(ii=0;ii<document.formulario.IdLocalCobranca.length;ii++){
								if(document.formulario.IdLocalCobranca[ii].value == IdLocalCobranca){
									document.formulario.IdLocalCobranca[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdLocalCobranca[0].selected = true;
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
