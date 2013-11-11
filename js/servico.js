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
		if(document.formulario.AgruparLancamentosFinanceiros.value==""){
			mensagens(1);
			document.formulario.AgruparLancamentosFinanceiros.focus();
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
		switch(document.formulario.IdTipoServico.value){
			case '1':
				if(document.formulario.Periodicidade.value==""){
					mensagens(1);
					document.formulario.IdPeriodicidade.focus();
					return false;
				}
				break;
			case '3':
				if(document.formulario.ServicoAgrupador.value==""){
					mensagens(1);
					document.formulario.IdServicoAgrupador.focus();
					return false;
				}
				break;
			case '4':
				if(document.formulario.ServicoAgrupador.value==""){
					mensagens(1);
					document.formulario.IdServicoAgrupador.focus();
					return false;
				}
				break;
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
		if(document.formulario.ExecutarRotinas.value==""){
			mensagens(1);
			document.formulario.ExecutarRotinas.focus();
			return false;
		}
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
		var Periodicidade 		=	IdPeriodicidade+'¬'+QtdParcela+'¬'+TipoContrato+'¬'+IdLocalCobranca+'¬'+MesFechado+'¬'+QtdMesesFidelidade;
		var PeriodicidadeTemp 	=	IdPeriodicidade+'¬'+QtdParcela+'¬'+TipoContrato+'¬'+IdLocalCobranca+'¬'+MesFechado;
		
		if(document.formulario.Periodicidade.value == ''){
			document.formulario.Periodicidade.value = Periodicidade;
			ii = 0;
		}else{
			var tempFiltro	=	document.formulario.Periodicidade.value.split('#');
				
			ii=0; 
			
			while(tempFiltro[ii] != undefined){
				temp		=	tempFiltro[ii].split('¬');
				Filtro		=	temp[0]+'¬'+temp[1]+'¬'+temp[2]+'¬'+temp[3]+'¬'+temp[4];
				
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
			temp	=	tempFiltro[ii].split("¬");
			
			temp2	=	temp[0]+'¬'+temp[1]+'¬'+temp[2]+'¬'+temp[3]+'¬'+temp[4];
			
			if(temp2 != IdPeriodicidade+'¬'+QtdParcela+'¬'+TipoContrato+'¬'+IdLocalCobranca+'¬'+MesFechado){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "#" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		for(var i=0; i<document.getElementById('tabelaPeriodicidade').rows.length; i++){
			if(IdPeriodicidade+'¬'+QtdParcela+'¬'+TipoContrato+'¬'+IdLocalCobranca+'¬'+MesFechado == document.getElementById('tabelaPeriodicidade').rows[i].accessKey){
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
			var temp	=	tempFiltro[ii].split('¬');
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
						
						linha.accessKey  = IdPeriodicidade+'¬'+Qtd+'¬'+TipoContrato+'¬'+IdLocalCobranca+'¬'+MesFechado; 
						
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
			document.getElementById('cpPeriodicidade').style.display		=	'none';
			document.getElementById('cpAgrupado').style.display				=	'none';
			document.getElementById('titAtivacaoAutomatica').style.color	=	'#C10000';
			document.getElementById('titEmailCobranca').style.color			=	'#C10000';
			document.getElementById('titRotinasDiarias').style.color		=	'#C10000';
			document.formulario.DiasAvisoAposVencimento.disabled			=	false;
			document.formulario.AtivacaoAutomatica.disabled					=	false;
			document.formulario.EmailCobranca.disabled						=	false;
			document.formulario.ExecutarRotinas.disabled					=	false;
			document.formulario.DiasLimiteBloqueio.disabled					=	false;
			
			return false;
		}else{
			switch(IdTipoServico){
				case '1': //Periodico
					document.getElementById('cpPeriodicidade').style.display		=	'block';
					document.getElementById('cpAgrupado').style.display				=	'none';
					document.getElementById('titAtivacaoAutomatica').style.color	=	'#C10000';
					document.getElementById('titEmailCobranca').style.color			=	'#C10000';
					document.getElementById('titRotinasDiarias').style.color		=	'#C10000';
					document.formulario.DiasAvisoAposVencimento.disabled			=	false;
					document.formulario.AtivacaoAutomatica.disabled					=	false;
					document.formulario.EmailCobranca.disabled						=	false;
					document.formulario.ExecutarRotinas.disabled					=	false;
					document.formulario.DiasLimiteBloqueio.disabled					=	false;
					break;
				case '2': //Eventual
					document.getElementById('cpPeriodicidade').style.display		=	'none';
					document.getElementById('cpAgrupado').style.display				=	'none';
					document.getElementById('titAtivacaoAutomatica').style.color	=	'#000';
					document.getElementById('titEmailCobranca').style.color			=	'#000';
					document.getElementById('titRotinasDiarias').style.color		=	'#000';
					document.formulario.DiasAvisoAposVencimento.disabled			=	true;
					document.formulario.AtivacaoAutomatica.disabled					=	true;
					document.formulario.EmailCobranca.disabled						=	true;
					document.formulario.ExecutarRotinas.disabled					=	true;
					document.formulario.DiasLimiteBloqueio.disabled					=	true;
					break;
				case '3': //Agrupado
					document.getElementById('cpPeriodicidade').style.display		=	'none';
					document.getElementById('cpAgrupado').style.display				=	'block';
					document.getElementById('titServicoAgrupado').innerHTML			=	'Serviço Agrupado';
					document.getElementById('titAtivacaoAutomatica').style.color	=	'#000';
					document.getElementById('titEmailCobranca').style.color			=	'#000';
					document.getElementById('titRotinasDiarias').style.color		=	'#000';
					document.formulario.DiasAvisoAposVencimento.disabled			=	true;
					document.formulario.AtivacaoAutomatica.disabled					=	true;
					document.formulario.EmailCobranca.disabled						=	true;
					document.formulario.ExecutarRotinas.disabled					=	true;
					document.formulario.DiasLimiteBloqueio.disabled					=	true;
					break;
				case '4': //Automatico
					document.getElementById('cpPeriodicidade').style.display		=	'none';
					document.getElementById('cpAgrupado').style.display				=	'block';
					document.getElementById('titServicoAgrupado').innerHTML			=	'Serviço Automático';
					document.getElementById('titAtivacaoAutomatica').style.color	=	'#000';
					document.getElementById('titEmailCobranca').style.color			=	'#000';
					document.getElementById('titRotinasDiarias').style.color		=	'#000';
					document.formulario.DiasAvisoAposVencimento.disabled			=	true;
					document.formulario.AtivacaoAutomatica.disabled					=	true;
					document.formulario.EmailCobranca.disabled						=	true;
					document.formulario.ExecutarRotinas.disabled					=	true;
					document.formulario.DiasLimiteBloqueio.disabled					=	true;
					break;
			}
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
