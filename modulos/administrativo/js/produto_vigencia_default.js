	function busca_produto_vigencia(IdProduto,DataInicioCond,Erro,Local){
		if(IdProduto == ''){
			IdProduto = 0;
		}
		if(DataInicioCond == '' || DataInicioCond == undefined){
			DataInicioCond = 0;
		}else{
			var tam		=	document.getElementById('tabelaVigencia').rows.length;	
			var i;
			for(i=0; i<tam; i++){
				if(document.getElementById('tabelaVigencia').rows[i].accessKey == DataInicioCond){
					document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#A0C4EA";
				}
				else{
					if(i%2 == 0 && i!=0 && i!=(tam-1)){
						document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#E2E7ED";
					}else if(i%2 != 0 && i!=0 && i!=(tam-1)){
						document.getElementById('tabelaVigencia').rows[i].style.backgroundColor = "#FFFFFF";
					}
				}
			}
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
	    url = "xml/produto_vigencia.php?IdProduto="+IdProduto+"&DataInicio="+DataInicioCond;
		
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
						document.formulario.DataInicio.value 					= dateFormat(DataInicioCond);		
						document.formulario.DataTermino.value 					= '';
						document.formulario.ValorDesconto.value 				= '';
						document.formulario.Valor.value							= '';
						document.formulario.ValorFinal.value					= '';
						document.formulario.DataLimiteDesconto.value			= '';
						document.formulario.IdProdutoTipoVigencia.value			= '';
						document.formulario.DescricaoProdutoTipoVigencia.value	= '';
						document.formulario.Acao.value							= 'inserir';
						
						status_inicial();
						
						busca_produto(IdProduto,false,Local);
						
						// Fim de Carregando
						carregando(false);
					}else{
						var DescricaoProduto, DataInicio, DataTermino, Valor, ValorDesconto, DescricaoProdutoTipoVigencia, IdProdutoTipoVigencia, DataLimiteDesconto, ValorTotal;
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DataInicio").length; i++){
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProduto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataInicio")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataInicio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataTermino")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataTermino = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProdutoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdProdutoTipoVigencia = nameTextNode.nodeValue;					
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProdutoTipoVigencia")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoProdutoTipoVigencia = nameTextNode.nodeValue;					
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorDesconto = nameTextNode.nodeValue;					
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("Valor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							Valor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataLimiteDesconto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DataLimiteDesconto = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;
							
							ValorTotal		=	Valor - ValorDesconto;
							DescontoPerc	=	(ValorDesconto*100)/Valor;	
							
							document.formulario.IdProduto.value						= IdProduto;
							document.formulario.DescricaoProduto.value 				= DescricaoProduto;
							document.formulario.DataInicio.value					= dateFormat(DataInicio);
							document.formulario.DataTermino.value 					= dateFormat(DataTermino);
							document.formulario.ValorDesconto.value 				= formata_float(Arredonda(ValorDesconto,2),2).replace(".",",");
							document.formulario.DescontoPerc.value					= formata_float(Arredonda(DescontoPerc,2),2).replace(".",",");
							document.formulario.Valor.value							= formata_float(Arredonda(Valor,2),2).replace(".",",");
							document.formulario.ValorFinal.value					= formata_float(Arredonda(ValorTotal,2),2).replace(".",",");
							document.formulario.DataLimiteDesconto.value			= DataLimiteDesconto;
							document.formulario.IdProdutoTipoVigencia.value			= IdProdutoTipoVigencia;
							document.formulario.DescricaoProdutoTipoVigencia.value	= DescricaoProdutoTipoVigencia;
							document.formulario.DataCriacao.value					= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value					= LoginCriacao;
							document.formulario.DataAlteracao.value					= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value				= LoginAlteracao;
							
							addParmUrl("marProduto","IdProduto",IdProduto);
							addParmUrl("marProdutoVigencia","IdProduto",IdProduto);
							addParmUrl("marProdutoVigenciaNovo","IdProduto",IdProduto);
							
							document.formulario.Acao.value					= 'alterar';
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
	
	
	
	
