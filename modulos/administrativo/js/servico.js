	function excluir(IdServico){
		if(IdServico== '' || undefined){
			IdServico = document.formulario.IdServico.value;
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
    
   			url = "files/excluir/excluir_servico.php?IdServico="+IdServico;
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
								url = 'cadastro_servico.php?Erro='+document.formulario.Erro.value;
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
									if(IdServico == document.getElementById('tableListar').rows[i].accessKey){
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
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	function validar(){
		if(document.formulario.IdTipoServico.value==''){
			mensagens(1);
			document.formulario.IdTipoServico.focus();
			return false;
		}
		if(document.formulario.DescricaoServico.value==''){
			mensagens(1);
			document.formulario.DescricaoServico.focus();
			return false;
		}
		if(document.formulario.Unidade.value==""){
			mensagens(1);
			document.formulario.Unidade.focus();
			return false;
		}		
		if(document.formulario.IdServicoGrupo.value==""){
			mensagens(1);
			document.formulario.IdServicoGrupo.focus();
			return false;
		}
		if(document.formulario.IdStatus.value==""){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		if(document.formulario.ExibirReferencia.value==""){
			mensagens(1);
			document.formulario.ExibirReferencia.focus();
			return false;
		}
		if((document.formulario.IdTipoServico.value == '2' || document.formulario.IdTipoServico.value == '3' ) && document.formulario.IdOrdemServicoLayout[0].selected == true){
			mensagens(1);
			document.formulario.IdOrdemServicoLayout.focus();
			return false;
		}
		if((document.formulario.IdTipoServico.value == '1' || document.formulario.IdTipoServico.value == '4') && document.formulario.IdFaturamentoFracionado.value == ''){
			mensagens(1);
			document.formulario.IdFaturamentoFracionado.focus();
			return false;
		}
		if(document.getElementById("cpDadosServicoImportacaoParametros").style.display == 'block' && document.formulario.IdServicoImportar_Obrigatorio.value == 1 && document.formulario.IdServicoImportar.value == ''){
			mensagens(1);
			document.formulario.IdServicoImportar.focus();
			return false;
		}
		if(document.formulario.ImportarMascaraVigencia.value == ""){
			mensagens(1);
			document.formulario.ImportarMascaraVigencia.focus();
			return false;
		}		
		if(document.formulario.ImportarParametro.value == ""){
			mensagens(1);
			document.formulario.ImportarParametro.focus();
			return false;
		}
		if(document.formulario.ImportarRotina.value == ""){
			mensagens(1);
			document.formulario.ImportarRotina.focus();
			return false;
		}
		if(document.formulario.ImportarAliquota.value == ""){
			mensagens(1);
			document.formulario.ImportarAliquota.focus();
			return false;
		}
		if(document.formulario.ImportarParametroNF.value == ""){
			mensagens(1);
			document.formulario.ImportarParametroNF.focus();
			return false;
		}
		if(document.formulario.ImportarCFOP.value == ""){
			mensagens(1);
			document.formulario.ImportarCFOP.focus();
			return false;
		}
		if(document.formulario.ImportarAgendamento.value == ""){
			mensagens(1);
			document.formulario.ImportarAgendamento.focus();
			return false;
		}		
		if(document.formulario.Acao.value == "inserir" && (document.formulario.IdServicoImportar.value == '' && !confirm("ATENÇÃO\n\nVocê não importou os parâmetros e as rotinas de outro serviço.\nDeseja continuar?"))){
			document.formulario.IdServicoImportar.focus();
			return false;
		}
		if(document.formulario.IdCentroCusto.value==""){
			mensagens(1);
			document.formulario.IdCentroCusto.focus();
			return false;
		}
		if(document.formulario.IdPlanoConta.value==""){
			mensagens(1);
			document.formulario.IdPlanoConta.focus();
			return false;
		}
		if(document.formulario.IdNotaFiscalTipo.value != 0 && document.formulario.IdCategoriaTributaria.value == 0){
			mensagens(1);
			document.formulario.IdCategoriaTributaria.focus();
			return false;
		}
		if(document.formulario.Acao.value=="inserir"){
			if(document.formulario.ValorInicial.value==""){
				mensagens(1);
				document.formulario.ValorInicial.focus();
				return false;
			}
		}
		if(document.formulario.IdTipoServico.value == 1){
			if(document.formulario.AtivacaoAutomatica.value==""){
				mensagens(1);
				document.formulario.AtivacaoAutomatica.focus();
				return false;
			}
			if(document.formulario.EmailCobranca.value==""){
				mensagens(1);
				document.formulario.EmailCobranca.focus();
				return false;
			}
		}
		if(document.formulario.ExecutarRotinas.disabled == false && document.formulario.ExecutarRotinas.value==""){
			mensagens(1);
			document.formulario.ExecutarRotinas.focus();
			return false;
		}
		if(document.formulario.IdTipoServico.value == '1' && document.formulario.Periodicidade.value == ''){
			mensagens(1);
			document.formulario.IdPeriodicidade.focus();
			return false;
		}
		if((document.formulario.IdTipoServico.value == '3' || document.formulario.IdTipoServico.value == '4') && document.formulario.ServicoAgrupador.value==""){
			mensagens(1);
			document.formulario.IdServicoAgrupador.focus();
			return false;
		}
/*		if(document.formulario.IdPessoa.value!=""){
			if(document.formulario.Acao.value=="inserir"){
				if(document.formulario.IdTipoServico.value != 2 && document.formulario.IdTipoServico.value != 3){
					if(document.formulario.ValorRepasseTerceiro.value==""){
						mensagens(1);
						document.formulario.ValorRepasseTerceiro.focus();
						return false;
					}
				} else{
					if(document.formulario.PercentualRepasseTerceiroOutros.value==""){
						mensagens(1);
						document.formulario.PercentualRepasseTerceiroOutros.focus();
						return false;
					}
				}
				if(document.formulario.PercentualRepasseTerceiro.value==""){
					mensagens(1);
					document.formulario.PercentualRepasseTerceiro.focus();
					return false;
				}
			}
			if(document.formulario.DetalheDemonstrativoTerceiro.value==0){
				mensagens(1);
				document.formulario.DetalheDemonstrativoTerceiro.focus();
				return false;
			}
		}*/
		
		if(document.formulario.SICIAtivoDefault.value == '1' && document.formulario.IdTipoServico.value == '1' && document.formulario.IdNotaFiscalTipo.value == '1'){
			if(Number(document.formulario.ColetarSICI.value) == 1){
				if(document.formulario.IdTecnologia.value == ''){
					mensagens(1);
					document.formulario.IdTecnologia.focus();
					return false;
				}
				
				if(document.formulario.IdDedicado.value == ''){
					mensagens(1);
					document.formulario.IdDedicado.focus();
					return false;
				}
				
				if(document.formulario.FatorMega.value == '' || Number((document.formulario.FatorMega.value.replace(/\./g, '')).replace(/\,/i, '')) == 0){
					mensagens(1);
					document.formulario.FatorMega.focus();
					return false;
				}
				
				if(document.formulario.IdGrupoVelocidade.value == ''){
					mensagens(1);
					document.formulario.IdGrupoVelocidade.focus();
					return false;
				}
			} /*else if(document.formulario.IdTecnologia.value != ''){
				if(document.formulario.IdDedicado.value == ''){
					mensagens(1);
					document.formulario.IdDedicado.focus();
					return false;
				}
				
				if(document.formulario.FatorMega.value == '' || Number((document.formulario.FatorMega.value.replace(/\./g, '')).replace(/\,/i, '')) == 0){
					mensagens(1);
					document.formulario.FatorMega.focus();
					return false;
				}
				
				if(document.formulario.IdGrupoVelocidade.value == ''){
					mensagens(1);
					document.formulario.IdGrupoVelocidade.focus();
					return false;
				}
			}*/
		}
		
		mensagens(0);
		return true;
	}
	function inicia(){
		status_inicial();
		document.formulario.IdServico.focus();
	}
	function addParametroDemonstrativo(IdServico,Erro){
		if(IdServico == ''){
			IdServico = 0;
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
		url = xmlhttp.open("GET", "xml/servico_parametro.php?IdServico="+IdServico); 
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){		
						while(document.formulario.ParametroDemonstrativo.options.length > 0){
							document.formulario.ParametroDemonstrativo.options[0] = null;
						}
						document.getElementById('cpParametroDemonstratito').style.color	=	'#000';
						
						document.getElementById('ParametroDemonstrativo').style.display	=	'none';
					}else{	
						var nameNode, nameTextNode, IdParametroServico, DescricaoParametroServico, ParametroDemonstrativo,temp='';					
						while(document.formulario.ParametroDemonstrativo.options.length > 0){
							document.formulario.ParametroDemonstrativo.options[0] = null;
						}
						
						document.getElementById('ParametroDemonstrativo').style.display	=	'block';						
						document.getElementById('cpParametroDemonstratito').style.color	=	'#C10000';
						
						addOption(document.formulario.ParametroDemonstrativo,"","0");
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdParametroServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ParametroDemonstrativo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ParametroDemonstrativo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroServico = nameTextNode.nodeValue;
							
							DescricaoParametroServico = DescricaoParametroServico.substr(0,20);
							
							addOption(document.formulario.ParametroDemonstrativo,DescricaoParametroServico,IdParametroServico);
							
							if(ParametroDemonstrativo == 1){
								temp = IdParametroServico;
							}
						}
						if(temp != ''){
							document.formulario.ParametroDemonstrativo[temp].selected = true;
						}else{
							document.formulario.ParametroDemonstrativo[0].selected = true;
						}
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function periodicidade_parcelas(IdPeriodicidade){
		if(IdPeriodicidade == ''){
			while(document.formulario.QtdParcelaMaximo.options.length > 0){
				document.formulario.QtdParcelaMaximo.options[0] = null;
			}
		}else{
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
			url = xmlhttp.open("GET", "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade); 
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							while(document.formulario.QtdParcelaMaximo.options.length > 0){
								document.formulario.QtdParcelaMaximo.options[0] = null;
							}
						}else{	
							var nameNode, nameTextNode, IdPeriodicidade, QtdParcelaMaximo;					
							while(document.formulario.QtdParcelaMaximo.options.length > 0){
								document.formulario.QtdParcelaMaximo.options[0] = null;
							}
							
							addOption(document.formulario.QtdParcelaMaximo,"","0");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcelaMaximo")[i]; 
								nameTextNode = nameNode.childNodes[0];
								QtdParcelaMaximo = nameTextNode.nodeValue;
								
								for(var ii=1;ii<=QtdParcelaMaximo;ii++){
									addOption(document.formulario.QtdParcelaMaximo,ii,ii);
								}
							}
							
							document.formulario.QtdParcelaMaximo[1].selected = true;
						}
						// Fim de Carregando
						carregando(false);
						
					}
				}
			}
			xmlhttp.send(null);	
		}
	}
	function adicionar_terceiro(limpar, IdServico, IdTerceiro) {
		if(limpar) {
			while(document.getElementById("tabelaTerceiro").rows.length > 2) {
				document.getElementById("tabelaTerceiro").deleteRow(1);
			}
			
			document.getElementById("totaltabelaTerceiro").innerHTML = "Total: 0";
			document.formulario.Terceiros.value = '';
		}
		
		if(IdServico == undefined) {
			IdServico = '';
		}
		
		if(IdTerceiro != undefined) {
			if(document.formulario.IdTerceiro.value == '') {
				document.formulario.IdTerceiro.focus();
				mensagens(1);
				return false;
			}
			
			if(document.formulario.IdRepasse.value == 0) {
				document.formulario.IdRepasse.focus();
				mensagens(1);
				return false;
			}
			
			if(Number(document.formulario.IdRepasse.value) == 1) {
				if(parseFloat(document.formulario.ValorRepasseTerceiro.value.replace(/\./g,'').replace(/,/i,'.')) <= 0) {
					document.formulario.ValorRepasseTerceiro.focus();
					mensagens(1);
					return false;
				}
			} else {
				if(parseFloat(document.formulario.PercentualRepasseTerceiro.value.replace(/\./g,'').replace(/,/i,'.')) <= 0) {
					document.formulario.PercentualRepasseTerceiro.focus();
					mensagens(1);
					return false;
				}
			}
		} else {
			IdTerceiro = '';
		}
		
		if(document.formulario.Terceiros.value.replace(/_[\d\.]*/g,'').split(",").in_array(IdTerceiro) && IdTerceiro != '') {
			return;
		}
		
		call_ajax("xml/servico_terceiro.php?IdServico="+IdServico+"&IdTerceiro="+IdTerceiro, function (xmlhttp) {
			busca_terceiro(0,false,document.formulario.Local.value);
			
			if(xmlhttp.responseText != "false") {
				var nameNode, nameTextNode, RepasseTerceiro = (IdTerceiro != '');
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdTerceiro").length; i++) {
					if(RepasseTerceiro) {
						var ValorRepasseTerceiro = document.formulario.ValorRepasseTerceiro.value.replace(/\./g, '').replace(/,/g, '.');
						var PercentualRepasseTerceiro = document.formulario.PercentualRepasseTerceiro.value.replace(/\./g, '').replace(/,/g, '.');
						var PercentualRepasseTerceiroOutros = document.formulario.PercentualRepasseTerceiroOutros.value.replace(/\./g, '').replace(/,/g, '.');
					} else {
						nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRepasseTerceiro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var ValorRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiro")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var PercentualRepasseTerceiro = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualRepasseTerceiroOutros")[i]; 
						nameTextNode = nameNode.childNodes[0];
						var PercentualRepasseTerceiroOutros = nameTextNode.nodeValue;
					}
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdTerceiro")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdTerceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdServico = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var LoginAlteracao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataAlteracao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					var tam = document.getElementById("tabelaTerceiro").rows.length;
					var linha = document.getElementById("tabelaTerceiro").insertRow(tam - 1);
					linha.accessKey = IdTerceiro;
					var Terceiro = IdTerceiro+"_"+ValorRepasseTerceiro+"_"+PercentualRepasseTerceiro+"_"+PercentualRepasseTerceiroOutros;
					document.formulario.Terceiros.value += document.formulario.Terceiros.value != '' ? ","+Terceiro : Terceiro;
					
					if((tam % 2) != 0) {
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					var c0 = linha.insertCell(0);
					var c1 = linha.insertCell(1);
					var c2 = linha.insertCell(2);
					var c3 = linha.insertCell(3);
					var c4 = linha.insertCell(4);
					var c5 = linha.insertCell(5);
					
					c0.innerHTML = IdTerceiro;
					c0.style.paddingLeft = "5px";
					c1.innerHTML = Nome;
					c2.className = "valor";
					c2.innerHTML = formata_float(Arredonda(ValorRepasseTerceiro,2),2).replace(/\./i, ",");
					c3.className = "valor";
					c3.innerHTML = formata_float(Arredonda(PercentualRepasseTerceiro,2),2).replace(/\./i, ",");
					c4.className = "valor";
					c4.innerHTML = formata_float(Arredonda(PercentualRepasseTerceiroOutros,2),2).replace(/\./i, ",");
					c5.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_terceiro("+IdTerceiro+")\">";
					c5.style.cursor = "pointer";
				}
				
				document.getElementById("totaltabelaTerceiro").innerHTML = "Total: "+(tam - 1);
			}
		});
	}
	function remover_terceiro(IdTerceiro) {
		var campo = document.getElementById("tabelaTerceiro"), tam = campo.rows.length;
		
		for(var i = 0; i < tam; i++) {
			if(campo.rows[i].accessKey == IdTerceiro) {
				IdTerceiro = IdTerceiro+"[_0-9\.]*";
				IdTerceiro = new RegExp(IdTerceiro+",|,"+IdTerceiro+"|"+IdTerceiro);
				campo = document.formulario.Terceiros;
				campo.value = campo.value.replace(IdTerceiro, '');
				document.getElementById("tabelaTerceiro").deleteRow(i);
				document.getElementById("totaltabelaTerceiro").innerHTML = "Total: "+(tam - 3);
				return;
			}
		}
	}
	function adicionar_periodicidade(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,valor){
		if(document.formulario.IdServico.value == ''){
			if(valor == undefined)	valor = '';
			if(valor == ''){
				document.formulario.ValorInicial.readOnly	=	false;
				document.formulario.ValorInicial.focus();
				mensagens(84);
				return false;
			}else{
				document.formulario.ValorInicial.readOnly	=	true;
				mensagens(0);
			}
		}
		if(IdPeriodicidade == ''){
			document.formulario.IdPeriodicidade.focus();
			mensagens(1);
			return false;
		}
		if(QtdParcela == 0){
			document.formulario.QtdParcela.focus();
			mensagens(1);
			return false;
		}
		if(TipoContrato == 0){
			document.formulario.TipoContrato.focus();
			mensagens(1);
			return false;
		}
		if(IdLocalCobranca == 0){
			document.formulario.IdLocalCobranca.focus();
			mensagens(1);
			return false;
		}
		if(MesFechado == 0){
			document.formulario.MesFechado.focus();
			mensagens(1);
			return false;
		}
		if(QtdMesesFidelidade == ''){
			document.formulario.QtdMesesFidelidade.focus();
			mensagens(1);
			return false;
		}

		var cont = 0; ii=0;
		var Periodicidade 		=	IdPeriodicidade+'_'+QtdParcela+'_'+TipoContrato+'_'+IdLocalCobranca+'_'+MesFechado+'_'+QtdMesesFidelidade;
		var PeriodicidadeTemp 	=	IdPeriodicidade+'_'+QtdParcela+'_'+TipoContrato+'_'+IdLocalCobranca+'_'+MesFechado;
		
		if(document.formulario.Periodicidade.value == ''){
			document.formulario.Periodicidade.value = Periodicidade;
			ii = 0;
		}else{
			var tempFiltro	=	document.formulario.Periodicidade.value.split('#');
				
			ii=0; 
			
			while(tempFiltro[ii] != undefined){
				temp		=	tempFiltro[ii].split('_');
				Filtro		=	temp[0]+'_'+temp[1]+'_'+temp[2]+'_'+temp[3]+'_'+temp[4];
				
				if(Filtro != PeriodicidadeTemp){
					cont++;		
				}
				ii++;
			}
			if(ii == cont){
				document.formulario.Periodicidade.value = document.formulario.Periodicidade.value + "#" + Periodicidade;
			}
		}
		if(ii == cont){
			adicionar_periodicidade_temp(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,valor);
			
			document.getElementById('totaltabelaPeriodicidade').innerHTML	=	'Total: '+(ii+1);			
		}
	}
	function remover_periodicidade(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado){
		var tempFiltro	=	document.formulario.Periodicidade.value.split('#');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			temp	=	tempFiltro[ii].split("_");
			
			temp2	=	temp[0]+'_'+temp[1]+'_'+temp[2]+'_'+temp[3]+'_'+temp[4];
			
			if(temp2 != IdPeriodicidade+'_'+QtdParcela+'_'+TipoContrato+'_'+IdLocalCobranca+'_'+MesFechado){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "#" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		for(var i=0; i<document.getElementById('tabelaPeriodicidade').rows.length; i++){
			if(IdPeriodicidade+'_'+QtdParcela+'_'+TipoContrato+'_'+IdLocalCobranca+'_'+MesFechado == document.getElementById('tabelaPeriodicidade').rows[i].accessKey){
				document.getElementById('tabelaPeriodicidade').deleteRow(i);
				tableMultColor('tabelaPeriodicidade');
				break;
			}
		}	
		
		
		document.formulario.Periodicidade.value = novoFiltro;
		
		if(novoFiltro == ""){
			document.formulario.ValorInicial.readOnly	=	false;
		}else{
			document.formulario.ValorInicial.readOnly	=	true;
		}
		
		document.getElementById('totaltabelaPeriodicidade').innerHTML	=	'Total: '+(ii-1);
	}
	function busca_periodicidade(IdServico,valor){
		if(IdServico == ''){
			return false;
		}else{
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
			url = xmlhttp.open("GET", "xml/servico_periodicidade.php?IdServico="+IdServico); 
			
			var ValorInicial	=	valor;
			
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
								document.getElementById('tabelaPeriodicidade').deleteRow(1);
							}
							
							document.getElementById('totaltabelaPeriodicidade').innerHTML				=	'Total: 0';
							
							// Carregando...
							carregando(false);
							return false;
						}else{	
							var nameNode, nameTextNode, IdPeriodicidade, QtdParcela;					
							while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
								document.getElementById('tabelaPeriodicidade').deleteRow(1);
							}
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdPeriodicidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var DescricaoPeriodicidade = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var QtdParcela = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("TipoContrato")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var TipoContrato = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdLocalCobranca = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("MesFechado")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var MesFechado = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMesesFidelidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var QtdMesesFidelidade = nameTextNode.nodeValue;
								
								adicionar_periodicidade(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,ValorInicial);
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
	function atualiza_periodicidade(Periodicidade,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,valor){
		if(Periodicidade == '' || valor == ''){
			return false;
		}

		var tempFiltro	=	document.formulario.Periodicidade.value.split('#');
		
		while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
			document.getElementById('tabelaPeriodicidade').deleteRow(1);
		}
		
		ii=0; 
		while(tempFiltro[ii] != undefined){
			var temp	=	tempFiltro[ii].split('_');
			var IdPeriodicidade		=	temp[0];
			var QtdParcela			= 	temp[1];
			var TipoContrato		=	temp[2];
			var IdLocalCobranca		= 	temp[3];
			var MesFechado			=	temp[4];
			var QtdMesesFidelidade	=	temp[5];
		
			adicionar_periodicidade_temp(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,valor);
		
			ii++;
		}
		document.getElementById('totaltabelaPeriodicidade').innerHTML	=	'Total: '+(ii);
		
		if(document.formulario.Erro.value != '0'){
			scrollWindow('bottom');
		}				
	}
	
	function adicionar_periodicidade_temp(IdPeriodicidade,QtdParcela,TipoContrato,IdLocalCobranca,MesFechado,QtdMesesFidelidade,valor){
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
		url = xmlhttp.open("GET", "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade+"&IdLocalCobranca="+IdLocalCobranca+"&TipoContrato="+TipoContrato+"&MesFechado="+MesFechado); 
		var Qtd	= QtdParcela;	
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){	
						var nameNode, nameTextNode, DescricaoPeriodicidade, ValorPeriodicidade, DescMesFechado;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoPeriodicidade = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Fator")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Fator = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("AbreviacaoNomeLocalCobranca")[0]; 
						nameTextNode = nameNode.childNodes[0];
						AbreviacaoNomeLocalCobranca = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoContrato")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoContrato = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescMesFechado")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescMesFechado = nameTextNode.nodeValue;
						
						var tam, linha, c0, c1, c2, c3;
						
						tam 	= document.getElementById('tabelaPeriodicidade').rows.length;
						linha	= document.getElementById('tabelaPeriodicidade').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						if(valor != ''){
							if(valor.indexOf(",") != -1){	
								valor = valor.replace('.','');
								valor = valor.replace('.','');
								valor = valor.replace(',','.');
								
								ValorPeriodicidade	=	valor*parseInt(Fator);
							}
						}else{
							ValorPeriodicidade = '0';
						}
						
						linha.accessKey  = IdPeriodicidade+'_'+Qtd+'_'+TipoContrato+'_'+IdLocalCobranca+'_'+MesFechado; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);
						c2	= linha.insertCell(2);
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);
						c5	= linha.insertCell(5);
						c6	= linha.insertCell(6);
						c7	= linha.insertCell(7);
						
						c0.innerHTML = DescricaoPeriodicidade;
						c0.style.padding  =	"0 0 0 5px";
						
						c1.innerHTML = Qtd;
						
						c2.innerHTML = DescTipoContrato;
						
						c3.innerHTML = AbreviacaoNomeLocalCobranca;
						
						c4.innerHTML = DescMesFechado;
												
						c5.innerHTML = QtdMesesFidelidade;
						
						c6.innerHTML 	= formata_float(Arredonda(ValorPeriodicidade,2),2).replace(".",",")+"&nbsp;&nbsp;";
						c6.style.textAlign	= "right";
						
						c7.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_periodicidade("+IdPeriodicidade+","+Qtd+","+TipoContrato+","+IdLocalCobranca+","+MesFechado+")\">";
						c7.style.cursor = "pointer";
					}
					
				}
				// Fim de Carregando
				carregando(false);
			}
		}
		xmlhttp.send(null);
	}
	function verificaTipoServico(IdTipoServico){
		if(IdTipoServico==''){
			document.formulario.PercentualRepasseTerceiroOutros.readOnly				=	false;
			document.getElementById('tb_FaturamentoFracionado').style.display			=	'none';
			document.getElementById('setFaturamentoFracionado').style.display			=	'none';
			document.formulario.IdFaturamentoFracionado.style.display					=	'none';
			document.getElementById("descIdFaturamentoFracionado").style.display		=	'none';
			document.formulario.IdFaturamentoFracionado.value							=	'';
			document.getElementById('cp_SICI').style.display							=	'none';
			document.getElementById('cpPeriodicidade').style.display					=	'none';
			document.getElementById('cpAgrupado').style.display							=	'none';
			//document.getElementById('sepDiasAviso').style.display						=	'block';
			document.getElementById('titDiasAviso').style.display						=	'block';
			document.getElementById('cpDiasAviso').style.display						=	'block';
			//document.getElementById('sep2DiasAviso').style.display					=	'block';
			document.getElementById('titAtivacaoAutomatica').style.color				=	'#C10000';
			document.getElementById('titEmailCobranca').style.color						=	'#C10000';
			document.getElementById('titRotinasDiarias').style.color					=	'#C10000';
			document.getElementById('cpValorInicial').innerHTML							=	'Valor Mensal';
			document.formulario.DiasAvisoAposVencimento.readOnly						=	false;
			document.formulario.AtivacaoAutomatica.disabled								=	false;
			document.formulario.EmailCobranca.disabled									=	false;
			document.formulario.ExecutarRotinas.disabled								=	false;
			document.formulario.DiasLimiteBloqueio.readOnly								=	false;
			document.formulario.IdOrdemServicoLayout.style.display 						= 	'none';
			document.getElementById('setOrdemServicoLayout').style.display				= 	'none';
			document.formulario.IdOrdemServicoLayout[0].selected   						= 	true;
			document.formulario.IdServicoImportar.value									= 	'';
			document.formulario.DescricaoServicoImportar.value							= 	'';
			document.formulario.IdTecnologia.value										= '';
			document.formulario.IdDedicado.value										= '';
			document.formulario.FatorMega.value											= '';
			document.formulario.IdGrupoVelocidade.value									= '';

			document.formulario.Cor.style.display										=	"none";
			document.getElementById("ico_cor").style.display							=	"none";
			document.getElementById("tit_cor").style.display							=	"none";
			document.formulario.ExibirReferencia.style.marginRight						=	"5px";
			document.formulario.DescricaoServico.style.width							=	"412px";
			return false;
		}else{
			switch(IdTipoServico){
				case '1': //Periodico
					document.formulario.PercentualRepasseTerceiroOutros.readOnly				=	true;
					document.getElementById('tb_FaturamentoFracionado').style.display			=	'block';
					document.getElementById('setFaturamentoFracionado').style.display			=	'block';
					document.formulario.IdFaturamentoFracionado.style.display					=	'block';
					document.getElementById("descIdFaturamentoFracionado").style.display		=	'block';
					document.formulario.IdFaturamentoFracionado.value							=	document.formulario.FaturamentoFracionadoDefault.value;
					document.getElementById('cpPeriodicidade').style.display					=	'block';
					document.getElementById('cpAgrupado').style.display							=	'none';					
					document.getElementById('titDiasAviso').style.display						=	'block';
					document.getElementById('cpDiasAviso').style.display						=	'block';
					document.getElementById('titAtivacaoAutomatica').style.color				=	'#C10000';
					document.getElementById('titEmailCobranca').style.color						=	'#C10000';
					document.getElementById('titRotinasDiarias').style.color					=	'#C10000';
					document.formulario.DiasAvisoAposVencimento.readOnly						=	false;
					document.formulario.AtivacaoAutomatica.disabled								=	false;
					document.formulario.EmailCobranca.disabled									=	false;
					document.formulario.ExecutarRotinas.disabled								=	false;
					document.formulario.DiasLimiteBloqueio.readOnly								=	false;
					document.getElementById('cpValorInicial').innerHTML							=	'Valor Mensal';
					document.formulario.Cor.style.display										=	"none";
					document.getElementById("ico_cor").style.display							=	"none";
					document.getElementById("tit_cor").style.display							=	"none";
					document.formulario.ExibirReferencia.style.marginRight						=	"5px";
					document.formulario.DescricaoServico.style.width							=	"412px";
					
					if(document.formulario.IdOrdemServicoLayout.style.display != 'none'){						
						document.formulario.IdOrdemServicoLayout.style.display 			= 'none';
						document.getElementById('setOrdemServicoLayout').style.display	= 'none';
						document.formulario.IdOrdemServicoLayout[0].selected   = true;
					}					
					
					if(document.formulario.SICIAtivoDefault.value == '1'){
						if(document.formulario.IdNotaFiscalTipo.value != "0"){
							document.getElementById('cp_SICI').style.display = 'block';
						}else{
							document.getElementById('cp_SICI').style.display = 'none';
							document.formulario.IdTecnologia.value = '';
							document.formulario.IdDedicado.value = '';
							document.formulario.FatorMega.value = '';
							document.formulario.IdGrupoVelocidade.value = '';
						}
					} else{
						document.getElementById('cp_SICI').style.display = 'none';
						document.formulario.IdTecnologia.value = '';
						document.formulario.IdDedicado.value = '';
						document.formulario.FatorMega.value = '';
						document.formulario.IdGrupoVelocidade.value = '';
					}
					break;
				case '2': //Eventual
					document.formulario.PercentualRepasseTerceiroOutros.readOnly				=	false;
					document.getElementById('tb_FaturamentoFracionado').style.display			=	'block';
					document.getElementById('setFaturamentoFracionado').style.display			=	'none';
					document.formulario.IdFaturamentoFracionado.style.display					=	'none';
					document.getElementById("descIdFaturamentoFracionado").style.display		=	'none';
					document.formulario.IdFaturamentoFracionado.value							=	'';
					document.getElementById('cp_SICI').style.display							=	'none';
					document.getElementById('cpPeriodicidade').style.display					=	'none';
					document.getElementById('cpAgrupado').style.display							=	'none';					
					document.getElementById('titDiasAviso').style.display						=	'block';
					document.getElementById('cpDiasAviso').style.display						=	'block';
					document.getElementById('titAtivacaoAutomatica').style.color				=	'#000';
					document.getElementById('titEmailCobranca').style.color						=	'#000';
					document.getElementById('titRotinasDiarias').style.color					=	'#000';
					document.formulario.DiasAvisoAposVencimento.readOnly						=	true;
					document.formulario.AtivacaoAutomatica.disabled								=	true;
					document.formulario.EmailCobranca.disabled									=	true;
					document.formulario.ExecutarRotinas.disabled								=	true;
					document.formulario.DiasLimiteBloqueio.readOnly								=	true;
					document.getElementById('cpValorInicial').innerHTML							=	'Valor';
					document.formulario.Cor.style.display										=	"block";
					document.getElementById("ico_cor").style.display							=	"block";
					document.getElementById("tit_cor").style.display							=	"block";
					document.formulario.ExibirReferencia.style.marginRight						=	"9px";
					document.formulario.DescricaoServico.style.width							=	"309px";
					
					if(document.formulario.IdOrdemServicoLayout.style.display != 'block'){
						document.formulario.IdOrdemServicoLayout.style.display 			= 'block';
						document.getElementById('setOrdemServicoLayout').style.display	= 'block';
					}				

					document.formulario.IdServicoImportar.value 					= '';
					document.formulario.DescricaoServicoImportar.value				= '';
					document.formulario.IdTecnologia.value							= '';
					document.formulario.IdDedicado.value							= '';
					document.formulario.FatorMega.value								= '';
					document.formulario.IdGrupoVelocidade.value						= '';
					break;
				case '3': //Agrupado
					document.formulario.PercentualRepasseTerceiroOutros.readOnly				=	false;
					document.getElementById('tb_FaturamentoFracionado').style.display			=	'block';
					document.getElementById('setFaturamentoFracionado').style.display			=	'none';
					document.formulario.IdFaturamentoFracionado.style.display					=	'none';
					document.getElementById("descIdFaturamentoFracionado").style.display		=	'none';
					document.formulario.IdFaturamentoFracionado.value							=	'';
					document.getElementById('cp_SICI').style.display							=	'none';
					document.getElementById('cpPeriodicidade').style.display					=	'none';
					document.getElementById('cpAgrupado').style.display							=	'block';
					document.getElementById('titDiasAviso').style.display						=	'block';
					document.getElementById('cpDiasAviso').style.display						=	'block';
					document.getElementById('titServicoAgrupado').innerHTML						=	'Serviço Agrupado';
					document.getElementById('titAtivacaoAutomatica').style.color				=	'#000';
					document.getElementById('titEmailCobranca').style.color						=	'#000';
					document.getElementById('titRotinasDiarias').style.color					=	'#000';
					document.formulario.DiasAvisoAposVencimento.readOnly						=	true;
					document.formulario.AtivacaoAutomatica.disabled								=	true;
					document.formulario.EmailCobranca.disabled									=	true;
					document.formulario.ExecutarRotinas.disabled								=	true;
					document.formulario.DiasLimiteBloqueio.readOnly								=	true;
					document.getElementById('cpValorInicial').innerHTML							=	'Valor';
					document.formulario.Cor.style.display										=	"block";
					document.getElementById("ico_cor").style.display							=	"block";
					document.getElementById("tit_cor").style.display							=	"block";
					document.formulario.ExibirReferencia.style.marginRight						=	"9px";
					document.formulario.DescricaoServico.style.width							=	"309px";
					
					if(document.formulario.IdOrdemServicoLayout.style.display != 'block'){
						document.formulario.IdOrdemServicoLayout.style.display 			= 'block';
						document.getElementById('setOrdemServicoLayout').style.display	= 'block';
					}

					document.formulario.IdServicoImportar.value 					= '';
					document.formulario.DescricaoServicoImportar.value				= '';	
					document.formulario.IdTecnologia.value							= '';
					document.formulario.IdDedicado.value							= '';
					document.formulario.FatorMega.value								= '';
					document.formulario.IdGrupoVelocidade.value						= '';
					break;
				case '4': //Automatico
					document.formulario.PercentualRepasseTerceiroOutros.readOnly				=	true;
					document.getElementById('tb_FaturamentoFracionado').style.display			=	'block';
					document.getElementById('setFaturamentoFracionado').style.display			=	'block';
					document.formulario.IdFaturamentoFracionado.style.display					=	'block';
					document.getElementById("descIdFaturamentoFracionado").style.display		=	'block';
					document.formulario.IdFaturamentoFracionado.value							=	document.formulario.FaturamentoFracionadoDefault.value;
					document.getElementById('cp_SICI').style.display							=	'none';
					document.getElementById('cpPeriodicidade').style.display					=	'none';
					document.getElementById('cpAgrupado').style.display							=	'block';
					document.getElementById('sepDiasAviso').style.display						=	'none';
					document.getElementById('titDiasAviso').style.display						=	'none';
					document.getElementById('cpDiasAviso').style.display						=	'none';
					document.getElementById('sep2DiasAviso').style.display						=	'none';
					document.getElementById('titServicoAgrupado').innerHTML						=	'Serviço Automático';
					document.getElementById('titAtivacaoAutomatica').style.color				=	'#000';
					document.getElementById('titEmailCobranca').style.color						=	'#000';
					document.getElementById('titRotinasDiarias').style.color					=	'#000';
					document.formulario.DiasAvisoAposVencimento.readOnly						=	true;
					document.formulario.AtivacaoAutomatica.disabled								=	true;
					document.formulario.EmailCobranca.disabled									=	true;
					document.formulario.ExecutarRotinas.disabled								=	true;
					document.formulario.DiasLimiteBloqueio.readOnly								=	true;
					document.getElementById('cpValorInicial').innerHTML							=	'Valor Mensal';
					document.formulario.Cor.style.display										=	"none";
					document.getElementById("ico_cor").style.display							=	"none";
					document.getElementById("tit_cor").style.display							=	"none";
					document.formulario.ExibirReferencia.style.marginRight						=	"5px";
					document.formulario.DescricaoServico.style.width							=	"412px";
					
					if(document.formulario.IdOrdemServicoLayout.style.display != 'none'){						
						document.formulario.IdOrdemServicoLayout.style.display 			= 'none';
						document.getElementById('setOrdemServicoLayout').style.display	= 'none';
						document.formulario.IdOrdemServicoLayout[0].selected   = true;
					}
					
					document.formulario.IdTecnologia.value							= '';
					document.formulario.IdDedicado.value							= '';
					document.formulario.FatorMega.value								= '';
					document.formulario.IdGrupoVelocidade.value						= '';
					break;
			}
		}
	}
	function verificarRepasse(IdRepasse){
		if(document.formulario.IdTipoServico.value == 1 || document.formulario.IdTipoServico.value == 4) {
			document.formulario.PercentualRepasseTerceiroOutros.readOnly = true;
		} else {
			document.formulario.PercentualRepasseTerceiroOutros.readOnly = false;
		}
		
		document.formulario.ValorRepasseTerceiro.value = "0,00";
		document.formulario.PercentualRepasseTerceiro.value = "0,00";
		document.formulario.PercentualRepasseTerceiroOutros.value = "0,00";
		
		switch(Number(IdRepasse)){
			case 1:
				document.getElementById("cpValorRepasseMensal").style.display = "block";
				document.formulario.ValorRepasseTerceiro.style.display = "block";
				document.getElementById("cpPercRepasseMensal").style.display = "none";
				document.formulario.PercentualRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensalOutros").style.display = "none";
				document.formulario.PercentualRepasseTerceiroOutros.style.display = "none";
				document.formulario.bt_add_terceiro.style.marginLeft = "4px";
				break;
			case 2:
				document.getElementById("cpValorRepasseMensal").style.display = "none";
				document.formulario.ValorRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensal").style.display = "block";
				document.formulario.PercentualRepasseTerceiro.style.display = "block"
				document.getElementById("cpPercRepasseMensalOutros").style.display = "block";
				document.formulario.PercentualRepasseTerceiroOutros.style.display = "block";
				document.formulario.bt_add_terceiro.style.marginLeft = "8px";
				break;
			default:
				document.getElementById("cpValorRepasseMensal").style.display = "none";
				document.formulario.ValorRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensal").style.display = "none";
				document.formulario.PercentualRepasseTerceiro.style.display = "none";
				document.getElementById("cpPercRepasseMensalOutros").style.display = "none";
				document.formulario.PercentualRepasseTerceiroOutros.style.display = "none";
				document.formulario.bt_add_terceiro.style.marginLeft = "4px";
		}
	}
	function adicionar_servico(IdServicoAgrupador){
		if(IdServicoAgrupador == ''){
			document.formulario.IdServicoAgrupador.focus();
			mensagens(1);
			return false;
		}

		var cont = 0; ii=0;
		
		if(document.formulario.ServicoAgrupador.value == ''){
			document.formulario.ServicoAgrupador.value = IdServicoAgrupador;
			ii = 0;
		}else{
			var tempFiltro	=	document.formulario.ServicoAgrupador.value.split('#');
				
			ii=0; 
			
			while(tempFiltro[ii] != undefined){
				if(tempFiltro[ii] != IdServicoAgrupador){
					cont++;		
				}
				ii++;
			}
			if(ii == cont){
				document.formulario.ServicoAgrupador.value = document.formulario.ServicoAgrupador.value + "#" + IdServicoAgrupador;
			}
		}
		if(ii == cont){
			adicionar_servico_temp(IdServicoAgrupador);
			
			document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii+1);	
			
			document.formulario.IdServicoAgrupador.value			=	"";
			document.formulario.DescricaoServicoAgrupador.value		=	"";
					
		}
	}
	function adicionar_servico_temp(IdServico){
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
		url = xmlhttp.open("GET", "xml/servico.php?IdServico="+IdServico); 
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){	
						var nameNode, nameTextNode, DescricaoServico, Valor;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoServico = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Valor = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescTipoServico")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescTipoServico = nameTextNode.nodeValue;
						
						var tam, linha, c0, c1, c2, c3;
						
						tam 	= document.getElementById('tabelaServico').rows.length;
						linha	= document.getElementById('tabelaServico').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						if(Valor == ''){	Valor = 0;	}
						
						linha.accessKey  = IdServico; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);
						c2	= linha.insertCell(2);
						c3	= linha.insertCell(3);
						c4	= linha.insertCell(4);
						
						c0.innerHTML = IdServico;
						c0.style.padding  =	"0 0 0 5px";
						
						c1.innerHTML = DescricaoServico;
						
						c2.innerHTML = DescTipoServico;
						
						c3.innerHTML 	= formata_float(Arredonda(Valor,2),2).replace(".",",");
						c3.style.textAlign	= "right";
						c3.style.padding  =	"0 8px 0 0";
						
						c4.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_servico("+IdServico+")\">";
						c4.style.cursor = "pointer";
					}
					
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function remover_servico(IdServicoAgrupador){
		for(var i=0; i<document.getElementById('tabelaServico').rows.length; i++){
			if(IdServicoAgrupador == document.getElementById('tabelaServico').rows[i].accessKey){
				document.getElementById('tabelaServico').deleteRow(i);
				tableMultColor('tabelaServico');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.ServicoAgrupador.value.split('#');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdServicoAgrupador){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "#" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.ServicoAgrupador.value = novoFiltro;
		document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii-1);
	}
	function busca_servico_agrupado(IdServico){
		if(IdServico == ''){
			return false;
		}else{
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
			url = xmlhttp.open("GET", "xml/servico_agrupado.php?IdServico="+IdServico); 
			
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							while(document.getElementById('tabelaServico').rows.length > 2){
								document.getElementById('tabelaServico').deleteRow(1);
							}
							
							document.getElementById('totaltabelaServico').innerHTML				=	'Total: 0';
						}else{	
							var nameNode, nameTextNode, IdServicoAgrupador;					
							while(document.getElementById('tabelaPeriodicidade').rows.length > 2){
								document.getElementById('tabelaPeriodicidade').deleteRow(1);
							}
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServicoAgrupador").length; i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdServicoAgrupador")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdServicoAgrupador = nameTextNode.nodeValue;
								
								adicionar_servico(IdServicoAgrupador);
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
	
	function adicionar_cidade(IdPais,IdEstado,IdCidade,ListarCampo){
		if(IdPais!="" && IdEstado!="" && IdCidade!=""){
			var cont = 0; ii='';
			if(ListarCampo == '' || ListarCampo == undefined){
				if(document.formulario.Filtro_IdPaisEstadoCidade.value == ''){
					document.formulario.Filtro_IdPaisEstadoCidade.value = IdPais+","+IdEstado+","+IdCidade;
					ii = 0;
				}else{
					var tempFiltro	=	document.formulario.Filtro_IdPaisEstadoCidade.value.split('^');
						
					ii=0; 
					while(tempFiltro[ii] != undefined){
						if(tempFiltro[ii] != IdPais+","+IdEstado+","+IdCidade){
							cont++;		
						}
						ii++;
					}
					if(ii == cont){
						document.formulario.Filtro_IdPaisEstadoCidade.value = document.formulario.Filtro_IdPaisEstadoCidade.value + "^" + IdPais+","+IdEstado+","+IdCidade;
					}
				}
			}else{
				ii=0;
			}
			if(ii == cont){
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
			    
			    url = "xml/cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade;
				xmlhttp.open("GET", url,true);
		
				xmlhttp.onreadystatechange = function(){ 
			
					// Carregando...
					carregando(true);
			
					if(xmlhttp.readyState == 4){ 
						if(xmlhttp.status == 200){
							if(xmlhttp.responseText != 'false'){
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomePais")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomePais = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomeEstado")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomeEstado = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomeCidade = nameTextNode.nodeValue;
														
								var tam, linha, c0, c1, c2, c3, c4;
								
								tam 	= document.getElementById('tabelaCidade').rows.length;
								linha	= document.getElementById('tabelaCidade').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 	= IdPais+","+IdEstado+","+IdCidade; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);	
								c3	= linha.insertCell(3);
								c4	= linha.insertCell(4);
								
								var linkIni = "<a href='cadastro_cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade+"'>";
								var linkFim = "</a>";
								
								c0.innerHTML = linkIni + (document.getElementById('tabelaCidade').rows.length-2) + linkFim;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = linkIni + NomePais + linkFim;
								
								c2.innerHTML = linkIni + NomeEstado + linkFim;
								
								c3.innerHTML = linkIni + NomeCidade + linkFim;
								
								if(document.formulario.IdStatus.value == 1){
									c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_cidade("+IdPais+","+IdEstado+","+IdCidade+")\"></tr>";
								}else{
									c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c4.style.textAlign = "center";
								c4.style.cursor = "pointer";
								
								if(document.formulario.IdServico.value == ''){
									document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
									//	scrollWindow('bottom');
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
	}
	
	function remover_filtro_cidade(IdPais,IdEstado,IdCidade){
		for(var i=0; i<document.getElementById('tabelaCidade').rows.length; i++){
			if(IdPais+","+IdEstado+","+IdCidade == document.getElementById('tabelaCidade').rows[i].accessKey){
				document.getElementById('tabelaCidade').deleteRow(i);
				tableMultColor('tabelaCidade');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdPaisEstadoCidade.value.split('^');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdPais+","+IdEstado+","+IdCidade){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "^" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdPaisEstadoCidade.value = novoFiltro;
		document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii-1);
	}	
	function calcula_percentual(campo){
		if(document.formulario.Acao.value == "inserir"){
			var ValorInicial				=	document.formulario.ValorInicial.value;
			var ValorRepasseTerceiro		=	document.formulario.ValorRepasseTerceiro.value;
			var PercentualRepasseTerceiro	=	document.formulario.PercentualRepasseTerceiro.value;
			var	tempPerc = 0, tempValor;
			
			ValorInicial				=	ValorInicial.replace(".","");
			ValorInicial				=	ValorInicial.replace(".","");
			ValorInicial				=	ValorInicial.replace(",",".");
			
			ValorRepasseTerceiro		=	ValorRepasseTerceiro.replace(".","");
			ValorRepasseTerceiro		=	ValorRepasseTerceiro.replace(".","");
			ValorRepasseTerceiro		=	ValorRepasseTerceiro.replace(",",".");
			
			PercentualRepasseTerceiro	=	PercentualRepasseTerceiro.replace("."," ");
			PercentualRepasseTerceiro	=	PercentualRepasseTerceiro.replace("."," ");
			PercentualRepasseTerceiro	=	PercentualRepasseTerceiro.replace(",",".");
			
			if(document.formulario.IdTipoServico.value != 2 && document.formulario.IdTipoServico.value != 3){
				if(ValorInicial == '' || ValorInicial=='0.00'){
					document.formulario.PercentualRepasseTerceiro.value			=	'0,00';
					document.formulario.PercentualRepasseTerceiroOutros.value	=	'0,00';
				}else{
					if(ValorRepasseTerceiro == "")		ValorRepasseTerceiro 	  = 0;
					if(PercentualRepasseTerceiro == "") PercentualRepasseTerceiro = 0;
					
					if(campo.name == 'ValorRepasseTerceiro'){
						PercentualRepasseTerceiro = (parseFloat(ValorRepasseTerceiro)*100)/parseFloat(ValorInicial);
					
						PercentualRepasseTerceiro		= 	formata_float(Arredonda(PercentualRepasseTerceiro,2),2);
						PercentualRepasseTerceiro		=	PercentualRepasseTerceiro.replace('.',',');
						
						if(ValorRepasseTerceiro == '0'){
							document.formulario.ValorRepasseTerceiro.value			=	'0,00';
						}
						
						document.formulario.PercentualRepasseTerceiro.value		=	PercentualRepasseTerceiro;
					}else if(campo.name == 'PercentualRepasseTerceiro'){
						ValorRepasseTerceiro	=	(parseFloat(PercentualRepasseTerceiro)*parseFloat(ValorInicial))/100;
					
						ValorRepasseTerceiro		= 	formata_float(Arredonda(ValorRepasseTerceiro,2),2);
						ValorRepasseTerceiro		=	ValorRepasseTerceiro.replace('.',',');
					
						document.formulario.ValorRepasseTerceiro.value	=	ValorRepasseTerceiro;
					}
				}
			}
			
			if(document.formulario.PercentualRepasseTerceiro.value == ''){
				document.formulario.PercentualRepasseTerceiro.value = '0,00';
			}
			
			if(document.formulario.PercentualRepasseTerceiroOutros.value == ''){
				document.formulario.PercentualRepasseTerceiroOutros.value = '0,00';
			}
		}
	}
	function mudarNotaFicalTipo(id){
		//verifica_nota_fiscal_tipo(id);
		
		if(id == undefined){
			id = '';
		}
		
		if(id != '' && id != '0'){
			document.getElementById('tit_CategoriaTributaria').style.display	= 'block';
			document.getElementById('cp_CategoriaTributaria').style.display		= 'block';
			document.formulario.MsgAuxiliarCobranca.style.height				= '58px';
			if(document.formulario.IdTipoServico.value == 1 && document.formulario.SICIAtivoDefault.value == '1'){
				document.getElementById('cp_SICI').style.display					= 'block';
			}else{
				document.getElementById('cp_SICI').style.display					= 'none';
			}
		}else{
			document.getElementById('tit_CategoriaTributaria').style.display	= 'none';
			document.getElementById('cp_CategoriaTributaria').style.display		= 'none';
			document.getElementById('cp_SICI').style.display					= 'none';
			document.formulario.MsgAuxiliarCobranca.style.height				= '98px';
			
			document.formulario.IdCategoriaTributaria.value						= 0;
		}
	}
	function atualizarHistorico(IdServico){
		if(IdServico == undefined){
			IdServico = 0;
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
		
		url = xmlhttp.open("GET", "xml/servico.php?IdServico="+IdServico); 
		// Carregando...
		carregando(true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.formulario.HistoricoObs.value = '';
						document.getElementById("cpHistorico").style.display = "none";
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Obs = nameTextNode.nodeValue;
						
						document.formulario.HistoricoObs.value = Obs;
						
						if(Obs != ''){
							document.getElementById("cpHistorico").style.display = "block";
						} else{
							document.getElementById("cpHistorico").style.display = "none";
						}
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);
	}
	function listar_servico_vinculado(IdServico, Listar){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
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
	    
	   	url = "xml/servico_vinculado.php?IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.getElementById('cpServicoVinculado').style.display = "none";
						getAllElementsByName("div", "titServicoVinculado")[0].innerHTML = "Serviços Vinculados Ativos";
						document.getElementById('tableServicoVinculadoTotal').innerHTML	= "Total: 0";
						document.getElementById("ServicoVinculadoLista").style.display = "none";
						document.formulario.ServicoVinculado.value = '';
						
						// Fim de Carregando
						carregando(false);
					} else{
						while(document.getElementById('tableServicoVinculado').rows.length > 2){
							document.getElementById('tableServicoVinculado').deleteRow(1);
						}
						
						document.formulario.ServicoVinculado.value = '';
						var cabecalho, tam, linha, c0, c1, c2, c3, c4, c5;
						var IdServicoVinculado = '', IdServico, DescricaoServico, Valor, TipoServico, DescricaoServicoGrupo, linkIni, linkFim, total = 0;
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdServico").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoTemp")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServicoTemp = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("TipoServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							TipoServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServicoGrupo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoServicoGrupo = nameTextNode.nodeValue;
							
							total += Number(Valor);
							
							tam 	= document.getElementById('tableServicoVinculado').rows.length;
							linha	= document.getElementById('tableServicoVinculado').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							IdServicoVinculado += "," + IdServico;
							linha.accessKey = IdServico; 
							
							c0	= linha.insertCell(0);	
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);	
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							
							linkIni	= "<a href='cadastro_servico.php?IdServico=" + IdServico + "' target='_blank' ";
							linkFim	= "</a>";
							
							c0.innerHTML = linkIni + ">" + IdServico + linkFim;
							c0.style.padding  =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + "title='" + DescricaoServico + "'>" + DescricaoServicoTemp + linkFim;
							
							c2.innerHTML = linkIni + ">" + TipoServico + linkFim;
							
							c3.innerHTML = linkIni + ">" + DescricaoServicoGrupo + linkFim;
							
							c4.innerHTML = linkIni + " id='Valor_IdServico_" + IdServico + "'>" + Valor.replace(/\./i, ',') + linkFim;
							c4.className = "valor";
							
							c5.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' onClick='excluir_servico_vinculado(" + IdServico + ")' alt='Cancelar?'>";
							c5.style.cursor = "pointer";
						}
						
						document.formulario.ServicoVinculado.value = IdServicoVinculado.replace(/,/i, '');
						document.getElementById('tableServicoVinculadoTotal').innerHTML = "Total: "+i;
						document.getElementById('tableServicoVinculadoValorTotal').innerHTML = formata_float(Arredonda(total,2),2).replace('.',',');
						
						servico_vinculado_lista(Listar);
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
	function servico_vinculado_lista(Listar){
		if(document.formulario.Acao.value == "alterar"){
			if(Listar){
				document.getElementById('cpServicoVinculado').style.display = "block";
				getAllElementsByName("div", "titServicoVinculado")[0].style.backgroundColor = "#004492";
				getAllElementsByName("div", "titServicoVinculado")[0].innerHTML = "<a style='cursor:pointer;' onClick='servico_vinculado_lista(false);'>Serviços Vinculados Ativos. [Ocultar]</a>";
				document.getElementById("ServicoVinculadoLista").style.display = "block"
			} else{
				document.getElementById('cpServicoVinculado').style.display = "block";
				getAllElementsByName("div", "titServicoVinculado")[0].style.backgroundColor = "#FFE07B";
				getAllElementsByName("div", "titServicoVinculado")[0].innerHTML = "<a style='cursor:pointer; color:#000;' onClick='servico_vinculado_lista(true);'>Há '" + (document.getElementById('tableServicoVinculado').rows.length-2) + "' serviços vinculado a este serviço selecionado. [Clique aqui]</a>";
				document.getElementById("ServicoVinculadoLista").style.display = "none";
			}
		} else{
			document.getElementById('cpServicoVinculado').style.display = "block";
			getAllElementsByName("div", "titServicoVinculado")[0].style.backgroundColor = "#004492";
			getAllElementsByName("div", "titServicoVinculado")[0].innerHTML = "Serviços Vinculados Ativos";
			document.getElementById("ServicoVinculadoLista").style.display = "block"
		}
	}
	function excluir_servico_vinculado(IdServico){
		var aux = 0;
		var tam = document.getElementById('tableServicoVinculado').rows.length;
		var campoServicoVinculado = document.formulario.ServicoVinculado;
		
		for(var i = 0; i < tam; i++){
			if(IdServico == document.getElementById('tableServicoVinculado').rows[i].accessKey){
				var temp = (document.getElementById('Valor_IdServico_' + IdServico).innerHTML.replace(/\./g, '')).replace(/,/i, '.');
				temp = Number((document.getElementById("tableServicoVinculadoValorTotal").innerHTML.replace(/\./g, '')).replace(/,/i, '.')) - temp;
				
				document.getElementById('tableServicoVinculado').deleteRow(i);
				tableMultColor('tableServicoVinculado', document.filtro.corRegRand.value);
				
				document.getElementById("tableServicoVinculadoTotal").innerHTML = "Total: " + (document.getElementById('tableServicoVinculado').rows.length-2);
				document.getElementById("tableServicoVinculadoValorTotal").innerHTML = formata_float(Arredonda(temp, 2), 2).replace(/\./i, ',');
				
				campoServicoVinculado.value = campoServicoVinculado.value.replace(IdServico, '');
				campoServicoVinculado.value = campoServicoVinculado.value.replace(/,,/g, ',');
				
				if(campoServicoVinculado.value.substring(campoServicoVinculado.value.length-1) == ','){
					campoServicoVinculado.value = campoServicoVinculado.value.substring(0, campoServicoVinculado.value.length-1);
				}
				
				if(campoServicoVinculado.value.split(',')[0] == ''){
					campoServicoVinculado.value = campoServicoVinculado.value.replace(/,/i, '');
				}
				break;
			}
		}
	}
	function limpar_servico_vinculado(){
		if(document.formulario.Acao.value == "inserir"){
			listar_servico_vinculado(document.formulario.IdServicoImportar.value, false);
		}
	}
	
	function verificarColetaSICI(){
	
		var CampoSICI = document.getElementById("cp_SICI"), Color = "#000000";
		
		if(Number(document.formulario.ColetarSICI.value) == 1){
			Color = "#c10000";
		}else{
			Color = "#000000";
		}
	
		for(var i = 0; i < CampoSICI.getElementsByTagName("b").length; i++){
			if(i <= CampoSICI.getElementsByTagName("b").length){
				CampoSICI.getElementsByTagName("b")[i].style.color = Color;
			}
		}
	}
	
	function verificar_Sici(Atual,Proximo){
		if(Atual == undefined || Proximo == undefined) return;
		if(Atual.value != ""){
			if(Proximo.value == ""){
				mensagens(1);
				Proximo.focus();
				document.formulario.bt_alterar.disable = false;
				return false;
			}else{
				return true;
			}
		}
	}
	
	function busca_filtro_cidade_estado(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
		}
		
		var url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;
		
		call_ajax(url, function(xmlhttp){
			var nameNode, nameTextNode;
			
			if(xmlhttp.responseText != 'false'){		
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}
				
				var nameNode, nameTextNode, NomeCidade;					
				
				addOption(document.filtro.filtro_cidade,"","");	
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdCidade = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
					nameTextNode = nameNode.childNodes[0];
					NomeCidade = nameTextNode.nodeValue;
		
					addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
				}					
				
				if(IdCidadeTemp!=""){
					for(i=0;i<document.filtro.filtro_cidade.length;i++){
						if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
						    document.filtro.filtro_cidade[i].selected	=	true;
							break;
						}
					}
				}else{
					document.filtro.filtro_cidade[0].selected	=	true;
				}						
			}else{
				while(document.filtro.filtro_cidade.options.length > 0){
					document.filtro.filtro_cidade.options[0] = null;
				}						
			}
		});
	}
	
	jQuery(document).ready(function(){
		var $ = jQuery.noConflict();
		
		if($("#Id_Servico").val() != ""){
			$("#Id_Servico").adcTabelaDevice();
		}
		
		$("input[name=IdServico]").change(function(){
			$(this).adcTabelaDevice();
		});
		
		$("#bt_add_device").click(function(){
			var cont = 0;
			var IdGrupoDevice = $("#IdGrupoDevice").val();
			
			if($("#IdGrupoDevice").val() == ""){
				mensagens(1);
				$("#IdGrupoDevice").focus();
				return false;
			}
			
			$(".excluir").each(function(index){
				var IdGrupo = $(this).attr('id').split('_');
				
				if(IdGrupo[1] == IdGrupoDevice){
					cont++;
					return false;
				}
					
			});
			
			if(cont == 0){
				$.ajax({
					dataType: 'html',
					type: 'POST',
					data: {IdGrupoDevice: IdGrupoDevice},
					url: 'xml/servico_grupo_device.php',
					success: function(data){
						$("#listaDevice").after(data);
						
						total = $("#tabelaDevice tr").length - 2;
						$("#tabelaDevice .tableListarTitleCad:last").find('.tableListarEspaco').text("Total: " + total);
						$(".dadosDevice:odd").css('background-color', 'rgb(226, 231, 237)');
						
						$("#IdGrupoDevice").val("");
						$("#DescricaoGrupoDevice").val("");
					}
				});
			}
		});
		
		$("#IdGrupoDevice").change(function(){
			var id = $(this).val();
			var IdLoja = $("#IdLoja").val();
			if(id != ""){
				var data = {
							IdGrupoDevice: id,
							IdLoja: IdLoja
				};
				
				$.ajax({
					dataType: 'html',
					type: 'POST',
					url: 'xml/grupo_device.php',
					data: {dadosWhere: data},
					success: function(data){
						if(data != ""){
							data = decodeURI(data);
							data = JSON.parse(data);
							if(data.DisponivelContrato == 1){
								$("#IdGrupoDevice").val(data.IdGrupoDevice);
								$("#DescricaoGrupoDevice").val(data.DescricaoGrupoDevice);
							}else{
								$("#IdGrupoDevice").val("");
								$("#DescricaoGrupoDevice").val("");
							}
						}else{
							$("#IdGrupoDevice").val("");
							$("#DescricaoGrupoDevice").val("");
						}
					}
				});
			}
		});
		
		$("body").on('click', '.bt_lista .excluir', function(){
			var IdServico = $('#Id_Servico').val();
			var IdGrupoDevice = $(this).attr('id').split('_');
			if(typeof $('#Id_GrupoDevice_'+IdGrupoDevice[1]).attr('name') == 'undefined'){
				$(this).parent('td').parent('tr').css('display', 'none');
				$('#Id_GrupoDevice_'+IdGrupoDevice[1]).attr('name', 'removeDevice['+IdGrupoDevice[1]+']');
			}else{
				$(this).parent('td').parent('tr').remove();
			}
		});
		
		$("body").on('submit', '#formDialog', function(event){
			event.preventDefault();
			var IdGrupoDevice = $("tr.dadosTable[style*=rgb]:first").find('td:first').text();
			var DescricaoGrupoDevice = $("tr.dadosTable[style*=rgb]:first").find('td:last').text();
			//alert($("tr.dadosTable[style*=rgb]:first").find('td:first').text());
			if(IdGrupoDevice != ""){
				$('#IdGrupoDevice').val(IdGrupoDevice);
				$('#DescricaoGrupoDevice').val(DescricaoGrupoDevice);
				$('#mask').hide();
				$('.quadroFlutuante').hide();
			}
		});
		
		$("#cancelarQuadroFlutuante").click(function(){
			$('#mask').hide();
			$('.quadroFlutuante').hide();
		});
		
		
		if($("img[name=modal]").attr("name") == "modal")
			new Draggable('dialog');
		
		$('img[name=modal]').click(function(e) {
			data = "IdGrupoDevice > 0 AND DisponivelContrato = 1 LIMIT 10";
		   
			e.preventDefault();
			
			$("input[name=DescricaoGrupoDevice]").val("");
			
			$.ajax({
				dataType: "html",
				type: "POST",
				url: "xml/grupo_device.php",
				data: {dadosWhereQuadro: data},
				beforeSend: function(){
					carregando(true);
				},
				complete: function(){
					carregando(false);
				},
				success: function(data){
					if($(".teste").is(":visible")){
						$(".teste").remove();
					}
					$('#listaDadosQuadroGrupoDevice').append(data);
					
					$(".dadosTable").on("click", function(){
						if($(this).css("background-color") == "rgb(255, 255, 255)"){
							$(this).css('background-color', "rgb(160, 196, 234)");
						}else{
							var id = $(this).find("td:eq(0)").text();
							var nome = $(this).find("td:eq(1)").text();
							
							$("#IdGrupoDevice").val(id);
							$("#DescricaoGrupoDevice").val(nome);
							
							$('#mask').hide();
							$('.quadroFlutuante').hide();
							$("#IdGrupoDevice").focus();
						}
					});
				}
			});
			
			var id = $(this).attr('id');
		    //Inicio mascara para escurecer a janela
			var maskHeight = $(document).height();
			var maskWidth = $(window).width();
		
			$('#mask').css({'width':maskWidth,'height':maskHeight});

			$('#mask').fadeIn(0);	
			$('#mask').fadeTo("slow",0);//Fim mascara escurecer janela	
		
			//Obter a altura da janela e largura
			var winH = $(window).height();
			var winW = $(window).width();
	              
			//$(id).css('top',  winH/2-$(id).height()/2);
			$(id).css('top',  $(document).scrollTop());
			$(id).css('left', winW/2-$(id).width()/2);
		
			$(id).fadeIn(0);
			
			$("input[name=DescricaoGrupoDevice]").focus();
		
		});
		
		$('.quadroFlutuante .close').click(function (e) {
			e.preventDefault();
			
			$('#mask').hide();
			$('.quadroFlutuante').hide();
		});		
		
		$('#mask').click(function () {
			$(this).hide();
			$('.quadroFlutuante').hide();
		});
		
		$("input[name=DescricaoGrupoDevice]").keyup(function(){
			//alert($(this).val());
			val = $(this).val();
			//var name = $(this).attr('name');
			var IdLoja = $("#IdLoja").val();
			//alert(IdLoja);
			val = ' IdLoja = '+ IdLoja +' AND DisponivelContrato = 1 AND DescricaoGrupoDevice LIKE \'%'+val+'%\'';
			
			//var dados = name + " " + val;
			//url.Like = name + " " + val;
			
			//if($(this).val() != ""){
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "xml/grupo_device.php",
					data: {dadosWhereQuadro: val},
					beforeSend: function(){
						carregando(true);
					},
					complete: function(){
						carregando(false);
					},
					success: function(data){
						//alert(data);
						if($(".teste").is(":visible")){
							$(".teste").remove();
						}
						
						
						$('#listaDadosQuadroGrupoDevice').append(data);
						
						$(".dadosTable").on("click", function(){
							if($(this).css("background-color") == "rgb(255, 255, 255)"){
								$(this).css('background-color', "rgb(160, 196, 234)");
							}else{
								var id = $(this).find("td:eq(0)").text();
								var nome = $(this).find("td:eq(1)").text();
								
								$("#IdGrupoDevice").val(id);
								$("#DescricaoGrupoDevice").val(nome);
								
								$('#mask').hide();
								$('.quadroFlutuante').hide();
								$("#IdGrupoDevice").focus();
							}
						});
					}
				});
			//}
		});
		
		$(".dadosTable").on("click", function(){
			if($(this).css("background-color") == "rgb(255, 255, 255)"){
				$(this).css('background-color', "rgb(160, 196, 234)");
			}else{
				var id = $(this).find("td:eq(0)").text();
				var nome = $(this).find("td:eq(1)").text();
				
				$("#IdGrupoDevice").val(id);
				$("#DescricaoGrupoDevice").val(nome);
				
				$('#mask').hide();
				$('.quadroFlutuante').hide();
				$("#IdGrupoDevice").focus();
			}
		});
		
	});
	
	(function($){
		$.fn.adcTabelaDevice = function(){
			var IdServico = $(this).val();
			
			if(IdServico != ""){
				$.ajax({
					dataType: 'html',
					type: 'POST',
					data: {IdServico: IdServico},
					url: 'xml/servico_grupo_device.php',
					success: function(data){
						
						$("#listaDevice").after(data);
						
						total = $("#tabelaDevice tr").length - 2;
						$("#tabelaDevice .tableListarTitleCad:last").find('.tableListarEspaco').text("Total: " + total);
						$(".dadosDevice:odd").css('background-color', 'rgb(226, 231, 237)');
					}
				});
			}
		}
	})(jQuery);
