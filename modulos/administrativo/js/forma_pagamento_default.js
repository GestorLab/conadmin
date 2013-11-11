	function janela_busca_forma_pagamento(){
		janelas('busca_forma_pagamento.php',360,283,250,100,'');
	}
	function busca_forma_pagamento(IdFormaPagamento, Erro, Local){
		if(IdFormaPagamento == ''){
			IdFormaPagamento = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		
		var url = "xml/forma_pagamento.php?IdFormaPagamento="+IdFormaPagamento;
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				
				document.formulario.IdFormaPagamento.value 			= '';
				document.formulario.DescricaoFormaPagamento.value 	= '';
				
				addParmUrl("marFormaPagamento","IdFormaPagamento",'');
				
				switch (Local){
					case "FormaPagamento":
						del_parcela();
						
						document.formulario.DataCriacao.value 			= '';
						document.formulario.LoginCriacao.value 			= '';
						document.formulario.DataAlteracao.value 		= '';
						document.formulario.LoginAlteracao.value		= '';
						document.formulario.Acao.value 					= 'inserir';
						break;
				}
				
				document.formulario.IdFormaPagamento.focus();
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdFormaPagamento = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaPagamento")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoFormaPagamento = nameTextNode.nodeValue;
				
				document.formulario.IdFormaPagamento.value		  = IdFormaPagamento;
				document.formulario.DescricaoFormaPagamento.value = DescricaoFormaPagamento;
				
				addParmUrl("marFormaPagamento","IdFormaPagamento",IdFormaPagamento);
				
				switch (Local){		
					case "FormaPagamento":
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
						
						var XMLResponse = xmlhttp.responseXML.getElementsByTagName("FormaPagamentoParcela")[0];
						
						del_parcela();
						
						for(var i = 0; i < XMLResponse.getElementsByTagName("QtdParcela").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdParcela")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdParcela = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("PercentualJurosMes")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var PercentualJurosMes = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ExcluirParcela")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ExcluirParcela = nameTextNode.nodeValue;
							
							add_parcela(PercentualJurosMes, ExcluirParcela);
						}
						
						document.formulario.DataCriacao.value 					= dateFormat(DataCriacao);
						document.formulario.LoginCriacao.value 					= LoginCriacao;
						document.formulario.DataAlteracao.value 				= dateFormat(DataAlteracao);
						document.formulario.LoginAlteracao.value				= LoginAlteracao;
						
						document.formulario.Acao.value 							= 'alterar';
						break;
				}
			}
			
			if(window.janela != undefined){
				window.janela.close();
			}
			
			verificaAcao();
		});
	}