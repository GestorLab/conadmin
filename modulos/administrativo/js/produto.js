	var fotos 	= new  Array();
	var desc 	= new  Array();
	
	function excluir(IdProduto){
		if(IdProduto == ''){
			IdProduto = document.formulario.IdProduto.value;
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
    
   			url = "files/excluir/excluir_produto.php?IdProduto="+IdProduto;
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
								url = 'cadastro_produto.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, medio=0, ultima=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdProduto == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[3].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										medio	+=	parseFloat(temp1[0].replace(',','.'));
										
										temp	=	document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										ultima	+=	parseFloat(temp1[0].replace(',','.'));
										
									}
									document.getElementById('tableListarMedio').innerHTML	=	formata_float(Arredonda(medio,2),2).replace('.',',');	
									document.getElementById('tableListarUltima').innerHTML	=	formata_float(Arredonda(ultima,2),2).replace('.',',');	
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
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
	function validar(){
		if(document.formulario.DescricaoProduto.value==''){
			mensagens(1);
			document.formulario.DescricaoProduto.focus();
			return false;
		}
		if(document.formulario.DescricaoReduzidaProduto.value==''){
			mensagens(1);
			document.formulario.DescricaoReduzidaProduto.focus();
			return false;
		}
		if(document.formulario.IdUnidade.value==''){
			mensagens(1);
			document.formulario.IdUnidade.focus();
			return false;
		}
		if(document.formulario.IdFabricante.value==''){
			mensagens(1);
			document.formulario.IdFabricante.focus();
			return false;
		}
		if(document.formulario.Garantia.value==''){
			mensagens(1);
			document.formulario.Garantia.focus();
			return false;
		}else{
			if(document.formulario.Garantia.value>0){
				if(document.formulario.IdUnidadeGarantia.value==''){
					mensagens(1);
					document.formulario.IdUnidadeGarantia.focus();
					return false;
				}
				if(document.formulario.IdTipoGarantia.value==''){
					mensagens(1);
					document.formulario.IdTipoGarantia.focus();
					return false;
				}
			}
		}
		if(document.formulario.QtdMinima.value==""){
			mensagens(1);
			document.formulario.QtdMinima.focus();
			return false;
		}
		if(document.formulario.NumeroSerie.value==''){
			mensagens(1);
			document.formulario.NumeroSerie.focus();
			return false;
		}else{
			if(document.formulario.NumeroSerie.value == 1 && document.formulario.NumeroSerieObrigatorio.value==''){
				mensagens(1);
				document.formulario.NumeroSerieObrigatorio.focus();
				return false;
			}
		}
		if(document.formulario.SubGrupoProduto.value==''){
			mensagens(1);
			if(document.formulario.IdGrupoProduto.value ==0 || document.formulario.IdGrupoProduto.value ==''){
				document.formulario.IdGrupoProduto.focus();
			}else{
				document.formulario.IdSubGrupoProduto.focus();
			}
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdProduto.focus();
	}
	function ativa_imagem(endFoto,descricao){
		if(endFoto == '' || endFoto == undefined){
						
			document.getElementById('quadroFotoFoto').src			 = "../../img/estrutura_sistema/sem_foto.gif";
			document.getElementById('quadroFotoDescricao').innerHTML = "";
			document.formulario.EndFoto.value 						 = "";
			
			if(document.formulario.IdProduto.value!=''){
				busca_produto_foto(document.formulario.IdProduto.value);
			}
			
			return false;
		}else{
			var tipo = endFoto.substring(endFoto.length-4,endFoto.length);
			
			tipo = tipo.toLowerCase();
			if(tipo == ".jpg" || tipo == ".jpeg" || tipo == ".png" || tipo == ".gif"){
			
				if(descricao == undefined){
					descricao = "";
				}
				
				document.getElementById('quadroFotoFoto').src			  = endFoto;
				document.getElementById('quadroFotoDescricao').innerHTML  = descricao;
				document.formulario.EndFoto.value 						  = endFoto;

			}
		}
		return true;
	}
	function avancaFoto(foto){
		if(foto != ''){
			var temp = foto.split('/');
			i=temp.length;
			tempfoto = temp[i-1];
			var cont = 0;
			
			for(i=0;i<document.formulario.qtdFoto.value;i++){
				if(fotos[i] == tempfoto){
					if(i== (document.formulario.qtdFoto.value -2)){
						document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right_c.gif";
						if(document.formulario.qtdFoto.value > 1){
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left.gif";						
						}
					}else{
						if(document.formulario.qtdFoto.value > 1){
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left.gif";
						}else{
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
						}
					}
					ativa_imagem('../../img/produtos/'+document.formulario.IdLoja.value+'/'+document.formulario.IdProduto.value+'/'+fotos[(i+1)],desc[(i+1)]);
					cont ++;
					break;
				}
			}
			if(cont == 0){
				ativa_imagem('../../img/produtos/'+document.formulario.IdLoja.value+'/'+document.formulario.IdProduto.value+'/'+fotos[0],desc[0]);
				
				document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right.gif";
				document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
			}
		}else{
			return false;
		}
	}
	function voltarFoto(foto){
		if(foto != ''){
			var temp = foto.split('/');
			i=temp.length;
			tempfoto = temp[i-1];
			
			for(i=(document.formulario.qtdFoto.value-1);i>=0;i--){
				if(fotos[i] == tempfoto){
					if(i== 1){
						document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
						if(document.formulario.qtdFoto.value > 1){
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right.gif";						
						}
					}else{
						if(i < (document.formulario.qtdFoto.value-1)){
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right.gif";
						}else{
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right_c.gif";
						}
					}
					
					ativa_imagem('../../img/produtos/'+document.formulario.IdLoja.value+'/'+document.formulario.IdProduto.value+'/'+fotos[(i-1)],desc[(i-1)]);
					break;
				}
			}
		}else{
			return false;
		}
	}
	function verificaObrigatoriedade(valor){
		if(valor == '' || valor == 0){
			document.getElementById('cpUnidadeGarantia').style.color	=	'#000';
			document.getElementById('cpTipoGarantia').style.color		=	'#000';
		}else{
			document.getElementById('cpUnidadeGarantia').style.color	=	'#C10000';
			document.getElementById('cpTipoGarantia').style.color		=	'#C10000';
		}
	}
	function subgrupo_produto(IdGrupoProduto){
		if(IdGrupoProduto == ''){
			while(document.formulario.IdSubGrupoProduto.options.length > 0){
				document.formulario.IdSubGrupoProduto.options[0] = null;
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
			url = xmlhttp.open("GET", "xml/subgrupo_produto.php?IdGrupoProduto="+IdGrupoProduto); 
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							while(document.formulario.IdSubGrupoProduto.options.length > 0){
								document.formulario.IdSubGrupoProduto.options[0] = null;
							}
						}else{	
							var nameNode, nameTextNode, IdSubGrupoProduto, DescricaoSubGrupoProduto;					
							while(document.formulario.IdSubGrupoProduto.options.length > 0){
								document.formulario.IdSubGrupoProduto.options[0] = null;
							}
							
							addOption(document.formulario.IdSubGrupoProduto,"","0");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdSubGrupoProduto").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubGrupoProduto")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdSubGrupoProduto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubGrupoProduto")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoSubGrupoProduto = nameTextNode.nodeValue;
								
								addOption(document.formulario.IdSubGrupoProduto,DescricaoSubGrupoProduto,IdSubGrupoProduto);
							}
							
							document.formulario.IdSubGrupoProduto[0].selected = true;
						}
						// Fim de Carregando
						carregando(false);
						
					}
				}
			}
			xmlhttp.send(null);	
		}
	}
	function adicionar_subgrupo(IdGrupoProduto,IdSubGrupoProduto){
		if(IdGrupoProduto == '' || IdSubGrupoProduto == 0){
			return false;
		}
		
		var cont = 0; ii=0;
		var SubGrupoProduto 	=	IdGrupoProduto+'¬'+IdSubGrupoProduto;
		
		if(document.formulario.SubGrupoProduto.value == ''){
			document.formulario.SubGrupoProduto.value = SubGrupoProduto;
			ii = 0;
		}else{
			var tempFiltro	=	document.formulario.SubGrupoProduto.value.split('#');
				
			ii=0; 
			while(tempFiltro[ii] != undefined){
				if(tempFiltro[ii] != SubGrupoProduto){
					cont++;		
				}
				ii++;
			}
			if(ii == cont){
				document.formulario.SubGrupoProduto.value = document.formulario.SubGrupoProduto.value + "#" + SubGrupoProduto;
			}
		}
		if(ii == cont){
			adicionar_subgrupo_temp(IdGrupoProduto,IdSubGrupoProduto);
		
			document.getElementById('totaltabelaSubGrupo').innerHTML	=	'Total: '+(ii+1);			
		}
	}
	function adicionar_subgrupo_temp(IdGrupoProduto,IdSubGrupoProduto){
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
		url = xmlhttp.open("GET", "xml/subgrupo_produto.php?IdGrupoProduto="+IdGrupoProduto+"&IdSubGrupoProduto="+IdSubGrupoProduto); 
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){	
						var nameNode, nameTextNode, DescricaoSubGrupoProduto, DescricaoProduto;					
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoGrupoProduto = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoSubGrupoProduto")[0]; 
						nameTextNode = nameNode.childNodes[0];
						DescricaoSubGrupoProduto = nameTextNode.nodeValue;
						
						var tam, linha, c0, c1, c2, c3;
						
						tam 	= document.getElementById('tabelaSubGrupo').rows.length;
						linha	= document.getElementById('tabelaSubGrupo').insertRow(tam-1);
						
						if(tam%2 != 0){
							linha.style.backgroundColor = "#E2E7ED";
						}
						
						linha.accessKey  = IdGrupoProduto+'¬'+IdSubGrupoProduto; 
						
						c0	= linha.insertCell(0);	
						c1	= linha.insertCell(1);
						c2	= linha.insertCell(2);
						
						c0.innerHTML = DescricaoGrupoProduto;
						c0.style.padding  =	"0 0 0 5px";
						
						c1.innerHTML = DescricaoSubGrupoProduto;
						
						c2.innerHTML 	= "<img src='../../img/estrutura_sistema/ico_del.gif' alt='Excluir?' onClick=\"remover_subgrupo("+IdGrupoProduto+","+IdSubGrupoProduto+")\">";
						c2.style.cursor = "pointer";
					}
					
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function remover_subgrupo(IdGrupoProduto,IdSubGrupoProduto){
		for(var i=0; i<document.getElementById('tabelaSubGrupo').rows.length; i++){
			if(IdGrupoProduto+'¬'+IdSubGrupoProduto == document.getElementById('tabelaSubGrupo').rows[i].accessKey){
				document.getElementById('tabelaSubGrupo').deleteRow(i);
				tableMultColor('tabelaSubGrupo');
				break;
			}
		}	
		var tempFiltro	=	document.formulario.SubGrupoProduto.value.split('#');
		var novoFiltro  = '';
		
		var ii = 0;
		while(tempFiltro[ii] != undefined){
			if(tempFiltro[ii] != IdGrupoProduto+'¬'+IdSubGrupoProduto){
				if(novoFiltro == ''){
					novoFiltro = tempFiltro[ii];
				}else{
					novoFiltro = novoFiltro + "#" + tempFiltro[ii];
				}
			}
			ii=ii+1;
		}
		
		document.formulario.SubGrupoProduto.value = novoFiltro;
		document.getElementById('totaltabelaSubGrupo').innerHTML	=	'Total: '+(ii-1);
	}
	function busca_subgrupo(IdProduto){
		if(IdProduto == ''){
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
			url = xmlhttp.open("GET", "xml/produto_subgrupo_produto.php?IdProduto="+IdProduto); 
			
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							while(document.getElementById('tabelaSubGrupo').rows.length > 2){
								document.getElementById('tabelaSubGrupo').deleteRow(1);
							}
						}else{	
							var nameNode, nameTextNode, IdGrupoProduto, IdSubGrupoProduto;					
							while(document.getElementById('tabelaSubGrupo').rows.length > 2){
								document.getElementById('tabelaSubGrupo').deleteRow(1);
							}
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto").length; i++){
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdGrupoProduto")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdGrupoProduto = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdSubGrupoProduto")[i]; 
								nameTextNode = nameNode.childNodes[0];
								var IdSubGrupoProduto = nameTextNode.nodeValue;
								
								adicionar_subgrupo(IdGrupoProduto,IdSubGrupoProduto);
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
	function addFoto(nomeJanela,largura,altura,vtop,vleft,parametro,scro){
		if(document.formulario.IdProduto.value!=''){
			if(scro == ''){
				scro = 'no';
			}
			dados 	=	"top="+vtop+",left="+vleft+",scrollbars="+scro+",status=no,toolbar=no,location=no,menu=no,width="+largura+",height="+altura;
			janela	=	window.open(nomeJanela+parametro,"_blank",dados);
			
			if(janela == null){
				alert('ERRO\nVerifique seu bloqueador de pop-up!');
			}
		}else{
			return false;
		}
	}
	function verificaNumeroSerie(valor){
		if(valor == '' || valor == 2 || valor == 0){
			document.getElementById('cpNumeroSerieObrigatoriedade').style.color	=	'#000';
			document.formulario.NumeroSerieObrigatorio[0].selected				=	true;
			document.formulario.NumeroSerieObrigatorio.disabled					=	true;	
		}else{
			document.getElementById('cpNumeroSerieObrigatoriedade').style.color	=	'#C10000';
			document.formulario.NumeroSerieObrigatorio[0].selected				=	true;
			document.formulario.NumeroSerieObrigatorio.disabled					=	false;
		}
	}
	function busca_produto_foto(IdProduto){
	
		for(i=0;i<fotos.length;i++){
			fotos[i]			=	"";
			desc[i]				=	"";
		}
		document.formulario.qtdFoto.value =	0;
		
		if(IdProduto == ''){
			IdProduto = 0;
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
	    
	   	url = "../administrativo/xml/produto_foto.php?IdProduto="+IdProduto;
	   	
		xmlhttp.open("GET", url,true);
		
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						
							document.getElementById('quadroFotoFoto').src			 = "../../img/estrutura_sistema/sem_foto.gif";
							document.getElementById('quadroFotoDescricao').innerHTML = "";
							
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right_c.gif";
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
							
							document.formulario.qtdFoto.value						 = 0;
						
						// Fim de Carregando
						carregando(false);
					}else{
						var i=0;
						for(i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdProdutoFoto").length;i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdProdutoFoto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var IdProdutoFoto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFoto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoFoto = nameTextNode.nodeValue;
		
							nameNode = xmlhttp.responseXML.getElementsByTagName("ExtFoto")[i]; 
							nameTextNode = nameNode.childNodes[0];
							var ExtFoto = nameTextNode.nodeValue;
							
							fotos[i]			= IdProdutoFoto + '.' + ExtFoto;
							desc[i]				= DescricaoFoto;
							
						}
						if(i==1){
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right_c.gif";
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
						}
						else if(i>1){
							document.formulario.qtdFoto.value = i;
							document.getElementById('seta_proximo').src = "../../img/estrutura_sistema/ico_seta_right.gif";
							document.getElementById('seta_voltar').src = "../../img/estrutura_sistema/ico_seta_left_c.gif";
							
						}
						ativa_imagem('../../img/produtos/'+document.formulario.IdLoja.value+'/'+IdProduto+'/'+fotos[0],desc[0]);
					}
				/*	if(window.janela != undefined){
						window.janela.close();
					}*/
					verificaAcao();
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
