	function inicia(){
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function excluir(IdServico,IdParametroServico,listar){
		if(IdServico== '' || IdServico==undefined){
			IdServico = document.formulario.IdServico.value;
		}
		if(IdParametroServico== '' || IdParametroServico==undefined){
			IdParametroServico = document.formulario.IdParametroServico.value;
		}
		if(excluir_registro() == true){
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
    
   			url = "files/excluir/excluir_servico_parametro.php?IdServico="+IdServico+"&IdParametroServico="+IdParametroServico;
			xmlhttp.open("GET", url,true);

			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							document.formulario.Erro.value = xmlhttp.responseText;
							if(listar == 'listar'){
								if(parseInt(xmlhttp.responseText) == 7){
									if(document.formulario.IdParametroServico.value == IdParametroServico){
										document.formulario.IdParametroServico.value 		= '';		
										document.formulario.IdTipoAcesso.value 				= '';		
										document.formulario.DescricaoParametroServico.value = '';
										document.formulario.Editavel_Texto.value 			= '';
										document.formulario.Editavel_Selecao.value 			= '';
										document.formulario.Obrigatorio_Texto.value			= '';
										document.formulario.Obrigatorio_Selecao.value		= '';
										document.formulario.ValorDefaultInput.value			= '';
										document.formulario.OpcaoValor.value				= '';
										document.formulario.Visivel_Texto.value				= '';
										document.formulario.Visivel_Selecao.value			= '';
										document.formulario.VisivelOS_Texto.value			= '';
										document.formulario.VisivelOS_Selecao.value			= '';
										document.formulario.VisivelCDA_Texto.value			= '';
										document.formulario.VisivelCDA_Selecao.value		= '';
										document.formulario.Obs.value						= '';
										document.formulario.IdStatusParametro.value			= '';
										document.formulario.IdTipoParametro.value			= '';
										document.formulario.IdMascaraCampo.value			= '';
										document.formulario.DataCriacao.value				= '';
										document.formulario.LoginCriacao.value				= '';
										document.formulario.DataAlteracao.value				= '';
										document.formulario.LoginAlteracao.value			= '';
										document.formulario.Obrigatorio_Texto.disabled		= false;
										document.formulario.Obrigatorio_Selecao.disabled	= false;
										document.formulario.IdMascaraCampo.disabled			= false;
										document.formulario.Acao.value						= 'inserir';
										
										while(document.formulario.ValorDefaultSelect.options.length > 0){
											document.formulario.ValorDefaultSelect.options[0] = null;
										}
								
										status_inicial();
										verificaAcao();
										verificaTipoParametro();
										
										document.formulario.IdParametroServico.focus();
									}
									
									for(var i=0; i<document.getElementById('tabelaParametro').rows.length; i++){
										if(IdParametroServico == document.getElementById('tabelaParametro').rows[i].accessKey){
											document.getElementById('tabelaParametro').deleteRow(i);
											tableMultColor('tabelaParametro',document.filtro.corRegRand.value);
											document.getElementById('tabelaParametroTotal').innerHTML	=	"Total: "+(document.getElementById('tabelaParametro').rows.length-2);
											break;
										}
									}
									document.getElementById('tabelahelpText2').style.display	=	'block';
									verificaErro2();
								}else{
									document.getElementById('tabelahelpText2').style.display	=	'block';
									verificaErro2();
								}
							}else{
								if(parseInt(xmlhttp.responseText) == 7){
									document.formulario.Acao.value 	= 'inserir';
									url = 'cadastro_servico_parametro.php?Erro='+document.formulario.Erro.value+'&IdServico='+IdServico;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdServico+"_"+IdParametroServico == document.getElementById('tableListar').rows[i].accessKey){
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
	} 
	function validar(){
		if(document.formulario.DescricaoParametroServico.value==""){
			mensagens(1);
			document.formulario.DescricaoParametroServico.focus();
			return false;
		}
		if(document.formulario.IdTipoAcesso.value==""){
			mensagens(1);
			document.formulario.IdTipoAcesso.focus();
			return false;
		}
		if(document.formulario.SalvarHistorico.value==""){
			mensagens(1);
			document.formulario.SalvarHistorico.focus();
			return false;
		}
		if(document.formulario.IdTipoParametro.value==""){
			mensagens(1);
			document.formulario.IdTipoParametro.focus();
			return false;
		}
		if(document.formulario.IdStatusParametro.value==""){
			mensagens(1);
			document.formulario.IdStatusParametro.focus();
			return false;
		}
		switch(document.formulario.IdTipoParametro.value){
			case '1':
				if(document.formulario.Editavel_Texto.value==""){
					mensagens(1);
					document.formulario.Editavel_Texto.focus();
					return false;
				}
				if(document.formulario.Obrigatorio_Texto.value==""){
					mensagens(1);
					document.formulario.Obrigatorio_Texto.focus();
					return false;
				}
				if(document.formulario.ParametroDemonstrativo_Texto.value==""){
					mensagens(1);
					document.formulario.ParametroDemonstrativo_Texto.focus();
					return false;
				}
				if(document.formulario.Calculavel_Texto.value==""){
					mensagens(1);
					document.formulario.Calculavel_Texto.focus();
					return false;
				}
				if(document.formulario.Visivel_Texto.value==""){
					mensagens(1);
					document.formulario.Visivel_Texto.focus();
					return false;
				}
				if(document.formulario.VisivelOS_Texto.value==""){
					mensagens(1);
					document.formulario.VisivelOS_Texto.focus();
					return false;
				}
				if(document.formulario.VisivelCDA_Texto.value==""){
					mensagens(1);
					document.formulario.VisivelCDA_Texto.focus();
					return false;
				}
				if(document.formulario.AcessoCDA_Texto.value==""){
					mensagens(1);
					document.formulario.AcessoCDA_Texto.focus();
					return false;
				}
				if(document.formulario.IdTipoTexto.value==""){
					mensagens(1);
					document.formulario.IdTipoTexto.focus();
					return false;
				}
				if((document.formulario.IdTipoTexto.value=="2" || document.formulario.IdTipoTexto.value=="3") && document.formulario.IdMascaraCampo.value == ""){
					mensagens(1);
					document.formulario.IdMascaraCampo.focus();
					return false;
				}
				if(document.formulario.VisivelCDA_Texto.value==1 && document.formulario.DescricaoParametroServicoCDA.value==""){
					mensagens(1);
					document.formulario.DescricaoParametroServicoCDA.focus();
					return false;
				}
				if(document.formulario.Calculavel_Texto.value == 1){
					if(document.formulario.RotinaCalculo.value == ''){
						mensagens(1);
						document.formulario.RotinaCalculo.focus();
						return false;
					}
				}
				break;
			case '2':
				if(document.formulario.Editavel_Selecao.value==""){
					mensagens(1);
					document.formulario.Editavel_Selecao.focus();
					return false;
				}
				if(document.formulario.Obrigatorio_Selecao.value==""){
					mensagens(1);
					document.formulario.Obrigatorio_Selecao.focus();
					return false;
				}
				if(document.formulario.ParametroDemonstrativo_Selecao.value==""){
					mensagens(1);
					document.formulario.ParametroDemonstrativo_Selecao.focus();
					return false;
				}
				if(document.formulario.Calculavel_Selecao.value==""){
					mensagens(1);
					document.formulario.Calculavel_Selecao.focus();
					return false;
				}
				if(document.formulario.CalculavelOpcoes.value==""){
					mensagens(1);
					document.formulario.CalculavelOpcoes.focus();
					return false;
				}
				if(document.formulario.Visivel_Selecao.value==""){
					mensagens(1);
					document.formulario.Visivel_Selecao.focus();
					return false;
				}
				if(document.formulario.VisivelOS_Selecao.value==""){
					mensagens(1);
					document.formulario.VisivelOS_Selecao.focus();
					return false;
				}
				if(document.formulario.VisivelCDA_Selecao.value==""){
					mensagens(1);
					document.formulario.VisivelCDA_Selecao.focus();
					return false;
				}
				if(document.formulario.AcessoCDA_Selecao.value==""){
					mensagens(1);
					document.formulario.AcessoCDA_Selecao.focus();
					return false;
				}
				if(document.formulario.VisivelCDA_Selecao.value==1 && document.formulario.DescricaoParametroServicoCDA.value==""){
					mensagens(1);
					document.formulario.DescricaoParametroServicoCDA.focus();
					return false;
				}
				if(document.formulario.CalculavelOpcoes.value != 1){
					if(document.formulario.OpcaoValor.value==""){
						mensagens(1);
						document.formulario.OpcaoValor.focus();
						return false;
					}
				}
				if(document.formulario.Calculavel_Selecao.value == 1){
					if(document.formulario.RotinaCalculo.value == ''){
						mensagens(1);
						document.formulario.RotinaCalculo.focus();
						return false;
					}
				}
				if(document.formulario.CalculavelOpcoes.value == 1){
					if(document.formulario.RotinaOpcoes.value == ''){
						mensagens(1);
						document.formulario.RotinaOpcoes.focus();
						return false;
					}
					if(document.formulario.RotinaOpcoesContrato.value == ''){
						mensagens(1);
						document.formulario.RotinaOpcoesContrato.focus();
						return false;
					}
				}
				break;
			case '3':
				break;
		}
		return true;
	}
	function listarParametro(IdServico,Erro){
		while(document.getElementById('tabelaParametro').rows.length > 2){
			document.getElementById('tabelaParametro').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdServico == '' || IdServico == undefined){
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
	    
	   	url = "xml/servico_parametro.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){		
						document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: 0";
						// Fim de Carregando
						//carregando(false);
					}else{
						var IdParametroServico,DescricaoParametroServico,ValorDefault;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoAcesso")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdTipoAcesso = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDefault")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDefault = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescEditavel")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescEditavel = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescObrigatorio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescObrigatorio = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescStatus = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaParametro').rows.length;
							linha	= document.getElementById('tabelaParametro').insertRow(tam-1);
								
							if(i%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							linha.accessKey = IdParametroServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);	
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a href='#' onClick=\"busca_servico_parametro("+IdServico+",false,'"+document.formulario.Local.value+"','"+IdParametroServico+"');\" tabindex='43'>";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdParametroServico + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoParametroServico.substr(0,30) + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + ValorDefault + linkFim;
							c2.style.cursor = "pointer";
							
							c3.innerHTML = linkIni + DescEditavel + linkFim;
							c3.style.cursor = "pointer";
							
							c4.innerHTML = linkIni + DescObrigatorio + linkFim;
							c4.style.cursor = "pointer";
							
							c5.innerHTML = linkIni + DescStatus + linkFim;
							c5.style.cursor = "pointer";
										
							c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdServico+","+IdParametroServico+",'listar')\">";
							c6.style.textAlign = "center";
							c6.style.cursor = "pointer";
						}
						document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: "+i;
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
	function mensagens2(n,Local){
		var msg='';
		var prioridade='';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		if(n == 0){
			return help(msg,prioridade);
		}
		
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
		url = "../../xml/mensagens.xml";
   		xmlhttp.open("GET", url,true);
   		xmlhttp.onreadystatechange = function(){ 
   			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					nameNode = xmlhttp.responseXML.getElementsByTagName("msg"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						msg = nameTextNode.nodeValue;
					}else{
						msg = '';
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("pri"+n)[0]; 
					if(nameNode != null){
						nameTextNode = nameNode.childNodes[0];
						prioridade = nameTextNode.nodeValue;
					}else{
						prioridade = '';
					}
					
					return help2(msg,prioridade);
				}
			}
		}
		xmlhttp.send(null);
	}
	function verificaErro2(){
		var nerro = parseInt(document.formulario.Erro.value);
		mensagens2(nerro,document.formulario.Local.value);
	}
	function help2(msg,prioridade){
		if(msg!=''){
			scrollWindow("bottom");
		}
		document.getElementById('helpText2').innerHTML = msg;
		document.getElementById('helpText2').style.display = "block";
		switch (prioridade){
			case 'atencao':
				document.getElementById('helpText2').style.color = "#C10000";
				return true;
			default:
				document.getElementById('helpText2').style.color = "#004975";
				return true;
		}
	}
	function ativarRotina(valor){
		if(valor == 1){
			document.getElementById('cpRotinaCalculo').style.display = "block";
		}else{
			document.getElementById('cpRotinaCalculo').style.display = "none";
		}
	}
	function ativarRotinaOpcoes(valor){
		if(valor == 1){
			document.getElementById('cpOpcaoValor').style.display 				= 	"none";
			document.getElementById("cpRotinaOpcoes").style.display				=	"block";
			document.getElementById("cpRotinaOpcoesContrato").style.display		=	"block";
		}else{
			document.getElementById('cpOpcaoValor').style.display 				= 	"block";
			document.getElementById("cpRotinaOpcoes").style.display				=	"none";
			document.getElementById("cpRotinaOpcoesContrato").style.display		=	"none";
		}
	}
	function verificaEditavel(campo){
		if(campo.value == ''){
			document.formulario.IdMascaraCampo.disabled		=	false;
			return false;
		}
		
		switch(campo.name){
			case 'Editavel_Texto':
				if(campo.value == 2){
					document.formulario.Obrigatorio_Texto.value				=	2;
					document.formulario.ObrigatorioStatus_Texto.value		=	2;
					document.formulario.Obrigatorio_Texto.disabled			=	true;
					
					if(document.formulario.IdTipoParametro.value == 1){
					//	document.formulario.IdMascaraCampo[0].selected		=	true;
						if(document.formulario.IdTipoTexto.value == 2 || document.formulario.IdTipoTexto.value == 3){
							document.formulario.IdMascaraCampo.disabled		=	false;
						}else{
							document.formulario.IdMascaraCampo.disabled		=	true;
						}
					}
				}else{
					document.formulario.Obrigatorio_Texto.disabled			=	false;
					
					if(document.formulario.IdTipoParametro.value == 1){
					//	document.formulario.IdMascaraCampo[0].selected		=	true;
						document.formulario.IdMascaraCampo.disabled			=	false;
					}
				}
				break;
			case 'Editavel_Selecao':
				if(campo.value == 2){
					document.formulario.Obrigatorio_Selecao.value			=	2;
					document.formulario.ObrigatorioStatus_Selecao.value		=	2;
					document.formulario.Obrigatorio_Selecao.disabled		=	true;
					
					if(document.formulario.IdTipoParametro.value == 1){
						document.formulario.IdMascaraCampo[0].selected		=	true;
						if(document.formulario.IdTipoTexto.value == 2){
							document.formulario.IdMascaraCampo.disabled		=	false;
						}else{
							document.formulario.IdMascaraCampo.disabled		=	true;
						}
					}
				}else{
					document.formulario.Obrigatorio_Selecao.disabled		=	false;
					
					if(document.formulario.IdTipoParametro.value == 1){
						document.formulario.IdMascaraCampo[0].selected		=	true;
						document.formulario.IdMascaraCampo.disabled			=	false;
					}
				}
				break;	
		}
	}
	function verificaTipoParametro(valor){
		switch(valor){
			case '1': //Caixa de Texto
				document.getElementById("tableTexto").style.display				=	"block";
				document.getElementById("tableSelecao").style.display			=	"none";
				document.getElementById("tableCampoTexto").style.display		=	"block";
				document.getElementById("cpOpcaoValor").style.display			=	"none";
				document.getElementById("cpValorInput").style.display			=	"block";
				document.getElementById("cpValorSelect").style.display			=	"none";			
			
				/*if(document.formulario.IdTipoParametro.value == 2){
					document.formulario.IdTipoTexto[2].selected 					= 	true;
				}else if(document.formulario.IdTipoParametro.value == 1){
					document.formulario.IdTipoTexto[1].selected 					= 	true;
				}*/
				
				if(document.formulario.Calculavel_Texto.value != ""){
					ativarRotina(document.formulario.Calculavel_Texto.value);
				}
				break;
			case '2': //Caixa de Seleção	
				document.getElementById("tableTexto").style.display				=	"none";
				document.getElementById("tableSelecao").style.display			=	"block";
				document.getElementById("tableCampoTexto").style.display		=	"none";
				document.getElementById("cpOpcaoValor").style.display			=	"block";
				document.getElementById("cpValorInput").style.display			=	"none";
				document.getElementById("cpValorSelect").style.display			=	"block";
				
				/*if(document.formulario.IdTipoParametro.value == 2){
					document.formulario.IdTipoTexto[2].selected 					= 	true;
				}else if(document.formulario.IdTipoParametro.value == 1){
					document.formulario.IdTipoTexto[1].selected 					= 	true;
				}*/
				
				if(document.formulario.Calculavel_Selecao.value != ""){
					ativarRotina(document.formulario.Calculavel_Selecao.value);
				}
				if(document.formulario.CalculavelOpcoes.value != ""){
					ativarRotinaOpcoes(document.formulario.CalculavelOpcoes.value);
				}
					
				break;
			default:
				document.getElementById("tableTexto").style.display				=	"block";
				document.getElementById("tableSelecao").style.display			=	"none";
				document.getElementById("tableCampoTexto").style.display		=	"none";
				document.getElementById("cpOpcaoValor").style.display			=	"none";
				document.getElementById("cpValorInput").style.display			=	"block";
				document.getElementById("cpValorSelect").style.display			=	"none";
		}
	}
	function verificaTipoParametroCDA(valor){
		switch(valor){
			case '1':
				document.getElementById("cpDescricaoCDA").style.display		=	"block";
				break;
			default:
				document.getElementById("cpDescricaoCDA").style.display		=	"none";
				
		}
	}
	function busca_opcao_valor(OpcaoValor,ValorDefault){
		if(OpcaoValor != "" ){
			while(document.formulario.ValorDefaultSelect.options.length > 0){
				document.formulario.ValorDefaultSelect.options[0] = null;
			}
			
			addOption(document.formulario.ValorDefaultSelect,"","");
			
			novo	=	"";
			valor	=	OpcaoValor.split("\n");
			for(var i=0; i<valor.length; i++){
				valor[i]	=	trim(valor[i]);
				if(valor[i] != ""){
					addOption(document.formulario.ValorDefaultSelect,valor[i],valor[i]);
					if(novo != ""){
						novo	+=	"\n";
					}
					novo	+=	valor[i];
				}
			}
			
			document.formulario.OpcaoValor.value	=	novo;
			
			if(ValorDefault!=""){
				for(i=0;i<document.formulario.ValorDefaultSelect.length;i++){
					if(document.formulario.ValorDefaultSelect[i].value == ValorDefault){
						document.formulario.ValorDefaultSelect[i].selected = true;	
					}
				}
			}else{
				document.formulario.ValorDefaultSelect[0].selected	=	true;
			}
		}
	}
	function atualizaTipoTexto(IdTipoTexto,IdMascaraCampoTemp){
		if(IdMascaraCampoTemp == undefined){
			IdMascaraCampoTemp = "";
		}
		
		switch(IdTipoTexto){
			//Texto
			case '1':
				document.getElementById("titMascara").innerHTML				=	"Máscara para Campo";
				document.getElementById("titMascara").style.color			=	"#000000";
				document.getElementById("titMascara").style.display			=	"block";
				document.getElementById("cpMascara").style.display			=	"block";
				//document.formulario.TamMinimo.value							= 	"";
				//document.formulario.TamMaximo.value							= 	"";
				document.formulario.ValorDefaultInput.readOnly				=	false;
				document.formulario.TamMinimo.readOnly						= 	false;
				document.formulario.TamMaximo.readOnly						= 	false;
				document.formulario.Editavel_Texto.disabled					=	false;
				//document.formulario.Editavel_Texto.value					=	1;
				break;
			//Senha
			case '2':
				document.getElementById("titMascara").innerHTML				=	"Exibir Senha";
				document.getElementById("titMascara").style.color			=	"#C10000";
				document.getElementById("titMascara").style.display			=	"block";
				document.getElementById("cpMascara").style.display			=	"block";
				document.formulario.TamMinimo.value							= 	"";
				document.formulario.TamMaximo.value							= 	"";
				document.formulario.ValorDefaultInput.readOnly				=	false;
				document.formulario.TamMinimo.readOnly						= 	false;
				document.formulario.TamMaximo.readOnly						= 	false;
				document.formulario.Editavel_Texto.disabled					=	false;
				break;
			//Grupo Radius
			case '3':
				document.getElementById("titMascara").innerHTML				=	"Grupo Radius";
				document.getElementById("titMascara").style.color			=	"#C10000";
				document.getElementById("titMascara").style.display			=	"block";
				document.getElementById("cpMascara").style.display			=	"block";
				document.formulario.ValorDefaultInput.readOnly				=	true;
				document.formulario.TamMinimo.readOnly						= 	true;
				document.formulario.TamMaximo.readOnly						= 	true;
				document.formulario.TamMinimo.value							= 	"";
				document.formulario.TamMaximo.value							= 	"";
				//document.formulario.Editavel_Texto.value					=	2;
				document.formulario.Editavel_Texto.disabled					= 	true;
//				document.formulario.IdMascaraCampo.disabled					=	false;		
				break;
			//IPv4
			case '4':
				document.formulario.TamMinimo.readOnly						= 	true;
				document.formulario.TamMaximo.readOnly						= 	true;
				document.formulario.TamMinimo.value							= 	"";
				document.formulario.TamMaximo.value							= 	"15";	
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";	
				document.formulario.Editavel_Texto.disabled					=	false;
				break;
			//IPv6
			case '5':
				document.formulario.TamMinimo.readOnly						= 	true;
				document.formulario.TamMaximo.readOnly						= 	true;
				document.formulario.TamMinimo.value							= 	"";
				document.formulario.TamMaximo.value							= 	"39";	
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";	
				document.formulario.Editavel_Texto.disabled					=	false;
				break;
			//Asterisk - Terminal
			case '6':
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";
				document.formulario.TamMinimo.value							= 	"";
				document.formulario.TamMaximo.value							= 	"";
				document.formulario.ValorDefaultInput.readOnly				=	false;
				document.formulario.TamMinimo.readOnly						= 	false;
				document.formulario.TamMaximo.readOnly						= 	false;
				document.formulario.Editavel_Texto.disabled					=	false;
				break;
			default:
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";
				//document.formulario.TamMinimo.value						= 	"";
				//document.formulario.TamMaximo.value						= 	"";
				document.formulario.ValorDefaultInput.readOnly				=	false;
				document.formulario.TamMinimo.readOnly						= 	false;
				document.formulario.TamMaximo.readOnly						= 	false;
				document.formulario.Editavel_Texto.disabled					=	false;
		}
		
		if(IdTipoTexto == 3){
			busca_grupo_acesso_radius(IdMascaraCampoTemp);
		} else{
			adicionaOpcaoCampo(IdTipoTexto,IdMascaraCampoTemp);
		}
		//chama_mascara(IdMascaraCampoTemp);
		
		switch(document.formulario.IdTipoParametro.value){
			case '1':
				verificaEditavel(document.formulario.Editavel_Texto);
				break;
			case '2':
				verificaEditavel(document.formulario.Editavel_Selecao);
				break;
		}
	}
	function adicionaOpcaoCampo(IdTipoTexto,IdMascaraCampoTemp){
		switch(IdTipoTexto){
			//Texto
			case '1':
				IdGrupoParametroSistema	=	'75';
				break;
			//Senha
			case '2':
				IdGrupoParametroSistema = 	'123';
				break;
			default:
				while(document.formulario.IdMascaraCampo.options.length > 0){
					document.formulario.IdMascaraCampo.options[0] = null;
				}
				addOption(document.formulario.IdMascaraCampo,"","");
				return false;
		}
		
		if(IdMascaraCampoTemp == undefined){
			IdMascaraCampoTemp = "";
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
	    
	   	url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						while(document.formulario.IdMascaraCampo.options.length > 0){
							document.formulario.IdMascaraCampo.options[0] = null;
						}
						
						addOption(document.formulario.IdMascaraCampo,"","");
						
						// Fim de Carregando
						carregando(false);
					}else{
						while(document.formulario.IdMascaraCampo.options.length > 0){
							document.formulario.IdMascaraCampo.options[0] = null;
						}
						
						addOption(document.formulario.IdMascaraCampo,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroSistema").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroSistema = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorParametroSistema = nameTextNode.nodeValue;	
							
							
							addOption(document.formulario.IdMascaraCampo,ValorParametroSistema,IdParametroSistema);
						}
						
						if(IdMascaraCampoTemp == ""){
							document.formulario.IdMascaraCampo[0].selected	=	true;
						}else{
							for(i=0;i<document.formulario.IdMascaraCampo.length;i++){
								if(document.formulario.IdMascaraCampo[i].value == IdMascaraCampoTemp){
									document.formulario.IdMascaraCampo[i].selected	=	true;
									i	=	document.formulario.IdMascaraCampo[i].length;
								}
							}
						}
					}
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function atualizaCampo(campo){
		document.formulario.TamMaximo.readOnly = false;
		document.formulario.TamMinimo.readOnly = false;
		document.formulario.TamMaximo.setAttribute("onblur","Foco(this,'out')");
		document.formulario.TamMaximo.setAttribute("onfocus","Foco(this,'in')");
		document.formulario.TamMinimo.setAttribute("onblur","Foco(this,'out')");
		document.formulario.TamMinimo.setAttribute("onfocus","Foco(this,'in')");
		if(campo.value != undefined){
			switch(campo.value){
				case '1': //Data
					document.formulario.ValorDefaultInput.maxLength	=	10;
					document.formulario.TamMaximo.readOnly = true;
					document.formulario.TamMinimo.readOnly = true;
					document.formulario.TamMaximo.value = '';
					document.formulario.TamMinimo.value = '';
					document.formulario.TamMaximo.setAttribute("onblur","");
					document.formulario.TamMaximo.setAttribute("onfocus","");
					document.formulario.TamMinimo.setAttribute("onfocus","");
					document.formulario.TamMinimo.setAttribute("onblur","");
					break;
				case '2': //Inteiro
					document.formulario.ValorDefaultInput.maxLength	=	11;
					break;
				case '3': //Real
					document.formulario.ValorDefaultInput.maxLength	=	12;
					break;
				case '4': //Usuário
					document.formulario.ValorDefaultInput.maxLength	=	255;
					break;
				case '5': //MAC
					document.formulario.ValorDefaultInput.maxLength	=	17;
					document.formulario.TamMaximo.readOnly = true;
					document.formulario.TamMinimo.readOnly = true;
					document.formulario.TamMaximo.value = '';
					document.formulario.TamMinimo.value = '';
					document.formulario.TamMaximo.setAttribute("onblur","");
					document.formulario.TamMaximo.setAttribute("onfocus","");
					document.formulario.TamMinimo.setAttribute("onfocus","");
					document.formulario.TamMinimo.setAttribute("onblur","");
					break;
				default:
					document.formulario.ValorDefaultInput.maxLength	=	255;
			}
			
			if(document.formulario.IdTipoTexto.value == 3){
				document.formulario.ValorDefaultInput.value	= campo.value;
			}
		} else if(campo != ""){
			switch(campo){
				case '1': //Data
					document.formulario.ValorDefaultInput.maxLength	=	10;
					document.formulario.TamMaximo.readOnly = true;
					document.formulario.TamMinimo.readOnly = true;
					document.formulario.TamMaximo.value = '';
					document.formulario.TamMinimo.value = '';
					document.formulario.TamMaximo.setAttribute("onblur","");
					document.formulario.TamMinimo.setAttribute("onfocus","");
					break;
				case '2': //Inteiro
					document.formulario.ValorDefaultInput.maxLength	=	11;
					break;
				case '3': //Real
					document.formulario.ValorDefaultInput.maxLength	=	12;
					break;
				case '4': //Usuário
					document.formulario.ValorDefaultInput.maxLength	=	255;
					break;
				case '5': //MAC
					document.formulario.ValorDefaultInput.maxLength	=	17;
					break;
				default:
					document.formulario.ValorDefaultInput.maxLength	=	255;
			}
			
			if(document.formulario.IdTipoTexto.value == 3){
				document.formulario.ValorDefaultInput.value	= campo;
			}
		}
	}
	function chama_mascara(campo,event){
		if(document.formulario.IdTipoTexto.value == 2){
			return false;
		}else{
			switch(document.formulario.IdMascaraCampo.value){
				case '1': //Data
					return mascara(campo,event,'date');
					break;
				case '2': //Inteiro
					return mascara(campo,event,'int');
					break;
				case '3': //Real
					return mascara(campo,event,'float');
					break;
				case '4': //MAC
					return mascara(campo,event,'usuario');
					break;
				case '5': //MAC
					return mascara(campo,event,'mac');
					break;
				default:
					return mascara(campo,event,'charVal');
					return false;
			}
		}
	}
	function busca_rotina_opcoes(Rotina,ValorDefault){
		if(Rotina == '' || Rotina == undefined){
			Rotina = '';
			
			while(document.formulario.ValorDefaultSelect.options.length > 0){
				document.formulario.ValorDefaultSelect.options[0] = null;
			}
			
			addOption(document.formulario.ValorDefaultSelect,"","");
			
			return false;
		}
		
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
		url = "xml/servico_parametro_opcoes.php?Rotina="+Rotina;
   		xmlhttp.open("GET", url,true);
   		xmlhttp.onreadystatechange = function(){ 
   			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var Valor, novo	=	"";
						
						while(document.formulario.ValorDefaultSelect.options.length > 0){
							document.formulario.ValorDefaultSelect.options[0] = null;
						}
			
						addOption(document.formulario.ValorDefaultSelect,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Valor").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							
							addOption(document.formulario.ValorDefaultSelect,Valor,Valor);
							
							if(novo != ""){
								novo	+=	"\n";
							}
							novo	+=	Valor;
						}
						document.formulario.OpcaoValor.value	=	novo;
							
						if(ValorDefault!=""){
							for(i=0;i<document.formulario.ValorDefaultSelect.length;i++){
								if(document.formulario.ValorDefaultSelect[i].value == ValorDefault){
									document.formulario.ValorDefaultSelect[i].selected = true;	
								}
							}
						}else{
							document.formulario.ValorDefaultSelect[0].selected	=	true;
						}
					}
				}
			}
		}
		xmlhttp.send(null);
	}
	function busca_grupo_acesso_radius(GroupNameTemp){
		if(GroupNameTemp == undefined){
			GroupNameTemp = '';
		}
		
	   	var url = "xml/usuario_grupo_acesso_radius.php"; 
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == "false"){
				while(document.formulario.IdMascaraCampo.options.length > 0){
					document.formulario.IdMascaraCampo.options[0] = null;
				}
				
				addOption(document.formulario.IdMascaraCampo,"","");
			} else{
				while(document.formulario.IdMascaraCampo.options.length > 0){
					document.formulario.IdMascaraCampo.options[0] = null;
				}
				
				addOption(document.formulario.IdMascaraCampo,"","");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("GroupName").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("GroupName")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var GroupName = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdMascaraCampo, GroupName, GroupName);
				}
				
				if(GroupNameTemp != ''){
					for(i = 0; i < document.formulario.IdMascaraCampo.length; i++){
						if(document.formulario.IdMascaraCampo[i].value == GroupNameTemp){
							document.formulario.IdMascaraCampo[i].selected = true;
							break;
						}
					}
				} else{
					document.formulario.IdMascaraCampo[0].selected = true;
				}
			}
		});					
	}
	function busca_grupo_usuario(IdGrupoUsuario){
		if(IdGrupoUsuario == '' || IdGrupoUsuario == undefined) {
			IdGrupoUsuario = 0;
		}
		
	   	var url = "xml/grupo_usuario.php?IdGrupoUsuario="+IdGrupoUsuario;
		
		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false') {
				document.formulario.IdGrupoUsuarioInput.value			= '';
				document.formulario.IdGrupoUsuarioSelect.value			= '';
				document.formulario.DescricaoGrupoUsuarioInput.value 	= '';
				document.formulario.DescricaoGrupoUsuarioSelect.value 	= '';
			} else {
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdGrupoUsuario = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoGrupoUsuario = nameTextNode.nodeValue;
				
				document.formulario.IdGrupoUsuarioInput.value			= IdGrupoUsuario;
				document.formulario.IdGrupoUsuarioSelect.value			= IdGrupoUsuario;
				document.formulario.DescricaoGrupoUsuarioInput.value 	= DescricaoGrupoUsuario;
				document.formulario.DescricaoGrupoUsuarioSelect.value 	= DescricaoGrupoUsuario;
			}
			
			if(document.getElementById("quadroBuscaGrupoUsuario") != null) {
				if(document.getElementById("quadroBuscaGrupoUsuario").style.display	==	"block") {
					document.getElementById("quadroBuscaGrupoUsuario").style.display = "none";
				}
			}
			
			verificaAcao();
		});
	}
	function busca_grupos_usuario(IdGrupoUsuario){
		document.formulario.IdGrupoUsuarioInput.value = "";
		document.formulario.DescricaoGrupoUsuarioInput.value = "";
		document.formulario.IdGruposUsuario.value = '';
		document.getElementById("totaltabelaGrupoUsuario").innerHTML = "Total: 0";
		
		while(document.getElementById('tabelaGrupoUsuario').rows.length > 2) {
			document.getElementById('tabelaGrupoUsuario').deleteRow(1);
		}
		
		if(IdGrupoUsuario != '' && IdGrupoUsuario != undefined) {
			IdGrupoUsuario = IdGrupoUsuario.split(',');
			
			for(var i = 0; i < IdGrupoUsuario.length; i++) {
				add_grupo_usuario(IdGrupoUsuario[i]);
			}
		}
	}
	function add_grupo_usuario(AddIdGrupoUsuario, ListarCampo) {
		if(AddIdGrupoUsuario != "" && AddIdGrupoUsuario != undefined) {
		    var url = "xml/grupo_usuario.php?IdGrupoUsuario="+AddIdGrupoUsuario;
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != "false") {
					var cont = 0, i = 0;
					
					if(ListarCampo == '' || ListarCampo == undefined) {
						if(document.formulario.IdGruposUsuario.value == '') {
							document.formulario.IdGruposUsuario.value = AddIdGrupoUsuario;
						} else {
							var temp = document.formulario.IdGruposUsuario.value.split(',');
							
							while(temp[i] != undefined) {
								if(temp[i] != AddIdGrupoUsuario) {
									cont++;
								}
								
								i++;
							}
							
							if(i == cont) {
								document.formulario.IdGruposUsuario.value = (document.formulario.IdGruposUsuario.value + "," + AddIdGrupoUsuario).replace(/\d,^/i, '');
							}
						}
					}
					
					if(i == cont) {
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdGrupoUsuario = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoUsuario")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoGrupoUsuario = nameTextNode.nodeValue, tam, linha, c0, c1, c2;
						
						tam 	= document.getElementById('tabelaGrupoUsuario').rows.length;
						linha	= document.getElementById('tabelaGrupoUsuario').insertRow(tam-1);
						
						if(tam%2 != 0) {
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey = IdGrupoUsuario; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);	
						c2	= linha.insertCell(2);
						
						c0.innerHTML = IdGrupoUsuario;
						c0.style.padding = "0 0 0 5px";
						
						c1.innerHTML = DescricaoGrupoUsuario;
						
						c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_grupo_usuario("+IdGrupoUsuario+")\"></tr>";
						c2.style.textAlign = "center";
						c2.style.cursor = "pointer";
						
						document.getElementById("totaltabelaGrupoUsuario").innerHTML = "Total: "+(i+1);
					}
				}
				
				document.formulario.IdGrupoUsuarioInput.value = "";
				document.formulario.DescricaoGrupoUsuarioInput.value = "";
				document.formulario.IdGrupoUsuarioInput.focus();
			});
		} else {
			document.formulario.IdGrupoUsuarioInput.value = "";
			document.formulario.DescricaoGrupoUsuarioInput.value = "";
			document.formulario.IdGrupoUsuarioInput.focus();
		}
	}
	function remover_grupo_usuario(DelIdGrupoUsuario) {
		var tam = document.getElementById('tabelaGrupoUsuario').rows.length;
		
		for(var i = 0; i < tam; i++) {
			if(DelIdGrupoUsuario == document.getElementById('tabelaGrupoUsuario').rows[i].accessKey) {
				document.getElementById('tabelaGrupoUsuario').deleteRow(i);
				tableMultColor('tabelaGrupoUsuario');
				
				document.formulario.IdGruposUsuario.value = document.formulario.IdGruposUsuario.value.replace(new RegExp(DelIdGrupoUsuario+"|"+DelIdGrupoUsuario+",|,"+DelIdGrupoUsuario), '');
				document.getElementById('totaltabelaGrupoUsuario').innerHTML = "Total: "+(tam-3);
				break;
			}
		}
	}
	function filtro_buscar_servico(IdServico){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		
	    var url = "xml/servico.php?IdServico="+IdServico+"&IdStatus=1&IdTipoServico=1";
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				
				if(document.filtro.IdServico != undefined) {
					document.filtro.IdServico.value = "";
				}
			}
		});
	}