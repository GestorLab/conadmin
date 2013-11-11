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
			}
		}	
	}
	function excluir(IdProcessoFinanceiro,IdStatus){
		if(IdProcessoFinanceiro == '' || IdProcessoFinanceiro == undefined){
			var IdProcessoFinanceiro = document.formulario.IdProcessoFinanceiro.value;
		}
		if(IdStatus == 3){
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
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdProcessoFinanceiro == document.getElementById('tableListar').rows[i].accessKey){
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
		if(document.formulario.Filtro_TipoLancamento.value==''){
			mensagens(1);
			document.formulario.Filtro_TipoLancamento.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	
	function inicia(){
		document.formulario.IdProcessoFinanceiro.focus();
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
	function busca_lancamento_financeiro(IdProcessoFinanceiro,Erro,IdStatusTemp){
		if(IdProcessoFinanceiro == undefined || IdProcessoFinanceiro==''){
			IdProcessoFinanceiro = 0;
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
	    
	   	url = "../administrativo/xml/demonstrativo.php?IdProcessoFinanceiro="+IdProcessoFinanceiro;
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
						document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
						document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
						document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";	
						
						// Fim de Carregando
						carregando2(false);
					}else{
						document.getElementById('cp_lancamentos_financeiros').style.display	=	'block';	
						var tam, linha, c0, c1, c2, c3, c4, c5, c6;
						var IdLancamentoFinanceiro,Nome,Valor,Referencia,Descricao,TotalValor=0,IdPessoa,Codigo,Tipo;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro").length; i++){	
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLancamentoFinanceiro")[i]; 
							nameTextNode = nameNode.childNodes[0];
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
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Tipo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Tipo = nameTextNode.nodeValue;
							
							tam 	= document.getElementById('tabelaLancFinanceiro').rows.length;
							linha	= document.getElementById('tabelaLancFinanceiro').insertRow(tam-1);
							
							if(tam%2 != 0){
								linha.style.backgroundColor = "#E2E7ED";
							}
							
							switch(IdStatus){
								case '0':
									linha.style.backgroundColor = "#FFD2D2";
									break;
								case '2': //Em Análise
									linha.style.backgroundColor = "#FFFFCA";
									break;
							}
							
							linha.accessKey = IdLancamentoFinanceiro; 
							
							c0	= linha.insertCell(0);
							c1	= linha.insertCell(1);	
							c2	= linha.insertCell(2);	
							c3	= linha.insertCell(3);
							c4	= linha.insertCell(4);
							c5	= linha.insertCell(5);
							c6	= linha.insertCell(6);
							c7	= linha.insertCell(7);
							
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
							
							c0.innerHTML = IdLancamentoFinanceiro;
							c0.style.padding =	"0 0 0 5px";
							
							c1.innerHTML = linkIni + Tipo + linkFim;
							c1.style.cursor  = "pointer";
							
							c2.innerHTML = linkIni + Codigo + linkFim;
							c2.style.cursor  = "pointer";
							
							c3.innerHTML = "<a href='cadastro_pessoa.php?IdPessoa="+IdPessoa+"'>"+Nome+"</a>";
							c4.style.cursor  = "pointer";
							
							c4.innerHTML = Descricao;
							
							c5.innerHTML = Referencia;
							
							c6.innerHTML = Valor.replace('.',',');
							c6.style.textAlign	=	"right";
							c6.style.padding =	"0 8px 0 0";
							
							if(IdStatusTemp == 3 || IdStatus == 0){
								c7.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'\">";
							}else{
								c7.innerHTML = "<img id='ImgLancamento"+IdLancamentoFinanceiro+"' src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"excluir_lancamento_financeiro("+IdLancamentoFinanceiro+")\">";
							}
							c7.style.cursor = "pointer";
							c7.style.textAlign = "center";
						}
						document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	formata_float(Arredonda(TotalValor,2)).replace('.',',');	
						document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: "+i;
						scrollWindow('bottom');
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
	function carregando2(acao){
		if(acao == true){
			document.getElementById("carregando2").style.display = 'block';
		}else{
			document.getElementById("carregando2").style.display = 'none';
		}
		return true;
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
    		
   			url = "files/excluir/excluir_lancamento_financeiro.php?IdLancamentoFinanceiro="+IdLancamentoFinanceiro;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						var numMsg = parseInt(xmlhttp.responseText);
						document.getElementById('helpText2table').style.display = 'block';
						mensagens2(numMsg);
						if(numMsg == 54){
							for(var i=0; i<document.getElementById('tabelaLancFinanceiro').rows.length; i++){
								if(IdLancamentoFinanceiro == document.getElementById('tabelaLancFinanceiro').rows[i].accessKey){
									document.getElementById('tabelaLancFinanceiro').rows[i].style.backgroundColor = '#FFD2D2';
									document.getElementById('ImgLancamento'+IdLancamentoFinanceiro).src = '../../img/estrutura_sistema/ico_del_c.gif';
									document.getElementById('ImgLancamento'+IdLancamentoFinanceiro).onclick = '';
									tableMultColor('tabelaLancFinanceiro','#E2E7ED');
									atualizaLogProcessamento(IdLancamentoFinanceiro,false);
									break;
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
			carregando(true);
	
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
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	function buscaVisualizar(){
		if(document.formulario.Visualizar.value == ''){
			if(document.formulario.IdProcessoFinanceiro.value != ''){
				busca_lancamento_financeiro(document.formulario.IdProcessoFinanceiro.value,false,document.formulario.IdStatus.value);
				document.formulario.Visualizar.value = true;
				document.formulario.bt_visualizar.value = 'Ocultar';
			}
		}else{
			document.formulario.Visualizar.value = '';
			document.formulario.bt_visualizar.value = 'Visualizar';
			
			while(document.getElementById('tabelaLancFinanceiro').rows.length > 2){
				document.getElementById('tabelaLancFinanceiro').deleteRow(1);
			}
			
			document.getElementById('cp_lancamentos_financeiros').style.display	=	'none';						
			document.getElementById('tabelaLancFinanceiroTotalValor').innerHTML	=	"0,00";	
			document.getElementById('tabelaLancFinanceiroTotal').innerHTML		=	"Total: 0";
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
		
	function remover_filtro_servico(IdServico){
		for(var i=0; i<document.getElementById('tabelaServico').rows.length; i++){
			if(IdServico == document.getElementById('tabelaServico').rows[i].accessKey){
				document.getElementById('tabelaServico').deleteRow(i);
				tableMultColor('tabelaServico');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.Filtro_IdServico.value.split(',');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdServico){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "," + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.Filtro_IdServico.value = novoFiltro;
		document.getElementById('totaltabelaServico').innerHTML	=	'Total: '+(ii-1);
	}
