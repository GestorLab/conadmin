	function validar(){
		if(document.formulario.Descricao.value == ""){
			document.formulario.Descricao.focus();
			mensagens(1);
			return false
		}
		if(document.formulario.IdOperadora.value == ""){
			document.formulario.IdOperadora.focus();
			mensagens(1);
			return false
		}
		if(document.formulario.IdStatus.value == ""){
			document.formulario.IdStatus.focus();
			mensagens(1);
			return false
		}
		if(document.getElementById("bl_ParametrosContaSMS").style.display != "none") {
			for(i = 0; i<document.formulario.length; i++){
				if(document.formulario[i].name != undefined){
					if(document.formulario[i].name.substring(0,22) == 'ParametroOperadoraSMS_' && document.formulario[i].value == '' && document.formulario[i+1].value == 1){
						document.formulario[i].focus();
						mensagens(1);
						return false
					}
				}
			}
		}
		
		return true;
	}
	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		switch(acao){
			case "inserir":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "alterar":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "excluir":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			case "testar":
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
		}
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){

				document.formulario.bt_inserir.disabled = false;
				document.formulario.bt_alterar.disabled	= true;
				document.formulario.bt_excluir.disabled	= true;
				document.formulario.bt_testar.disabled	= true;
			}
			
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_inserir.disabled = true;
				document.formulario.bt_alterar.disabled	= false;
				document.formulario.bt_excluir.disabled	= false;
				document.formulario.bt_testar.disabled	= false;
			}
			if(document.formulario.IdContaSMS.value != ""){
				document.formulario.bt_fila.disabled = false;
			}else{
				document.formulario.bt_fila.disabled = true;
			}
		}
	}
	function verifica_operadora(IdOperadora,IdContaSMS){
		document.getElementById("bl_ParametrosContaSMS").style.display = "none";
		
		if(IdOperadora != "" && IdOperadora != undefined){
			buscar_paramentro_operadora(IdOperadora,IdContaSMS);
		}
	}
	function buscar_paramentro_operadora(IdOperadora,IdContaSMS){
		if(IdOperadora == "" || IdOperadora == undefined){
			IdOperadora	= 0;
		}
		
		if(IdContaSMS == "" || IdContaSMS == undefined){
			IdContaSMS = 0;
		}
		
		var url = "xml/conta_sms_parametro_operadora.php?IdOperadora="+IdOperadora+"&IdContaSMS="+IdContaSMS;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != "false") {
				document.getElementById("bl_ParametrosContaSMS").style.display = "block";
				document.getElementById("bl_ParametrosContaSMS").innerHTML = "";
				
				var linha = [];
				var element = document.getElementById("bl_ParametrosContaSMS");
				var DivLN = document.createElement("div");
				DivLN.setAttribute("id", "cp_tit");
				DivLN.innerHTML = "Parametros da Conta SMS";
				element.insertBefore(DivLN, null);
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdParametroOperadoraSMS").length; i++) {
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroOperadoraSMS")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdParametroOperadoraSMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroOperadoraSMS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroOperadoraSMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSMSDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorParametroSMSDefault = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSMS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorParametroSMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Obrigatorio")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Obrigatorio = nameTextNode.nodeValue;
					
					if(Obrigatorio == 1){
						DescricaoParametroOperadoraSMS = "<b>"+DescricaoParametroOperadoraSMS+"</b>";
					}
					
					if(IdContaSMS == ""){
						ValorParametroSMS = ValorParametroSMSDefault;
					}
					
					var CampoParametroOperadoraSMS = "<input type='text' name='ParametroOperadoraSMS_"+IdParametroOperadoraSMS+"' value='"+ValorParametroSMS+"' autocomplete='off' style='width:200px' onFocus=\"Foco(this,'in');\" onBlur=\"Foco(this,'out');\" tabindex='"+(document.formulario.TabIndex.value++)+"' /><input type='hidden' name='ParametroOperadoraSMSObrigatorio_"+IdParametroOperadoraSMS+"' value='"+Obrigatorio+"' />";
					
					if((i%2) == 0){
						var IdNome = "ln_ParametrosContaSMS_"+IdParametroOperadoraSMS;
						var DivLN = document.createElement("table");
						DivLN.setAttribute("id", IdNome);
						element.insertBefore(DivLN, null);
						
						var table = document.getElementById(IdNome);
						linha[0] = table.insertRow((table.rows.length)-1);
						var c0 = linha[0].insertCell(0);
						var c1 = linha[0].insertCell(1);
						
						c0.className = "find";
						c0.innerHTML = "&nbsp;";
						
						c1.className = "campo";
						c1.innerHTML = CampoParametroOperadoraSMS;
						
						linha[1] = table.insertRow((table.rows.length)-1);
						c0 = linha[1].insertCell(0);
						c1 = linha[1].insertCell(1);
						
						c0.className = "find";
						c0.innerHTML = "&nbsp;";
						
						c1.className = "descCampo";
						c1.innerHTML = DescricaoParametroOperadoraSMS;
					} else{
						var c2 = linha[0].insertCell(2);
						var c3 = linha[0].insertCell(3);
						
						c2.className = "separador";
						c2.innerHTML = "&nbsp;";
						
						c3.className = "campo";
						c3.innerHTML = CampoParametroOperadoraSMS;
						
						c2 = linha[1].insertCell(2);
						c3 = linha[1].insertCell(3);
						
						c2.className = "separador";
						c2.innerHTML = "&nbsp;";
						
						c3.className = "descCampo";
						c3.innerHTML = DescricaoParametroOperadoraSMS;
					}
				}
			} else{
				document.getElementById("bl_ParametrosContaSMS").style.display = "none";
			}
		});
	}
	function busca_rapida_parametro(IdOperadora){
		var url = "xml/conta_sms_parametro_operadora.php?IdOperadora="+IdOperadora.value;
		
		call_ajax(url, function (xmlhttp) {
		
			while(document.filtro.filtro_parametro.options.length > 0){
				document.filtro.filtro_parametro.options[0] = null;
			}
			addOption(document.filtro.filtro_parametro,"","0");
			
			var nameNode, nameTextNode;	
			if(xmlhttp.responseText != "false") {
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdParametroOperadoraSMS").length; i++) {			
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroOperadoraSMS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdParametroOperadoraSMS = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroOperadoraSMS")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoParametroOperadoraSMS = nameTextNode.nodeValue;
					
					addOption(document.filtro.filtro_parametro,DescricaoParametroOperadoraSMS,IdParametroOperadoraSMS);
					document.filtro.filtro_parametro.options[0].selected;
				}
			}
		});
	}
	function excluir(IdContaSMS,IdOperadora){
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

		url = "files/excluir/excluir_conta_sms.php?IdContaSMS="+IdContaSMS+"&IdOperadora="+IdOperadora;
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
							url = 'cadastro_conta_sms.php?Erro='+document.formulario.Erro.value;
							window.location.replace(url);
						}else{
							verificaErro();
						}
					}else{
						var numMsg = parseInt(xmlhttp.responseText);
						mensagens(numMsg);
						if(numMsg == 7){
							var aux = 0;
							for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
								if(IdPessoa == document.getElementById('tableListar').rows[i].accessKey){
									document.getElementById('tableListar').deleteRow(i);										
									tableMultColor('tableListar',document.filtro.corRegRand.value);
									aux=1;
									break;
								}
							}
							if(aux=1){
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
	function visualizar_fila(IdContaSMS,Estado){
		if(Estado == true && document.getElementById("cp_fila_de_envio").style.display == "none"){
			document.getElementById("cp_fila_de_envio").style.display = "block";	
			
			url = "xml/conta_sms_fila_envio.php?IdContaSMS="+IdContaSMS;
			call_ajax(url, function(xmlhttp){
				var nameNode, nameTextNode;
				var c0,c1,c2,c3,c4,c5;
				var tam,linha;				
				if(document.getElementById('tabelaFilaDeEnvio').rows.length > 1){
					document.getElementById('tabelaFilaDeEnvio').innerHTML = "";
					linha	= document.getElementById('tabelaFilaDeEnvio').insertRow(0);
	
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);
					c2	= linha.insertCell(2);
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					
					c0.innerHTML = "Id";
					c1.innerHTML = "Nome Pessoa";
					c2.innerHTML = "Celular";
					c3.innerHTML = "Tipo Mensagem";
					c4.innerHTML = "Data Cadastro";
					c5.innerHTML = "Status";
					
					c0.style.color = "#FFF";
					c1.style.color = "#FFF";
					c2.style.color = "#FFF";
					c3.style.color = "#FFF";
					c4.style.color = "#FFF";
					c5.style.color = "#FFF";
					
					c0.style.fontWeight = "bold";
					c1.style.fontWeight = "bold";
					c2.style.fontWeight = "bold";
					c3.style.fontWeight = "bold";
					c4.style.fontWeight = "bold";
					c5.style.fontWeight = "bold";
					
					c0.style.background = "#004492";				
					c1.style.background = "#004492";
					c2.style.background = "#004492";
					c3.style.background = "#004492";
					c4.style.background = "#004492";
					c5.style.background = "#004492";
				}
				if(xmlhttp.responseText == 'false'){
					return false;
					document.getElementById('cp_fila_de_envio').innerHTML = "";
				}else{
					for(i=0;i< xmlhttp.responseXML.getElementsByTagName("IdHistoricoMensagem").length; i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdHistoricoMensagem")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						IdHistoricoMensagem = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Celular")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Celular = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Titulo")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Titulo = nameTextNode.nodeValue;			
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacaoTemp")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataCriacaoTemp = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataEnvioTemp")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var DataEnvioTemp = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Status = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("Color")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Color = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Total")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var Total = nameTextNode.nodeValue;

						tam 	= document.getElementById('tabelaFilaDeEnvio').rows.length;
						linha	= document.getElementById('tabelaFilaDeEnvio').insertRow(tam);

						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);	
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);						
						c5	= linha.insertCell(5);
						
						c0.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+IdHistoricoMensagem+"</a>";
						c0.style.cursor  = "pointer";
						c0.style.backgroundColor = Color;
						
						c1.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+Nome+"</a>";
						c1.style.cursor = "pointer";						
						c1.style.backgroundColor = Color;
						
						c2.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+Celular+"</a>";
						c2.style.cursor = "pointer";						
						c2.style.backgroundColor = Color;
						
						c3.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+Titulo+"</a>";
						c3.style.cursor = "pointer";						
						c3.style.backgroundColor = Color;
						
						c4.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+DataCriacaoTemp;
						c4.style.cursor = "pointer";										
						c4.style.backgroundColor = Color;
						
						c5.innerHTML = "<a href=\"cadastro_reenvio_mensagem.php?IdHistoricoMensagem="+IdHistoricoMensagem+"\">"+Status;
						c5.style.cursor = "pointer";
						c5.style.backgroundColor = Color;					
					}
					
					linha	= document.getElementById('tabelaFilaDeEnvio').insertRow(tam+1);
	
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);
					c2	= linha.insertCell(2);
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					
					c0.innerHTML = "Status: "+Total;					
					c0.style.color = "#FFF";					
					c0.style.fontWeight = "bold";
					
					c0.style.background = "#004492";					
					c1.style.background = "#004492";
					c2.style.background = "#004492";
					c3.style.background = "#004492";
					c4.style.background = "#004492";
					c5.style.background = "#004492";
				}
			});	
		}else{
			document.getElementById("cp_fila_de_envio").style.display = "none";
			url = "xml/conta_sms_fila_envio.php?IdContaSMS=0";
			call_ajax(url, function(xmlhttp){
				i = 1;
				while(document.getElementById('tabelaFilaDeEnvio').rows.length > i){
					document.getElementById('tabelaFilaDeEnvio').deleteRow(i);
					i++;
				}
			});
		}
	}