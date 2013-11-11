	function busca_conta_receber_vencimento(IdContaReceber,DataVencimento,Erro,Local){
		if(IdContaReceber == '' || IdContaReceber == undefined){
			IdContaReceber = 0;
		}
		
		if(DataVencimento=='' || DataVencimento==undefined){
			DataVencimento = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var nameNode, nameTextNode, url;
		
	   	url = "xml/conta_receber_vencimento.php?IdContaReceber="+IdContaReceber+"&DataVencimento="+DataVencimento+"&DataVencimentoAntiga="+document.formulario.DataVencimentoAntiga.value;
	   	
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.formulario.CalcularMulta.value 			= "";
				document.formulario.TaxaReimpressao.value 			= "";
				document.formulario.ValorVencimento.value			= document.formulario.ValorPrimeiroVencimento.value;
				document.formulario.ValorMoraMulta.value 			= "";
				document.formulario.ValorOutrasDespesas.value		= "";
				document.formulario.ValorDescontoVencimento.value	= "";
				document.formulario.PercentualVencimento.value		= "";
				document.formulario.ValorJurosVencimento.value		= "";
				document.formulario.ValorFinalVencimento.value		= document.formulario.ValorFinal.value;
				document.formulario.DataCriacao.value 				= "";
				document.formulario.LoginCriacao.value 				= "";
				document.formulario.Acao.value 						= 'alterar';										
				
				
				
				if(document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value == 0){
					document.formulario.TaxaReimpressao.value 		=	2;
				}else{
					document.formulario.TaxaReimpressao.value 		=	"";
				}
				document.formulario.DataVencimentoAntiga.value = document.formulario.DataPrimeiroVencimento.value;/*						
				if(document.formulario.DataVencimento.value!=""){
					var qtdDias	=	difDias(document.formulario.DataVencimento.value,document.formulario.DataVencimentoAntiga.value);
		
					document.formulario.QuantDias.value	=	qtdDias;
				}else{
					document.formulario.QuantDias.value	=	"";
				}*/
				
				quant_dias(document.formulario.DataVencimentoAntiga.value);
				
				document.formulario.ValorTaxaReImpressaoBoleto.value= document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value;
				document.formulario.ValorTaxaReImpressaoBoleto.value= '0,00';
				
				document.formulario.DataVencimento.focus();
				statusInicial();
				calculaValor(document.formulario.CalcularMulta.name);
				verificaAcao();
				
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataVencimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimentoAntiga")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataVencimentoAntiga = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMulta = nameTextNode.nodeValue;

				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[0]; 						
				nameTextNode = nameNode.childNodes[0];
				var ValorJuros = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorDesconto = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0];
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0];
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTotal")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorTotal = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[0];
				nameTextNode = nameNode.childNodes[0];
				var ValorFinal = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ManterDescontoAConceber")[0];
				nameTextNode = nameNode.childNodes[0];
				var ManterDescontoAConceber = nameTextNode.nodeValue;
				
				if(ValorDesconto != 0 && ValorDesconto != ""){
					var PercentualVencimento	=	(parseFloat(ValorDesconto)*100)/parseFloat(ValorTotal);			
				}else{
					var PercentualVencimento	=	"";
				}
				if(ValorMulta == 0 && ValorJuros == 0){
					document.formulario.CalcularMulta.value  = 2;	
				}else{
					document.formulario.CalcularMulta.value  = 1;	
				}
				
				if(ValorTaxaReImpressaoBoleto == 0 || ValorTaxaReImpressaoBoleto == ""){
					document.formulario.TaxaReimpressao.value = 2;
				}else{
					document.formulario.TaxaReimpressao.value = 1;
				}
				
				document.formulario.DataVencimento.value				=	dateFormat(DataVencimento);
				document.formulario.ValorVencimento.value				=	formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
				document.formulario.ValorMoraMulta.value				=	formata_float(Arredonda(ValorMulta,2),2).replace(".",",");
				document.formulario.ValorJurosVencimento.value			=	formata_float(Arredonda(ValorJuros,2),2).replace(".",",");
				document.formulario.ValorTaxaReImpressaoBoleto.value	= 	formata_float(Arredonda(ValorTaxaReImpressaoBoleto,2),2).replace(".",",");
				document.formulario.ValorOutrasDespesas.value			= 	formata_float(Arredonda(ValorOutrasDespesas,2),2).replace(".",",");
				document.formulario.ValorDescontoVencimento.value		= 	formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
				document.formulario.PercentualVencimento.value			=	formata_float(Arredonda(PercentualVencimento,2),2).replace(".",",");
				document.formulario.ValorFinalVencimento.value			= 	formata_float(Arredonda(ValorFinal,2),2).replace(".",",");
				document.formulario.ManterDescontoAConceber.value		= 	ManterDescontoAConceber;
				document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
				document.formulario.LoginCriacao.value 					= 	LoginCriacao;
				//document.formulario.Acao.value 							= 	'inserir';
				
				//alert(document.formulario.DataVencimentoAntiga.value+"----"+document.formulario.DataPrimeiroVencimento.value);
				document.formulario.DataVencimentoAntiga.value = document.formulario.DataPrimeiroVencimento.value;
				
				var qtdDias	=	difDias(dateFormat(DataVencimento), dateFormat(DataVencimentoAntiga));
				
				if(qtdDias < 0){
					qtdDias = 0;
				}
				
				document.formulario.QuantDias.value						=   qtdDias;
				
				if(qtdDias < 0){
					document.formulario.CalcularMulta.disabled = true;
					document.formulario.CalcularMulta.value = 2;
				}else{
					document.formulario.CalcularMulta.disabled = false;
				}
				
				listarVencimento(IdContaReceber,DataVencimento,false);
				verificaAcao();
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function quant_dias(Data){
		if(Data == '' || Data == undefined){
			Data = 0;
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
	
	   	url = "xml/dia_util.php?Data="+Data;
	   	xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
			// Carregando...
			carregando(true);
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.formulario.QuantDias.value	= "";
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
						nameTextNode = nameNode.childNodes[0];
						Data = nameTextNode.nodeValue;
						
						if(Data != ""){
							var qtdDias = difDias(document.formulario.DataVencimento.value, dateFormat(Data));
							
							if(qtdDias < 0){
								qtdDias = 0;
							}
							
							document.formulario.QuantDias.value	= qtdDias;
						} else{
							document.formulario.QuantDias.value	= "";
						}
					}
				}// fim do else
				// Fim de Carregando
				carregando(false);
			}//fim do if status
			return true;
		}
		xmlhttp.send(null);
	}
	function excluir(IdContaReceber,DataVencimento){
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
    
   			url = "files/excluir/excluir_conta_receber_vencimento.php?IdContaReceber="+IdContaReceber+"&DataVencimento="+DataVencimento;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						document.formulario.Erro.value = xmlhttp.responseText;
						if(parseInt(xmlhttp.responseText) == 7){
							document.formulario.Acao.value 	= 'alterar';
							url = 'cadastro_conta_receber_vencimento.php?IdContaReceber='+IdContaReceber+'&Erro='+document.formulario.Erro.value;
							window.location.replace(url);
						}else{
							verificaErro();
						}
					}//if
					// Fim de Carregando
					carregando(false);
				}//if
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function cadastrar(acao){
		document.formulario.Acao.value	=	acao;
		
		switch(acao){
			case 'alterar':
				if(validar(acao)==true){
					document.formulario.submit();
				}
				break;
			default:
				document.formulario.submit();
		}
	}
	function voltar(){
		location.replace('cadastro_conta_receber.php?IdContaReceber='+document.formulario.IdContaReceber.value);
	}
	function validar(){
		if(document.formulario.DataVencimento.value==''){
			mensagens(1);
			document.formulario.DataVencimento.focus();
			return false;
		}else{
			if(isData(document.formulario.DataVencimento.value) == false){
				mensagens(27);
				document.formulario.DataVencimento.focus();
				return false;
			}
		}
		if(document.formulario.CalcularMulta.value==''){
			mensagens(1);
			document.formulario.CalcularMulta.focus();
			return false;
		}
		if(document.formulario.TaxaReimpressao.value==''){
			mensagens(1);
			document.formulario.TaxaReimpressao.focus();
			return false;
		}
		if(Number(document.formulario.ValorDescontoAConceber.value.replace(/\./g,'').replace(/,/i,'.')) > 0 && document.formulario.ManterDescontoAConceber.value==''){
			mensagens(1);
			document.formulario.ManterDescontoAConceber.focus();
			return false;
		}
		if(document.formulario.ValorDescontoVencimento.value == ''){
			mensagens(1);
			document.formulario.ValorDescontoVencimento.focus();
			return false;
		}
		if(document.formulario.PercentualVencimento.value == ''){
			mensagens(1);
			document.formulario.PercentualVencimento.focus();
			return false;
		}
		if(document.formulario.Acao.value == 'inserir'){
			return false;
		}
		mensagens(0);
		return true;
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
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;		
		}else{	
			if(id='DataVencimento'){
				verificaDataFinal('DataVencimento',document.formulario.DataVencimentoAntiga.value,campo.value);
			}else{
				document.getElementById(id).style.backgroundColor='#FFFFFF';
				document.getElementById(id).style.color='#C10000';
				mensagens(0);
				return true;
			}
		}	
	}
	
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF <= dataI){
				document.formulario.CalcularMulta.disabled = true;
				document.formulario.CalcularMulta.value = 2;						
			}else{
				document.formulario.CalcularMulta.disabled = false;
				document.getElementById(campo).style.backgroundColor = '#FFFFFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
				
			}
			return true;
		}
	}
	
	function verificaAcao(){
		if(document.formulario.Acao.value == 'inserir'){
			document.formulario.bt_alterar.disabled		=	true;
		}else{
			document.formulario.bt_alterar.disabled		=	false;
		}
	}
	function inicia(){
		document.formulario.DataVencimento.focus();
		statusInicial();
	}
	function calculaValor(campo){
		if(campo == undefined) campo="";
	
		var desc, valor, valorFinal, valorTotal, moraC, mora, juros, taxa, perc, tempPerc, qtdDias, percM, percJ, desp;
		
		if(campo == 'TaxaReimpressao'){
			if(document.formulario.TaxaReimpressao.value == 2 || document.formulario.TaxaReimpressao.value == ''){
				document.formulario.ValorTaxaReImpressaoBoleto.value	=	'0,00';	
			}else{
				document.formulario.ValorTaxaReImpressaoBoleto.value	=	document.formulario.ValorTaxaReImpressaoBoletoLocalCobranca.value;	
			}
		}
		if(campo == 'CalcularMulta' && (document.formulario.CalcularMulta.value == 2 || document.formulario.CalcularMulta.value == '')){
			document.formulario.ValorJurosVencimento.value	=	'0,00';
			document.formulario.ValorMoraMulta.value		=	'0,00';
		}
		
		if(campo == 'PercentualVencimento'){
			document.formulario.ValorDescontoVencimento.value	=	'0,00';
		}
		if(campo == 'ValorDescontoVencimento'){
			document.formulario.PercentualVencimento.value		=	'0,00';
		}
		
		if(document.formulario.ValorDescontoVencimento.value == ''){	desc  	= 0;	}else{		desc		= document.formulario.ValorDescontoVencimento.value;	}
		if(document.formulario.ValorMoraMulta.value == ''){				mora  	= 0;	}else{		mora  		= document.formulario.ValorMoraMulta.value;				}
		if(document.formulario.ValorJurosVencimento.value == ''){		juros 	= 0;	}else{		juros		= document.formulario.ValorJurosVencimento.value;		}
		if(document.formulario.ValorTaxaReImpressaoBoleto.value == ''){	taxa  	= 0;	}else{		taxa  		= document.formulario.ValorTaxaReImpressaoBoleto.value;	}
		if(document.formulario.ValorOutrasDespesas.value == ''){		desp  	= 0;	}else{		desp  		= document.formulario.ValorOutrasDespesas.value;		}
		if(document.formulario.PercentualVencimento.value == ''){		perc  	= '0,00';	}else{	perc  		= document.formulario.PercentualVencimento.value;		}
		if(document.formulario.ValorVencimento.value == ''){			valor  	= 0;	}else{		valor  		= document.formulario.ValorVencimento.value;			}
		if(document.formulario.QuantDias.value==''){					qtdDias = 0;	}else{		qtdDias 	= document.formulario.QuantDias.value;					}  
		if(document.formulario.PercentualMulta.value==''){				percM	= 0;	}else{		percM		= document.formulario.PercentualMulta.value;			}  
		if(document.formulario.PercentualJurosDiarios.value==''){		percJ	= 0;	}else{		percJ		= document.formulario.PercentualJurosDiarios.value;		}  
		if(document.formulario.ValorFinal.value==''){					valorAtual	= 0;}else{		valorAtual	= document.formulario.ValorFinal.value;		}  
			
		desc	=	new String(desc);
		desc	=	desc.replace('.','');
		desc	=	desc.replace('.','');
		desc	=	desc.replace(',','.');
		
		mora	=	new String(mora);;
		mora	=	mora.replace('.','');
		mora	=	mora.replace('.','');
		mora	=	mora.replace(',','.');
		
		juros	=	new String(juros);
		juros	=	juros.replace('.','');
		juros	=	juros.replace('.','');
		juros	=	juros.replace(',','.');
		
		taxa	=	new String(taxa);;
		taxa	=	taxa.replace('.','');
		taxa	=	taxa.replace('.','');
		taxa	=	taxa.replace(',','.');
		
		desp	=	new String(desp);;
		desp	=	desp.replace('.','');
		desp	=	desp.replace('.','');
		desp	=	desp.replace(',','.');
		
		perc	=	new String(perc);;
		perc	=	perc.replace('.','');
		perc	=	perc.replace('.','');
		perc	=	perc.replace(',','.');
		
		valor	=	new String(valor);;
		valor	=	valor.replace('.','');
		valor	=	valor.replace('.','');
		valor	=	valor.replace(',','.');
		
		valorAtual	=	new String(valorAtual);;
		valorAtual	=	valorAtual.replace('.','');
		valorAtual	=	valorAtual.replace('.','');
		valorAtual	=	valorAtual.replace(',','.');
		
		if(document.formulario.CalcularMulta.value == 1 && campo == 'CalcularMulta'){
			mora	= 	Arredonda((parseFloat(valor) * parseFloat(percM)) / 100,2);
			juros	= 	Arredonda((parseFloat(valor) * parseFloat(percJ) / 100) * parseInt(qtdDias),2);
			
			document.formulario.ValorMoraMulta.value		=	formata_float(Arredonda(mora,2),2).replace('.',',');		
			document.formulario.ValorJurosVencimento.value	=	formata_float(Arredonda(juros,2),2).replace('.',',');	
		}
		
		valorTotal	=	parseFloat(valor) + parseFloat(mora) + parseFloat(juros) + parseFloat(taxa) + parseFloat(desp);
		valorFinal	=	Arredonda(formata_float(valorTotal,2),2) - parseFloat(desc);
		
		if(campo == 'ValorDescontoVencimento' || (desc != '' && desc != '0')){
			tempPerc =	(parseFloat(desc) * 100) / parseFloat(valorTotal);
			tempPerc = 	formata_float(Arredonda(tempPerc,2),2);
			tempPerc =	tempPerc.replace('.',',');
			
			document.formulario.PercentualVencimento.value	=	tempPerc;
		}else if(campo == 'PercentualVencimento'){
			desc		=	(parseFloat(perc) * parseFloat(valorTotal)) / 100;
			valorFinal	=	valorFinal - desc;
			desc		= 	formata_float(Arredonda(desc,2),2);
			desc		=	desc.replace('.',',');
			
			document.formulario.ValorDescontoVencimento.value	=	desc;
		}
		
		valorFinal	=	Arredonda(valorFinal,2);
		valorFinal	=	formata_float(valorFinal,2);
		
		document.formulario.ValorFinalVencimento.value	=	valorFinal.replace('.',',');
	}	
	function listarVencimento(IdContaReceber,Erro,DataVencimentoTemp){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;
		}
		if(DataVencimentoTemp == undefined){
			DataVencimentoTemp = '';
		}
		
		var nameNode, nameTextNode, url, Condicao;
	    
	   	url = "xml/conta_receber_vencimento.php?IdContaReceber="+IdContaReceber;
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText == 'false'){
				document.getElementById('totalValorReceber').innerHTML				=	"0,00";						
				document.getElementById('totalValorMulta').innerHTML				=	"0,00";	
				document.getElementById('totalValorJuros').innerHTML				=	"0,00";						
				document.getElementById('totalValorTaxa').innerHTML					=	"0,00";	
				document.getElementById('totalValorOutrasDespessas').innerHTML		=	"0,00";	
				document.getElementById('totalValorDesconto').innerHTML				=	"0,00";						
				document.getElementById('totalValorFinal').innerHTML				=	"0,00";	
				document.getElementById('totalVencimentos').innerHTML				=	"Total: 0";	
				
				// Fim de Carregando
				carregando(false);
			}else{
				while(document.getElementById('tabelaVencimentos').rows.length > 2){
					document.getElementById('tabelaVencimentos').deleteRow(1);
				}
				
				var tam, linha, c0, c1, c2, c3, c4;
				var DataVencimento,ValorDesconto,ValorOutrasDespesas,ValorMulta,ValorTaxaReImpressaoBoleto,LoginCriacao,ValorJuros;
				var TotalValor=0,TotalMulta=0,TotalJuros=0,TotalTaxa=0,TotalDesc=0,TotalFinal=0,TotalDesp=0;
				for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataVencimento").length; i++){	
					nameNode = xmlhttp.responseXML.getElementsByTagName("DataVencimento")[i]; 
					nameTextNode = nameNode.childNodes[0];
					DataVencimento = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorContaReceber = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMulta")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorMulta = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorJuros")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorJuros = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorTaxaReImpressaoBoleto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorTaxaReImpressaoBoleto = nameTextNode.nodeValue;	
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorOutrasDespesas = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorDesconto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
					nameTextNode = nameNode.childNodes[0];
					LoginCriacao = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ValorFinal")[i]; 
					nameTextNode = nameNode.childNodes[0];
					ValorFinal = nameTextNode.nodeValue;
					
					if(ValorContaReceber == '')  			ValorContaReceber = '0.00';
					if(ValorMulta == '')					ValorMulta = '0.00';
					if(ValorJuros == '')					ValorJuros = '0.00';
					if(ValorTaxaReImpressaoBoleto == '')	ValorTaxaReImpressaoBoleto = '0.00';
					if(ValorOutrasDespesas == '')			ValorOutrasDespesas = '0.00';
					if(ValorDesconto == '')					ValorDesconto = '0.00';
					if(ValorFinal == '')					ValorFinal = '0.00';
					
					
					TotalValor	=	TotalValor + parseFloat(ValorContaReceber);
					TotalMulta	=	TotalMulta + parseFloat(ValorMulta);
					TotalJuros	=	TotalJuros + parseFloat(ValorJuros);
					TotalTaxa	=	TotalTaxa  + parseFloat(ValorTaxaReImpressaoBoleto);
					TotalDesp	=	TotalDesp  + parseFloat(ValorOutrasDespesas);
					TotalDesc	=	TotalDesc  + parseFloat(ValorDesconto);
					
					TotalFinal	=	TotalFinal + parseFloat(ValorFinal);
					
					tam 	= document.getElementById('tabelaVencimentos').rows.length;
					linha	= document.getElementById('tabelaVencimentos').insertRow(tam-1);
					if(tam%2 != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					if(DataVencimento == DataVencimentoTemp){
						linha.style.backgroundColor = "#A0C4EA";
					}
					
					linha.accessKey = DataVencimento; 
					
					c0	= linha.insertCell(0);	
					c1	= linha.insertCell(1);	
					c2	= linha.insertCell(2);	
					c3	= linha.insertCell(3);
					c4	= linha.insertCell(4);
					c5	= linha.insertCell(5);
					c6	= linha.insertCell(6);
					c7	= linha.insertCell(7);
					c8	= linha.insertCell(8);
					c9	= linha.insertCell(9);
					c10	= linha.insertCell(10);
					
					var linkIni = "<a onClick=\"busca_conta_receber_vencimento("+IdContaReceber+",'"+DataVencimento+"')\">";
					var linkFim = "</a>";
					
					var Data	=	dateFormat(DataVencimento);
					
					c0.innerHTML = linkIni + (i+1) + linkFim;
					c0.style.cursor  = "pointer";
					c0.style.padding =	"0 0 0 5px";
					
					c1.innerHTML = linkIni + Data + linkFim;
					c1.style.cursor = "pointer";
					
					c2.innerHTML = linkIni + ValorContaReceber.replace('.',',')+ linkFim + "&nbsp;&nbsp;" ;
					c2.style.cursor = "pointer";
					c2.style.textAlign = "right";
					
					c3.innerHTML = linkIni + ValorMulta.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c3.style.cursor = "pointer";
					c3.style.textAlign = "right";
					
					c4.innerHTML = linkIni + ValorJuros.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c4.style.cursor = "pointer";
					c4.style.textAlign = "right";
					
					c5.innerHTML = linkIni + ValorTaxaReImpressaoBoleto.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c5.style.cursor = "pointer";
					c5.style.textAlign = "right";
					
					c6.innerHTML = linkIni + ValorOutrasDespesas.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c6.style.cursor = "pointer";
					c6.style.textAlign = "right";							
					
					c7.innerHTML = linkIni + ValorDesconto.replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c7.style.cursor = "pointer";
					c7.style.textAlign = "right";
					
					c8.innerHTML = linkIni + formata_float(Arredonda(ValorFinal,2),2).replace('.',',') + linkFim + "&nbsp;&nbsp;";
					c8.style.cursor = "pointer";
					c8.style.textAlign = "right";
					
					c9.innerHTML = linkIni + LoginCriacao + linkFim;
					c9.style.cursor = "pointer";
					
					if(i == (xmlhttp.responseXML.getElementsByTagName("DataVencimento").length)-1 && xmlhttp.responseXML.getElementsByTagName("DataVencimento").length > 1){
						c10.innerHTML    = "<a onClick=\"excluir("+IdContaReceber+",'"+DataVencimento+"')\"><img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?'></a>";
					}else{
						c10.innerHTML    = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
					}
					c10.style.cursor = "pointer";
				}
				
				document.getElementById('totalValorReceber').innerHTML			=	formata_float(Arredonda(TotalValor,2),2).replace('.',',');						
				document.getElementById('totalValorMulta').innerHTML			=	formata_float(Arredonda(TotalMulta,2),2).replace('.',',');	
				document.getElementById('totalValorJuros').innerHTML			=	formata_float(Arredonda(TotalJuros,2),2).replace('.',',');						
				document.getElementById('totalValorTaxa').innerHTML				=	formata_float(Arredonda(TotalTaxa,2),2).replace('.',',');	
				document.getElementById('totalValorOutrasDespessas').innerHTML	=	formata_float(Arredonda(TotalDesp,2),2).replace('.',',');	
				document.getElementById('totalValorDesconto').innerHTML			=	formata_float(Arredonda(TotalDesc,2),2).replace('.',',');						
				document.getElementById('totalValorFinal').innerHTML			=	formata_float(Arredonda(TotalFinal,2),2).replace('.',',');	
				document.getElementById('totalVencimentos').innerHTML			=	"Total: "+i;	
			
			}	
			var table = document.getElementById('tabelaVencimentos');
			var rows = table.getElementsByTagName("tr");
			for (i = 1; i < rows.length; i++) {
				row = table.rows[i];
				row.onclick = 	function(){
									var index = this.rowIndex;
									if(index == rows.length-2){
										habilita_desconto_ultimo_vencimento("habilita");
									}else{
										habilita_desconto_ultimo_vencimento("desabilita");
									}
								  
							  };
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}
	function visualizar_boleto(IdContaReceber){
		if(IdContaReceber == undefined || IdContaReceber==''){
			IdContaReceber = 0;			
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
	    
	    url = "xml/conta_receber_visualizar_boleto.php?IdContaReceber="+IdContaReceber;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						nameNode = xmlhttp.responseXML.getElementsByTagName("Url")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Url = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Erro")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Erro = nameTextNode.nodeValue;
						
						if(Erro == 0){
							window.open(Url);
						} else{
							document.formulario.Erro.value = Erro;
							verificaErro();
						}
					}
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
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
	function habilita_desconto_ultimo_vencimento(acao){
		if(acao == "habilita"){
			document.formulario.Acao.value = 'alterar';
			verificaAcao();
			
			
			document.formulario.DataVencimento.readOnly = true;
			
			document.formulario.ValorMoraMulta.readOnly = false;
			document.formulario.ValorJurosVencimento.readOnly = false;
			document.formulario.ValorTaxaReImpressaoBoleto.readOnly = false;
			document.formulario.ValorOutrasDespesas.readOnly = false;
			
			document.formulario.DataVencimento.setAttribute('onfocus', '');
			document.formulario.ValorMoraMulta.setAttribute('onFocus', 'Foco(this,\'in\')');
			document.formulario.ValorJurosVencimento.setAttribute('onFocus', 'Foco(this,\'in\')');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onFocus', 'Foco(this,\'in\')');
			document.formulario.ValorOutrasDespesas.setAttribute('onFocus', 'Foco(this,\'in\')');
			
			document.formulario.DataVencimento.setAttribute('tabIndex', '');
			document.formulario.ValorMoraMulta.setAttribute('tabIndex', '12');
			document.formulario.ValorJurosVencimento.setAttribute('tabIndex', '13');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('tabIndex', '14');
			document.formulario.ValorOutrasDespesas.setAttribute('tabIndex', '15');
			
			document.formulario.ValorMoraMulta.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
			document.formulario.ValorJurosVencimento.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
			document.formulario.ValorOutrasDespesas.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
			
			document.getElementById('DataVencimento').style.color = "#000000";
			document.getElementById('DataVencimento').style.backgroundColor = "#FFFFFF";
			document.getElementById('imgDataVencimento').src = '../../img/estrutura_sistema/ico_date_c.gif';
		}else{
			document.formulario.Acao.value = 'inserir';
			verificaAcao();
			
			document.formulario.DataVencimento.readOnly = false;
			
			document.formulario.ValorMoraMulta.readOnly = true;
			document.formulario.ValorJurosVencimento.readOnly = true;
			document.formulario.ValorTaxaReImpressaoBoleto.readOnly = true;
			document.formulario.ValorOutrasDespesas.readOnly = true;
			
			document.formulario.DataVencimento.setAttribute('onfocus', 'Foco(this,\'in\')');
			document.formulario.ValorMoraMulta.setAttribute('onFocus', '');
			document.formulario.ValorJurosVencimento.setAttribute('onFocus', '');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onFocus', '');
			document.formulario.ValorOutrasDespesas.setAttribute('onFocus', '');
			
			
			document.formulario.DataVencimento.setAttribute('tabIndex', '9');
			document.formulario.ValorMoraMulta.setAttribute('tabIndex', '');
			document.formulario.ValorJurosVencimento.setAttribute('tabIndex', '');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('tabIndex', '');
			document.formulario.ValorOutrasDespesas.setAttribute('tabIndex', '');
			
			document.formulario.ValorMoraMulta.setAttribute('onKeyPress', '');
			document.formulario.ValorJurosVencimento.setAttribute('onKeyPress', '');
			document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onKeyPress', '');
			document.formulario.ValorOutrasDespesas.setAttribute('onKeyPress', '');
			
			document.getElementById('DataVencimento').style.color = "#C10000";
			document.getElementById('DataVencimento').style.backgroundColor = "#FFFFFF";
			document.getElementById('imgDataVencimento').src = '../../img/estrutura_sistema/ico_date.gif';
		}
	}
	function retorna_campos_padrao(){
		document.formulario.Acao.value = 'inserir';
		verificaAcao();
		
		document.getElementById('DataVencimento').style.color = "#C10000";
		document.getElementById('DataVencimento').style.backgroundColor = "#FFFFFF";
		document.getElementById('imgDataVencimento').src = '../../img/estrutura_sistema/ico_date.gif';
		
		document.formulario.DataVencimento.readOnly = false;
		document.formulario.ValorMoraMulta.readOnly = false;
		document.formulario.ValorJurosVencimento.readOnly = false;
		document.formulario.ValorTaxaReImpressaoBoleto.readOnly = false;
		document.formulario.ValorOutrasDespesas.readOnly = false;
		
		document.formulario.DataVencimento.setAttribute('onfocus', 'Foco(this,\'in\')');
		document.formulario.ValorMoraMulta.setAttribute('onFocus', 'Foco(this,\'in\')');
		document.formulario.ValorJurosVencimento.setAttribute('onFocus', 'Foco(this,\'in\')');
		document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onFocus', 'Foco(this,\'in\')');
		document.formulario.ValorOutrasDespesas.setAttribute('onFocus', 'Foco(this,\'in\')');
		
		document.formulario.DataVencimento.setAttribute('tabIndex', '9');
		document.formulario.ValorMoraMulta.setAttribute('tabIndex', '12');
		document.formulario.ValorJurosVencimento.setAttribute('tabIndex', '13');
		document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('tabIndex', '14');
		document.formulario.ValorOutrasDespesas.setAttribute('tabIndex', '15');
		
		document.formulario.ValorMoraMulta.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
		document.formulario.ValorJurosVencimento.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
		document.formulario.ValorTaxaReImpressaoBoleto.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
		document.formulario.ValorOutrasDespesas.setAttribute('onKeyPress', 'mascara(this,event,\'float\')');
	}