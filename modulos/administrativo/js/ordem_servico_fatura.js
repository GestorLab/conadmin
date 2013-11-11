	function validar(){
		if(validar_parcial() == true){
			valorT	=	document.formulario.ValorTotal.value;
			valorT	=	new String(valorT);
			valorT	=	valorT.replace('.','');
			valorT	=	valorT.replace('.','');
			valorT	=	valorT.replace(',','.');
			
			if(parseFloat(valorT) > 0){
			
				var temp=0,posInicial=0,posFinal=0,valor,desp,total,perc,valorTotal=0,valorDesp=0,valorPerc=0,totalTotal=0,data;
				for(i = 0; i<document.formulario.length; i++){
					if(document.formulario[i].name != undefined){
						if(document.formulario[i].name.substring(0,4) == 'parc'){
							if(posInicial == 0){
								posInicial = i;
							}
							posFinal = i;
						}
					}
				}
				if(posInicial != 0){
					if(document.formulario.FormaCobranca.value == 2){
						for(i = posInicial; i<=posFinal; i=i+5){
							valor	=	document.formulario[i].value;
							valor	=	new String(valor);
							valor	=	valor.replace('.','');
							valor	=	valor.replace('.','');
							valor	=	valor.replace(',','.');
							
							data	=	document.formulario[i+4].value
							
							if(data == ""){
								mensagens(1);
								document.formulario[i+4].focus();
								return false;
							}else{
								if(isData(data) == false){
									mensagens(27);
									document.formulario[i+4].focus();
									return false;
								}else{
									if(formatDate(data) < formatDate(document.formulario.DataPrimeiroVencimentoIndividual.value)){
										mensagens(70);
										document.formulario[i+4].focus();	
										return false;
									}
								}
							}
							
							valorTotal	+=	parseFloat(valor);
							
							valor	=	Arredonda(valor,2);
							valor	=	formata_float(valor,2);
							valor	=	new String(valor);
							valor	=	valor.replace('.',',');
						}
						
						valorTotal	=	Arredonda(valorTotal,2);
						
						temp	=	document.formulario.ValorTotal.value;
						
						temp	=	new String(temp);
						temp	=	temp.replace('.','');
						temp	=	temp.replace('.','');
						temp	=	temp.replace(',','.');
						
						if(valorTotal != temp){
							mensagens(164);
							document.formulario[posInicial].focus();	
							return false;
						}
					}else{
						for(i = posInicial; i<posFinal; i=i+4){
							valor	=	document.formulario[i].value;
							valor	=	new String(valor);
							valor	=	valor.replace('.','');
							valor	=	valor.replace('.','');
							valor	=	valor.replace(',','.');
							
							data	=	document.formulario[i+3].value
							
							if(data == ""){
								mensagens(1);
								document.formulario[i+3].focus();
								return false;
							}else{
								if(isMes(data) == false){
									mensagens(27);
									document.formulario[i+3].focus();
									return false;
								}else{
									dataTemp	=	'01/'+data;
									dataTemp2	=	'01/'+document.formulario.DataPrimeiroVencimentoContrato.value;
									
									if(formatDate(dataTemp) < formatDate(dataTemp2)){
										mensagens(70);
										document.formulario[i+4].focus();	
										return false;
									}
								}
							}
							
							valorTotal	+=	parseFloat(valor);
							
							valor	=	Arredonda(valor,2);
							valor	=	formata_float(valor,2);
							valor	=	new String(valor);
							valor	=	valor.replace('.',',');
						}
						
						valorTotal	=	Arredonda(valorTotal,2);
						
						temp	=	document.formulario.ValorTotal.value;
						
						temp	=	new String(temp);
						temp	=	temp.replace('.','');
						temp	=	temp.replace('.','');
						temp	=	temp.replace(',','.');
						
						if(valorTotal != temp){
							mensagens(164);
							document.formulario[posInicial].focus();	
							return false;
						}
					}
				}else{
					
					if(document.formulario.IdContaDebitoCartao.value == "" && document.formulario.ObrigatoriedadeContaCartao.value == 1){
						mensagens(1);
						document.formulario.IdContaDebitoCartao.focus();
						return false;
					}
					
					mensagens(72);
					document.formulario.bt_simular.focus();	
					return false;
				}
				
				if(document.formulario.IdContaDebitoCartao.value == "" && document.formulario.ObrigatoriedadeContaCartao.value == 1){
					mensagens(1);
					document.formulario.IdContaDebitoCartao.focus();
					return false;
				}
			}
			return true;
		}
	}
	
	function atualiza_tipo_servico(IdTipoOrdemServico,IdSubTipoOrdemServico){
		switch(IdTipoOrdemServico){
			case '2':
				document.getElementById('cp_dadosCliente').style.display		=	'none';
				document.getElementById('cpDadosServico').style.display			=	'none';
				document.getElementById('cp_dadosContrato').style.display		=	'none';
				break;
			default:
				document.getElementById('cp_dadosCliente').style.display		=	'block';
				document.getElementById('cpDadosServico').style.display			=	'block';
				document.getElementById('cp_dadosContrato').style.display		=	'block';
		}
	
		busca_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServico);
	}
	
	function busca_subtipo_ordem_servico(IdTipoOrdemServico,IdSubTipoOrdemServicoTemp){
		if(IdTipoOrdemServico == undefined || IdTipoOrdemServico==''){
			IdTipoOrdemServico = 0;
		}
		if(IdSubTipoOrdemServicoTemp == undefined){
			IdSubTipoOrdemServicoTemp = '';
		}
		var xmlhttp = false;
		var Local = document.formulario.Local.value;
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
	    
	    url = "xml/subtipo_ordem_servico.php?IdTipoOrdemServico="+IdTipoOrdemServico;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.formulario.IdSubTipoOrdemServico.options.length > 0){
							document.formulario.IdSubTipoOrdemServico.options[0] = null;
						}
						
						var nameNode, nameTextNode, IdSubTipoOrdemServico;					
						
						addOption(document.formulario.IdSubTipoOrdemServico,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdSubTipoOrdemServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubTipoOrdemServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoSubTipoOrdemServico = nameTextNode.nodeValue;
						
							addOption(document.formulario.IdSubTipoOrdemServico,DescricaoSubTipoOrdemServico,IdSubTipoOrdemServico);
						}
						
						if(IdSubTipoOrdemServicoTemp!=""){
							for(i=0;i<document.formulario.IdSubTipoOrdemServico.length;i++){
								if(document.formulario.IdSubTipoOrdemServico[i].value == IdSubTipoOrdemServicoTemp){
									document.formulario.IdSubTipoOrdemServico[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.IdSubTipoOrdemServico[0].selected	=	true;
						}						
					}else{
						while(document.formulario.IdSubTipoOrdemServico.options.length > 0){
							document.formulario.IdSubTipoOrdemServico.options[0] = null;
						}
					}
//					if(Local != "OrdemServicoFatura"){
						// Fim de Carregando
						carregando(false);
//					}
				}
			}
		}
		xmlhttp.send(null);	
	}
	
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFF';
			document.getElementById(id).style.color='#C10000';
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
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function verificaAcao(){		
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_enviar.disabled				= true;			
				document.formulario.bt_alterar.disabled 			= true;
				document.formulario.bt_confirmar.disabled		 	= true;
				document.formulario.bt_cancelar.disabled 			= true;
				document.formulario.bt_gerar.style.display			= "none";	
				document.formulario.bt_imprimirCarne.style.display	= "none";
			}else{
				if(document.formulario.IdStatus.value < 500){
					if(document.formulario.simulado.value  ==  1){									
						document.formulario.bt_confirmar.disabled 	= false;
					}else{						
						document.formulario.bt_confirmar.disabled 	= true;
					}			
				
					//document.formulario.bt_enviar.disabled				= true;			
					document.formulario.bt_alterar.disabled 			= false;
					document.formulario.bt_cancelar.disabled 			= true;
					document.formulario.bt_gerar.style.display			= "none";	
					document.formulario.bt_imprimirCarne.style.display	= "none";
					
					if(document.formulario.Faturado.value == 1){						
						document.formulario.bt_simular.disabled		= true;		
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_confirmar.disabled 	= true;
	
						if(document.formulario.FormaCobranca.value == 2){
						//	document.formulario.bt_enviar.disabled		= false;
							
							if(document.formulario.IdFormatoCarne.value == 1){
								document.formulario.bt_imprimirCarne.style.display	= "block";
							} else if(document.formulario.IdFormatoCarne.value == 2){	
								document.formulario.bt_gerar.style.display			= "block";
							}	
						}	
					}
				} else{
					document.formulario.bt_gerar.style.display			= "none";	
					document.formulario.bt_imprimirCarne.style.display	= "none";
				}
				
				if(document.formulario.FormaCobranca.value == 1){
					document.formulario.bt_enviar.disabled = true;
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
						addOption(campo,"","0");
							
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("Login").length; i++){
								
							nameNode = xmlhttp.responseXML.getElementsByTagName("Login")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Login = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeUsuario = nameTextNode.nodeValue;
							
							var Descricao	=	"[" + Login + "] " + NomeUsuario;	
							
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
	function busca_status(IdStatusTemp){
		if(IdStatusTemp == undefined){
			IdStatusTemp = 0;
		}
		var xmlhttp   = false;
		var Local	  = document.formulario.Local.value;	
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
		ocultar_campos(document.formulario.IdOrdemServico.value);
		url = "xml/ordem_servico_status.php?IdStatus="+IdStatusTemp+"&IdOrdemServico="+document.formulario.IdOrdemServico.value;
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
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Parcela")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Parcela = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Cor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Cor = nameTextNode.nodeValue;
						
						if(document.formulario.Faturado.value == 1 && Parcela != ''){
							ValorParametroSistema += "<br /><span style='font-size:9px;'>" + Parcela + "</span>";
						}
					
						document.getElementById('cp_Status').style.display		=	"block";		
						document.getElementById('cp_Status').style.color		=	Cor;		
						document.getElementById('cp_Status').innerHTML			=	ValorParametroSistema;
						document.getElementById('cp_Status').style.lineHeight	=	"10px";
					}
				}
//				if(Local!='OrdemServicoFatura'){
					// Fim de Carregando
					carregando(false);
//				}
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function cadastrar(acao){
		document.formulario.Acao.value = acao;	
		if(acao == 'cancelar' || acao=='imprimir'){
			document.formulario.submit();			
		}else{
			if(validar()==true){
				document.formulario.submit();
			}
		}
	}
	
	function inicia(){
		document.formulario.IdOrdemServico.focus();		
		status_inicial();
	}
	function visualizarOS(){
		window.location.replace("cadastro_ordem_servico.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
	}
	function busca_forma_cobranca(valor,IdContratoFaturamento){
		ocultar_campos(document.formulario.IdOrdemServico.value);
		if(IdContratoFaturamento == undefined){
			IdContratoFaturamento	=	'';
		}
	
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
			
		document.getElementById('cp_Vencimento').style.display		=	'none';
		document.getElementById('cpTerceiro').style.display			=	'block';
		
		switch(valor){
			case '1': //Em Contrato
				document.formulario.IdPessoaEnderecoCobranca.value								= '0';
				document.getElementById('cpEnderecoCorrespondencia').style.display				= 'none';
				document.getElementById('titFormaCobranca').style.display						= "block";
				document.getElementById('titFormaCobranca').style.paddingRight					= "4px";
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "block";
				document.getElementById('titDataPrimeiroVencimentoContrato').style.width		= "115px";
				document.getElementById("titLocalCobranca").style.display						= "none";
				document.getElementById("titContrato").style.display							= "block";
				//document.getElementById('titContrato').style.paddingLeft						= "10px";
				document.getElementById('titContrato').style.width								= "359px";
				document.getElementById('titValorDespesas').style.display						= "none";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "block";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.width			= "114px";
				document.getElementById("cpValorDespesa").style.display							= "none";
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
				document.getElementById("descricaoNotaFiscal").innerHTML						= "";
				document.getElementById("descricaoNotaFiscal").style.display					= "block";
				
				document.getElementById('titDataPrimeiroVencimentoIndividual').style.display	= "none";
				document.getElementById('cpDataPrimeiroVencimentoIndividual').style.display		= "none";
				document.getElementById('cpDataPrimeiroVencimentoIndividualIco').style.display	= "none";
				document.getElementById('findDataPrimeiroVencimentoIndividualIco').style.width	= "0px";
				document.getElementById('tbIco').style.paddingBottom							= "8px";
				document.getElementById('sepcpQtdParcela').style.width							= "11px";
				document.getElementById('DvCo').style.width										= "11px";
				
				document.getElementById('titQtdParcela').style.display							= "block";
				document.getElementById('titQtdParcela').style.width							= "84px";
				document.getElementById('septitDtPriVenCo').style.width							= "9px";
				document.getElementById('cpQtdParcela').style.display							= "block";
				document.getElementById('cpQtdParcela').style.width								= "80px";
				document.getElementById('cpFormaCobranca').style.display						= "block";
				document.getElementById('cpFormaCobranca').style.width							= "120px";
				document.getElementById('cpLocalCobranca').style.display						= "none";
				document.getElementById('tdValorDespesas').style.width 							= "0px";
				document.getElementById('septitTab').style.display								= "block";
				document.getElementById('sepcpTab').style.display								= "block";
				document.getElementById('sepcpIdContrato').style.display						= "none";
				document.getElementById('sepcpIdContrato').style.paddingRight					= "4px";
				document.getElementById('cpContrato').style.display								= "block";
				document.getElementById('cpContrato').style.width								= "370px";
				
				document.getElementById("sepcpValorDespesa").style.display						= "none";
				document.getElementById('septitValorDespesas').style.display					= "none";
				
				//document.getElementById("septitLocalCobranca").style.display					= "block";
				//document.getElementById('sepcpLocalCobranca').style.display						= "block";
				document.getElementById('titContratoInd').style.display							= "none";
				document.getElementById('cpContratoInd').style.display							= "none";
				document.formulario.DataPrimeiroVencimentoIndividual.value						= "";		
				document.formulario.IdContratoIndividual.value									= "";
				
				document.getElementById('obsDataPrimeiroVencimentoContrato').style.display		= 'block';
				
				listar_contrato(document.formulario.IdPessoa.value,IdContratoFaturamento);				
						
				document.formulario.bt_confirmar.disabled	=	true;
				document.formulario.bt_alterar.disabled		=	true;
				
				if(document.formulario.IdStatus == 5){
					document.formulario.bt_simular.disabled		=	true;
				}else{
					document.formulario.bt_simular.disabled		=	false;
				}
				document.formulario.IdContaDebitoCartao.value = "";
				document.formulario.ObrigatoriedadeContaCartao.value = "";
				break;
			case '2': //Individual
				document.getElementById('titFormaCobranca').style.display						= "block";
				document.getElementById('cpFormaCobranca').style.display						= "block";	
				document.getElementById('titFormaCobranca').style.paddingRight					= "4px";
				document.getElementById('cpFormaCobranca').style.width							= "120px";
				document.getElementById("descricaoNotaFiscal").innerHTML						= "";
				document.getElementById("descricaoNotaFiscal").style.display					= "none";
				
				document.getElementById("titLocalCobranca").style.display						= "block";
				document.getElementById('cpLocalCobranca').style.display						= "block";
				document.getElementById("titLocalCobranca").style.width							= "220px";
				document.getElementById("cpLocalCobranca").style.width							= "220px";
				document.getElementById('tdValorDespesas').style.width 							= "123px";
				
				//document.getElementById("septitLocalCobranca").style.display					= "block";
				//document.getElementById('sepcpLocalCobranca').style.display						= "block";
				//document.getElementById("septitLocalCobranca").style.width						= "7px";
				//document.getElementById('sepcpLocalCobranca').style.width						= "10px";
				
				document.getElementById('titValorDespesas').style.display						= "block";
				document.getElementById("cpValorDespesa").style.display							= "block";
				document.getElementById('titValorDespesas').style.width							= "119px";
				document.getElementById("cpValorDespesa").style.width							= "119px";
				
				document.getElementById("sepcpValorDespesa").style.display						= "block";
				document.getElementById('septitValorDespesas').style.display					= "block";
				document.getElementById("sepcpValorDespesa").style.width						= "11px";
				document.getElementById('septitValorDespesas').style.width						= "13px";
				document.getElementById('septitDtPriVenCo').style.width							= "9px";
				//document.getElementById('septitQtdParcela').style.width							= "11px";
				
				document.getElementById('titQtdParcela').style.display							= "block";
				document.getElementById('cpQtdParcela').style.display							= "block";
				document.getElementById('titQtdParcela').style.width							= "82px";
				document.getElementById('titcpQtdParcela').style.width							= "82px";
				document.getElementById('cpQtdParcela').style.width								= "75px";
				
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById("titContrato").style.display							= "none";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById('septitTab').style.display								= "block";
				document.getElementById('sepcpTab').style.display								= "block";
				document.getElementById('sepcpIdContrato').style.display						= "none";
				document.getElementById('cpContrato').style.display								= "none";
								
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "block";
				
				document.getElementById('titDataPrimeiroVencimentoIndividual').style.display	= "block";
				document.getElementById('cpDataPrimeiroVencimentoIndividual').style.display		= "block";
				document.getElementById('cpDataPrimeiroVencimentoIndividualIco').style.display	= "block";
				document.getElementById('findDataPrimeiroVencimentoIndividualIco').style.width	= "20px";
				document.getElementById('tbIco').style.paddingBottom							= "0px";
				document.getElementById('sepcpQtdParcela').style.width							= "11px";
				document.getElementById('DvCo').style.width										= "9px";
				
				document.formulario.bt_confirmar.disabled										=	true;
				document.getElementById('titContratoInd').style.display							= "block";
				document.getElementById('cpContratoInd').style.display							= "block";
				document.formulario.DataPrimeiroVencimentoContrato.value						= "";	
				document.formulario.IdContratoIndividual.value									= "";
				
				document.formulario.IdPessoaEnderecoCobranca.value								= '0';
				document.formulario.NomeResponsavelEnderecoCobranca.value						= "";
				document.formulario.CEPCobranca.value											= "";
				document.formulario.EnderecoCobranca.value										= "";
				document.formulario.NumeroCobranca.value										= "";
				document.formulario.ComplementoCobranca.value									= "";
				document.formulario.BairroCobranca.value										= "";
				document.formulario.IdPaisCobranca.value										= "";
				document.formulario.PaisCobranca.value											= "";
				document.formulario.IdEstadoCobranca.value										= "";
				document.formulario.EstadoCobranca.value										= "";
				document.formulario.IdCidadeCobranca.value										= "";
				document.formulario.CidadeCobranca.value										= "";
				document.getElementById('cpEnderecoCorrespondencia').style.display				= 'block';
				
				document.getElementById('obsDataPrimeiroVencimentoContrato').style.display		= 'none';
				
				listar_contrato_individual(document.formulario.IdPessoa.value,IdContratoFaturamento);				
				//busca_local_cobranca_fatura();
				
				if(document.formulario.DataPrimeiroVencimentoIndividual.value == ""){
					var qtdMes	= document.formulario.QtdMesesVencimento.value;
					
					if(qtdMes>0){
						var dataTemp = addMonth(qtdMes);
					}else{
						var dataTemp = data();
					}
					
					document.formulario.DataPrimeiroVencimentoIndividual.value						= dataTemp;	
				}
				
				document.formulario.bt_alterar.disabled		=	true;
				
				if(document.formulario.IdStatus == 5){
					document.formulario.bt_simular.disabled		=	true;
				}else{
					document.formulario.bt_simular.disabled		=	false;
				}
				document.formulario.IdContaDebitoCartao.style.display = "none";
				document.getElementById("label_IdContaDebitoCartao").style.display = "none";
				document.formulario.IdContaDebitoCartao.value = "";
				document.formulario.ObrigatoriedadeContaCartao.value = "";
				break;
			default:
				document.getElementById('cpTerceiro').style.display								= 'none';
				//document.getElementById('cpTerceiro').style.width								= '100px';
				document.getElementById("descricaoNotaFiscal").innerHTML						= "";
				document.getElementById("descricaoNotaFiscal").style.display					= "none";
				document.formulario.IdPessoaEnderecoCobranca.value								= '0';
				document.getElementById('cpEnderecoCorrespondencia').style.display				= 'none';
				document.getElementById('titFormaCobranca').style.display						= "block";	
				document.getElementById('titFormaCobranca').style.paddingRight					= "4px";
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById("titLocalCobranca").style.display						= "none";
				document.getElementById("titContrato").style.display							= "none";
				document.getElementById('titValorDespesas').style.display						= "none";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById('cpFormaCobranca').style.display						= "block";	
				document.getElementById('cpFormaCobranca').style.width							= "120px";
				document.getElementById("cpValorDespesa").style.display							= "none";
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
				document.getElementById('titDataPrimeiroVencimentoIndividual').style.display	= "none";
				document.getElementById('cpDataPrimeiroVencimentoIndividual').style.display		= "none";
				document.getElementById('cpDataPrimeiroVencimentoIndividualIco').style.display	= "none";
				document.getElementById('findDataPrimeiroVencimentoIndividualIco').style.width	= "0px";
				document.getElementById('titQtdParcela').style.display							= "none";
				document.getElementById('cpQtdParcela').style.display							= "none";
				document.getElementById('cpLocalCobranca').style.display						= "none";
				document.getElementById('cpContrato').style.display								= "none";
				document.getElementById('titContratoInd').style.display							= "none";
				document.getElementById('cpContratoInd').style.display							= "none";
				document.getElementById('obsDataPrimeiroVencimentoContrato').style.display		= 'none';
		}
	}
	function listar_contrato(IdPessoa,IdContratoAgrupadorTemp){
		if(IdPessoa == ''){
			while(document.formulario.IdContratoAgrupador.options.length > 0){
				document.formulario.IdContratoAgrupador.options[0] = null;
			}
			return false;
		}
		if(IdContratoAgrupadorTemp == undefined){
			IdContratoAgrupadorTemp = '';
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
		
		url = "xml/contrato.php?IdPessoa="+IdPessoa+"&IdStatusExc=1";
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){	
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						document.formulario.IdContratoAgrupador.disabled	=	true;
					}else{
						while(document.formulario.IdContratoAgrupador.options.length > 0){
							document.formulario.IdContratoAgrupador.options[0] = null;
						}
						
						document.formulario.IdContratoAgrupador.disabled	=	false;
						
						addOption(document.formulario.IdContratoAgrupador,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServico = nameTextNode.nodeValue;
							
							var Descricao	=	"("+IdContrato+") "+DescricaoServico;
							
							addOption(document.formulario.IdContratoAgrupador,Descricao,IdContrato);
						}
						if(IdContratoAgrupadorTemp!=''){
							for(ii=0;ii<document.formulario.IdContratoAgrupador.length;ii++){
								if(document.formulario.IdContratoAgrupador[ii].value == IdContratoAgrupadorTemp){
									document.formulario.IdContratoAgrupador[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdContratoAgrupador[0].selected = true;
						}
					}
					ocultar_campos(document.formulario.IdOrdemServico.value);
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function listar_contrato_individual(IdPessoa,IdContratoIndividualTemp){
		if(IdPessoa == ''){
			while(document.formulario.IdContratoIndividual.options.length > 0){
				document.formulario.IdContratoIndividual.options[0] = null;
			}
			return false;
		}
		if(IdContratoIndividualTemp == undefined){
			IdContratoIndividualTemp = '';
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
		
		url = "xml/contrato.php?IdPessoa="+IdPessoa+"&IdStatusExc=1";
		
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){	
					if(xmlhttp.responseText == 'false'){
						while(document.formulario.IdContratoIndividual.options.length > 0){
							document.formulario.IdContratoIndividual.options[0] = null;
						}
						
						document.formulario.IdContratoIndividual.disabled	=	true;
					}else{
						while(document.formulario.IdContratoIndividual.options.length > 0){
							document.formulario.IdContratoIndividual.options[0] = null;
						}
						
						document.formulario.IdContratoIndividual.disabled	=	false;
						
						addOption(document.formulario.IdContratoIndividual,"","0");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContrato").length; i++){
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdContrato")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdContrato = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServico = nameTextNode.nodeValue;
							
							var Descricao	=	"("+IdContrato+") "+DescricaoServico;
							
							addOption(document.formulario.IdContratoIndividual,Descricao,IdContrato);
						}
						if(IdContratoIndividualTemp!=''){
							for(ii=0;ii<document.formulario.IdContratoIndividual.length;ii++){
								if(document.formulario.IdContratoIndividual[ii].value == IdContratoIndividualTemp){
									document.formulario.IdContratoIndividual[ii].selected = true;
									break;
								}
							}
						}else{
							document.formulario.IdContratoIndividual[0].selected = true;
						}
					}
					ocultar_campos(document.formulario.IdOrdemServico.value);
				}
				// Fim de Carregando
				carregando(false);
			}
			return true;
		}
		xmlhttp.send(null);
	} 
	function validar_parcial(){
		if(document.formulario.ValorTotal.value==''){
			mensagens(1);
			document.formulario.ValorTotal.focus();
			return false;
		}else{
			var valor	=	document.formulario.ValorTotal.value;
			valor	=	new String(valor);
			valor	=	valor.replace(/\./g,'');
			valor	=	valor.replace(/,/i,'.');

			if(parseFloat(valor) > 0){
				if(document.formulario.FormaCobranca.value==''){
					mensagens(1);
					document.formulario.FormaCobranca.focus();
					return false;
				}
				switch(document.formulario.FormaCobranca.value){
					case '1': //Contrato
						if(document.formulario.IdContratoAgrupador.value=='' || document.formulario.IdContratoAgrupador.value=='0'){
							mensagens(1);
							document.formulario.IdContratoAgrupador.focus();
							return false;
						}
						if(document.formulario.QtdParcela.value==''){
							mensagens(1);
							document.formulario.QtdParcela.focus();
							return false;
						}
						if(document.formulario.DataPrimeiroVencimentoContrato.value==''){
							mensagens(1);
							document.formulario.DataPrimeiroVencimentoContrato.focus();
							return false;
						}else{
							if(isMes(document.formulario.DataPrimeiroVencimentoContrato.value) == false){		
								document.getElementById('titDataPrimeiroVencimentoContrato').style.backgroundColor = '#C10000';
								document.getElementById('titDataPrimeiroVencimentoContrato').style.color='#FFFFFF';
								mensagens(27);
								return false;
							}
							else{
								document.getElementById('titDataPrimeiroVencimentoContrato').style.backgroundColor='#FFFFFF';
								document.getElementById('titDataPrimeiroVencimentoContrato').style.color='#C10000';
								mensagens(0);
							}
						}
						break;
					case '2':	//Individual
						if(document.formulario.IdLocalCobranca.value==0){
							mensagens(1);
							document.formulario.IdLocalCobranca.focus();
							return false;
						}
						
						Valor	=	document.formulario.ValorTotal.value;
						Valor	=	new String(Valor);
						Valor	=	Valor.replace(/\./g,'');	
						Valor	=	Valor.replace(/,/i,'.');	
							
						Despesa	=	document.formulario.ValorDespesaLocalCobranca.value;
						Despesa	=	new String(Despesa);
						Despesa	=	Despesa.replace(/\./g,'');	
						Despesa	=	Despesa.replace(/,/i,'.');
							
						Total	=	parseFloat(Valor) + parseFloat(Despesa);
							
						if(Total < document.formulario.ValorCobrancaMinima.value){
							mensagens(165);
							document.formulario.ValorDespesaLocalCobranca.focus();
							return false;
						} else if(document.formulario.ValorDespesaLocalCobranca.value == ''){
							mensagens(1);
							document.formulario.ValorDespesaLocalCobranca.focus();
							return false;
						}
						if(document.formulario.QtdParcela.value==''){
							mensagens(1);
							document.formulario.QtdParcela.focus();
							return false;
						}
						if(document.formulario.DataPrimeiroVencimentoIndividual.value==''){
							mensagens(1);
							document.formulario.DataPrimeiroVencimentoIndividual.focus();
							return false;
						}else{
							if(isData(document.formulario.DataPrimeiroVencimentoIndividual.value) == false){		
								document.getElementById('titDataPrimeiroVencimentoIndividual').style.backgroundColor = '#C10000';
								document.getElementById('titDataPrimeiroVencimentoIndividual').style.color='#FFFFFF';
								mensagens(27);
								return false;
							}
							else{
								document.getElementById('titDataPrimeiroVencimentoIndividual').style.backgroundColor='#FFFFFF';
								document.getElementById('titDataPrimeiroVencimentoIndividual').style.color='#C10000';
								mensagens(0);
							}
						}
						if(document.formulario.IdFormatoCarne.value==0){
							mensagens(1);
							document.formulario.IdFormatoCarne.focus();
							return false;
						}
						if(document.formulario.IdContaDebitoCartao.value == "" && document.formulario.ObrigatoriedadeContaCartao.value == 1){
							mensagens(1);
							document.formulario.IdContaDebitoCartao.focus();
							return false;
						}
						if(document.formulario.IdPessoaEnderecoCobranca.value=='0'){
							mensagens(1);
							document.formulario.IdPessoaEnderecoCobranca.focus();
							return false;
						}
						break;
				}
			}
		}
		return true;
	}
	function simular(){
		if(validar_parcial()== true){
			var FormaCobranca	=	document.formulario.FormaCobranca.value;
			var QtdParcela		=	parseInt(document.formulario.QtdParcela.value);
			var valorT			=	document.formulario.ValorTotal.value;
			
			while(document.getElementById('tabelaVencimento').rows.length > 2){
				document.getElementById('tabelaVencimento').deleteRow(1);
			}
			document.getElementById('cp_Vencimento').style.display	=	'block';
			
			var tam, linha, c0, c1, c2, c3, c4, c5, tabindex, QtdParcela, valor, perc, desp, total, valorTotal=0, percTotal=0;
			var valorT, despTotal=0, totalTotal=0, data, dianovo='', mes, ano, qtdDiasMes, dataI, dia='', i, cont=0, temp, ii=1;
			var ArrayData	= new Array();
			
			switch(FormaCobranca){
				case '1': //Contrato
					dataI		=	document.formulario.DataPrimeiroVencimentoContrato.value;
					document.getElementById('tabValorDesp').style.display	=	'none';
					mes		=	dataI.substring(0,2);
					ano		=	dataI.substring(3,7);
					break;
				case '2': //Individual
					dataI	=	document.formulario.DataPrimeiroVencimentoIndividual.value;
					dia		=	dataI.substring(0,2);
					mes		=	dataI.substring(3,5);
					ano		=	dataI.substring(6,10);
					document.getElementById('tabValorDesp').style.display	=	'block';
					break;
			}
			
			i			=	parseFloat(mes);
			temp		=	i+QtdParcela;
			while(i<temp){
				if(i < 13){
					mes	=	i;
				}else{
					if(cont == 0){
						mes	 =	1;
						ano++;
						cont = 1;
					}else{
						mes++;
					}
				}
				
				if(mes == 12) cont = 0;
				
				qtdDiasMes	=	numDiasMes(ano, mes);
				
				if (mes < 10)	mes= "0" + mes;
				
				if(FormaCobranca==2){
					if(parseInt(dia) > parseInt(qtdDiasMes))	dianovo = parseInt(qtdDiasMes);
					else										dianovo = dia;
					
					data	=	mostraDataFim(qtdDiasMes,dianovo+"/"+mes+"/"+ano);
				}else{
					data	=	mes+"/"+ano;
				}
				
				ArrayData[ii]	=	data;
				i++;
				ii++
			}
			
			ii=1;
			for(i=1;i<=QtdParcela;i++){
				tam 	= document.getElementById('tabelaVencimento').rows.length;
				linha	= document.getElementById('tabelaVencimento').insertRow(tam-1);
				
				if(tam%2 != 0){
					linha.style.backgroundColor = "#E2E7ED";
				}
				
				tabindex =	13 * i;
				
				linha.accessKey = i; 
				
				c0	= linha.insertCell(0);	
				c1	= linha.insertCell(1);	
				c2	= linha.insertCell(2);	
				c3	= linha.insertCell(3);	
				c4	= linha.insertCell(4);
				
				if(FormaCobranca == 2){
					c5	= linha.insertCell(5);
				}
				
				valorT	=	document.formulario.ValorTotal.value;
				valorT	=	new String(valorT);
				valorT	=	valorT.replace('.','');
				valorT	=	valorT.replace('.','');
				valorT	=	valorT.replace(',','.');
				
				if(FormaCobranca == 2){
					desp	=	document.formulario.ValorDespesaLocalCobranca.value;
					desp	=	new String(desp);
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace(',','.');
					desp	=	parseFloat(desp);
				}
					
				valor	=	parseFloat(valorT) / QtdParcela;
				valor	=	Arredonda(valor,2);
				
				
				perc	=	(100 * parseFloat(valor))/parseFloat(valorT);
				
				if(FormaCobranca == 2){
					total	=	parseFloat(valor) + parseFloat(desp);
				}else{
					total	=	parseFloat(valor);
				}
				
				valorTotal	+=	valor;
				
				if(FormaCobranca == 2){
					despTotal	+=  desp;
					
					desp	=	Arredonda(desp,2);
					desp	=	formata_float(desp,2);
					desp	=	new String(desp);
					desp	=	desp.replace('.',',');
				}
				totalTotal	+= 	total;
				percTotal	+=  perc;
				
				
				valor	=	formata_float(valor,2);
				valor	=	new String(valor);
				valor	=	valor.replace('.',',');
				
				perc	=	Arredonda(perc,2);
				perc	=	formata_float(perc,2);
				perc	=	new String(perc);
				perc	=	perc.replace('.',',');
				
				total	=	Arredonda(total,2);
				total	=	formata_float(total,2);
				total	=	new String(total);
				total	=	total.replace('.',',');
				
				data	=	ArrayData[ii];
				
				c0.innerHTML = i;
				c0.style.textAlign = "center";
				
				c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+i+"' value='"+valor+"' maxlength='12' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex)+" onChange=\"calcula_valor("+i+")\">&nbsp;&nbsp;";
				c1.style.textAlign = "right";
				
				c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+i+"' value='"+perc+"'  maxlength='6' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex+1)+" onChange=\"calcula_valor("+i+",this.value)\">&nbsp;&nbsp;";
				c2.style.textAlign = "right";
				
				if(FormaCobranca==2){
					c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+i+"' value='"+desp+"' maxlength='12' onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex="+(tabindex+2)+" onChange=\"calcula_valor("+i+")\">&nbsp;&nbsp;";
					c3.style.textAlign = "right";
				
					c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+i+"' value='"+total+"' readOnly>&nbsp;&nbsp;";
					c4.style.textAlign = "right";
				
					c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+i+"' value='"+data+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex="+(tabindex+3)+">";
				}else{
					c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+i+"' value='"+total+"' readOnly>&nbsp;&nbsp;";
					c3.style.textAlign = "right";
				
					c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+i+"' value='"+data+"' maxlength='7' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+">";
				}
				ii++
				
			}
			if(FormaCobranca == 2){	
				document.getElementById('tableDataVenc').innerHTML			= 	'Data Vencimento';
				document.getElementById('totalValorDespesa').style.display	=	'block';
				document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
			}else{
				document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
				document.getElementById('totalValorDespesa').style.display	=	'none';
			}
			document.getElementById('totalVencimentos').innerHTML		=	"Total: "+(i-1);
			document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
			document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
			document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
		
			document.formulario.bt_confirmar.disabled	=	true;
			document.formulario.bt_alterar.disabled		=	false;
		}
		else{
			document.formulario.bt_confirmar.disabled	=	true;
			document.formulario.bt_alterar.disabled		=	true;
			return false;
		}
	}
	function calcula_valor(parcela,percentual){
		
		var QtdParcela, valor=0, perc=0, desp=0, total=0, valorTotal=0, percTotal=0;
		var valorT, despTotal=0, totalTotal=0, i, cont=1, pos;
		var posInicial=0,posFinal=0,temp=0,valorTemp=0, despTemp=0, despT;
		
		if(percentual == undefined)	percentual= '';
		
		for(i = 0; i<document.formulario.length; i++){
			if(document.formulario[i].name != undefined){
				if(document.formulario[i].name.substring(0,4) == 'parc'){
					if(posInicial == 0){
						posInicial = i;
					}
					posFinal = i;
				}
			}
		}
		
		var FormaCobranca	=	document.formulario.FormaCobranca.value;
		var tam	=	0;
		
		switch(FormaCobranca){
			case '1': //Contrato
				QtdParcela	=	document.formulario.QtdParcela.value;
				valorT		=	document.formulario.ValorTotal.value;
				tam			=	4;
				break;
			case '2': //Individual
				QtdParcela	=	document.formulario.QtdParcela.value;
				valorT		=	document.formulario.ValorTotal.value;
				tam			=	5;
				
				desp	=	document.formulario.ValorDespesaLocalCobranca.value;
				desp	=	new String(desp);
				desp	=	desp.replace('.','');
				desp	=	desp.replace('.','');
				desp	=	desp.replace(',','.');
				break;
		}
		
		valorT	=	new String(valorT);
		valorT	=	valorT.replace('.','');
		valorT	=	valorT.replace('.','');
		valorT	=	valorT.replace(',','.');
		
		temp = 0;
		if(posInicial != 0){
			for(i = posInicial; i<=posFinal; i=i+tam){
				pos	= document.formulario[i].name.split('_');
				
				if(pos[1] == parcela || pos[1] < parcela){
					valor	=	document.formulario[i].value;
				}
				
				valor	=	new String(valor);
				valor	=	valor.replace('.','');
				valor	=	valor.replace('.','');
				valor	=	valor.replace(',','.');
				
				valorTemp	=	valor;
				
				if(FormaCobranca==2){
					desp	=	document.formulario[i+2].value;
					desp	=	new String(desp);
					desp	=	desp.replace('.','');
					desp	=	desp.replace('.','');
					desp	=	desp.replace(',','.');
				}
				
				if(pos[1] > parcela){
					valor	=	valor.replace(',','.');
					
					if(temp == 0){	
						if(valor != 0){
							valor	=	valorTotal;
						}
						valorTemp	=	valorT	-	valor;
						
						valor	=	parseFloat(valorTemp) / (QtdParcela-parcela);
						temp	=	1;	
					}
				}
				
				if(percentual != '' && pos[1] == parcela){
					perc	=	percentual;
					perc	=	new String(perc);
					perc	=	perc.replace('.','');
					perc	=	perc.replace('.','');
					perc	=	perc.replace(',','.');
					
					valor	=	(perc*valorT)/100;
				}else{
					perc	=	(100 * parseFloat(valor))/parseFloat(valorT);
				}
				
				if(valor < 0)	valor	=	0;
				if(perc < 0)	perc	=	0;
				
				if(FormaCobranca==2){
					if(desp < 0)	desp	=	0;
					
					desp	=	Arredonda(desp,2);
					desp	=	formata_float(desp,2);
				}
				
				valor	=	Arredonda(valor,2);
				valor	=	formata_float(valor,2);
					
				perc	=	Arredonda(perc,2);
				perc	=	formata_float(perc,2);
				
				if(FormaCobranca==2){	
					total	=	parseFloat(valor) + parseFloat(desp);	
				}
				if(FormaCobranca==1){	
					total	=	parseFloat(valor);	
				}
				
				if(total < 0)	total	=	0;
				
				total	=	Arredonda(total,2);
				total	=	formata_float(total,2);
				
				valorTotal	+=	parseFloat(valor);
				percTotal	+=  parseFloat(perc);
				
				if(FormaCobranca==2){	
					despTotal  +=  parseFloat(desp);
					desp		=	new String(desp);
					desp		=	desp.replace('.',',');
				}
				
				totalTotal	+= 	parseFloat(total);
				
				valor	=	new String(valor);
				valor	=	valor.replace('.',',');
					
				perc	=	new String(perc);
				perc	=	perc.replace('.',',');
					
				total	=	new String(total);
				total	=	total.replace('.',',');
				
				document.formulario[i].value	=	valor;
				document.formulario[i+1].value	=	perc;
				
				if(FormaCobranca==2){	
					document.formulario[i+2].value	=	desp;
					document.formulario[i+3].value	=	total;
				}else{
					document.formulario[i+2].value	=	total;
				}
				
				
				cont++;
			}

			
			temp	=	new String(percTotal);
			temp	=	temp.split('.');
			
			if(temp[1] > 0 && temp[0] == 100){
				percTotal	=	temp[0]+'.00';	
			}
			
			document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
			document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
			
			if(FormaCobranca==2){	
				document.getElementById('totalValorDespesa').display		=	'block';
				document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
			}else{
				document.getElementById('totalValorDespesa').display		=	'none';
			}
			document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
		}
	}
	function listar_ordem_servico_parcela(IdOrdemServico){
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
			
		if(IdOrdemServico == ''){
			IdOrdemServico = 0;
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
	    
	   	url = "../administrativo/xml/ordem_servico_parcela.php?IdOrdemServico="+IdOrdemServico;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						var valorTotal=0,despTotal=0,percTotal=0,totalTotal=0, valor, perc, desc, total, QtdParcela, percentualParcela='';
						QtdParcela	=	xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoParcela").length;
						
						if(QtdParcela > 0){
							document.formulario.bt_confirmar.disabled	=	true;
							document.formulario.bt_alterar.disabled		=	true;
							
							QtdParcela	=	xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoParcela").length;
							document.formulario.simulado.value						=	0;
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoParcela").length; i++){	
								
								if(i==0){
									document.formulario.simulado.value						=	1;
									document.getElementById('cp_Vencimento').style.display	=	'block';

									if(document.formulario.IdStatus.value > 99 && document.formulario.IdStatus.value < 500){ //document.formulario.IdStatus.value != 5 && document.formulario.IdStatus.value != 0 
										document.formulario.bt_confirmar.disabled	=	false;
										document.formulario.bt_alterar.disabled		=	false;
									}
								}
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("FormaCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var FormaCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdOrdemServicoParcela")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdOrdemServicoParcela = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("Vencimento")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var Vencimento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDespesaLocalCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorDespesaLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var Valor = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorTotal = nameTextNode.nodeValue;
								
								tam 	= document.getElementById('tabelaVencimento').rows.length;
								linha	= document.getElementById('tabelaVencimento').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								if(i==0){
									tabindex =	13;
								}else{
									tabindex =	8 * (i+1);
								}
								
								linha.accessKey = i; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);	
								c4	= linha.insertCell(4);
								
								if(FormaCobranca == 2){
									c5	= linha.insertCell(5);
									
									if(i==0){
										document.getElementById('tabValorDesp').style.display		=	'block';
										document.getElementById('totalValorDespesa').style.display	=	'block';
										document.getElementById('tableDataVenc').innerHTML			=	'Data Vencimento';
									}
								}else{
									if(i==0){
										if(document.getElementById('tabValorDesp')){
											document.getElementById('tabValorDesp').style.display		=	'none';
										}
										if(document.getElementById('totalValorDespesa')){
											document.getElementById('totalValorDespesa').style.display	=	'none';
										}
										if(document.getElementById('tableDataVenc')){
											document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
										}
									}
								}
								
								valorT	=	ValorTotal;
								
								valor	=	parseFloat(Valor) / QtdParcela;
								perc	=	(100 * parseFloat(Valor))/parseFloat(valorT);
								total	=	parseFloat(Valor) + parseFloat(ValorDespesaLocalCobranca);
								
								if(perc != ''){
									percentualParcela += '_'+formata_float(Arredonda(perc,2),2);
								}
								
								valorTotal	+=	parseFloat(Valor);
								percTotal	+=  parseFloat(perc);
								
								if(FormaCobranca == 2){
									despTotal	+=  parseFloat(ValorDespesaLocalCobranca);
								}
								
								totalTotal	+= 	parseFloat(total);
								
								if((document.formulario.IdStatus.value >= 0 && document.formulario.IdStatus.value <= 99) || (document.formulario.IdStatus.value >= 200 && document.formulario.IdStatus.value <= 200)){ // document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 2
									readOnly	=	"readOnly";
								}else{
									readOnly	=	"";
								}
								
								c0.innerHTML = IdOrdemServicoParcela;
								c0.style.textAlign = "center";
								
								c1.innerHTML = "<input class='valor' style='margin:0; width:150px;' name='parcValor_"+IdOrdemServicoParcela+"' value='"+formata_float(Arredonda(Valor,2),2).replace(".",",")+"' maxlength='12' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex)+" onChange=\"calcula_valor("+(i+1)+")\" "+readOnly+">&nbsp;&nbsp;";
								c1.style.textAlign = "right";
								
								c2.innerHTML = "<input class='valor' style='margin:0; width:60px' name='parcPerc_"+IdOrdemServicoParcela+"' value='"+formata_float(Arredonda(perc,2),2).replace(".",",")+"'  maxlength='6' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'float')\" tabindex="+(tabindex+1)+" onChange=\"calcula_valor("+(i+1)+",this.value)\" "+readOnly+">&nbsp;&nbsp;";
								c2.style.textAlign = "right";
								
								if(FormaCobranca == 2){
									c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcDesp_"+IdOrdemServicoParcela+"' value='"+formata_float(Arredonda(ValorDespesaLocalCobranca,2),2).replace(".",",")+"' maxlength='12' onkeypress=\"mascara(this,event,'float')\" onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" tabindex="+(tabindex+2)+" onChange=\"calcula_valor("+(i+1)+")\" "+readOnly+">&nbsp;&nbsp;";
									c3.style.textAlign = "right";
									
									c4.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdOrdemServicoParcela+"' value='"+formata_float(Arredonda(total,2),2).replace(".",",")+"' readOnly>&nbsp;&nbsp;";
									c4.style.textAlign = "right";
									
									c5.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdOrdemServicoParcela+"' value='"+dateFormat(Vencimento)+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'date')\" tabindex="+(tabindex+3)+">";
								}else{
									c3.innerHTML = "<input class='valor' style='margin:0; width:150px' name='parcTotal_"+IdOrdemServicoParcela+"' value='"+formata_float(Arredonda(total,2),2).replace(".",",")+"' readOnly>&nbsp;&nbsp;";
									c3.style.textAlign = "right";
									
									c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdOrdemServicoParcela+"' value='"+Vencimento+"' maxlength='7' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+" "+readOnly+">";
								}
							}
							if(document.getElementById('totalVencimentos')){
								document.getElementById('totalVencimentos').innerHTML		=	"Total: "+(i);
							}
							if(document.getElementById('totalValor')){
								document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
							}
							if(document.getElementById('totalPercentual')){
								document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
							}
							document.formulario.PercentualParcela.value					=	percentualParcela;
							
							if(FormaCobranca == 2){
								document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
							}
							
							if(document.getElementById('totalValorTotal')){
								document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
							}
						}
					}
					if(document.formulario.Faturado.value == 1){
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_confirmar.disabled 	= true;	
					}
				}
			} 
			if(document.formulario.Erro.value != 0){
				scrollWindow('bottom');
			}else{
				scrollWindow('top');
			}
			// Fim de Carregando
			carregando(false);
			return true;
		}
		xmlhttp.send(null);
		
	}
	function busca_valor_minimo(IdLocalCobranca){
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
	    
	   	url = "../administrativo/xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCobrancaMinima")[0]; 
						nameTextNode = nameNode.childNodes[0];
						ValorCobrancaMinima = nameTextNode.nodeValue;
						
						document.formulario.ValorCobrancaMinima.value	=	ValorCobrancaMinima;
					}
				}
			} 
		}
		xmlhttp.send(null);	
	}
	function verificaValor(valor){
		var IdStatus = document.formulario.IdStatus.value;
		if(/*(IdStatus >= 500 && IdStatus <= 599) || */(IdStatus >= 0 && IdStatus <= 99)){
			return false;
		}
		
		valor	=	new String(valor);
		valor	=	valor.replace('.','');
		valor	=	valor.replace('.','');
		valor	=	valor.replace(',','.');
		
		if(valor <= 0){
			document.getElementById('titFormaCobranca').style.display						= "none";
			document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
			document.getElementById("titLocalCobranca").style.display						= "none";
			document.getElementById("titContrato").style.display							= "none";
			document.getElementById('titValorDespesas').style.display						= "none";
			document.getElementById('cpFormaCobranca').style.display						= "none";
			document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
			document.getElementById("cpValorDespesa").style.display							= "none";
			document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
			document.getElementById('titQtdParcela').style.display							= "none";
			document.getElementById('cpQtdParcela').style.display							= "none";
			document.getElementById('cpLocalCobranca').style.display						= "none";
			document.getElementById('tdValorDespesas').style.width 							= "0px";
			document.getElementById('cpContrato').style.display								= "none";
			
			while(document.getElementById('tabelaVencimento').rows.length > 2){
				document.getElementById('tabelaVencimento').deleteRow(1);
			}
			document.getElementById('cp_Vencimento').style.display	=	'none';
			
			document.formulario.bt_simular.disabled	=	true;
			
			if(document.formulario.IdStatus.value > 99 && document.formulario.IdStatus.value < 500){ // document.formulario.IdStatus.value != 0 && document.formulario.IdStatus.value != 5
				document.formulario.bt_alterar.disabled	=	false;
			}
		}else{
			busca_forma_cobranca(document.formulario.FormaCobranca.value);
		}
	}
	function voltar(){
		window.location.replace("cadastro_ordem_servico.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
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
		
	function busca_pessoa_endereco_cobranca(IdPessoa,IdPessoaEndereco){
		if(IdPessoa==''){
			IdPessoa = 0;
		}
		if(IdPessoaEndereco=='' || IdPessoaEndereco==undefined){
			IdPessoaEndereco = 0;
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
	    
	    url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa+"&IdPessoaEndereco="+IdPessoaEndereco;
		
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoaEndereco = nameTextNode.nodeValue;
							
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeResponsavelEndereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeResponsavelEndereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("CEP")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var CEP = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Endereco = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Numero")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Numero = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Complemento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Complemento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Bairro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomePais = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeEstado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdCidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var NomeCidade = nameTextNode.nodeValue;
						
						document.formulario.NomeResponsavelEnderecoCobranca.value	=	NomeResponsavelEndereco;
						document.formulario.CEPCobranca.value						=	CEP;
						document.formulario.EnderecoCobranca.value					=	Endereco;
						document.formulario.NumeroCobranca.value					=	Numero;
						document.formulario.ComplementoCobranca.value				=	Complemento;
						document.formulario.BairroCobranca.value					=	Bairro;
						document.formulario.IdPaisCobranca.value					=	IdPais;
						document.formulario.PaisCobranca.value						=	NomePais;
						document.formulario.IdEstadoCobranca.value					=	IdEstado;
						document.formulario.EstadoCobranca.value					=	NomeEstado;
						document.formulario.IdCidadeCobranca.value					=	IdCidade;
						document.formulario.CidadeCobranca.value					=	NomeCidade;
					}else{
						document.formulario.NomeResponsavelEnderecoCobranca.value	=	"";
						document.formulario.CEPCobranca.value						=	"";
						document.formulario.EnderecoCobranca.value					=	"";
						document.formulario.NumeroCobranca.value					=	"";
						document.formulario.ComplementoCobranca.value				=	"";
						document.formulario.BairroCobranca.value					=	"";
						document.formulario.IdPaisCobranca.value					=	"";
						document.formulario.PaisCobranca.value						=	"";
						document.formulario.IdEstadoCobranca.value					=	"";
						document.formulario.EstadoCobranca.value					=	"";
						document.formulario.IdCidadeCobranca.value					=	"";
						document.formulario.CidadeCobranca.value					=	"";
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function busca_opcoes_pessoa_endereco(IdPessoa,IdPessoaEnderecoTemp){
		if(IdPessoaEnderecoTemp == undefined) 			IdPessoaEnderecoTemp 		 = "";
		
		while(document.formulario.IdPessoaEnderecoCobranca.options.length > 0){
			document.formulario.IdPessoaEnderecoCobranca.options[0] = null;
		}
		
		if(IdPessoa != ""){
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
		    
		    url = "xml/pessoa_endereco.php?IdPessoa="+IdPessoa;
			
			xmlhttp.open("GET", url,true);
		    
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText != 'false'){		
							var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;					
							
							addOption(document.formulario.IdPessoaEnderecoCobranca,"","0");
							
							for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco").length;i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoaEndereco")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdPessoaEndereco = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoEndereco")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoEndereco = nameTextNode.nodeValue;
								
								addOption(document.formulario.IdPessoaEnderecoCobranca,DescricaoEndereco,IdPessoaEndereco);
							}
							
							document.formulario.IdPessoaEnderecoCobranca[0].selected 		 = true;
							
							if(IdPessoaEnderecoTemp!=""){
								for(i=0;i<document.formulario.IdPessoaEnderecoCobranca.options.length;i++){
									if(document.formulario.IdPessoaEnderecoCobranca[i].value == IdPessoaEnderecoTemp){
										document.formulario.IdPessoaEnderecoCobranca[i].selected	=	true;
										i	=	document.formulario.IdPessoaEnderecoCobranca.options.length;
									}
								}
							}
						}
						// Fim de Carregando
						carregando(false);
					}
				}
			}
			xmlhttp.send(null);	
		}
	}
	function busca_local_cobranca_fatura(IdLocalCobrancaTemp, IdStatus){
		if(IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = '';
		}
		
		var nameNode, nameTextNode, url;
		var xmlhttp   = false;
		
		if(window.XMLHttpRequest){ // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
			}
		} else if(window.ActiveXObject){ // IE
			try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	            } catch(e){}
	        }
	    }
		
	    if((IdLocalCobrancaTemp == '' || (IdStatus >= 0 && IdStatus <= 99)) || document.formulario.Faturado.value == 1){
	   		url = "../administrativo/xml/local_cobranca.php";
	   	} else{
	   		url = "../administrativo/xml/local_cobranca_geracao.php";
		}

		url += "?IdPessoa="+document.formulario.IdPessoa.value;

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
						for(i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i];
							nameTextNode = nameNode.childNodes[0];
							var IdLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdStatus= nameTextNode.nodeValue;
							
							if(IdStatus == 1 || IdLocalCobranca == IdLocalCobrancaTemp){
								addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
							}
						}
						
						if(IdLocalCobrancaTemp != ""){
							for(var i=0; i<document.formulario.IdLocalCobranca.options.length; i++){
								if(document.formulario.IdLocalCobranca.options[i].value == IdLocalCobrancaTemp){
									document.formulario.IdLocalCobranca.options[i].selected = true;
									i = document.formulario.IdLocalCobranca.options.length;
								}
							}
						}else{
							document.formulario.IdLocalCobranca.options[0].selected = true;
						}
					}
				}
				// Fim de Carregando
				carregando(false);
			}
			
			if(document.getElementById("cp_Status").innerHTML.indexOf("(Aguard. Pagamento)") == -1){
				document.formulario.bt_cancelar.disabled = true;
			}
			if(document.getElementById("cp_Status").innerHTML.indexOf("(Lançamentos Cancelados)") == -1){
				document.formulario.bt_cancelar.disabled = false;
				document.formulario.bt_gerar.disabled = false;
			}
			if(document.getElementById("cp_Status").innerHTML == "Faturado"  || document.getElementById("cp_Status").innerHTML == "Em Aberto" || document.getElementById("cp_Status").innerHTML == "Aguardando Faturamento"){
				document.formulario.bt_cancelar.style.display = "none";
			}
			if(document.getElementById("cp_Status").innerHTML.indexOf("(Lançamentos Cancelados)") > -1){
				document.formulario.bt_cancelar.disabled = true;
				document.formulario.bt_gerar.disabled = true;
			}
			return true;
		}
		xmlhttp.send(null);
	}
	function verificaParcela(valor){
		if(valor > 1 || valor == ''){
			document.formulario.IdFormatoCarne.disabled = false;
		} else{
			document.formulario.IdFormatoCarne.value = 2;
			document.formulario.IdFormatoCarne.disabled = true;
		}
	}
	
	function atualizaPrimeiraReferencia(IdContrato, DataPrimeiroVencimento){
		if(IdContrato == '' || IdContrato == undefined) {
			return;
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

		url = "xml/contrato.php?IdContrato="+IdContrato;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.formulario.DataPrimeiroVencimentoContrato.value = ""; 
						
						while(document.getElementById('tabelaVencimento').rows.length > 2){
							document.getElementById('tabelaVencimento').deleteRow(1);
						}
						document.getElementById('cp_Vencimento').style.display	=	'none';
						
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataPrimeiraReferencia")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataPrimeiraReferencia = nameTextNode.nodeValue;										
												
						document.formulario.DataPrimeiroVencimentoContrato.value = DataPrimeiraReferencia; 
						
						if(DataPrimeiroVencimento != DataPrimeiraReferencia){
							while(document.getElementById('tabelaVencimento').rows.length > 2){
								document.getElementById('tabelaVencimento').deleteRow(1);
							}
							document.getElementById('cp_Vencimento').style.display	=	'none';
							document.formulario.bt_confirmar.disabled 				= 	true;
						}						
					}
				}
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	
	function buscar_descricao_layout(IdContrato){
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

		url = "xml/contrato_nota_fiscal.php?IdContrato="+IdContrato;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById("descricaoNotaFiscal").innerHTML = '';
					} else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoNotaFiscalLayout")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoNotaFiscalLayout = nameTextNode.nodeValue;
						
						document.getElementById("descricaoNotaFiscal").innerHTML = DescricaoNotaFiscalLayout;
					}
				}
			}
			return true;
		}
		
		xmlhttp.send(null);	
	}
	
	function atualizarSimulacao(){	
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
		document.getElementById('cp_Vencimento').style.display	= 'none';
		document.formulario.bt_confirmar.disabled 				= true;		
	}
	
	function selecionar_cancelamento(IdOrdemServico,FormaCobranca){
		if(FormaCobranca == 1){
			window.location = "./cadastro_cancelar_lancamento_financeiro.php?IdOrdemServico="+IdOrdemServico;
		}
		if(FormaCobranca == 2){
			window.location = "./cadastro_cancelar_multiplas_contas_receber.php?IdOrdemServico="+IdOrdemServico;
		}
	}
	
	function ocultar_campos(IdOrdemServico){
		var nameNode, nameTextNode, url;
		
		url = "xml/ordem_servico.php?IdOrdemServico="+IdOrdemServico;
		
		call_ajax(url, function (xmlhttp){
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubTipoOrdemServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdSTOS = nameTextNode.nodeValue;
				
				var FormaCobranca = document.formulario.FormaCobranca.value;
				
				if(IdStatus < 500){
					switch(FormaCobranca){//em contrato
						case '1':
							document.getElementById('septitValorDesp').style.width 						= "14px";
							document.getElementById('septitQtdParcela').style.width 					= "11px";
							document.getElementById('titContrato').style.width 							= "356px";
							document.getElementById('titQtdParcela').style.width 						= "86px";
							document.getElementById('septitDtPriVenCo').style.width 					= "12px";
							
							document.getElementById('titFormaCobranca').style.color						= '#C10';
							document.getElementById('titContrato').style.color							= '#C10';
							document.getElementById('titQtdParcela').style.color						= '#C10';
							document.getElementById('titDataPrimeiroVencimentoContrato').style.color	= '#C10';
							/*
							if((IdStatus >= 100 && IdStatus <= 199) || (IdStatus >= 400 && IdStatus <= 499)){
								document.formulario.IdTerceiro.disabled									= true;
							}else{
								document.formulario.IdTerceiro.disabled									= false;
							}*/
							
							document.formulario.FormaCobranca.setAttribute("tabIndex","2");
							document.formulario.IdContratoAgrupador.setAttribute("tabIndex","4");
							document.formulario.QtdParcela.setAttribute("tabIndex","6");
							document.formulario.DataPrimeiroVencimentoContrato.setAttribute("tabIndex","7");
							document.formulario.IdTerceiro.setAttribute("tabIndex","9");
							
							document.formulario.QtdParcela.setAttribute("onblur","Foco(this,'out')");
							document.formulario.IdTerceiro.setAttribute("onblur","Foco(this,'out')");
							document.formulario.IdContratoAgrupador.setAttribute("onblur","Foco(this,'out')");
							document.formulario.DataPrimeiroVencimentoContrato.setAttribute("onblur","Foco(this,'out')");
							
							document.formulario.QtdParcela.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.IdTerceiro.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.IdContratoAgrupador.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.DataPrimeiroVencimentoContrato.setAttribute("onFocus","Foco(this,'in')");
							break;
						case '2':
							document.getElementById('septitValorDesp').style.width 						= "11px";
							document.getElementById('septitValorDespesas').style.width 					= "12px";
							document.getElementById('tdValorDespesas').style.width 						= "120px";
							document.getElementById('septitQtdParcela').style.width 					= "11px";
							document.getElementById('cpValorDespesa').style.width 				    	= "115px";
							document.getElementById('cpQtdParcela').style.width 				    	= "79px";
							document.getElementById('titcpQtdParcela').style.width 				    	= "85px";
							
							document.getElementById('titFormaCobranca').style.color						= '#C10';
							document.getElementById('titLocalCobranca').style.color						= '#C10';
							document.getElementById('titValorDespesas').style.color						= '#C10';
							document.getElementById('titQtdParcela').style.color						= '#C10';
							document.getElementById('titDataPrimeiroVencimentoIndividual').style.color	= '#C10';
							
							if(document.formulario.QtdParcela.value > 1){
								document.formulario.IdFormatoCarne.disabled								= false;
							}else{
								document.formulario.IdFormatoCarne.disabled								= true;
							}
							document.formulario.IdContratoIndividual.disabled							= false;
							/*
							if((IdStatus >= 100 && IdStatus <= 199) || (IdStatus >= 400 && IdStatus <= 499)){
								document.formulario.IdTerceiro.disabled									= true;
							}else{
								document.formulario.IdTerceiro.disabled									= false;
							}*/
							
							document.formulario.FormaCobranca.setAttribute("tabIndex","2");
							document.formulario.IdLocalCobranca.setAttribute("tabIndex","3");
							document.formulario.ValorDespesaLocalCobranca.setAttribute("tabIndex","5");
							document.formulario.QtdParcela.setAttribute("tabIndex","6");
							document.formulario.DataPrimeiroVencimentoIndividual.setAttribute("tabIndex","8");
							document.formulario.IdTerceiro.setAttribute("tabIndex","9");
							document.formulario.IdContratoIndividual.setAttribute("tabIndex","10");
							document.formulario.IdFormatoCarne.setAttribute("tabIndex","11");
							
							document.formulario.QtdParcela.setAttribute("onblur","Foco(this,'out')");
							document.formulario.ValorDespesaLocalCobranca.setAttribute("onblur","Foco(this,'out')");
							document.formulario.DataPrimeiroVencimentoIndividual.setAttribute("onblur","Foco(this,'out')");
							document.formulario.IdContratoIndividual.setAttribute("onblur","Foco(this,'out')");
							document.formulario.IdFormatoCarne.setAttribute("onblur","Foco(this,'out')");
							
							document.formulario.QtdParcela.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.ValorDespesaLocalCobranca.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.DataPrimeiroVencimentoIndividual.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.IdContratoIndividual.setAttribute("onFocus","Foco(this,'in')");
							document.formulario.IdFormatoCarne.setAttribute("onFocus","Foco(this,'in')");
							
							document.formulario.cpDataPrimeiroVencimentoIndividualIco.setAttribute("src","../../img/estrutura_sistema/ico_date.gif");
							break;
					}
				}
			}
		});
	}
	
	function verificar_local_cobranca(IdLocalCobranca,IdCartao,IdContaDebito){
		if(IdLocalCobranca == undefined || IdLocalCobranca == ""){
			IdLocalCobranca = 0;
		}
		
		var url = "xml/local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdTipoLocalCobranca = nameTextNode.nodeValue;
				switch(IdTipoLocalCobranca){
					case '3':
						document.getElementById('label_IdContaDebitoCartao').style.display = "block";
						document.getElementById('label_IdContaDebitoCartao').style.display = "block";
						document.getElementById('label_IdContaDebitoCartao').innerHTML = "Conta Débito Automático";
						document.formulario.IdContaDebitoCartao.style.display = "block";
						buscar_conta_debito_atomatico(document.formulario.IdPessoa.value,IdContaDebito);
						document.formulario.ObrigatoriedadeContaCartao.value = 1;
						break;
					case '6':
						document.getElementById('label_IdContaDebitoCartao').style.display = "block";
						document.getElementById('label_IdContaDebitoCartao').style.display = "block";
						document.getElementById('label_IdContaDebitoCartao').innerHTML = "Cartão de Crédito";
						document.formulario.IdContaDebitoCartao.style.display = "block";
						busca_cartao_credito(document.formulario.IdPessoa.value,IdCartao);
						document.formulario.ObrigatoriedadeContaCartao.value = 1;
						break;
					default:
						document.getElementById('label_IdContaDebitoCartao').style.display = "none";
						document.getElementById('label_IdContaDebitoCartao').style.display = "none";
						document.formulario.IdContaDebitoCartao.style.display = "none";
						document.formulario.ObrigatoriedadeContaCartao.value = "";
						document.formulario.SeletorContaCartao.value = '';
						break;
				}
			}else{
				document.getElementById('label_IdContaDebitoCartao').style.display = "none";
				document.getElementById('label_IdContaDebitoCartao').style.display = "none";
				document.formulario.IdContaDebitoCartao.style.display = "none";
				document.formulario.ObrigatoriedadeContaCartao.value = "";
				document.formulario.SeletorContaCartao.value = '';
			}
		});
	}
	
	function buscar_conta_debito_atomatico(IdPessoa,IdNumeroContaDebito){
		if(IdPessoa == undefined || IdPessoa == 0){
			IdPessoa = 0;
		}
		
		if(IdNumeroContaDebito == undefined || IdNumeroContaDebito == 0 || IdNumeroContaDebito == "NULL"){
			IdNumeroContaDebito = "";
		}
		
		while(document.formulario.IdContaDebitoCartao.options[0] != null){
			document.formulario.IdContaDebitoCartao.remove(0);
		}
		addOption(document.formulario.IdContaDebitoCartao,"","");
		
		var url = "xml/pessoa_conta_debito.php?IdPessoa="+IdPessoa+"&IdLocalCobranca="+document.formulario.IdLocalCobranca.value;
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				for(var i=0;i<xmlhttp.responseXML.getElementsByTagName("IdContaDebito").length;i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaDebito")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaDebito = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroAgencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroAgencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoAgencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DigitoAgencia = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroConta")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NumeroConta = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DigitoConta")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DigitoConta = nameTextNode.nodeValue;
					
					var NumeroContaFinal = "";
					
					if(NumeroAgencia  != "" && DigitoAgencia != ""){
						NumeroContaFinal = NumeroAgencia+"-"+DigitoAgencia+" ";
					}else{
						NumeroContaFinal = NumeroAgencia+" ";
					}
					
					if(NumeroConta != "" && DigitoConta != ""){
						NumeroContaFinal += NumeroConta+"-"+DigitoConta;
					}else{
						NumeroContaFinal += NumeroConta;
					}
					
					addOption(document.formulario.IdContaDebitoCartao,NumeroContaFinal,IdContaDebito);
					
					if(IdNumeroContaDebito != ""){
						document.formulario.IdContaDebitoCartao.value = IdNumeroContaDebito;
					}else{
						document.formulario.IdContaDebitoCartao.options[0].selected = true;
					}
					document.formulario.SeletorContaCartao.value = 'IdContaDebito';
				}
			}else{
				while(document.formulario.IdContaDebitoCartao.options[0] != null){
					document.formulario.IdContaDebitoCartao.remove(0);
				}
				addOption(document.formulario.IdContaDebitoCartao,"","");
				document.formulario.SeletorContaCartao.value = '';
			}	
		});
	}
	
	function busca_cartao_credito(IdPessoa,IdNumeroCartao)
	{
		if(IdPessoa == "" || IdPessoa == "NULL") IdPessoa = 0;
		if(IdNumeroCartao == "" || IdNumeroCartao == "NULL" || IdNumeroCartao == undefined) IdNumeroCartao = "";
		var url = "xml/pessoa_cartao_credito.php?IdPessoa="+IdPessoa;
		
		while(document.formulario.IdContaDebitoCartao.options[0] != null){
			document.formulario.IdContaDebitoCartao.remove(0);
		}
		addOption(document.formulario.IdContaDebitoCartao,"","");
		
		call_ajax(url, function(xmlhttp){ 
				var nameNode, nameTextNode;
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPessoaEndereco,DescricaoEndereco;							
					for(i=0;i<xmlhttp.responseXML.getElementsByTagName("IdCartao").length;i++){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdCartao")[i]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdCartao = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroCartaoMascarado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var NumeroCartaoMascarado = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdContaDebitoCartao,NumeroCartaoMascarado,IdCartao);
						
					}
					if(IdNumeroCartao == ""){
						document.formulario.IdContaDebitoCartao.options[0].selected=true;
					}
					else{
						document.formulario.IdContaDebitoCartao.value = IdNumeroCartao;
					}
					
					document.formulario.SeletorContaCartao.value = 'IdCartao';
				}else{
					while(document.formulario.IdContaDebitoCartao.options[0] != null){
						document.formulario.IdContaDebitoCartao.remove(0);
					}
					addOption(document.formulario.IdContaDebitoCartao,"","");
					document.formulario.SeletorContaCartao.value = '';
				}
			});
	}