	function mensagem(n,VarAux){
		if(n == 0 || n==''){
			return false;
		}
		
		if(VarAux == undefined){
			VarAux = ""; 
		}
		
	    var url = "xml/parametro_sistema.php?IdGrupoParametroSistema=99&IdParametroSistema="+n;

		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var msg = nameTextNode.nodeValue;
				
				if(VarAux!=""){
					msg	=	msg.replace('Valor',VarAux);
				}
			
				alert(msg);
			}
		}, false);
	}
	function busca_estado_cda(IdPais,IdEstadoTemp,IdCidadeTemp){
		if(IdPais=="") IdPais=0;
		if(IdEstadoTemp==undefined) IdEstadoTemp="";
		if(IdCidadeTemp==undefined) IdCidadeTemp="";
		
		var url = "xml/estado.php?IdPais="+IdPais;
		
		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdEstado.options.length > 0){
					document.formulario.IdEstado.options[0] = null;
				}
				while(document.formulario.IdCidade.options.length > 0){
					document.formulario.IdCidade.options[0] = null;
				}
			}else{
				var IdEstado, NomeEstado;
				while(document.formulario.IdEstado.options.length > 0){
					document.formulario.IdEstado.options[0] = null;
				}
					
				addOption(document.formulario.IdEstado,"","");
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdEstado").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdEstado = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeEstado = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdEstado,NomeEstado,IdEstado);
				}
				if(IdEstadoTemp==""){
					document.formulario.IdEstado.options[0].selected = true;
				}else{
					for(var i=0; i<document.formulario.IdEstado.length; i++){
						if(document.formulario.IdEstado.options[i].value == IdEstadoTemp){
							document.formulario.IdEstado.options[i].selected	=	true;
							i = document.formulario.IdEstado.length;
						}
					}
				}
				if(IdCidadeTemp==""){
					while(document.formulario.IdCidade.options.length > 0){
						document.formulario.IdCidade.options[0] = null;
					}
				}else{
					busca_cidade_cda(IdPais,IdEstadoTemp,IdCidadeTemp)
				}
			}
		});
	}
	function busca_cidade_cda(IdPais,IdEstado,IdCidadeTemp){
		if(IdPais=="") IdPais=0;
		if(IdEstado=="" || IdEstado==undefined) IdEstado=0;
		if(IdCidadeTemp==undefined) IdCidadeTemp="";
		
		var url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado;
		
		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText == 'false'){
				while(document.formulario.IdCidade.options.length > 0){
					document.formulario.IdCidade.options[0] = null;
				}
			}else{
				var IdCidade, NomeCidade;
				while(document.formulario.IdCidade.options.length > 0){
					document.formulario.IdCidade.options[0] = null;
				}
					
				addOption(document.formulario.IdCidade,"","");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeCidade = nameTextNode.nodeValue;
					
					addOption(document.formulario.IdCidade,NomeCidade,IdCidade);
				}
				if(IdCidadeTemp == ""){
					document.formulario.IdCidade.options[0].selected = true;
				}else{
					for(var i=0; i<document.formulario.IdCidade.length; i++){
						if(document.formulario.IdCidade.options[i].value == IdCidadeTemp){
							document.formulario.IdCidade.options[i].selected	=	true;
							i = document.formulario.IdCidade.length;
						}
					}
				}
			}
		});
	}
	function mensagem_especial(IdGrupoParametroSistema,IdParametroSistema){
		if(IdGrupoParametroSistema == 0 || IdGrupoParametroSistema==''){
			return false;
		}
		
		if(IdParametroSistema == 0 || IdParametroSistema==''){
			return false;
		}
		
	    var url = "xml/parametro_sistema.php?IdGrupoParametroSistema="+IdGrupoParametroSistema+"&IdParametroSistema="+IdParametroSistema;
		
		call_ajax(url, function (xmlhttp) {
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var NomeCampo = nameTextNode.nodeValue;
			
				alert("Atenção!\n"+NomeCampo+" - Campo obrigatorio.");
			}
		});
	}
	function busca_cep(CEP){
		if(CEP == ''){
			CEP = 0;
		}
		if(atualizar() == true){
		   	var url = "xml/cep.php?CEP="+CEP;
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != 'false'){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPais")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdPais = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdEstado")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdEstado = nameTextNode.nodeValue;
			
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdCidade = nameTextNode.nodeValue;					
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Endereco")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Endereco = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("Bairro")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Bairro = nameTextNode.nodeValue;
					
					document.formulario.Endereco.value 		= Endereco;
					document.formulario.Bairro.value 		= Bairro;
					document.formulario.IdPais.value 		= IdPais;
					
					busca_estado_cda(IdPais,IdEstado,IdCidade);
				}
			});
		}
	}
	function atualizar(){
		return confirm("ATENCAO!\n\nDeseja atualizar endereço?","SIM","NAO");
	}
	function servico_periodicidade_parcelas(IdLoja,IdServico,IdPeriodicidade,Temp){
		if(Temp == undefined)
			Temp = "";
		
		while(document.formulario.QtdParcela.options.length > 0){
			document.formulario.QtdParcela.options[0] = null;
		}
		
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(IdPeriodicidade != ""){
		   	var url = "./xml/servico_periodicidade.php?IdLoja="+IdLoja+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode;					
					addOption(document.formulario.QtdParcela,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdParcela = nameTextNode.nodeValue;
						
						addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
					}
					if(i==1){
						document.formulario.QtdParcela[1].selected = true;
						QtdParcela	=	document.formulario.QtdParcela[1].value;
						
						busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp);
					}else{
						document.formulario.QtdParcela[0].selected = true;
					}
				}else{
					contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
				}
			});
		}
	}
	function busca_servico_tipo_contrato(IdServico,IdPeriodicidade,QtdParcela,Temp){
		if(Temp == undefined) 
			Temp = "";
		
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		var nameNode, nameTextNode, url;
		
		if(QtdParcela != ''){
		    var url = "./xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela;			
			call_ajax(url, function (xmlhttp) { 
				if(xmlhttp.responseText != 'false'){
					var nameNode, nameTextNode, DescTipoContrato, TipoContrato;					
					addOption(document.formulario.TipoContrato,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("TipoContrato").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						TipoContrato = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoContrato = nameTextNode.nodeValue;
						
						addOption(document.formulario.TipoContrato,DescTipoContrato,TipoContrato);
					}
					
					if(i == 1){
						document.formulario.TipoContrato[1].selected		=	true;
						TipoContrato	=	document.formulario.TipoContrato[1].value;
						
						busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp);
					}else{
						document.formulario.TipoContrato[0].selected = true;
					}
				}else{
					contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
				}
			});
		}
	}
	function busca_servico_local_cobranca(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,Temp){
		if(Temp == undefined)
			Temp = "";
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(TipoContrato!=""){
			var url = "xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&Local=Contrato";
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdLocalCobranca, DescricaoLocalCobranca, IdTipoLocalCobranca;					
					
					addOption(document.formulario.IdLocalCobranca,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoLocalCobranca = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdLocalCobranca,"","");
						addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca,Temp);
					}
					
					if(i==1){
						document.formulario.IdLocalCobranca[1].selected		=	true;	
						IdLocalCobranca	=	document.formulario.IdLocalCobranca[1].value;
						
						busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca);
					}else{
						document.formulario.IdLocalCobranca[0].selected = true;
					}
				}else{
					contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
				}
			});
		}
	}
	function busca_servico_mes_fechado(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,Temp){
		if(Temp == undefined) 
			Temp = "";
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(IdLocalCobranca != ''){
			var url = "xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&IdLocalCobranca="+IdLocalCobranca+"&Local=Contrato";
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
			
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, MesFechado, DescricaoLocalCobranca, QtdMesesFidelidade;					
					
					addOption(document.formulario.MesFechado,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("MesFechado").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MesFechado = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoMesFechado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdMesesFidelidade = nameTextNode.nodeValue;
						
						addOption(document.formulario.MesFechado,DescricaoMesFechado,MesFechado);
					}
					
					if(i==1){
						document.formulario.MesFechado[1].selected  	= true;							
						
						if(document.formulario.QtdMesesFidelidadeTemp != undefined){
							if(document.formulario.QtdMesesFidelidadeTemp.value == ''){
								document.formulario.QtdMesesFidelidadeTemp.value	=	QtdMesesFidelidade;
							}else{
								document.formulario.QtdMesesFidelidadeTemp.value = '';
							}
						}
					}else{
						document.formulario.MesFechado[0].selected = true;
					}
				}else{
					contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidadeTemp.value);
				}
			});
		}
	}
	function contrato_periodicidade_parcela(QtdParcela){
		var temp	=	0;
		for(i=0;i<document.formulario.QtdParcela.options.length;i++){
			if(document.formulario.QtdParcela[i].value == QtdParcela){
				document.formulario.QtdParcela[i].selected	=	true;
				temp++;
				break;
			}
		}
		if(temp == 0){
			if(document.formulario.QtdParcela.options.length == 0){
				addOption(document.formulario.QtdParcela,"","");
			}
			
			addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
			contrato_tipo_contrato(document.formulario.TipoContratoTemp.value);
		}
	}
	function contrato_tipo_contrato(TipoContrato){
		var temp	=	0;
		
		for(i=0;i<document.formulario.TipoContrato.options.length;i++){
			if(document.formulario.TipoContrato[i].value == TipoContrato){
				document.formulario.TipoContrato[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
			var url = "./xml/parametro_sistema.php?IdGrupoParametroSistema=28&IdParametroSistema="+TipoContrato;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdParametroSistema;
					
					if(document.formulario.TipoContrato.options.length == 0){
						addOption(document.formulario.TipoContrato,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;

					addOption(document.formulario.TipoContrato,ValorParametroSistema,IdParametroSistema);
					contrato_local_cobranca(document.formulario.IdLocalCobrancaTemp.value);
				}
			});
		}	
	}
	function contrato_local_cobranca(IdLocalCobranca){
		var temp	=	0;
		
		for(i=0;i<document.formulario.IdLocalCobranca.options.length;i++){
			if(document.formulario.IdLocalCobranca[i].value == IdLocalCobranca){
				document.formulario.IdLocalCobranca[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
		    var url = "./xml/local_cobranca.php?IdLoja="+document.formulario.IdLoja.value+"&IdLocalCobranca="+IdLocalCobranca;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdLocalCobranca;
					
					if(document.formulario.IdLocalCobranca.options.length == 0){
						addOption(document.formulario.IdLocalCobranca,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobranca = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[0]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalCobranca = nameTextNode.nodeValue;

					addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
					contrato_mes_fechado(document.formulario.MesFechadoTemp.value,document.formulario.QtdMesesFidelidadeTemp.value);
				}
			});
		}	
	}
	function contrato_mes_fechado(MesFechado,QtdMesesFidelidade){
		var temp	=	0;
		
		for(i=0;i<document.formulario.MesFechado.options.length;i++){
			if(document.formulario.MesFechado[i].value == MesFechado){
				document.formulario.MesFechado[i].selected	=	true;
				temp++;
				break;
			}
		}
		
		if(temp == 0){
			var url = "./xml/parametro_sistema.php?IdGrupoParametroSistema=70&IdParametroSistema="+MesFechado;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdPeriodicidade;
					
					if(document.formulario.MesFechado.options.length == 0){
						addOption(document.formulario.MesFechado,"","");
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdParametroSistema = nameTextNode.nodeValue;

					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
					nameTextNode = nameNode.childNodes[0];
					ValorParametroSistema = nameTextNode.nodeValue;

					addOption(document.formulario.MesFechado,ValorParametroSistema,IdParametroSistema);
					
					document.formulario.QtdMesesFidelidadeTemp.value	=	QtdMesesFidelidade;
				}
			});
		}	
	}
	function calculaPeriodicidade(IdPeriodicidade,valor,campo){
		if(campo == '' || campo == undefined){
			campo	=	document.formulario.ValorPeriodicidade;
		}
		
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace(/\./g,'');
				valor = valor.replace(/,/i,'.');
			}
			
			valor = parseFloat(valor);
		    var url = "./xml/periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != 'false'){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Fator = nameTextNode.nodeValue;
					
					campo.value = valor*parseInt(Fator);
					campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
				}
			});
		}else{
			if(campo == ''){
				document.formulario.ValorServico.value 		 = '0,00';
/*				document.formulario.ValorDesconto.value		 = '0,00';
				document.formulario.ValorFinal.value		 = '0,00';*/
				document.formulario.ValorPeriodicidade.value = '0,00';
			}
		}
	}
	function calculaPeriodicidadeTerceiro(IdPeriodicidade,valor,campo,IdLoja){
		if(campo == '' || campo == undefined){
			campo	=	document.formulario.ValorPeriodicidadeTerceiro;
		}
		
		if(valor != ''){
			if(valor.indexOf(",") != -1){	
				valor = valor.replace(/\./g,'');
				valor = valor.replace(/,/i,'.');
			}
			
			valor = parseFloat(valor);
		   	var url = "xml/periodicidade.php?IdLoja="+IdLoja+"&IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;

				if(xmlhttp.responseText != 'false'){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Fator = nameTextNode.nodeValue;
					
					campo.value = valor*parseInt(Fator);
					campo.value = formata_float(Arredonda(campo.value,2),2).replace(".",",");
				}
			});
		}else{
			if(campo == ''){
				document.formulario.ValorServico.value 		 = '0,00';
				document.formulario.ValorPeriodicidade.value = '0,00';
			}
		}
	}
	function calculaServicoAutomatico(IdPeriodicidade){	
		if(document.formulario.ServicoAutomatico.value != ""){
			var posIni = 0, posFim = 0;
			
			for(ii=0;ii<document.formulario.length;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					if(posIni == 0){
						posIni	=	ii;
					}
					
					posFim	=	ii;
				}
			}
			
			for(ii=posIni;ii<=posFim;ii++){
				if(document.formulario[ii].name.substring(0,10)	==	"IdServico_"){
					calculaPeriodicidade(document.formulario.IdPeriodicidade.value,document.formulario[ii+2].value,document.formulario[ii+3]);
				}
			}
		}
	}
	function busca_dia_cobranca(DiaCobrancaDefault){
		if(DiaCobrancaDefault == undefined){
			DiaCobrancaDefault = 0;
		}
		
	    var url = "./xml/dia_cobranca.php?IdLoja="+document.formulario.IdLoja.value;
		
		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.DiaCobranca.options.length > 0){
					document.formulario.DiaCobranca.options[0] = null;
				}
				
				var nameNode, nameTextNode;					
				
				addOption(document.formulario.DiaCobranca,"","0");
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorCodigoInterno = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoCodigoInterno")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoCodigoInterno = nameTextNode.nodeValue;
				
					addOption(document.formulario.DiaCobranca,DescricaoCodigoInterno,ValorCodigoInterno);
				}
				
				for(i = 0; i < document.formulario.DiaCobranca.length; i++){
					if(document.formulario.DiaCobranca[i].value == DiaCobrancaDefault){
						document.formulario.DiaCobranca[i].selected	= true;
						document.formulario.DiaCobrancaTemp.value = DiaCobrancaDefault;
						break;
					}
				}
			}else{
				while(document.formulario.DiaCobranca.options.length > 0){
					document.formulario.DiaCobranca.options[0] = null;
				}
			}
		});
	}
	function avalia_atendimento(Nota, IdOrdemServico) {
		var ult;
		
		for(var i = 1; i < 6; i++) {
			if((/[\w\W]*(ico_estrela.gif)/).test(document.getElementById("nota_"+i+"_"+IdOrdemServico).src)) {
				ult = i;
			}
			
			document.getElementById("nota_"+i+"_"+IdOrdemServico).src = "../../img/estrutura_sistema/ico_estrela_c.gif";
		}
		
		if(ult == Nota) {
			Nota = null;
		}
		
		var url = "xml/avalia_atendimento.php?IdOrdemServico="+IdOrdemServico+"&Nota="+Nota;
		
		call_ajax(url, function (xmlhttp) {
			if(Number(xmlhttp.responseText) == 1) {
				for(i = 1; i <= Number(Nota); i++) {
					document.getElementById("nota_"+i+"_"+IdOrdemServico).src = "../../img/estrutura_sistema/ico_estrela.gif";
				}
			}
		});
	}
	
	function habilitar_butao_salvar(campos){
		if(campos.checked){
			document.formulario.bt_salvar.disabled = false;
			document.formulario.ContratoAssinado.value = 1;
		}else{
			document.formulario.bt_salvar.disabled = true;
			document.formulario.ContratoAssinado.value = 2;
		}
	}
	function busca_servico_tipo_contrato_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContratoTemp,IdLocalCobranca,QTDmes,Temp){
		if(Temp == undefined) 
			Temp = "";
		
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		var nameNode, nameTextNode, url;
		
		if(QtdParcela != ''){
		    var url = "./xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela;
			
			call_ajax(url, function (xmlhttp) { 
				if(xmlhttp.responseText != 'false'){
					var nameNode, nameTextNode, DescTipoContrato, TipoContrato;					
					addOption(document.formulario.TipoContrato,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("TipoContrato").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						TipoContrato = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoContrato = nameTextNode.nodeValue;
						
						addOption(document.formulario.TipoContrato,DescTipoContrato,TipoContrato,Temp);
					}
				
					for(i = 0; i < document.formulario.TipoContrato.length; i++){
						if(document.formulario.TipoContrato[i].value == TipoContratoTemp){
							document.formulario.TipoContrato[i].selected	= true;
							document.formulario.TipoContratoTemp.value = TipoContratoTemp;
							break;
						}
					}
					busca_servico_local_cobranca_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContratoTemp,IdLocalCobranca,QTDmes,Temp);
				}else{
					while(document.formulario.TipoContrato.options.length > 0){
						document.formulario.TipoContrato.options[0] = null;
					}
				}
			});
		}
	}
	function busca_servico_local_cobranca_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobrancaTemp,QTDmes,Temp){
		if(Temp == undefined)
			Temp = "";
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(TipoContrato!=""){
			var url = "xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&Local=Contrato";
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
				
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, IdLocalCobranca, DescricaoLocalCobranca, IdTipoLocalCobranca;					
					
					addOption(document.formulario.IdLocalCobranca,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						IdTipoLocalCobranca = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoLocalCobranca = nameTextNode.nodeValue;
						
						addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca,Temp);
					}
					
					for(i = 0; i < document.formulario.IdLocalCobranca.length; i++){
						if(document.formulario.IdLocalCobranca[i].value == IdLocalCobrancaTemp){
							document.formulario.IdLocalCobranca[i].selected	= true;
							document.formulario.IdLocalCobrancaTemp.value = IdLocalCobrancaTemp;
							break;
						}
					}
					busca_servico_mes_fechado_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobrancaTemp,QTDmes);
				}else{
					while(document.formulario.IdLocalCobranca.options.length > 0){
						document.formulario.IdLocalCobranca.options[0] = null;
					}
				}
			});
		}
	}
	function busca_servico_mes_fechado_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,QTDmes,Temp){
		if(Temp == undefined) 
			Temp = "";
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(IdLocalCobranca != ''){
			var url = "xml/servico_periodicidade.php?IdLoja="+document.formulario.IdLoja.value+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade+"&QtdParcela="+QtdParcela+"&TipoContrato="+TipoContrato+"&IdLocalCobranca="+IdLocalCobranca+"&Local=Contrato";
			
			call_ajax(url, function (xmlhttp) {
				var nameNode, nameTextNode;
			
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode, MesFechado, DescricaoLocalCobranca, QtdMesesFidelidade;					
					
					addOption(document.formulario.MesFechado,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("MesFechado").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						MesFechado = nameTextNode.nodeValue;

						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMesFechado")[i]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoMesFechado = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdMesesFidelidade = nameTextNode.nodeValue;
						
						addOption(document.formulario.MesFechado,DescricaoMesFechado,MesFechado,Temp);
					}
					
					for(i = 0; i < document.formulario.MesFechado.length; i++){
						if(document.formulario.MesFechado[i].value == QTDmes){
							document.formulario.MesFechado[i].selected	= true;
							document.formulario.MesFechadoTemp.value = QTDmes;
							break;
						}
					}
				}else{
					while(document.formulario.MesFechado.options.length > 0){
						document.formulario.MesFechado.options[0] = null;
					}
				}
			});
		}
	}
	function servico_periodicidade_parcelas_visualiza(IdLoja,IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,QTDmes,Temp){
		if(Temp == undefined)
			Temp = "";
		
		while(document.formulario.QtdParcela.options.length > 0){
			document.formulario.QtdParcela.options[0] = null;
		}
		
		while(document.formulario.TipoContrato.options.length > 0){
			document.formulario.TipoContrato.options[0] = null;
		}
		
		while(document.formulario.IdLocalCobranca.options.length > 0){
			document.formulario.IdLocalCobranca.options[0] = null;
		}
		
		while(document.formulario.MesFechado.options.length > 0){
			document.formulario.MesFechado.options[0] = null;
		}
		
		if(IdPeriodicidade != ""){
		   	var url = "./xml/servico_periodicidade.php?IdLoja="+IdLoja+"&IdServico="+IdServico+"&IdPeriodicidade="+IdPeriodicidade;
			
			call_ajax(url, function (xmlhttp) {
				if(xmlhttp.responseText != 'false'){		
					var nameNode, nameTextNode;					
					addOption(document.formulario.QtdParcela,"","");
					
					for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
						nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
						nameTextNode = nameNode.childNodes[0];
						QtdParcela = nameTextNode.nodeValue;
						
						addOption(document.formulario.QtdParcela,QtdParcela,QtdParcela);
					}
					if(i==1){
						document.formulario.QtdParcela[1].selected = true;
						QtdParcela	=	document.formulario.QtdParcela[1].value;
						
						busca_servico_tipo_contrato_visualiza(IdServico,IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,QTDmes,Temp);
					}else{
						document.formulario.QtdParcela[0].selected = true;
					}
				}else{
					contrato_periodicidade_parcela(document.formulario.QuantParcela.value);
				}
			});
		}
	}