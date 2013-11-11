	function validar(){
		if(document.formulario.IdProduto.value==''){
			mensagens(1);
			document.formulario.IdProduto.focus();
			return false;
		}
		for(i=12;i<document.formulario.length;i++){
			if(document.formulario[i].name.substr(0,10) == 'ValorPreco'){
				if(document.formulario[i].value == ''){
					mensagens(1);
					document.formulario[i].focus();
					return false;	
				}
			} 
		}
		return true;
	}
	
	function inicia(){
		document.formulario.IdProduto.focus();
	}
	
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.IdProduto.value==''){			
				document.formulario.bt_alterar.disabled 	= true;
			}else{
				document.formulario.bt_alterar.disabled 	= false;
			}
		}	
	}
	
	function preencheValor(campo){
		valor = campo.value;	
		if(valor == '')	valor = 0;
		
		temp	=	campo.name.split('_');
		
		CampoValor		=	temp[0];
		IdTabelaPreco	=	temp[1];
		
		for(i=0;i<document.formulario.length;i++){
			
			temp	=	document.formulario[i].name.split('_');
			
			if(temp[0] ==  CampoValor){
				if(IdTabelaPreco == temp[1] && document.formulario[i].value == document.formulario.ValorMinimo.value){
					document.formulario[i].value	=	valor;	
				}
			}
		}
	}
	
	function busca_produto_tabela_preco(IdProduto,Erro,Local){
		if(IdProduto == undefined || IdProduto==''){
			IdProduto = 0;
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
	    
	   	url = "xml/produto_tabela_preco.php?IdProduto="+IdProduto;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){
						for(i=0;i<document.formulario.length;i++){
							if(document.formulario[i].name.substr(0,10) == 'ValorPreco'){
								document.formulario[i].value	=	document.formulario.ValorMinimo.value;	
							} 
						}
					}else{
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdTabelaPreco").length; i++){	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTabelaPreco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdTabelaPreco = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdFormaPagamento")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdFormaPagamento = nameTextNode.nodeValue;	
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrecoMinimo")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorPrecoMinimo = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPreco")[i]; 
							nameTextNode = nameNode.childNodes[0];
							ValorPreco = nameTextNode.nodeValue;
							
							CampoValorPrecoMinimo	=	'ValorPrecoMinimo_'+IdTabelaPreco+'_'+IdFormaPagamento;
							CampoValorPreco			=	'ValorPreco_'+IdTabelaPreco+'_'+IdFormaPagamento;
							
							for(ii=0;ii<document.formulario.length;ii++){
								if(document.formulario[ii].name ==  CampoValorPrecoMinimo){
									document.formulario[ii].value	=	formata_float(Arredonda(ValorPrecoMinimo,2),2).replace('.',',');	
								}
								if(document.formulario[ii].name ==  CampoValorPreco){
									document.formulario[ii].value	=	formata_float(Arredonda(ValorPreco,2),2).replace('.',',');	
								}
							}
							
							
						}
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
			} 
			return true;
		}
		xmlhttp.send(null);
	}
