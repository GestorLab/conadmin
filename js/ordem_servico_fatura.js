	function validar(){
		if(validar_parcial() == true){
			valorT	=	document.formulario.ValorTotal.value;
			valorT	=	new String(valorT);
			valorT	=	valorT.replace('.','');
			valorT	=	valorT.replace('.','');
			valorT	=	valorT.replace(',','.');
			
			if(parseFloat > 0){
			
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
							mensagens(71);
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
							mensagens(71);
							document.formulario[posInicial].focus();	
							return false;
						}
					}
				}else{
					mensagens(72);
					document.formulario.bt_simular.focus();	
					return false;
				}
			}
			return true;
		}
	}
	
	function atualiza_tipo_servico(IdTipoOrdemServico){
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
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){	
				document.formulario.bt_enviar.disabled		= true;	
				document.formulario.bt_gerar.disabled		= true;			
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_confirmar.disabled 	= true;
				document.formulario.bt_cancelar.disabled 	= true;
			}
			else{	
				switch(document.formulario.IdStatus.value){
					case '5': //faturado
						document.formulario.bt_enviar.disabled		= false;	
						document.formulario.bt_gerar.disabled		= false;			
						document.formulario.bt_alterar.disabled 	= true;
						document.formulario.bt_confirmar.disabled 	= true;
						document.formulario.bt_cancelar.disabled 	= false;
						break;
					default:
						if(document.formulario.simulado.value  ==  1){
							document.formulario.bt_confirmar.disabled 	= false;
						}else{
							document.formulario.bt_confirmar.disabled 	= true;
						}
					
						document.formulario.bt_enviar.disabled		= true;	
						document.formulario.bt_gerar.disabled		= true;			
						document.formulario.bt_alterar.disabled 	= false;
						document.formulario.bt_cancelar.disabled 	= true;
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
		document.formulario.Acao.value = acao;	
		if(acao == 'cancelar' || acao=='imprimir'){
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
	
	function inicia(){
		document.formulario.IdOrdemServico.focus();
	}
	function visualizarOS(){
		window.location.replace("cadastro_ordem_servico.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
	}
	function busca_forma_cobranca(valor){
		while(document.getElementById('tabelaVencimento').rows.length > 2){
			document.getElementById('tabelaVencimento').deleteRow(1);
		}
			
		document.getElementById('cp_Vencimento').style.display		=	'none';
		
		switch(valor){
			case '1': //Em Contrato
				document.getElementById('titFormaCobranca').style.display						= "block";
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "block";
				document.getElementById("titLocalCobranca").style.display						= "none";
				document.getElementById("titContrato").style.display							= "block";
				document.getElementById('titValorDespesas').style.display						= "none";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "block";
				document.getElementById("cpValorDespesa").style.display							= "none";
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
				document.getElementById('titQtdParcela').style.display							= "block";
				document.getElementById('cpQtdParcela').style.display							= "block";
				document.getElementById('cpFormaCobranca').style.display						= "block";
				document.getElementById('cpLocalCobranca').style.display						= "none";
				document.getElementById('cpContrato').style.display								= "block";
				document.getElementById("sepcpValorDespesa").style.display						= "none";
				document.getElementById('septitValorDespesas').style.display					= "none";
				document.getElementById("septitLocalCobranca").style.display					= "none";
				document.getElementById('sepcpLocalCobranca').style.display						= "none";

				listar_contrato(document.formulario.IdPessoa.value,document.formulario.IdContrato.value );				
				
				if(document.formulario.DataPrimeiroVencimentoContrato.value == ""){
					var dte 	= new Date();
					var month 	= (dte.getMonth() + 1);
					var year 	= dte.getFullYear();
					
					month	=	month + 1;
					
					if(month > 12){
						month	=	1;
						year	=	year+1;
					}
					
					if(month < 10){	month	=	'0'+1;	}
					
					document.formulario.DataPrimeiroVencimentoContrato.value = month+"/"+year;	
				}
		
				document.formulario.bt_confirmar.disabled	=	true;
				document.formulario.bt_alterar.disabled		=	true;
				
				if(document.formulario.IdStatus == 5){
					document.formulario.bt_simular.disabled		=	true;
				}else{
					document.formulario.bt_simular.disabled		=	false;
				}
				break;
			case '2': //Individual			
				document.getElementById('titFormaCobranca').style.display						= "block";	
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById("titLocalCobranca").style.display						= "block";
				document.getElementById("titContrato").style.display							= "none";
				document.getElementById('titValorDespesas').style.display						= "block";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById("cpValorDespesa").style.display							= "block";
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "block";
				document.getElementById('titQtdParcela').style.display							= "block";
				document.getElementById('cpQtdParcela').style.display							= "block";
				document.getElementById('cpLocalCobranca').style.display						= "block";
				document.getElementById('cpContrato').style.display								= "none";
				document.getElementById('cpFormaCobranca').style.display						= "block";
				document.getElementById("sepcpValorDespesa").style.display						= "block";
				document.getElementById('septitValorDespesas').style.display					= "block";
				document.getElementById("septitLocalCobranca").style.display					= "block";
				document.getElementById('sepcpLocalCobranca').style.display						= "block";
				
				document.formulario.bt_confirmar.disabled	=	true;
				document.formulario.bt_alterar.disabled		=	true;
				
				
				if(document.formulario.IdStatus == 5){
					document.formulario.bt_simular.disabled		=	true;
				}else{
					document.formulario.bt_simular.disabled		=	false;
				}
				break;
			default:
				document.getElementById('titFormaCobranca').style.display						= "block";
				document.getElementById('titDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById("titLocalCobranca").style.display						= "none";
				document.getElementById("titContrato").style.display							= "none";
				document.getElementById('titValorDespesas').style.display						= "none";
				document.getElementById('cpDataPrimeiroVencimentoContrato').style.display		= "none";
				document.getElementById('cpFormaCobranca').style.display						= "block";
				document.getElementById("cpValorDespesa").style.display							= "none";
				document.getElementById('cpDataPrimeiroVencimento').style.display				= "none";
				document.getElementById('titQtdParcela').style.display							= "none";
				document.getElementById('cpQtdParcela').style.display							= "none";
				document.getElementById('cpLocalCobranca').style.display						= "none";
				document.getElementById('cpContrato').style.display								= "none";
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
			valor	=	valor.replace('.','');	
			valor	=	valor.replace('.','');	
			valor	=	valor.replace(',','.');

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
						Valor	=	Valor.replace('.','');	
						Valor	=	Valor.replace('.','');	
						Valor	=	Valor.replace(',','.');	
							
						Despesa	=	document.formulario.ValorDespesaLocalCobranca.value;
						Despesa	=	new String(Despesa);
						Despesa	=	Despesa.replace('.','');	
						Despesa	=	Despesa.replace('.','');	
						Despesa	=	Despesa.replace(',','.');
							
						Total	=	parseFloat(Valor) + parseFloat(Despesa);
							
						if(Total < document.formulario.ValorCobrancaMinima.value){
							mensagens(80);
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
				
				tabindex =	10 * i;
				
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
				
					c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+i+"' value='"+data+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+">";
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
				break
			case '2': //Individual
				QtdParcela	=	document.formulario.QtdParcela.value;
				valorT		=	document.formulario.ValorTotal.value;
				tam			=	5;
				
				desp	=	document.formulario.ValorDespesaLocalCobranca.value;
				desp	=	new String(desp);
				desp	=	desp.replace('.','');
				desp	=	desp.replace('.','');
				desp	=	desp.replace(',','.');
				break
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
						var valorTotal=0,despTotal=0,percTotal=0,totalTotal=0, valor, perc, desc, total, QtdParcela;
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

									if(document.formulario.IdStatus.value != 5 && document.formulario.IdStatus.value != 0){
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
								
								tabindex =	10 * i;
								
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
										document.getElementById('tabValorDesp').style.display		=	'none';
										document.getElementById('totalValorDespesa').style.display	=	'none';
										document.getElementById('tableDataVenc').innerHTML			=	'Mês Referência';
									}
								}
								
								valorT	=	ValorTotal;
								
								valor	=	parseFloat(Valor) / QtdParcela;
								perc	=	(100 * parseFloat(Valor))/parseFloat(valorT);
								total	=	parseFloat(Valor) + parseFloat(ValorDespesaLocalCobranca);
								
								valorTotal	+=	parseFloat(Valor);
								percTotal	+=  parseFloat(perc);
								
								if(FormaCobranca == 2){
									despTotal	+=  parseFloat(ValorDespesaLocalCobranca);
								}
								
								totalTotal	+= 	parseFloat(total);
								
								if(document.formulario.IdStatus.value == 0 || document.formulario.IdStatus.value == 2){
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
									
									c4.innerHTML = "<input style='margin:0; width: 100px' name='parcData_"+IdOrdemServicoParcela+"' value='"+Vencimento+"' maxlength='10' onFocus=\"Foco(this,'in')\"  onBlur=\"Foco(this,'out')\" onkeypress=\"mascara(this,event,'mes')\" tabindex="+(tabindex+3)+" "+readOnly+">";
								}
							}
							document.getElementById('totalVencimentos').innerHTML		=	"Total: "+(i);
							document.getElementById('totalValor').innerHTML				=	formata_float(Arredonda(valorTotal,2),2).replace(".",",");
							document.getElementById('totalPercentual').innerHTML		=	formata_float(Arredonda(percTotal,2),2).replace(".",",");
							
							if(FormaCobranca == 2){
								document.getElementById('totalValorDespesa').innerHTML		=	formata_float(Arredonda(despTotal,2),2).replace(".",",");
							}
							
							document.getElementById('totalValorTotal').innerHTML		=	formata_float(Arredonda(totalTotal,2),2).replace(".",",");
						}
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
			document.getElementById('cpContrato').style.display								= "none";
			
			while(document.getElementById('tabelaVencimento').rows.length > 2){
				document.getElementById('tabelaVencimento').deleteRow(1);
			}
			document.getElementById('cp_Vencimento').style.display	=	'none';
			
			document.formulario.bt_simular.disabled	=	true;
			
			if(document.formulario.IdStatus.value != 0 && document.formulario.IdStatus.value != 5){
				document.formulario.bt_alterar.disabled	=	false;
			}
		}else{
			busca_forma_cobranca(document.formulario.FormaCobranca.value);
		}
	}
	function voltar(){
		window.location.replace("cadastro_ordem_servico.php?IdOrdemServico="+document.formulario.IdOrdemServico.value);
	}
