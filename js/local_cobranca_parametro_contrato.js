	function inicia(){
		status_inicial();
		document.formulario.IdLocalCobranca.focus();
	}
	function excluir(IdLocalCobranca,IdLocalCobrancaParametroContrato,listar){
		if(IdLocalCobranca== '' || IdLocalCobranca==undefined){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
		}
		if(IdLocalCobrancaParametroContrato== '' || IdLocalCobrancaParametroContrato==undefined){
			IdLocalCobrancaParametroContrato = document.formulario.IdLocalCobrancaParametroContrato.value;
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
    
   			url = "files/excluir/excluir_local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca+"&IdLocalCobrancaParametroContrato="+IdLocalCobrancaParametroContrato;
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
									if(document.formulario.IdLocalCobrancaParametroContrato.value == IdLocalCobrancaParametroContrato){
										document.formulario.IdLocalCobrancaParametroContrato.value 		= '';		
										document.formulario.DescricaoParametroContrato.value = '';
										document.formulario.Editavel.value 					= '';
										document.formulario.Obrigatorio.value				= '';
										document.formulario.ValorDefaultInput.value			= '';
										document.formulario.OpcaoValor.value				= '';
										document.formulario.Visivel.value					= '';
										document.formulario.VisivelOS.value					= '';
										document.formulario.Obs.value						= '';
										document.formulario.IdStatusParametro.value			= '';
										document.formulario.IdTipoParametro.value			= '';
										document.formulario.IdMascaraCampo.value			= '';
										document.formulario.DataCriacao.value				= '';
										document.formulario.LoginCriacao.value				= '';
										document.formulario.DataAlteracao.value				= '';
										document.formulario.LoginAlteracao.value			= '';
										document.formulario.Obrigatorio.disabled			=	false;
										document.formulario.IdMascaraCampo.disabled			=	false;
										document.formulario.Acao.value						= 'inserir';
										
										while(document.formulario.ValorDefaultSelect.options.length > 0){
											document.formulario.ValorDefaultSelect.options[0] = null;
										}
								
										status_inicial();
										verificaAcao();
										verificaTipoParametro();
										
										document.formulario.IdLocalCobrancaParametroContrato.focus();
									}
									
									for(var i=0; i<document.getElementById('tabelaParametro').rows.length; i++){
										if(IdLocalCobrancaParametroContrato == document.getElementById('tabelaParametro').rows[i].accessKey){
											document.getElementById('tabelaParametro').deleteRow(i);
											tableMultColor('tabelaParametro',document.filtro.corRegRand.value);
											document.getElementById('tabelaParametroTotal').innerHTML			=	"Total: "+(document.getElementById('tabelaParametro').rows.length - 2);
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
									url = 'cadastro_local_cobranca_parametro_contrato.php?Erro='+document.formulario.Erro.value+'&IdLocalCobranca='+IdLocalCobranca;
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
									if(IdLocalCobranca+"_"+IdLocalCobrancaParametroContrato == document.getElementById('tableListar').rows[i].accessKey){
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
		if(document.formulario.DescricaoParametroContrato.value==""){
			mensagens(1);
			document.formulario.DescricaoParametroContrato.focus();
			return false;
		}
		if(document.formulario.IdTipoParametro.value==""){
			mensagens(1);
			document.formulario.IdTipoParametro.focus();
			return false;
		}else{
			if(document.formulario.IdTipoParametro.value=="2"){
				if(document.formulario.OpcaoValor.value==""){
					mensagens(1);
					document.formulario.OpcaoValor.focus();
					return false;
				}
			}
		}
		if(document.formulario.Editavel.value==""){
			mensagens(1);
			document.formulario.Editavel.focus();
			return false;
		}
		if(document.formulario.Obrigatorio.value==""){
			mensagens(1);
			document.formulario.Obrigatorio.focus();
			return false;
		}
		if(document.formulario.ParametroDemonstrativo.value==""){
			mensagens(1);
			document.formulario.ParametroDemonstrativo.focus();
			return false;
		}
		if(document.formulario.Calculavel.value==""){
			mensagens(1);
			document.formulario.Calculavel.focus();
			return false;
		}
		if(document.formulario.Visivel.value==""){
			mensagens(1);
			document.formulario.Visivel.focus();
			return false;
		}
		if(document.formulario.VisivelOS.value==""){
			mensagens(1);
			document.formulario.VisivelOS.focus();
			return false;
		}
		if(document.formulario.IdStatusParametro.value==""){
			mensagens(1);
			document.formulario.IdStatusParametro.focus();
			return false;
		}
		if(document.formulario.IdTipoParametro.value == 1){
			if(document.formulario.Editavel.value == 1 && document.formulario.IdMascaraCampo.value==""){
				mensagens(1);
				document.formulario.IdMascaraCampo.focus();
				return false;
			}
		}
		if(document.formulario.Calculavel.value == 1){
			if(document.formulario.RotinaCalculo.value == ''){
				mensagens(1);
				document.formulario.RotinaCalculo.focus();
				return false;
			}
		}
		return true;
	}
	function listarParametro(IdLocalCobranca,Erro){
		while(document.getElementById('tabelaParametro').rows.length > 2){
			document.getElementById('tabelaParametro').deleteRow(1);
		}		
		
		var tam, linha, c0, c1, c2, c3, c4, c5;
		
		if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
			IdLocalCobranca = 0;
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
	    
	   	url = "xml/local_cobranca_parametro_contrato.php?IdLocalCobranca="+IdLocalCobranca;
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
						var IdLocalCobrancaParametroContrato,DescricaoParametroServico,ValorDefault;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobrancaParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobrancaParametroContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroContrato = nameTextNode.nodeValue;
							
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
							
							linha.accessKey = IdLocalCobrancaParametroContrato; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);	
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							
							var linkIni = "<a href='#' onClick=\"busca_local_cobranca_parametro_contrato("+IdLocalCobranca+",false,'"+document.formulario.Local.value+"','"+IdLocalCobrancaParametroContrato+"');\">";
							var linkFim = "</a>";
							
							c0.innerHTML = linkIni + IdLocalCobrancaParametroContrato + linkFim;
							c0.style.cursor  = "pointer";
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + DescricaoParametroContrato.substr(0,30) + linkFim;
							c1.style.cursor = "pointer";
							
							c2.innerHTML = linkIni + ValorDefault + linkFim;
							c2.style.cursor = "pointer";
							
							c3.innerHTML = linkIni + DescEditavel + linkFim;
							c3.style.cursor = "pointer";
							
							c4.innerHTML = linkIni + DescObrigatorio + linkFim;
							c4.style.cursor = "pointer";
							
							c5.innerHTML = linkIni + DescStatus + linkFim;
							c5.style.cursor = "pointer";
										
							c6.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir("+IdLocalCobranca+","+IdLocalCobrancaParametroContrato+",'listar')\">";
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
		if(valor == ''){
			return false;
		}
		if(valor == 1){
			document.getElementById('cpRotinaCalculo').style.display = "block";
		}else{
			document.getElementById('cpRotinaCalculo').style.display = "none";
		}
	}
	function verificaEditavel(valor){
		if(valor == ''){
			return false;
		}
		if(valor == 2){
			document.formulario.Obrigatorio.value			=	2;
			document.formulario.ObrigatorioStatus.value		=	2;
			document.formulario.Obrigatorio.disabled		=	true;
			
			if(document.formulario.IdTipoParametro.value == 1){
				document.formulario.IdMascaraCampo.disabled				=	true;
				document.formulario.IdMascaraCampo[0].selected			=	true;
				document.getElementById('titMascaraCor').style.color	=	'#000';
			}
		}else{
			document.formulario.Obrigatorio.disabled		=	false;
			
			if(document.formulario.IdTipoParametro.value == 1){
				document.formulario.IdMascaraCampo.disabled				=	false;
				document.formulario.IdMascaraCampo[0].selected			=	false;
				document.getElementById('titMascaraCor').style.color	=	'#C10000';
			}
		}	
	}
	function verificaTipoParametro(valor){
		switch(valor){
			case '1': //Caixa de Texto
				document.getElementById("titMascara").style.display			=	"block";
				document.getElementById("cpMascara").style.display			=	"block";
				document.formulario.IdStatusParametro.style.width			=	"95px";
				document.formulario.Visivel.style.width						=	"70px";
				document.formulario.VisivelOS.style.width					=	"70px";
				document.formulario.Calculavel.style.width					=	"70px";
				document.formulario.Obrigatorio.style.width					=	"70px";
				document.formulario.Editavel.style.width					=	"70px";
				document.formulario.ParametroDemonstrativo.style.width		=	"110px";
				document.getElementById("cpOpcaoValor").style.display		=	"none";	
				document.getElementById("cpValorInput").style.display		=	"block";	
				document.getElementById("cpValorSelect").style.display		=	"none";	
				break;
			case '2': //Caixa de Seleção
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";
				document.formulario.IdStatusParametro.style.width			=	"122px";
				document.formulario.Visivel.style.width						=	"105px";
				document.formulario.VisivelOS.style.width					=	"105px";
				document.formulario.Calculavel.style.width					=	"105px";
				document.formulario.Obrigatorio.style.width					=	"105px";
				document.formulario.Editavel.style.width					=	"105px";
				document.formulario.ParametroDemonstrativo.style.width		=	"110px";
				document.getElementById("cpOpcaoValor").style.display		=	"block";
				document.getElementById("cpValorInput").style.display		=	"none";	
				document.getElementById("cpValorSelect").style.display		=	"block";			
				break;
			default:
				document.getElementById("titMascara").style.display			=	"none";
				document.getElementById("cpMascara").style.display			=	"none";
				document.formulario.IdStatusParametro.style.width			=	"130px";
				document.formulario.Visivel.style.width						=	"100px";
				document.formulario.VisivelOS.style.width					=	"100px";
				document.formulario.Calculavel.style.width					=	"100px";
				document.formulario.Obrigatorio.style.width					=	"100px";
				document.formulario.Editavel.style.width					=	"100px";
				document.formulario.ParametroDemonstrativo.style.width		=	"127px";
				document.getElementById("cpOpcaoValor").style.display		=	"none";	
				document.getElementById("cpValorInput").style.display		=	"block";	
				document.getElementById("cpValorSelect").style.display		=	"none";		
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
	function atualizaCampo(campo){
		switch(campo.value){
			case '1': //Data
				document.formulario.ValorDefaultInput.maxLength	=	10;
				break;
			case '2': //Inteiro
				document.formulario.ValorDefaultInput.maxLength	=	11;
				break;
			case '3': //Real
				document.formulario.ValorDefaultInput.maxLength	=	12;
				break;
			case '5': //MAC
				document.formulario.ValorDefaultInput.maxLength	=	17;
				break;
			default:
				document.formulario.ValorDefaultInput.maxLength	=	255;
		}
		document.formulario.ValorDefaultInput.value	=	"";
	}
	function chama_mascara(campo,event){
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
			case '5': //MAC
				return mascara(campo,event,'mac');
				break;
			default:
				return false;
		}
	}
