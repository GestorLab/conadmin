
	function busca_conta_receber_recebimento(IdContaReceber,IdContaReceberRecebimento,Erro,Local){
		if(IdContaReceber == ''){
			IdContaReceber = 0;
		}
		if(IdContaReceberRecebimento == '' || IdContaReceberRecebimento == undefined){
			IdContaReceberRecebimento = 0;
		}
		
		if(Local=="" || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var nameNode, nameTextNode, url;
		
	   	url = "xml/conta_receber_recebimento.php?IdContaReceber="+IdContaReceber+"&IdContaReceberRecebimento="+IdContaReceberRecebimento;
	    
		url = url+"&"+Math.random();
		
		call_ajax(url, function (xmlhttp) {
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			if(xmlhttp.responseText == 'false'){		
				document.formulario.IdContaReceberRecebimento.value = "";
				document.formulario.IdLocalRecebimento.value		= '';
				document.formulario.DataRecebimento.value 			= '';
				document.formulario.IdArquivoRetorno.value 			= '';
				document.formulario.IdRecibo.value 					= '';
				document.formulario.ValorRecebimento.value 			= document.formulario.ValorRecebimento.value;
				document.formulario.ValorDescontoRecebimento.value	= '';
				document.formulario.ValorMoraMulta.value			= '';
				document.formulario.ValorOutrasDespesas.value 		= '';
				document.formulario.ValorReceber.value 				= document.formulario.ValorRecebimento.value;
				document.formulario.IdStatusRecebimento.value 		= '';
				document.formulario.IdCaixa.value 					= '';
				document.formulario.IdCaixaMovimentacao.value 		= '';
				document.formulario.IdCaixaItem.value 				= '';
				
				document.getElementById("sp_titLocalRecebimento").style.display = "table-cell";
				document.getElementById("titLocalRecebimento").style.display = "table-cell";
				document.getElementById("sp_cpIdLocalRecebimento").style.display = "table-cell";
				document.getElementById("cpIdLocalRecebimento").style.display = "table-cell";
				
				document.getElementById("sp_titCaixa").style.display = "none";
				document.getElementById("titCaixa").style.display = "none";
				document.getElementById("sp_titMovimentacao").style.display = "none";
				document.getElementById("titMovimentacao").style.display = "none";
				document.getElementById("sp_titItem").style.display = "none";
				document.getElementById("titItem").style.display = "none";
				document.getElementById("sp_cpIdCaixa").style.display = "none";
				document.getElementById("cpIdCaixa").style.display = "none";
				document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "none";
				document.getElementById("cpIdCaixaMovimentacao").style.display = "none";
				document.getElementById("sp_cpIdCaixaItem").style.display = "none";
				document.getElementById("cpIdCaixaItem").style.display = "none";
				
				switch(Local){
					case 'ContaReceberRecebimento':
						document.formulario.DataCriacao.value 				= '';
						document.formulario.LoginCriacao.value 				= '';
						document.formulario.DataAlteracao.value 			= '';
						document.formulario.LoginAlteracao.value			= '';
						document.formulario.Obs.value						= '';
						document.formulario.Acao.value 						= 'inserir';
						break;
				}	
				
				verificaAcao();
						
				// Fim de Carregando
				carregando(false);
			}else{
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdContaReceberRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdContaReceberRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDescontoRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorDescontoRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorMoraMulta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorMoraMulta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorOutrasDespesas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorOutrasDespesas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdArquivoRetorno")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdArquivoRetorno = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdRecibo")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdRecibo = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalRecebimento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdLocalRecebimento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaMovimentacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixaMovimentacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdCaixaItem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdCaixaItem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorRecebido")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorRecebido = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ValorContaReceber")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ValorContaReceber = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Obs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Obs = nameTextNode.nodeValue;
			
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;					
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;

				document.formulario.IdContaReceberRecebimento.value = IdContaReceberRecebimento;
				document.formulario.DataRecebimento.value 			= dateFormat(DataRecebimento);
				document.formulario.ValorMoraMulta.value 			= formata_float(Arredonda(ValorMoraMulta,2),2).replace(".",",");
				document.formulario.ValorDescontoRecebimento.value	= formata_float(Arredonda(ValorDescontoRecebimento,2),2).replace(".",",");
				document.formulario.ValorOutrasDespesas.value		= formata_float(Arredonda(ValorOutrasDespesas,2),2).replace(".",",");
				document.formulario.ValorRecebimento.value			= formata_float(Arredonda(ValorContaReceber,2),2).replace(".",",");
				document.formulario.ValorReceber.value				= formata_float(Arredonda(ValorRecebido,2),2).replace(".",",");
				document.formulario.IdArquivoRetorno.value			= IdArquivoRetorno;
				document.formulario.IdStatusRecebimento.value 		= IdStatus;
				document.formulario.IdRecibo.value 					= IdRecibo;
				document.formulario.Acao.value 						= 'alterar';
				document.formulario.IdLocalRecebimento.value 		= IdLocalRecebimento;
				document.formulario.IdCaixa.value 					= IdCaixa;
				document.formulario.IdCaixaMovimentacao.value 		= IdCaixaMovimentacao;
				document.formulario.IdCaixaItem.value 				= IdCaixaItem;
								
				if(IdLocalRecebimento != ''){
					document.getElementById("sp_titLocalRecebimento").style.display = "table-cell";
					document.getElementById("titLocalRecebimento").style.display = "table-cell";
					document.getElementById("sp_cpIdLocalRecebimento").style.display = "table-cell";
					document.getElementById("cpIdLocalRecebimento").style.display = "table-cell";
					
					document.getElementById("sp_titCaixa").style.display = "none";
					document.getElementById("titCaixa").style.display = "none";
					document.getElementById("sp_titMovimentacao").style.display = "none";
					document.getElementById("titMovimentacao").style.display = "none";
					document.getElementById("sp_titItem").style.display = "none";
					document.getElementById("titItem").style.display = "none";
					document.getElementById("sp_cpIdCaixa").style.display = "none";
					document.getElementById("cpIdCaixa").style.display = "none";
					document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "none";
					document.getElementById("cpIdCaixaMovimentacao").style.display = "none";
					document.getElementById("sp_cpIdCaixaItem").style.display = "none";
					document.getElementById("cpIdCaixaItem").style.display = "none";
				} else {
					document.getElementById("sp_titLocalRecebimento").style.display = "none";
					document.getElementById("titLocalRecebimento").style.display = "none";
					document.getElementById("sp_cpIdLocalRecebimento").style.display = "none";
					document.getElementById("cpIdLocalRecebimento").style.display = "none";
					
					document.getElementById("sp_titCaixa").style.display = "table-cell";
					document.getElementById("titCaixa").style.display = "table-cell";
					document.getElementById("sp_titMovimentacao").style.display = "table-cell";
					document.getElementById("titMovimentacao").style.display = "table-cell";
					document.getElementById("sp_titItem").style.display = "table-cell";
					document.getElementById("titItem").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixa").style.display = "table-cell";
					document.getElementById("cpIdCaixa").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixaMovimentacao").style.display = "table-cell";
					document.getElementById("cpIdCaixaMovimentacao").style.display = "table-cell";
					document.getElementById("sp_cpIdCaixaItem").style.display = "table-cell";
					document.getElementById("cpIdCaixaItem").style.display = "table-cell";
				}
				
				switch(Local){
					case 'CancelarContaReceberRecebimento':
						document.formulario.IdContaReceberRecebimento.value	=	IdContaReceberRecebimento;
						document.formulario.ObsCancelamento.value			= 	'';
						document.formulario.IdCaixa.value					= 	IdCaixa;
						document.formulario.IdStatus.value					= 	IdStatus;
						break;
					case 'ContaReceberRecebimento':
						document.formulario.Obs.value						= Obs;
						document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 				= LoginCriacao;
						document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value			= LoginAlteracao;
						break;
				}
				
				verificaAcao();
					
			}
			if(window.janela != undefined){
				window.janela.close();
			}
		});
	}


