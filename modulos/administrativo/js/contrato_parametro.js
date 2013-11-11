	function excluir(IdContrato,IdStatus){
		if(IdStatus  == 1){
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
	    
	   			url = "files/excluir/excluir_contrato.php?IdContrato="+IdContrato;
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
									url = 'cadastro_contrato.php?Erro='+document.formulario.Erro.value;
									window.location.replace(url);
								}else{
									verificaErro();
								}
							}else{
								var temp   = xmlhttp.responseText.split("_");
								if(temp.length>0){
									var numMsg = parseInt(temp[0]);
								}else{
									var numMsg = parseInt(xmlhttp.responseText);
								}
								mensagens(numMsg);
								if(numMsg == 7){
									var aux = 0, valor=0, desc=0, total=0;
									for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
										if(IdContrato == document.getElementById('tableListar').rows[i].accessKey){
											document.getElementById('tableListar').deleteRow(i);
											tableMultColor('tableListar',document.filtro.corRegRand.value);
											aux=1;
											break;
										}
									}
									if(aux==1){
										document.getElementById("tableListarTotal").innerHTML		=	"Total: "+(document.getElementById('tableListar').rows.length-2);
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
		}else{
			return false;
		}
	}
	function busca_parametro(campo,valor,filtroBusca,valorBusca,valorBusca3,valorBusca4){
		var IdParametro1,IdParametro2,IdParametro3,IdParametro4;
		var aux				=	campo.split("_");
		var Parametro		=	aux[2];
		var valorDefault	=	unescape("Valor Par%E2metro");

		switch(Parametro){
			case '1':
				IdParametro1	=	valor;
				IdParametro2	=	"";
				IdParametro3	=	"";
				IdParametro4	=	"";
				
				document.filtro.filtro_valor_parametro1.readOnly = false;
				document.filtro.filtro_valor_parametro2.readOnly = true;
				document.filtro.filtro_valor_parametro3.readOnly = true;
				document.filtro.filtro_valor_parametro4.readOnly = true;
				
				document.getElementById("titValorParametro1").innerHTML = valor;
				document.getElementById("titValorParametro2").innerHTML = valorDefault+" 2";
				document.getElementById("titValorParametro3").innerHTML = valorDefault+" 3";
				document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
				
				while(document.filtro.filtro_parametro_2.options.length > 0){
					document.filtro.filtro_parametro_2.options[0] = null;
				}
				while(document.filtro.filtro_parametro_3.options.length > 0){
					document.filtro.filtro_parametro_3.options[0] = null;
				}
				while(document.filtro.filtro_parametro_4.options.length > 0){
					document.filtro.filtro_parametro_4.options[0] = null;
				}
				
				document.filtro.filtro_servico.value	=	"";
				
				addOption(document.filtro.filtro_parametro_2,"Todos","");		
				addOption(document.filtro.filtro_parametro_3,"Todos","");		
				addOption(document.filtro.filtro_parametro_4,"Todos","");		
				break;
			case '2':
				IdParametro1	=	document.filtro.filtro_parametro_1.value;
				IdParametro2	=	valor;
				IdParametro3	=	"";
				IdParametro4	=	"";
				
				document.filtro.filtro_valor_parametro1.readOnly = false;
				document.filtro.filtro_valor_parametro2.readOnly = false;
				document.filtro.filtro_valor_parametro3.readOnly = true;
				document.filtro.filtro_valor_parametro4.readOnly = true;
				
				if(valor != ''){
					document.getElementById("titValorParametro2").innerHTML = valor;
					document.getElementById("titValorParametro3").innerHTML = valorDefault+" 3";
					document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
				} else{
					document.getElementById("titValorParametro2").innerHTML = valorDefault+" 2";
					document.getElementById("titValorParametro3").innerHTML = valorDefault+" 3";
					document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
					document.filtro.filtro_valor_parametro2.readOnly		= true;
				}
				
				while(document.filtro.filtro_parametro_3.options.length > 0){
					document.filtro.filtro_parametro_3.options[0] = null;
				}
				while(document.filtro.filtro_parametro_4.options.length > 0){
					document.filtro.filtro_parametro_4.options[0] = null;
				}
				
				addOption(document.filtro.filtro_parametro_3,"Todos","");		
				addOption(document.filtro.filtro_parametro_4,"Todos","");	
				break;
			case '3':
				IdParametro1	=	document.filtro.filtro_parametro_1.value;
				IdParametro2	=	document.filtro.filtro_parametro_2.value;;
				IdParametro3	=	valor;
				IdParametro4	=	"";
				
				document.filtro.filtro_valor_parametro1.readOnly = false;
				document.filtro.filtro_valor_parametro2.readOnly = false;
				document.filtro.filtro_valor_parametro3.readOnly = false;
				document.filtro.filtro_valor_parametro4.readOnly = true;
				
				if(valor != ''){
					document.getElementById("titValorParametro3").innerHTML = valor;
					document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
				} else{
					document.getElementById("titValorParametro3").innerHTML = valorDefault+" 3";
					document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
					document.filtro.filtro_valor_parametro3.readOnly		= true;
				}
				
				while(document.filtro.filtro_parametro_4.options.length > 0){
					document.filtro.filtro_parametro_4.options[0] = null;
				}
						
				addOption(document.filtro.filtro_parametro_4,"Todos","");
				break;
			case '4':
				document.filtro.filtro_valor_parametro1.readOnly = false;
				document.filtro.filtro_valor_parametro2.readOnly = false;
				document.filtro.filtro_valor_parametro3.readOnly = false;
				document.filtro.filtro_valor_parametro4.readOnly = false;
				
				if(valor != ''){
					document.getElementById("titValorParametro4").innerHTML = valor;
				} else{
					document.getElementById("titValorParametro4").innerHTML = valorDefault+" 4";
					document.filtro.filtro_valor_parametro4.readOnly		= true;
				}
				
				IdParametro1	=	document.filtro.filtro_parametro_1.value;
				IdParametro2	=	document.filtro.filtro_parametro_2.value;;
				IdParametro3	=	document.filtro.filtro_parametro_3.value;
				IdParametro4	=	valor;
				break;
			default:
				document.getElementById("titValorParametro1").innerHTML	= valorDefault+" 1";
				document.getElementById("titValorParametro2").innerHTML	= valorDefault+" 2";
				document.getElementById("titValorParametro3").innerHTML	= valorDefault+" 3";
				document.getElementById("titValorParametro4").innerHTML	= valorDefault+" 4";
				
				document.filtro.filtro_valor_parametro1.readOnly		= true;
				document.filtro.filtro_valor_parametro2.readOnly		= true;
				document.filtro.filtro_valor_parametro3.readOnly		= true;
				document.filtro.filtro_valor_parametro4.readOnly		= true;
				return false;
		}		
		
		if(filtroBusca == undefined){
			return false;
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
	    
	    var IdServico	=	document.filtro.filtro_servico.value;

	    url = "xml/servico_parametro_relatorio.php?IdParametro1="+IdParametro1+"&IdParametro2="+IdParametro2+"&IdParametro3="+IdParametro3+"&IdParametro4="+IdParametro4+"&IdServico="+IdServico;
		xmlhttp.open("GET", url,true);
	    
		// Carregando...
		carregando(true);
		
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					while(filtroBusca.options.length > 0){
						filtroBusca.options[0] = null;
					}
					
					addOption(filtroBusca,"Todos","");
					//alert(xmlhttp.responseText);
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode, DescricaoParametroServico;					
						var vetor = new Array();
						
						document.filtro.filtro_servico.value	=	"";
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServicoValor")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroServicoValor = nameTextNode.nodeValue;
							
							addOption(filtroBusca,DescricaoParametroServicoValor,DescricaoParametroServicoValor);
							
							var cont = 0; ii='';
							if(document.filtro.filtro_servico.value == ''){
								document.filtro.filtro_servico.value = IdServico;
								ii = 0;
							}else{
								var tempFiltro	=	document.filtro.filtro_servico.value.split(',');
									
								ii=0; 
								while(tempFiltro[ii] != undefined){
									if(tempFiltro[ii] != IdServico){
										cont++;		
									}
									ii++;
								}
								if(ii == cont){
									document.filtro.filtro_servico.value = document.filtro.filtro_servico.value + "," + IdServico;
								}
							}
						}
						if(valorBusca == "" || valorBusca == undefined){
							filtroBusca[0].selected	=	true;
						}else{
							var opcao="",aux=0;
							for(i=0;i<filtroBusca.length;i++){
								opcao	=	filtroBusca[i].value;
								opcao	=	removeAcento(opcao);
								//alert(opcao+" "+valorBusca);
								if(opcao == valorBusca){
									filtroBusca[i].selected	=	true;
									aux = 1;
									i = filtroBusca.length;
								}
							}
							if(aux == 0){
								filtroBusca[0].selected	=	true;
							}
							if(Parametro == 1){
								busca_parametro('filtro_parametro_2',valorBusca,document.filtro.filtro_parametro_3,valorBusca3,valorBusca3,valorBusca4);
							}
							if(Parametro == 2){
								busca_parametro('filtro_parametro_3',valorBusca3,document.filtro.filtro_parametro_4,valorBusca4,valorBusca3,valorBusca4);
							}
							if(Parametro == 3){
								busca_parametro('filtro_parametro_4',valorBusca4,document.filtro.filtro_parametro_4,valorBusca4,valorBusca3,valorBusca4);
							}
						}
					} else{
						document.getElementById("titValorParametro1").innerHTML	= valorDefault+" 1";
						document.getElementById("titValorParametro2").innerHTML	= valorDefault+" 2";
						document.getElementById("titValorParametro3").innerHTML	= valorDefault+" 3";
						document.getElementById("titValorParametro4").innerHTML	= valorDefault+" 4";
						
						document.filtro.filtro_valor_parametro1.readOnly		= true;
						document.filtro.filtro_valor_parametro2.readOnly		= true;
						document.filtro.filtro_valor_parametro3.readOnly		= true;
						document.filtro.filtro_valor_parametro4.readOnly		= true;
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function filtro_buscar_pessoa(IdPessoa){
		var xmlhttp = false;
		if(IdPessoa == '' ||  IdPessoa == undefined){
			IdPessoa = 0;
		}
		
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
	    
	    url = "xml/pessoa.php?IdPessoa="+IdPessoa+"&IdStatus=1&IdTipoServico=1";
		xmlhttp.open("GET", url,true);
		// Carregando...
		carregando(true);
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText == 'false'){
						document.filtro.filtro_id_pessoa.value				= '';
						document.filtro.filtro_descricao_id_pessoa.value	= '';
					} else {
						nameNode = xmlhttp.responseXML.getElementsByTagName("IdPessoa")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var IdPessoa = nameTextNode.nodeValue;
						
						nameNode = xmlhttp.responseXML.getElementsByTagName("Nome")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var Nome = nameTextNode.nodeValue;
						
						document.filtro.filtro_id_pessoa.value				= IdPessoa;
						document.filtro.filtro_descricao_id_pessoa.value	= Nome;
						document.filtro.IdPessoa.value = "";
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}
	function filtro_buscar_servico(IdServico,Local){
		if(IdServico == '' || IdServico == undefined){
			IdServico = 0;
		}
		var IdStatus = '';
		
		switch(Local){
			case 'ContratoParametro':
				IdStatus = '';
				break;
			default:
				IdStatus = 1;
				break;
		}
		
		
	    url = "xml/servico.php?IdServico="+IdServico+"&IdStatus="+IdStatus+"&IdTipoServico=1";

		call_ajax(url, function (xmlhttp) {
			if(xmlhttp.responseText == 'false'){
				document.filtro.filtro_id_servico.value				= '';
				document.filtro.filtro_descricao_id_servico.value	= '';
			} else {
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdServico = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoServico")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoServico = nameTextNode.nodeValue;
				
				document.filtro.filtro_id_servico.value				= IdServico;
				document.filtro.filtro_descricao_id_servico.value	= DescricaoServico;
				document.filtro.IdServico.value = "";
			}
		});	
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