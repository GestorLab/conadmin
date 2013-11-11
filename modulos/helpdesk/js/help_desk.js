	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_imprimir.disabled	= true;	
				document.formulario.bt_imprimir2.disabled	= true;	
				document.formulario.bt_inserir.disabled		= false;
				document.formulario.bt_inserir2.disabled	= false;
				
				document.getElementById("txtAdvertencia").innerHTML = "<B>Favor abrir um ticket para cada solicitação.<br />Tickets com mais de uma solicitação serão devolvidos.</B>";
			}
			if(document.formulario.Acao.value=='alterar'){
				document.formulario.bt_imprimir.disabled	= false;	
				document.formulario.bt_imprimir2.disabled	= false;	
				document.formulario.bt_inserir.disabled		= true;
				document.formulario.bt_inserir2.disabled	= true;
				
				document.getElementById("txtAdvertencia").innerHTML = "<B>Os prazos informados são previsões referentes a cada etapa específica e não do tempo total de conclusão do Ticket.</B>";
			}
		}	
	}
	function inicia(){
		addArquivo();
		document.formulario.IdTicket.focus();
	}
	function validar(){
		if(document.formulario.IdTipoTicket.value == ""){
			mensagens(1);
			document.formulario.IdTipoTicket.focus();
			return false;
		}
		if(document.formulario.IdSubTipoTicket.value == ""){
			mensagens(1);
			document.formulario.IdSubTipoTicket.focus();
			return false;
		}
		if(document.formulario.Assunto.value == ''){				
			mensagens(1);
			document.formulario.Assunto.focus();
			return false;
		}
		if(document.formulario.Mensagem.value == ''){				
			mensagens(1);
			document.formulario.Mensagem.focus();
			return false;
		}
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,11) == "EndArquivo_"){
					var temp = document.formulario[i].value.split('.');
					var ext = temp[temp.length-1].toLowerCase();
					
					if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
						mensagens(10);
						document.formulario[i-1].focus();
						return false;
					} else{
						if(document.formulario[i].value != '' && document.formulario[i+1].value == ''){
							mensagens(1);
							document.formulario[i+1].focus();
							return false;
						}
					}
				}
			}
		}
		
		if(document.formulario.Acao.value == "reabrir" && !confirm("ATENÇÃO!\n\nVocê está prestes a Re-Abrir este Ticket.\nDeseja continuar?")){
			mensagens(0);
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
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'alterar':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'aceitar':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'reabrir':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'encaminhar':
				if(validar()){ 
					carregando(true);
					document.formulario.submit();
				}
				break;
			case 'imprimir':
				window.open("../administrativo/ticket/layout/1/imprimir_help_desk.php?IdTicket="+document.formulario.IdTicket.value+"&Local=1");
				break;
			default:
				carregando(true);
				document.formulario.submit();
				break;
		}
	}
	function busca_status(IdStatusTemp){
		if(IdStatusTemp == undefined || IdStatusTemp == ''){
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
		
		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=128&IdParametroSistema="+IdStatusTemp;
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
						
					} else{
						document.getElementById('cp_Status').innerHTML		=	"&nbsp;";
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function busca_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp,campo){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		if(campo == undefined){
			campo = document.formulario.IdSubTipoTicket;
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoTicket;
						addOption(campo,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdSubTipoHelpDesk = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoSubTipoHelpDesk = nameTextNode.nodeValue;
							
							addOption(campo,DescricaoSubTipoHelpDesk,IdSubTipoHelpDesk);
						}
						
						if(IdSubTipoTicketTemp!=""){
							for(i=0;i<campo.length;i++){
								if(campo[i].value == IdSubTipoTicketTemp){
									campo[i].selected	=	true;
									break;
								}
							}
						}else{
							campo[0].selected	=	true;
						}						
					}else{
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						addOption(campo,"","");
					}
					
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function alualizar_filtro_subtipo_help_desk(IdTipoTicket,IdSubTipoTicketTemp){
		if(IdTipoTicket == undefined || IdTipoTicket==''){
			IdTipoTicket = 0;
		}
		if(IdSubTipoTicketTemp == undefined){
			IdSubTipoTicketTemp = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_subtipo.php?IdTipoTicket="+IdTipoTicket;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						while(document.filtro.filtro_sub_tipo.options.length > 0){
							document.filtro.filtro_sub_tipo.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoTicket;
						addOption(document.filtro.filtro_sub_tipo,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdSubTipoHelpDesk = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoHelpDesk")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoSubTipoHelpDesk = nameTextNode.nodeValue;
							
							addOption(document.filtro.filtro_sub_tipo,DescricaoSubTipoHelpDesk,IdSubTipoHelpDesk);
						}
						
						if(IdSubTipoTicketTemp!=""){
							for(i=0;i<document.filtro.filtro_sub_tipo.length;i++){
								if(document.filtro.filtro_sub_tipo[i].value == IdSubTipoTicketTemp){
									document.filtro.filtro_sub_tipo[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_sub_tipo[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_sub_tipo.options.length > 0){
							document.filtro.filtro_sub_tipo.options[0] = null;
						}
						addOption(document.filtro.filtro_sub_tipo,"Todos","");
					}
					
				}
			}
			return true;
		}
		xmlhttp.send(null);	
	}	
	function alualizar_filtro_usuario_help_desk(Campo, Usuario){
		if(Usuario == undefined){
			Usuario = '';
		}
		var xmlhttp = false;
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
	    
	    url = "xml/help_desk_usuario.php?IdStatus=1";
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_valor.options.length > 0){
							document.filtro.filtro_valor.options[0] = null;
						}
						
						var nameNode, nameTextNode, Login;
						addOption(document.filtro.filtro_valor,"Todos","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Login = nameTextNode.nodeValue;
							
							if(Campo != ''){
								addOption(document.filtro.filtro_valor,Login,Login);
							}
						}
						
						if(Campo!="" && Usuario!=""){
							for(i=0;i<document.filtro.filtro_valor.length;i++){
								if(document.filtro.filtro_valor[i].value == Usuario){
									document.filtro.filtro_valor[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_valor[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_valor.options.length > 0){
							document.filtro.filtro_valor.options[0] = null;
						}
						addOption(document.filtro.filtro_valor,"Todos","");
					}
					
				}
			}
			return true;
		}
		xmlhttp.send(null);	
	}	
	function listarHistorico(IdTicket){
		if(IdTicket == undefined || IdTicket==''){
			IdTicket = 0;
		}
		
		var nameNode, nameTextNode, url, Condicao;
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
	    
	   	url = "xml/help_desk_historico.php?IdTicket="+IdTicket;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cp_help_desk_historico').style.display = "none";
						
						// Fim de Carregando
						carregando(false);
					}else{
						document.getElementById('cp_help_desk_historico').style.display = "block";	
						while(document.getElementById('tabelaHelpDeskHistorico').rows.length > 1){
							document.getElementById('tabelaHelpDeskHistorico').deleteRow(1);
						}
						
						var cabecalho, tam, linha, c0;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTicketHistorico").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTicketHistorico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTicketHistorico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Anexo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Anexo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Status = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Cor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Assunto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Assunto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var Obs = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaHelpDeskHistorico').rows.length;
							linha	= document.getElementById('tabelaHelpDeskHistorico').insertRow(tam-1);
							
							linha.accessKey = IdTicketHistorico; 
							
							c0				= linha.insertCell(0);
							c0.style.height	= "14px";
							
							tam 	= document.getElementById('tabelaHelpDeskHistorico').rows.length;
							linha	= document.getElementById('tabelaHelpDeskHistorico').insertRow(tam-1);
							
							linha.accessKey = IdTicketHistorico;
							linha.style.backgroundColor = Cor;
							
							Obs			= Obs.replace(/  /g,"&nbsp;&nbsp;");
							Obs			= Obs.replace(/\n/g,"<br />");
							cabecalho	= "<div style='height: 14px; margin-bottom: 2px;'><div style='float:left;'><b>Data:</b> "+dateFormat(DataCriacao)+"</div><div style='float:right;'><b>Status:</b> "+Status+"</div></div>";
							
							c0					= linha.insertCell(0);
							c0.innerHTML		= cabecalho+Obs+Anexo;
							c0.style.textAlign	= "justify";
							c0.style.padding	= "2px 5px 4px 5px";
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
	function busca_login_usuario(IdGrupoUsuario,campo,IdUsuarioTemp){
		if(IdGrupoUsuario == ''){
			while(campo.options.length > 0){
				campo.options[0] = null;
			}
			
			if(document.filtro.filtro_usuario != undefined){
				addOption(campo,"Todos","");
			} else{
				addOption(campo,"","");
			}
			return false;
		}
		if(IdUsuarioTemp == undefined){
			IdUsuarioTemp = '';
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
						addOption(campo,"","");
					}else{
						while(campo.options.length > 0){
							campo.options[0] = null;
						}
						
						if(document.filtro.filtro_usuario != undefined){
							addOption(campo,"Todos","");
						}else{
							addOption(campo,"","");
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
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function addArquivo(IdArquivo){
		document.formulario.CountArquivo.value = parseInt(document.formulario.CountArquivo.value) + parseInt(1);
		
		if(IdArquivo=='' || IdArquivo == undefined){
			IdArquivo = '';	
		}
		
		var CountArquivo = parseInt(document.formulario.CountArquivo.value);
		var tam, linha, c0, c1, c2, c3, c4;
		var tabindex = CountArquivo+5;
		
		if(CountArquivo > document.formulario.MaxUploads.value && document.formulario.MaxUploads.value != ''){
			return false;
		}
		
		tam 	= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className	= "find";		
		c0.innerHTML	= "&nbsp;";
		c1.className	= "descCampo";
		c1.innerHTML	= "Anexar Arquivo";
		c2.className	= "separador";
		c2.innerHTML	= "&nbsp;";
		c3.className	= "descCampo";
		c3.innerHTML	= "<B id='titDescricaoArquivo_"+CountArquivo+"' style='color:#000;'>Descrição do Arquivo</B>";
		c4.className	= "find";
		c4.innerHTML	= "&nbsp;";
		
		tam		= document.getElementById('tabelaArquivos').rows.length;
		linha	= document.getElementById('tabelaArquivos').insertRow(tam);
		linha.accessKey = tam;
		
		c0	= linha.insertCell(0);
		c1	= linha.insertCell(1);
		c2	= linha.insertCell(2);
		c3	= linha.insertCell(3);
		c4	= linha.insertCell(4);
		
		c0.className			= "find";
		c0.innerHTML			= "&nbsp;";
		c1.className			= "campo";
		c1.style.width			= "450px";
		c1.innerHTML			= "<input type='text' name='fakeupload_"+CountArquivo+"' value='' autocomplete='off' style='width:356px; margin:0px;' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+");' onFocus=\"Foco(this,'in','auto');\" onBlur=\"Foco(this,'out');\" tabindex='"+tabindex+"'><div id='bt_procurar' style='margin:-22px 0px 0px 360px;' tabindex='"+tabindex+"'></div><input type='file' id='realupload' name='EndArquivo_"+CountArquivo+"' size='1' class='realupload' onchange='verificar_obrigatoriedade(this, document.formulario.DescricaoArquivo_"+CountArquivo+", "+CountArquivo+"); document.formulario.fakeupload_"+CountArquivo+".value = this.value;' /><div style='margin:-1px 0px 4px 0px;'>Atenção, tamanho máximo do arquivo é "+document.formulario.MaxSize.value+". <span title='"+document.formulario.ExtensaoAnexo.value.replace(/,/g, ', ')+".'>Tipos de arquivo a anexar.</span></div>";
		c2.className			= "separador";
		c2.innerHTML			= "&nbsp;";
		c3.className			= "campo";
		c3.style.verticalAlign	= "top";
		c3.innerHTML			= "<input type='text' name='DescricaoArquivo_"+CountArquivo+"' value='' style='width:338px; margin-top:0px;' tabindex='"+tabindex+"' maxlength='100' onFocus=\"Foco(this,'in');\"  onBlur=\"Foco(this,'out');\" readOnly>";
		c4.className			= "find";
		c4.style.verticalAlign	= "top";
		c4.innerHTML			= "<img src='../../img/estrutura_sistema/ico_del.gif' style='margin-top:6px;' alt='Excluir title='Excluir' onClick=\"excluir_arquivo('"+CountArquivo+"','"+IdArquivo+"');\">";
	}
	function verificar_obrigatoriedade(campo1, campo2, CountArquivo){
		if(campo1.value != ''){
			var temp = campo1.value.split('.');
			var ext = temp[temp.length-1].toLowerCase();
			document.formulario.Erro.value = 0;
			
			if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name == campo1.name){
							document.formulario.Erro.value = 10;
							
							document.formulario[i-1].focus();
						}
					}
				}
			} else{
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,11) == 'EndArquivo_'){
							var temp = document.formulario[i].value.split('.');
							var ext = temp[temp.length-1].toLowerCase();
							
							if(!document.formulario.ExtensaoAnexo.value.split(',').in_array(ext) && ext != ''){
								mensagens(10);
								document.formulario[i-1].focus();
							}
						}
					}
				}
			}
			
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#C10000";
			campo2.readOnly = false;			
			verificaErro();
		} else{
			document.getElementById("titDescricaoArquivo_"+CountArquivo).style.color = "#000000";
			campo2.readOnly = true;
			campo2.value = '';
		}
	}
	function excluir_arquivo(pos, IdArquivo){
		if(confirm("ATENÇÃO\n\nVocê está prestes a excluir este arquivo.\nDeseja continuar?") == false){
			return false;
		}	
		
		var tam = document.getElementById('tabelaArquivos').rows.length;
		
		var cont = 1;
		for(var i=1; i<=tam; i++){
			if(document.getElementById('tabelaArquivos').rows[i].accessKey == (pos*2)-1){
				document.getElementById('tabelaArquivos').deleteRow(i);
				document.getElementById('tabelaArquivos').deleteRow(i-1);
				i=tam;
				document.formulario.CountArquivo.value	= parseInt(document.formulario.CountArquivo.value) - 1;
				break;
			}
		}
		
		if(parseInt(document.formulario.CountArquivo.value) == 0){
			addArquivo();
		}
	}
	function atualiza_sessao(nome,valor){
		var xmlhttp = false;
		var nameNode, nameTextNode, url;	
		
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
		
	    url = "./rotinas/muda_parametro_consulta.php?nome="+nome+"&valor="+valor;
		xmlhttp.open("GET", url, true);
		xmlhttp.send(null);
	}
	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens(nerro,document.formulario.Local.value);
	}
	function help(msg,prioridade){
		if(msg != ''){
			scrollWindow("bottom");
		}

		document.getElementById('helpText').innerHTML = msg;
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText').style.display = "block";
		document.getElementById('helpText2').style.display = "block";
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText').style.color = "#C10000";
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText').style.color = "#004975";
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}