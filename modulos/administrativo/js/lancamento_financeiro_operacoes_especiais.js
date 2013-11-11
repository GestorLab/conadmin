	function excluir(IdLancamentoFinanceiro,IdStatus){
		if(IdLancamentoFinanceiro!='' && (IdStatus == 2 || IdStatus == 3)){
			window.location.replace('cadastro_cancelar_lancamento_financeiro.php?IdLancamentoFinanceiro='+IdLancamentoFinanceiro);
		}
	}
	function validar(){
		if(document.formulario.IdLancamentoFinanceiro.value==''){
			mensagens(1);
			document.formulario.IdLancamentoFinanceiro.focus();
			alert(0);
			return false;
		}
		if(document.getElementById("cpContrato").style.display == "block"){
			if(!validar_Data("titDataReferenciaInicial", document.formulario.DataReferenciaInicial)){
				if(document.formulario.Erro.value == 0){
					mensagens(1);
					document.formulario.DataReferenciaInicial.focus();
				}
				
				return false;
			}
			if(!validar_Data("titDataReferenciaFinal", document.formulario.DataReferenciaFinal)){
				if(document.formulario.Erro.value == 0){
					mensagens(1);
					document.formulario.DataReferenciaFinal.focus();
				}
				
				return false;
			}
		}
		if(document.formulario.ValorDescontoAConceber.value==''){
			mensagens(1);
			document.formulario.ValorDescontoAConceber.focus();
			return false;
		}
		if(document.formulario.PercentualDesconto.value==''){
			mensagens(1);
			document.formulario.PercentualDesconto.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdLancamentoFinanceiro.focus();
	}
	function verificaAcao(){
		if(document.formulario.IdStatus.value == 2 || document.formulario.IdStatus.value == 3){ //Aguardando Cobranca
			document.formulario.bt_cancelar.disabled = false;
		}else{
			document.formulario.bt_cancelar.disabled = true;
		}
		
//		alert(document.formulario.IdContaReceber.value+" ==  || "+document.formulario.IdStatusContaReceber.value+" != 2");
		if(document.formulario.IdContaReceber.value == "" || document.formulario.IdStatusContaReceber.value != 2){
			document.formulario.bt_alterar.disabled = false;
		}else{
			document.formulario.bt_alterar.disabled = true;
		}
		
//		alert(document.formulario.IdStatus.value+" == 0");
		if(document.formulario.IdStatus.value == 0){ //Cancelado
			document.formulario.bt_alterar.disabled  = true;
		}else{
			document.formulario.bt_alterar.disabled  = false;
		}
	}
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataReferenciaInicial':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'DataReferenciaFinal':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
				break;
			case 'IdContaReceber':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdContrato':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			case 'IdProcessoFinanceiro':
				campo.maxLength	=	11;
				return mascara(campo,event,'int');
				break;
			default:
				campo.maxLength	=	100;
		}
	}
	function calculaValorFinal(valor,desc,perc,campo){
		if(valor=='' || desc == '' || perc==''){
			if(valor=='')	valor = '0,00';
			if(desc=='')	desc  = '0,00';
			if(perc=='')	perc  = '0,00';
		}
		var tempValor	=	valor.replace(".","");
		tempValor		=	tempValor.replace(".","");
		tempValor		=	tempValor.replace(",",".");
		
		var tempDesc	=	desc.replace("."," ");
		tempDesc		=	tempDesc.replace("."," ");
		tempDesc		=	tempDesc.replace(",",".");
		
		var valFinal	=	tempValor - tempDesc;
		
		if(campo.name == 'ValorDescontoAConceber'){
			tempPerc = (parseFloat(tempDesc)*100)/parseFloat(tempValor);
			
			tempPerc		= 	formata_float(Arredonda(tempPerc,2),2);
			tempPerc		=	tempPerc.replace('.',',');
			
			document.formulario.PercentualDesconto.value	=	tempPerc;
		}else if(campo.name == 'PercentualDesconto'){
			var tempPerc	=	perc.replace("."," ");
			tempPerc		=	tempPerc.replace("."," ");
			tempPerc		=	tempPerc.replace(",",".");
		
			tempDesc		=	(parseFloat(tempPerc)*parseFloat(tempValor))/100;
			valFinal		=	tempValor -	tempDesc;
			
			tempDesc		= 	formata_float(Arredonda(tempDesc,2),2);
			tempDesc		=	tempDesc.replace('.',',');
			
			document.formulario.ValorDescontoAConceber.value	=	tempDesc;
		}
		
		valFinal		= 	formata_float(Arredonda(valFinal,2),2);
		valFinal		=	valFinal.replace('.',',');
		
		document.formulario.ValorFinal.value	=	valFinal;
	}
	
	function cadastrar(acao){
		if(validar()==true){
			document.formulario.Acao.value	=	acao;
			document.formulario.submit();
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
	function listar_contrato_individual(IdPessoa,IdContratoTemp){
		if(IdPessoa == ''){
			while(document.formulario.IdContratoAgrupador.options.length > 0){
				document.formulario.IdContratoAgrupador.options[0] = null;
			}
			return false;
		}
		if(IdContratoTemp == undefined){
			IdContratoTemp = '';
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
		
		var url = "xml/contrato.php?IdPessoa="+IdPessoa+"&IdStatusExc=1";
		
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
						if(IdContratoTemp!=''){
							for(ii=0;ii<document.formulario.IdContratoAgrupador.length;ii++){
								if(document.formulario.IdContratoAgrupador[ii].value == IdContratoTemp){
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

		url = "xml/parametro_sistema.php?IdGrupoParametroSistema=51&IdParametroSistema="+IdStatusTemp;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 

			// Carregando...
			carregando(true);

			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					//alert(xmlhttp.responseText);
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
	function validar_data_referencia(campo_DataReferenciaIncio, campo_DataReferenciaFim){
		var temp = campo_DataReferenciaIncio.value.split("/");
		var DataReferenciaIncio = temp[2] + temp[1] + temp[0];
		temp = campo_DataReferenciaFim.value.split("/");
		var DataReferenciaFim = temp[2] + temp[1] + temp[0];
		
		if(DataReferenciaIncio > DataReferenciaFim){
			campo_DataReferenciaIncio.focus();
			document.formulario.Erro.value = 139;
			
			verificaErro();
			return false;
		} else{
			document.formulario.Erro.value = 0;
			
			verificaErro();
			return true;
		}
	}
	function validar_Data(id, campo){
		if(campo.value == ''){
			document.getElementById(id).style.color = "#C10000";
			document.getElementById(id).style.backgroundColor='#FFF';
			document.formulario.Erro.value = 0;
			mensagens(0);
			return false;
		}
		
		if(isData(campo.value)){
			document.getElementById(id).style.color = "#CC0000";
			document.getElementById(id).style.backgroundColor='#FFF';
			
			if(validar_data_referencia(document.formulario.DataReferenciaInicial, document.formulario.DataReferenciaFinal)){
				return true;
			} else{
				return false;
			}
		} else{
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFF';
			document.formulario.Erro.value = 27;
			mensagens(27);
			return false;
		}
	}
	function busca_pessoa_aproximada(campo,event){
		var url = "xml/pessoa_nome.php?Nome="+campo.value;
		
		call_ajax(url,function (xmlhttp){
			var NomeDefault = new Array(), nameNode, nameTextNode;
			
			if(campo.value != '' && xmlhttp.responseText != "false"){
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("NomeDefault").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeDefault")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeDefault[i] = nameTextNode.nodeValue;
				}
			}
			
			busca_aproximada('filtro',campo,event,NomeDefault,22,5);
		},false);
	}
