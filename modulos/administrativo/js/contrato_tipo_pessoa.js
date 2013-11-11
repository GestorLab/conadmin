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
								var aux = 0, valor=0, desc=0, total=0;
								if(temp.length>0){
									var numMsg = parseInt(temp[0]);
								}else{
									var numMsg = parseInt(xmlhttp.responseText);
								}
								mensagens(numMsg);
								if(numMsg == 7){
									var aux = 0, valor=0, desc=0, total=0;
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[4].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										temp2	=	temp1[0].replace(',','.');
										if(temp2=='') temp2 = 0;
										valor	+=	parseFloat(temp2);
											
										temp	=	document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										temp2	=	temp1[0].replace(',','.');
										if(temp2=='') temp2 = 0;
										desc	+=	parseFloat(temp2);
										
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										temp2	=	temp1[0].replace(',','.');
										if(temp2=='') temp2 = 0;
										total	+=	parseFloat(temp2);
									}
									document.getElementById('tableListarValor').innerHTML				=	formata_float(Arredonda(valor,2),2).replace('.',',');	
									document.getElementById('tableListarDesconto').innerHTML			=	formata_float(Arredonda(desc,2),2).replace('.',',');	
									document.getElementById('tableListarFinal').innerHTML				=	formata_float(Arredonda(total,2),2).replace('.',',');
									document.getElementById("tableListarTotal").innerHTML				=	"Total: "+(document.getElementById('tableListar').rows.length-2);						
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

		switch(Parametro){
			case '1':
				IdParametro1	=	valor;
				IdParametro2	=	"";
				IdParametro3	=	"";
				IdParametro4	=	"";
				
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
				
				while(document.filtro.filtro_parametro_4.options.length > 0){
					document.filtro.filtro_parametro_4.options[0] = null;
				}
						
				addOption(document.filtro.filtro_parametro_4,"Todos","");
				break;
			case '4':
				IdParametro1	=	document.filtro.filtro_parametro_1.value;
				IdParametro2	=	document.filtro.filtro_parametro_2.value;;
				IdParametro3	=	document.filtro.filtro_parametro_3.value;
				IdParametro4	=	valor;
				break;
			default:
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
					if(xmlhttp.responseText != 'false'){		
						var nameNode, nameTextNode, DescricaoParametroServico;					
						var vetor = new Array();
						
						document.filtro.filtro_servico.value	=	"";
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdServico = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoParametroServico")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoParametroServico = nameTextNode.nodeValue;
							
							addOption(filtroBusca,DescricaoParametroServico,DescricaoParametroServico);
							
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
							for(i=0;i<filtroBusca.length;i++){
								if(filtroBusca[i].value == valorBusca){
									filtroBusca[i].selected	=	true;
									i = filtroBusca.length;
								}
							}
							if(Parametro == 1){
								busca_parametro('filtro_parametro_2',valorBusca,document.filtro.filtro_parametro_3,valorBusca3,valorBusca3,valorBusca4);
							}
							if(Parametro == 2){
								busca_parametro('filtro_parametro_3',valorBusca3,document.filtro.filtro_parametro_4,valorBusca4,valorBusca3,valorBusca4);
							}
						}
					}
					// Fim de Carregando
					carregando(false);
				}
			}
		}
		xmlhttp.send(null);	
	}	
	function busca_filtro_cidade(IdEstado,IdCidadeTemp){
		if(IdEstado == undefined || IdEstado==''){
			IdEstado = 0;			
		}
		if(IdCidadeTemp == undefined){
			IdCidadeTemp = '';
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
	    
	    url = "xml/cidade.php?IdPais="+1+"&IdEstado="+IdEstado;

		xmlhttp.open("GET", url,true);
	    	
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){		
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
						}
						
						var nameNode, nameTextNode, NomeCidade;	
						addOption(document.filtro.filtro_cidade,"","");				
											
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdCidade").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdCidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeCidade")[i]; 
							nameTextNode = nameNode.childNodes[0];
							NomeCidade = nameTextNode.nodeValue;
						
							addOption(document.filtro.filtro_cidade,NomeCidade,IdCidade);
						}					
						
						if(IdCidadeTemp!=""){
							for(i=0;i<document.filtro.filtro_cidade.length;i++){
								if(document.filtro.filtro_cidade[i].value == IdCidadeTemp){
									document.filtro.filtro_cidade[i].selected	=	true;
									break;
								}
							}
						}else{
							document.filtro.filtro_cidade[0].selected	=	true;
						}						
					}else{
						while(document.filtro.filtro_cidade.options.length > 0){
							document.filtro.filtro_cidade.options[0] = null;
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