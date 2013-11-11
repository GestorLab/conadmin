	var quantLancamentos = 0,temporizador=0;
		
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){
				case 'inserir':
					document.formulario.bt_inserir.disabled 	= false;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_visualizar.disabled 	= true;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_imprimir.disabled 	= true;
					document.formulario.bt_enviar.disabled 		= true;
					break;
				case 'alterar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= false;
					document.formulario.bt_visualizar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_imprimir.disabled 	= true;
					document.formulario.bt_enviar.disabled 		= true;
					break;
				case 'processar':
					document.formulario.bt_inserir.disabled 	= false;
					document.formulario.bt_alterar.disabled 	= false;
					document.formulario.bt_excluir.disabled 	= false;
					document.formulario.bt_processar.disabled 	= false;
					document.formulario.bt_visualizar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_imprimir.disabled 	= true;
					document.formulario.bt_enviar.disabled 		= true;
					break;
				case 'confirmar':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_visualizar.disabled 	= false;
					document.formulario.bt_confirmar.disabled 	= false;
					document.formulario.bt_cancelar.disabled 	= false;
					document.formulario.bt_imprimir.disabled 	= true;
					document.formulario.bt_enviar.disabled 		= true;
					break;
				case 'gerado':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_visualizar.disabled 	= false;
					document.formulario.bt_cancelar.disabled 	= false;
					document.formulario.bt_imprimir.disabled 	= false;
					document.formulario.bt_enviar.disabled 		= false;
					break;
				case 'imprimir':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_visualizar.disabled 	= false;
					document.formulario.bt_cancelar.disabled 	= false;
					document.formulario.bt_imprimir.disabled 	= false;
					document.formulario.bt_enviar.disabled 		= false;
					
					if(document.formulario.EmailEnviado.value == 'S'){
						document.formulario.bt_enviar.disabled 		= true;
					}
					break;
				case 'emprocessamento':
					document.formulario.bt_inserir.disabled 	= true;
					document.formulario.bt_alterar.disabled 	= true;
					document.formulario.bt_excluir.disabled 	= true;
					document.formulario.bt_processar.disabled 	= true;
					document.formulario.bt_visualizar.disabled 	= true;
					document.formulario.bt_confirmar.disabled 	= true;
					document.formulario.bt_cancelar.disabled 	= true;
					document.formulario.bt_imprimir.disabled 	= true;
					document.formulario.bt_enviar.disabled 		= true;
					break;
			}
		}	
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataNotaFiscal'){			
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return false;
		}		
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{			
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id=='DataNotaFiscal'){			
				document.getElementById(id).style.color='#C10000';
			}
			mensagens(0);
			return true;
		}			
	}
	
	function busca_nota_fiscal_data_emissao(IdLocalCobranca,Erro){			
		if(IdLocalCobranca == undefined || IdLocalCobranca==''){
			IdLocalCobranca = 0;
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
	    
	   	url = "../administrativo/xml/nota_fiscal_data_emissao.php?IdLocalCobranca="+IdLocalCobranca;
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
						document.formulario.DataEmissao.value = "";												
						// Fim de Carregando
						carregando(false);
					}else{								
						nameNode = xmlhttp.responseXML.getElementsByTagName("DataNotaFiscal")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DataNotaFiscal = nameTextNode.nodeValue;		
					
						document.formulario.DataEmissao.value = dateFormat(DataNotaFiscal);					
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
	function excluir(IdProcessoFinanceiro,IdStatus){
		if(IdProcessoFinanceiro == '' || IdProcessoFinanceiro == undefined){
			var IdProcessoFinanceiro = document.formulario.IdProcessoFinanceiro.value;
		}
		if(IdStatus == 2 || IdStatus == 3 || IdStatus == 4){
			return false;
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
    		
   			url = "files/excluir/excluir_processo_financeiro.php?IdProcessoFinanceiro="+IdProcessoFinanceiro;
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
								url = 'cadastro_processo_financeiro.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdProcessoFinanceiro == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}	
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
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
	function confirmar_processo(){
		if(confirm('ATENÇÃO\n\nVocê está prestes a confirmar um processo financeiro.\nDeseja realmente confirmar este processo?\n\n\nObs: Processo Irreversível.')){
			document.formulario.Acao.value = 'confirmar';
			document.formulario.submit();
		}		
	}
	function cancelar_processo(){
		if(confirm('ATENÇÃO\n\nVocê está prestes a cancelar todos os lancamentos deste processo financeiro.\nDeseja realmente confirmar este cancelamento?')){
			document.formulario.Acao.value = 'cancelar';
			document.formulario.submit();
		}	
	}
	function validar(){
		
		switch(document.formulario.AcaoTemp.value){
			case 'imprimir':
				return true;
				break;
		}

		if(document.formulario.MesReferencia.value==''){
			mensagens(1);
			document.formulario.MesReferencia.focus();
			return false;
		}else{
			if(val_Mes(document.formulario.MesReferencia.value) == false){
				document.getElementById('cp_MesReferencia').style.backgroundColor = '#C10000';
				document.getElementById('cp_MesReferencia').style.color='#FFF';
				document.formulario.MesReferencia.focus();
				mensagens(45);		
				return false;
			}else{
				document.getElementById('cp_MesReferencia').style.backgroundColor = '#FFF';
				document.getElementById('cp_MesReferencia').style.color='#C10000';
			}
		}
		if(document.formulario.MesVencimento.value==''){
			mensagens(1);
			document.formulario.MesVencimento.focus();
			return false;
		}else{
			if(val_Mes(document.formulario.MesVencimento.value) == false){
				document.getElementById('cp_MesVencimento').style.backgroundColor = '#C10000';
				document.getElementById('cp_MesVencimento').style.color='#FFF';
				document.formulario.MesVencimento.focus();
				mensagens(45);	
				return false;
			}else{
				document.getElementById('cp_MesReferencia').style.backgroundColor = '#FFF';
				document.getElementById('cp_MesReferencia').style.color='#C10000';
			}
		}
		if(document.formulario.MenorVencimento.value==''){
			mensagens(1);
			document.formulario.MenorVencimento.focus();
			return false;
		}
		if(document.formulario.Filtro_IdLocalCobranca.value==''){
			mensagens(1);
			document.formulario.Filtro_IdLocalCobranca.focus();
			return false;
		}
		if(document.formulario.IdStatusContratoObrigatoriedade.value == 1){
			if(document.getElementById('totaltabelaStatus').innerHTML == "Total: 0"){
				mensagens(1);
				document.formulario.IdStatusContrato.focus();
				return false;
			}
		}
		if(document.formulario.Filtro_TipoCobranca[0].selected==true){
			mensagens(1);
			document.formulario.Filtro_TipoCobranca.focus();		
			return false;
		}
		
		if(document.getElementById("DataNotaFiscal").style.display == 'block' && (document.formulario.AcaoTemp.value == "inserir" || document.formulario.AcaoTemp.value == "alterar" || document.formulario.AcaoTemp.value == "processar" || document.formulario.AcaoTemp.value == "confirmar")){
			if(document.formulario.DataNotaFiscal.value == ""){
				mensagens(1);
				document.formulario.DataNotaFiscal.focus();
				return false;
			}
			if((document.formulario.Data.value > dataConv(document.formulario.DataNotaFiscal.value, "d/m/Y", "Ymd") && (document.formulario.AcaoTemp.value == 'alterar' || document.formulario.AcaoTemp.value == 'inserir')) || (document.formulario.Data.value > dataConv(document.formulario.DataNotaFiscalTemp.value, "d/m/Y", "Ymd") && document.formulario.AcaoTemp.value != 'alterar' && document.formulario.AcaoTemp.value != 'inserir')){
				mensagens(143);	
				document.formulario.DataNotaFiscal.focus();	
				return false;
			} else if(dataConv(document.formulario.DataNotaFiscal.value, "d/m/Y", "Ymd") < dataConv(document.formulario.DataEmissao.value, "d/m/Y", "Ymd")){	
				mensagens(137);	
				document.formulario.DataNotaFiscal.focus();	
				return false;
			}
		}
		
		mensagens(0);
		return true;
	}
	
	function inicia(){
		document.formulario.IdProcessoFinanceiro.focus();
		listaLocalCobranca();
		statusInicial();
	}
	function verifica_mes(nome,campo){
		if(campo.value != ''){
			if(val_Mes(campo.value) == false){
				document.getElementById(nome).style.backgroundColor = '#C10000';
				document.getElementById(nome).style.color='#FFF';
				mensagens(45);			
			}else{
				document.getElementById(nome).style.backgroundColor = '#FFF';
				document.getElementById(nome).style.color='#C10000';
				mensagens(0);
				
				if(nome == 'cp_MesVencimento'){
					buscaVencimento(campo.value);
				}
			}
		}else{
			while(document.formulario.MenorVencimento.options.length > 0){
				document.formulario.MenorVencimento.options[0] = null;
			}
			
			addOption(document.formulario.MenorVencimento,"","");
			
		
			document.getElementById(nome).style.backgroundColor = '#FFF';
			document.getElementById(nome).style.color='#C10000';
			mensagens(0);
		}
	}
	function buscaVencimento(mesAno,MenorVencimento){
		if(mesAno == ""){
			while(document.formulario.MenorVencimento.options.length > 0){
				document.formulario.MenorVencimento.options[0] = null;
			}
			
			addOption(document.formulario.MenorVencimento,"","");
			
			return false;
		}else{
			var mes	=	new String(mes);
			var ano	=	new String(ano);
			
			var mes	=	mesAno.substring(0,2);
			var ano	=	mesAno.substring(3,7);
			
			mes		=	parseFloat(mes)-1;
			
			var qtdDias = 	new Array();
			
			qtdDias		=	[31,(ano%4 == 0) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];	
			
			while(document.formulario.MenorVencimento.options.length > 0){
				document.formulario.MenorVencimento.options[0] = null;
			}
			
			addOption(document.formulario.MenorVencimento,"","");
			
			if(qtdDias[mes] >=1){
				for(i=1;i<=qtdDias[mes];i++){
					addOption(document.formulario.MenorVencimento,i,i);
				}	
				if(MenorVencimento == "" || MenorVencimento == undefined){
					document.formulario.MenorVencimento[0].selected = true;			
				}else{
					for(i=0;i<document.formulario.MenorVencimento.options.length;i++){
						if(document.formulario.MenorVencimento[i].value == MenorVencimento){
							document.formulario.MenorVencimento[i].selected = true;			
						}
					}
				}
			}
		}
	}
	function cadastrar(Acao){	
		document.formulario.AcaoTemp.value = Acao;
		if(validar()==true){					
			if(Acao != ''){				
				document.formulario.Acao.value	=	Acao;
			}
			if(Acao == 'enviar'){
				if(document.formulario.EmailEnviado.value == 'S'){
					if(confirm("ATENÇÃO\n\nO(s) e-mail(s) já foram enviado(s).\nDeseja continuar?") == false){
						return false;
					}
				}		
			}
			document.formulario.submit();
		}
	}
	function busca_lancamento_financeiro(Cell,Order){
		var IdProcessoFinanceiro = document.formulario.IdProcessoFinanceiro.value;
		var IdStatusTemp = document.formulario.IdStatus.value;
		var contador = 0;
		var tam = document.getElementById('tabelaLancFinanceiro').rows.length;
		
		if(tam > 2){
			for(var i= tam;i > 2; i--){
				if(i > 2){
					document.getElementById('tabelaLancFinanceiro').deleteRow(i-2);
				}
				contador++;
			}
		}
		
		if(parseInt(document.formulario.QdtLancamentos.value) > parseInt(quantLancamentos)){
			window.location.replace('listar_lancamento_financeiro.php?IdProcessoFinanceiro='+IdProcessoFinanceiro+'&filtro_limit='+document.formulario.QdtLancamentos.value);
			return false;
		}
	    
		var OrderBy = new Array();
		OrderBy[0] = "IdLancamentoFinanceiro";
		OrderBy[1] = "IdContaReceber";
		OrderBy[2] = "Tipo";
		OrderBy[3] = "Codigo";
		OrderBy[4] = "Nome";
		OrderBy[5] = "Descricao";
		OrderBy[6] = "Referencia";
		OrderBy[7] = "Valor";
		OrderBy[8] = "";
		
		if((Cell != "" && Cell != undefined) && (Order != "" && Order != undefined))
			var url = "../administrativo/xml/processo_financeiro_lancamentos.php?IdProcessoFinanceiro="+IdProcessoFinanceiro+"&OrderBy="+OrderBy[Cell]+"&Order="+Order;
		else
			var url = "../administrativo/xml/processo_financeiro_lancamentos.php?IdProcessoFinanceiro="+IdProcessoFinanceiro;
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
			} else{		
				document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';
				var tam, linha, c0, c1, c2, c3, c4, c5, c6;
				var IdLancamentoFinanceiro,Nome,Valor,Referencia,Descricao,TotalValor=0,IdPessoa,Codigo,Tipo, NomeTemp, CorTemp;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					IdLancamentoFinanceiro = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Valor = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Codigo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Codigo = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Descricao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Descricao = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Referencia")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Referencia = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdPessoa = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("SequenciaCorNome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					SequenciaCorNome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
					nameTextNode = nameNode.childNodes[0];
					Tipo = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdContaReceber = nameTextNode.nodeValue;
					
					tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
					linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
					
					if(SequenciaCorNome == '1') {
						if(NomeTemp != Nome){
							if(CorTemp == "#FFF"){
								CorTemp = "#E2E7ED";
							} else {
								CorTemp = "#FFF";
							}
							
							NomeTemp = Nome;
						}
					} else {
						if(tam%2 != 0){
							CorTemp = "#E2E7ED";
						} else {
							CorTemp = "#FFF";
						}
						
						switch(IdStatus){
							case '0':
								CorTemp = "#FFD2D2";
								break;
							case '2': //Em Análise
								CorTemp = "#FFFFCA";
								break;
						}
					}
					
					linha.style.backgroundColor = CorTemp;
					linha.accessKey = IdLancamentoFinanceiro; 
					
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);	
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					c7	= linha.insertCell(7);
					c8	= linha.insertCell(8);
					
					switch(Tipo){
						case 'CO':
							linkIni	= "<a href='cadastro_contrato.php?IdContrato="+Codigo+"'>";	
							break;
						case 'EV':
							linkIni	= "<a href='cadastro_conta_eventual.php?IdContaEventual="+Codigo+"'>";	
							break;
						case 'OS':
							linkIni	= "<a href='cadastro_ordem_servico.php?IdOrdemServico="+Codigo+"'>";	
							break;
					}
					
					TotalValor	=	parseFloat(TotalValor) + parseFloat(Valor);
					
					linkFim	=	"</a>";
					
					c0.innerHTML = "<a href='cadastro_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"'>"+IdLancamentoFinanceiro+"</a>";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>"+IdContaReceber+"</IdContaReceber>";
					
					c2.innerHTML = linkIni + Tipo + linkFim;
					c2.style.cursor  = "pointer";
					
					c3.innerHTML = linkIni + Codigo + linkFim;
					c3.style.cursor  = "pointer";
					
					c4.innerHTML = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>"+Nome+"</a>";
					c4.style.cursor  = "pointer";
					
					c5.innerHTML = Descricao.substr(0,40);
					
					c6.innerHTML = Referencia;
					
					c7.innerHTML = Valor.replace('.',',');
					c7.style.textAlign	=	"right";
					c7.style.padding =	"0 8px 0 0";
					
					if(IdStatusTemp == 3 || IdStatus == 0){
						c8.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
					}else{
						c8.innerHTML = "<img id='ImgLancamento"+IdLancamentoFinanceiro+"' src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir_lancamento_financeiro("+IdLancamentoFinanceiro+")\">";
					}
					c8.style.cursor = "pointer";
					c8.style.textAlign = "center";
				}
				
				document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2)).replace('.',',');	
				document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: "+i;
				scrollWindow('bottom');
			}
		});
	}
	function mensagens2(n,Local){
		var msg='';
		var prioridade='';
		
		if(Local == '' || Local == undefined){
			Local = '';
		}
		if(n == 0){
			return help2(msg,prioridade);
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
	function excluir_lancamento_financeiro(IdLancamentoFinanceiro){
		if(IdLancamentoFinanceiro == ''){
			mensagens(6);
		}
		if(excluir_registro() == true){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == 'inserir'){
					return false;
				}
			}
    		
   			url = "files/excluir/excluir_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro;
			
			call_ajax(url,function (xmlhttp){
				var numMsg = parseInt(xmlhttp.responseText);
				document.getElementById('helpText2table').style.display = 'block';
				mensagens2(numMsg);
				switch(numMsg){
					case 54:
						for(var i=0; i<document.getElementById('tabelaLancFinanceiro').rows.length; i++){
							if(IdLancamentoFinanceiro == document.getElementById('tabelaLancFinanceiro').rows[i].accessKey){
								document.getElementById('tabelaLancFinanceiro').rows[i].style.backgroundColor = '#FFD2D2';
								document.getElementById('ImgLancamento'+IdLancamentoFinanceiro).src = '../../img/estrutura_sistema/ico_del_c.gif';
								document.getElementById('ImgLancamento'+IdLancamentoFinanceiro).onclick = '';
								tableMultColor('tabelaLancFinanceiro','#E2E7ED');
								atualizaLogProcessamento(IdLancamentoFinanceiro,false);
								i=document.getElementById('tabelaLancFinanceiro').rows.length;
							}
						}	
						break;
					case 121:
						var aux=0,valor=0,temp='';
						for(var i=0; i<document.getElementById('tabelaLancFinanceiro').rows.length; i++){
							if(IdLancamentoFinanceiro == document.getElementById('tabelaLancFinanceiro').rows[i].accessKey){
								document.getElementById('tabelaLancFinanceiro').deleteRow(i);
								tableMultColor('tabelaLancFinanceiro','#E2E7ED');
								atualizaLogProcessamento(IdLancamentoFinanceiro,false);
								aux=1;
								break;
							}
						}	
						if(aux==1){
							for(var i=1; i<(document.getElementById('tabelaLancFinanceiro').rows.length-1); i++){
								temp	=	document.getElementById('tabelaLancFinanceiro').rows[i].cells[6].innerHTML;
								temp2	=	temp.replace(',','.');
								if(temp2=='') temp2 = 0;
								valor	+=	parseFloat(temp2);
							}
							document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML		=	formata_float(Arredonda(valor,2),2).replace('.',',');
							document.getElementById("tabelaLancFinanceiroTotal").innerHTML			=	"Total: "+(document.getElementById('tabelaLancFinanceiro').rows.length-2);
						}	
						break;							
				}
				
			});
		}
	} 
	function excluir_conta_receber(IdContaReceber){
		if(cancelar_registro() == true){
			window.location.href = "cadastro_cancelar_conta_receber.php?IdContaReceber="+IdContaReceber;
		}
	} 
	function atualizaLogProcessamento(IdLancamentoFinanceiro,Erro,IdProcessoFinanceiro){
		if(IdProcessoFinanceiro == '' || IdProcessoFinanceiro == undefined){
			IdProcessoFinanceiro = '';
		}
		if(IdLancamentoFinanceiro == '' && IdProcessoFinanceiro == ''){
			IdLancamentoFinanceiro = 0;
			IdProcessoFinanceiro = 0;
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
	    
	    url = "xml/processo_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro+"&IdProcessoFinanceiro="+IdProcessoFinanceiro;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			//carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("LogProcessamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LogProcessamento = nameTextNode.nodeValue;						
				
						document.formulario.LogProcessamento.value			= LogProcessamento;
						
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				//carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function buscaVisualizar(primeiro){
		// Carregando...
		//carregando(true);
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdProcessoFinanceiro.value != ''){
				if(primeiro){
					order_table('tabelaLancFinanceiro', 1, 'asc', busca_lancamento_financeiro);
				}else{
					order_table('tabelaLancFinanceiro', 0, 'asc', busca_lancamento_financeiro);
				}
				
				document.formulario.Visualizar.value = true;
			}
		}else{
			document.formulario.Visualizar.value = '';
			
			while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
				document.getElementById('tabelaLancFinanceiro').deleteRow(1);
			}
			
			document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
			document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
			document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
			
			// Carregando...
			//carregando(false);
		}
		
		document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';	
	}
	
	function buscaVisualizarContaReceber(primeiro){
		// Carregando...
		//carregando(true);
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdProcessoFinanceiro.value != ''){
				if(primeiro){
					order_table('tabelaContaReceber', 0, 'asc', listarRecebimento);
				}else{
					order_table('tabelaContaReceber', 0, 'asc', listarRecebimento);
				}
				
				document.formulario.Visualizar.value = true;
			}
		}else{
			document.formulario.Visualizar.value = '';
			
			while(document.getElementById('tabelaContaReceber').rows.length > 2){
				document.getElementById('tabelaContaReceber').deleteRow(1);
			}
			
			document.getElementById('cp_contas_receber').style.display			=	'none';						
			document.getElementById('tabelaContaReceberTotalValor').innerHTML	=	"0,00";	
			document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
			
			// Carregando...
			//carregando(false);
		}
		
		document.getElementById('cp_contas_receber').style.display	=	'none';	
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
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaCidade').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
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
	function addContrato(){
		if(document.formulario.IdContrato.value != ""){
			busca_contrato(document.formulario.IdContrato.value,false,'AdicionarContrato');
		}else{
			document.formulario.IdContrato.value				=	"";
			document.formulario.IdServicoContrato.value			=	"";
			document.formulario.DescricaoServicoContrato.value	=	"";
			document.formulario.DescPeriodicidadeContrato.value	=	"";
			document.formulario.DescTipoContrato.value			=	"";
								
			document.formulario.IdContrato.focus();
		}
	}
	function addStatus(IdStatusContrato,ListarCampo){
		if(IdStatusContrato != "" && IdStatusContrato!=undefined){
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
		    
		    url = "xml/parametro_sistema.php?IdGrupoParametroSistema=69&IdParametroSistema="+IdStatusContrato;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 
		
				// Carregando...
				carregando(true);
		
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							document.formulario.IdStatusContrato.value			= '';
							
							// Fim de Carregando
							carregando(false);
						}else{
							var cont = 0; ii='';
							if(ListarCampo == '' || ListarCampo == undefined){
								if(document.formulario.Filtro_IdStatusContrato.value == ''){
									document.formulario.Filtro_IdStatusContrato.value = IdStatusContrato;
									ii = 0;
								}else{
									var tempFiltro	=	document.formulario.Filtro_IdStatusContrato.value.split(',');
										
									ii=0; 
									while(tempFiltro[ii] != undefined){
										if(tempFiltro[ii] != IdStatusContrato){
											cont++;		
										}
										ii++;
									}
									if(ii == cont){
										document.formulario.Filtro_IdStatusContrato.value = document.formulario.Filtro_IdStatusContrato.value + "," + IdStatusContrato;
									}
								}
							}else{
								ii=0;
							}
							if(ii == cont){
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdParametroSistema")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var IdParametroSistema = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("ValorParametroSistema")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var ValorParametroSistema = nameTextNode.nodeValue;
								
								var tam, linha, c0, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11;
								
								tam 	= document.getElementById('tabelaStatus').rows.length;
								linha	= document.getElementById('tabelaStatus').insertRow(tam-1);
								
								if(tam%2 != 0){
									linha.style.backgroundColor = "#E2E7ED";
								}
								
								linha.accessKey 			= IdParametroSistema; 
								
								c0	= linha.insertCell(0);	
								c1	= linha.insertCell(1);	
								c2	= linha.insertCell(2);
								
								c0.innerHTML = IdParametroSistema;
								c0.style.padding =	"0 0 0 5px";
								
								c1.innerHTML = ValorParametroSistema;
								
								if(document.formulario.IdStatus.value == 1){
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_status("+IdStatusContrato+")\"></tr>";
								}else{
									c2.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
								}
								c2.style.textAlign = "center";
								c2.style.cursor = "pointer";
								
								if(document.formulario.IdProcessoFinanceiro.value == ''){
									document.getElementById('totaltabelaStatus').innerHTML	=	'Total: '+(ii+1);
								}else{
									if(document.formulario.Erro.value != ''){
										scrollWindow('bottom');
									}
								}
							}
							
							document.formulario.IdStatusContrato.value			=	"";
						}
					}
					// Fim de Carregando
					carregando(false);
				} 
				return true;
			}
			xmlhttp.send(null);
		}else{
			document.formulario.IdStatusContrato.value			= '';
			document.formulario.IdStatusContrato.focus();
		}		
	}
	function remover_filtro_status(IdStatusContrato){
		for(var i=0; i<document.getElementById('tabelaStatus').rows.length; i++){
			if(IdStatusContrato == document.getElementById('tabelaStatus').rows[i].accessKey){
				document.getElementById('tabelaStatus').deleteRow(i);
				tableMultColor('tabelaStatus');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdStatusContrato.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdStatusContrato){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdStatusContrato.value = novoFiltro;
		document.getElementById('totaltabelaStatus').innerHTML	=	'Total: '+(ii-1);
	}
	
	function remover_filtro_grupo_pessoa(IdGrupoPessoa){
		for(var i=0; i<document.getElementById('tabelaGrupoPessoa').rows.length; i++){
			if(IdGrupoPessoa == document.getElementById('tabelaGrupoPessoa').rows[i].accessKey){
				document.getElementById('tabelaGrupoPessoa').deleteRow(i);
				tableMultColor('tabelaGrupoPessoa');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdGrupoPessoa.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdGrupoPessoa){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdGrupoPessoa.value = novoFiltro;
		document.getElementById('totaltabelaGrupoPessoa').innerHTML	=	'Total: '+(ii-1);
	}
	
	function addVencimentoContrato(VencimentoContrato,ListarCampo){
		if(VencimentoContrato != "" && VencimentoContrato!=undefined){
		
			var cont = 0; ii='';
			if(ListarCampo == '' || ListarCampo == undefined){
				if(document.formulario.Filtro_VencimentoContrato.value == ''){
					document.formulario.Filtro_VencimentoContrato.value = VencimentoContrato;
					ii = 0;
				}else{
					var tempFiltro	=	document.formulario.Filtro_VencimentoContrato.value.split(',');

					ii=0; 
					while(tempFiltro[ii] != undefined){
						if(tempFiltro[ii] != VencimentoContrato){							
							cont++;		
						}
						ii++;
					}
					if(ii == cont){
						document.formulario.Filtro_VencimentoContrato.value = document.formulario.Filtro_VencimentoContrato.value + "," + VencimentoContrato;
					}
				}
			}else{
				ii=0;
			}
			if(ii == cont){
																				
				var tam, linha, c0, c1;
				
				tam 	= document.getElementById('tabelaVencimentoContrato').rows.length;
				linha	= document.getElementById('tabelaVencimentoContrato').insertRow(tam-1);
				
				if(tam%2 != 0){
					linha.style.backgroundColor = "#E2E7ED";
				}
				
				linha.accessKey 			= VencimentoContrato; 
				
				c0	= linha.insertCell(0);	
				c1	= linha.insertCell(1);	
				
				c0.innerHTML = VencimentoContrato;
				c0.style.padding =	"0 0 0 5px";
							
				if(document.formulario.IdStatus.value == 1){
					c1.innerHTML = "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_filtro_vencimento_contrato("+VencimentoContrato+")\"></tr>";
				}else{
					c1.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
				}
				c1.style.textAlign = "center";
				c1.style.cursor = "pointer";

				if(document.formulario.IdProcessoFinanceiro.value == ''){
					document.getElementById('totaltabelaVencimentoContrato').innerHTML	=	'Total: '+(ii+1);
				}else{
					if(document.formulario.Erro.value != ''){
						scrollWindow('bottom');
					}
				}
			}
			
			document.formulario.VencimentoContrato.value			= "";			
			
		}else{
			document.formulario.VencimentoContrato.value			= '';
			document.formulario.VencimentoContrato.focus();
		}
	}
	
	function remover_filtro_vencimento_contrato(VencimentoContrato){
		for(var i=0; i<document.getElementById('tabelaVencimentoContrato').rows.length; i++){
			if(VencimentoContrato == document.getElementById('tabelaVencimentoContrato').rows[i].accessKey){
				document.getElementById('tabelaVencimentoContrato').deleteRow(i);
				tableMultColor('tabelaVencimentoContrato');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_VencimentoContrato.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != VencimentoContrato){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_VencimentoContrato.value 				= novoFiltro;
		document.getElementById('totaltabelaVencimentoContrato').innerHTML	= 'Total: '+(ii-1);
	}
	
	function verificaTipoLancamento(TipoLancamento){
		if(TipoLancamento == 1 || TipoLancamento == ''){
			document.getElementById("cp_filtro_vencimento_contrato").style.display  = 'block'; 			
			document.getElementById("cp_filtro_status").style.display 				= 'block';					
		}else{
			while(document.getElementById('tabelaVencimentoContrato').rows.length > 2){
				document.getElementById('tabelaVencimentoContrato').deleteRow(1);
			}															
			while(document.getElementById('tabelaStatus').rows.length > 2){
				document.getElementById('tabelaStatus').deleteRow(1);
			}
	
			document.getElementById('totaltabelaVencimentoContrato').innerHTML		= "Total: 0";																											
			document.getElementById('totaltabelaStatus').innerHTML					= "Total: 0";
			
			document.getElementById("cp_filtro_vencimento_contrato").style.display  = 'none'; 
			document.getElementById("cp_filtro_status").style.display 				= 'none';
		}
	}
	
	function verificaLocalCobranca(IdLocalCobranca){
		if(document.formulario.IdStatus.value == 2 || document.formulario.IdStatus.value == 3){
			busca_local_cobranca(IdLocalCobranca,false,document.formulario.Local.value);
		} else{
			busca_local_cobranca_geracao(IdLocalCobranca,false,document.formulario.Local.value);
		}
		
		busca_nota_fiscal_data_emissao(IdLocalCobranca,false);
	}
	
	/*function listaLocalCobranca(IdLocalCobrancaTemp, IdStatus){ Barra por: Gilmaico dia 01/06/2012 Motivo: Esta função se encontra fora do padrão CNT e se encontra com diversos erros atrapalhando o cadastro do processo financeiro.
		if(IdLocalCobrancaTemp == undefined || IdLocalCobrancaTemp==''){
			IdLocalCobrancaTemp = 0;
		}
		
		if(IdStatus == undefined || IdStatus == ''){
			IdStatus = 0;
		}
		
		var xmlhttp = false;
		var achou = 0;
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
	    
	    if(IdStatus == 2 || IdStatus == 3){
	    	url = "xml/local_cobranca.php";
	    } else{
	    	url = "xml/local_cobranca_geracao.php";
	    }

		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 				
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						while(document.formulario.Filtro_IdLocalCobranca.options.length > 0){
							document.formulario.Filtro_IdLocalCobranca.options[0] = null;
						}
						
						var nameNode, nameTextNode, DescricaoLocalCobranca, IdLocalCobranca;					
						
						addOption(document.formulario.Filtro_IdLocalCobranca,"","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalCobranca = nameTextNode.nodeValue;
						
							addOption(document.formulario.Filtro_IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
						}
						alert(IdLocalCobrancaTemp);
						if(IdLocalCobrancaTemp!=""){
							for(i=0;i<document.formulario.Filtro_IdLocalCobranca.length;i++){
								if(document.formulario.Filtro_IdLocalCobranca[i].value == IdLocalCobrancaTemp){
									document.formulario.Filtro_IdLocalCobranca[i].selected	=	true;
									document.formulario.Filtro_IdLocalCobrancaTemp.value = document.formulario.Filtro_IdLocalCobranca[i].value;
									achou = 1;
									break;
								}
								if(achou == 0){									
									document.formulario.Filtro_IdLocalCobranca[0].selected	=	true;
									document.formulario.Filtro_IdLocalCobrancaTemp.value = document.formulario.Filtro_IdLocalCobranca[0].value;
								}
							}
						}else{
							document.formulario.Filtro_IdLocalCobranca[0].selected	=	true;
							document.formulario.Filtro_IdLocalCobrancaTemp.value = "";
						}
					}else{
						while(document.formulario.Filtro_IdLocalCobranca.options.length > 0){
							document.formulario.Filtro_IdLocalCobranca.options[0] = null;
						}
						addOption(document.formulario.Filtro_IdLocalCobranca,"","");
					}					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}*/
	
	function chama_mascara(campo,event){
		switch(document.filtro.filtro_campo.value){
			case 'DataCadastro':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			case 'DataAlteracao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			case 'DataConfirmacao':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			case 'DataNotaFiscal':
				campo.maxLength	=	10;
				return mascara(campo,event,'date');
			case 'MesCadastro':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
			case 'MesAlteracao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
			case 'MesConfirmacao':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
			case 'MesVencimento':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
			case 'MesNotaFiscal':
				campo.maxLength	=	7;
				return mascara(campo,event,'mes');
			default:
				campo.maxLength	=	100;
		}
	}
	function listaLocalCobranca(IdLocalCobrancaTemp,IdStatus){
		if(IdLocalCobrancaTemp == "" || IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = "";
		}
		if(IdStatus == ""){
			IdStatus = 0;
		}
		
		if(IdStatus == 2 || IdStatus == 3){
	    	var url = "xml/local_cobranca.php";
	    } else{
	    	var url = "xml/local_cobranca_geracao.php";
	    }
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != 'false'){		
				while(document.formulario.Filtro_IdLocalCobranca.options.length > 0){
					document.formulario.Filtro_IdLocalCobranca.options[0] = null;
				}
				addOption(document.formulario.Filtro_IdLocalCobranca,"","");
				
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					IdLocalCobranca = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DescricaoLocalCobranca = nameTextNode.nodeValue;
				
					addOption(document.formulario.Filtro_IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);	
				}
				if(IdLocalCobrancaTemp != ""){	
					for(i=0;i<document.formulario.Filtro_IdLocalCobranca.length;i++){
						if(document.formulario.Filtro_IdLocalCobranca[i].value == IdLocalCobrancaTemp){
							document.formulario.Filtro_IdLocalCobranca[i].selected	=	true;
							document.formulario.Filtro_IdLocalCobrancaTemp.value = document.formulario.Filtro_IdLocalCobranca[i].value;
						}
					}
				}
				
				if(document.formulario.Filtro_IdLocalCobrancaTemp.value != ""){	
					for(i=0;i<document.formulario.Filtro_IdLocalCobranca.length;i++){
						if(document.formulario.Filtro_IdLocalCobranca[i].value == document.formulario.Filtro_IdLocalCobrancaTemp.value){
							document.formulario.Filtro_IdLocalCobranca[i].selected	=	true;
							document.formulario.Filtro_IdLocalCobrancaTemp.value = document.formulario.Filtro_IdLocalCobranca[i].value;
						}
					}
				}
					
				else{
					document.formulario.Filtro_IdLocalCobranca[0].selected	=	true;
				}
			}
		});
	}
	
	function buscaVisualizarTemp(IdCell, Order){
		var OrderBy = new Array();
		OrderBy[0] = "IdLancamentoFinanceiro";
		OrderBy[1] = "IdContaReceber";
		OrderBy[2] = "Tipo";
		OrderBy[3] = "Codigo";
		OrderBy[4] = "Nome";
		OrderBy[5] = "Descricao";
		OrderBy[6] = "Referencia";
		OrderBy[7] = "Valor";
		OrderBy[8] = "";
	}
	function Temporizador(){
		if(document.formulario.IdProcessoFinanceiro.value != ""){
			if(temporizador == 120){
				window.location = "./cadastro_processo_financeiro.php?IdProcessoFinanceiro="+document.formulario.IdProcessoFinanceiro.value;
				temporizador = 0;
			}
			temporizador++;
			setTimeout("Temporizador()",1000);
		}
	}
	//em desenvolvimento-Leonardo--11-02-13
	function listarRecebimento(Cell,Order){
		var IdProcessoFinanceiro = document.formulario.IdProcessoFinanceiro.value;
		var contador = 0;
		var tam = document.getElementById('tabelaContaReceber').rows.length;
		
		if(tam > 2){
			for(var i= tam;i > 2; i--){
				if(i > 2){
					document.getElementById('tabelaContaReceber').deleteRow(i-2);
				}
				contador++;
			}
		}
		
		var OrderBy = new Array();
		OrderBy[0] = "IdContaReceber";
		OrderBy[1] = "NumeroDocumento";
		OrderBy[2] = "NumeroNF";
		OrderBy[3] = "IdLocalCobranca";
		OrderBy[4] = "DataLancamento";
		OrderBy[5] = "DataVencimento";
		OrderBy[6] = "ValorContaReceber";
		
	   	var url = "xml/conta_receber_processo_financeiro.php?IdProcessoFinanceiro="+IdProcessoFinanceiro+"&OrderBy="+OrderBy[Cell]+"&Order="+Order;
		
		call_ajax(url, function(xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('cp_contas_receber').style.display	=	'none';						
				document.getElementById('tabelaContaReceberTotalValor').innerHTML	=	"0,00";	
				document.getElementById('tabelaContaReceberTotal').innerHTML		=	"Total: 0";	
			} else{		
				document.getElementById('cp_contas_receber').style.display	=	'block';
				var tam, linha, c0, c1, c2, c3, c4, c5, c6;
				var TotalValor=0,TotalValorFinal=0, NomeTemp, CorTemp;
				
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdContaReceber").length; i++){	
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceber")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdContaReceber = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var Nome = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroNF")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroNF = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataLancamento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataLancamento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DataVencimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalRecebimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoLocalRecebimento = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroDocumento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var NumeroDocumento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					var ValorContaReceber = nameTextNode.nodeValue;
					
					DataVencimento = DataVencimento.split("-");
					DataVencimento = DataVencimento[2]+"/"+DataVencimento[1]+"/"+DataVencimento[0];
					
					DataLancamento = DataLancamento.split("-");
					DataLancamento = DataLancamento[2]+"/"+DataLancamento[1]+"/"+DataLancamento[0];
					
					tam 	= document.getElementById('tabelaContaReceber').rows.length;
					linha	= document.getElementById('tabelaContaReceber').insertRow(tam-1);
					
					if(tam%2 != 0){
						CorTemp = "#E2E7ED";
					} else {
						CorTemp = "#FFF";
					}
					
					switch(IdStatus){
						case '0': //Cancelado
							CorTemp = "#FFF";
							break;
						case '2': //Quitado
							CorTemp = "#A7E7A7";
							break;
						case '7': //Excluido
							CorTemp = "#E2E7ED";
							break;
					}
					
					linha.style.backgroundColor = CorTemp;
					linha.accessKey = IdContaReceber; 
					
					c0	= linha.insertCell(0);
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					c7	= linha.insertCell(7);
					
					linkIni	= "<a href='cadastro_conta_receber.php?IdContaReceber="+IdContaReceber+"'>";
					linkFim	= "</a>";
					
					TotalValor	=	parseFloat(TotalValor) + parseFloat(ValorContaReceber);
					
					c0.innerHTML = linkIni+IdContaReceber+linkIni;
					c0.style.padding =	"0 0 0 5px";
					c0.style.cursor  = "pointer";
					
					c1.innerHTML = NumeroDocumento;
					
					c2.innerHTML = NumeroNF;
					
					c3.innerHTML = DescricaoLocalRecebimento;
					//c3.style.cursor  = "pointer";
					
					c4.innerHTML = DataLancamento;
					
					c5.innerHTML = DataVencimento;
					
					c6.innerHTML = formata_float(Arredonda(ValorContaReceber,2)).replace('.',',');
					c6.style.textAlign	=	"right";
					c6.style.padding =	"0 8px 0 0";
					
					if(IdStatus == 7 || IdStatus == 0){
						c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
					}else{
						c7.innerHTML = "<img id='ImgLancamento"+IdContaReceber+"' src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir_conta_receber("+IdContaReceber+")\">";
					}
					c7.style.cursor = "pointer";
					c7.style.textAlign = "center";
				}
				
				document.getElementById('tabelaContaReceberTotalValor').innerHTML			=	formata_float(Arredonda(TotalValor,2)).replace('.',',');
				document.getElementById('tabelaContaReceberTotal').innerHTML				=	"Total: "+i;
				scrollWindow('bottom');
			}
		});
	}
	function visualizar_botoes_financeiro(botao){
		if(document.formulario.Botoes_Financeiro.value == "Visualizar"){
			var qtdCR = document.formulario.QtdContaReceber.value;
			var qtdLF = document.formulario.QdtLancamentos.value;
		
			if(qtdLF > 0 && qtdCR == 0){
				busca_lancamento_financeiro();
			}else{
				if(qtdCR == 0){
					document.formulario.bt_CR.style.display = "none";
				}else{
					document.formulario.bt_CR.style.display = "block";
					scrollWindow('bottom');
				}
				if(qtdLF == 0){
					document.formulario.bt_LF.style.display = "none";
				}else{
					document.formulario.bt_LF.style.display = "block";
					scrollWindow('bottom');
				}
				if(qtdCR == 0 && qtdLF == 0){
					document.getElementById('mostra_LF_CR').style.display = "none";
					mensagens(189);
				}else{
					document.getElementById('mostra_LF_CR').setAttribute("style","");
					scrollWindow('bottom');
				}
			}
			
			document.formulario.bt_visualizar.value 	= "Ocultar";
			document.formulario.Botoes_Financeiro.value = "Ocultar";
		}else{
			document.getElementById('mostra_LF_CR').setAttribute("style","display:none");
			document.getElementById('cp_lancamentos_financeiros').style.display = "none";
			document.getElementById('cp_contas_receber').style.display = "none";
			
			document.formulario.bt_visualizar.value 	= "Visualizar";
			document.formulario.Botoes_Financeiro.value = "Visualizar";
			
			mensagens(0);
		}
	}