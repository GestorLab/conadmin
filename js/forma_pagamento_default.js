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
		url = "xml/forma_pagamento.php?IdFormaPagamento="+IdFormaPagamento;
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
						
						document.formulario.IdFormaPagamento.value 			= '';
						document.formulario.DescricaoFormaPagamento.value 	= '';
						
						addParmUrl("marFormaPagamento","IdFormaPagamento",'');
						
						switch (Local){
							case "FormaPagamento":
								document.formulario.LocalFinalizador[0].selected= true;
								document.formulario.DataCriacao.value 			= '';
								document.formulario.LoginCriacao.value 			= '';
								document.formulario.DataAlteracao.value 		= '';
								document.formulario.LoginAlteracao.value		= '';
								document.formulario.Acao.value 					= 'inserir';
								
								status_inicial();
								break;
						}
						
						document.formulario.IdFormaPagamento.focus();
						
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						IdFormaPagamento = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFormaPagamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var DescricaoFormaPagamento = nameTextNode.nodeValue;
												
						document.formulario.IdFormaPagamento.value		  = IdFormaPagamento;
						document.formulario.DescricaoFormaPagamento.value = DescricaoFormaPagamento;
						
						addParmUrl("marFormaPagamento","IdFormaPagamento",IdFormaPagamento);
						
						switch (Local){		
							case "FormaPagamento":
								nameNode = xmlhttp.responseXML.getElementsByTagName("LocalFinalizador")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LocalFinalizador = nameTextNode.nodeValue;
							
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
						
								document.formulario.LocalFinalizador.value				= LocalFinalizador;
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
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}	
	
