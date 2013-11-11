	function excluir(IdArquivoRemessa,IdLocalCobranca){
		if(IdLocalCobranca == '' || IdLocalCobranca == undefined){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
		}
		if(IdArquivoRemessa == '' || IdArquivoRemessa == undefined){
			IdArquivoRemessa = document.formulario.IdArquivoRemessa.value;
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
    
   			url = "files/excluir/excluir_arquivo_remessa.php?IdLocalCobranca="+IdLocalCobranca+"&IdArquivoRemessa="+IdArquivoRemessa;
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
								url = 'cadastro_arquivo_remessa.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdArquivoRemessa+"_"+IdLocalCobranca== document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										aux=1;
										break;
									}
								}	
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[6].innerHTML.split(">");
										temp1	=	temp[1].split("<");
										valor	+=	parseFloat(temp1[0].replace(',','.'));
									}
									document.getElementById('tableListarValor').innerHTML	=	formata_float(Arredonda(valor,2),2).replace('.',',');	
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
		if(document.formulario.IdLocalCobranca.value==''){
			mensagens(1);
			document.formulario.IdLocalCobranca.focus();
			return false;
		}
		mensagens(0);
		return true;
	}
	function inicia(){
		document.formulario.IdLocalCobranca.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.IdStatus.value){				
				case '1': //cadastrado
					document.formulario.bt_cancelar.disabled 	= true;
					break;
				case '2': //processado
					document.formulario.bt_cancelar.disabled 	= false;
					break;
				case '3': //confirmado
					document.formulario.bt_cancelar.disabled 	= false;
					break;
                case '4': //confirmado entrega
				    document.formulario.bt_cancelar.disabled 	= false;
				    break;
				default:
					document.formulario.bt_cancelar.disabled 	= true;
					break;
			}
		}	
	}
	function cadastrar(Acao){
		if(validar() == true){
			if(Acao != ''){
				document.formulario.Acao.value = Acao;
			}
			
			switch(Acao){
				case "cancelar":
					if(confirm("ATENÇÃO!\r\n\r\nVocê esta prestes a cancelar o arquivo de remessa.")){
						document.formulario.submit();
					}
					break;
				default:
					document.formulario.submit();
					break;
			}
		}
	}
	function listaLocalCobranca(IdArquivoRemessa, IdLocalCobrancaTemp){
		if(IdLocalCobrancaTemp == undefined){
			IdLocalCobrancaTemp = '';
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
	    
	    url = "xml/arquivo_remessa_local_cobranca.php";
		xmlhttp.open("GET", url,true);
	    
		xmlhttp.onreadystatechange = function(){ 
			if(xmlhttp.readyState == 4){ 				
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						var nameNode, nameTextNode, DescricaoLocalCobranca, IdLocalCobranca;					
						
						addOption(document.formulario.IdLocalCobranca," ","");
						
						for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca").length; i++){
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							IdLocalCobranca = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoLocalCobranca")[i]; 
							nameTextNode = nameNode.childNodes[0];
							DescricaoLocalCobranca = nameTextNode.nodeValue;
						
							addOption(document.formulario.IdLocalCobranca,DescricaoLocalCobranca,IdLocalCobranca);
						}					
						
						if(IdLocalCobrancaTemp!=""){
							for(i=0;i<document.formulario.IdLocalCobranca.length;i++){
								if(document.formulario.IdLocalCobranca[i].value == IdLocalCobrancaTemp){
									document.formulario.IdLocalCobranca[i].selected	=	true;
									break;
								}
							}
						}else{
							document.formulario.IdLocalCobranca[0].selected	=	true;
						}
						
						if(IdArquivoRemessa != ""){
							busca_arquivo_remessa(IdArquivoRemessa,IdLocalCobrancaTemp,false);
						} else{
							busca_arquivo_remessa_tipo(0,false,document.formulario.Local.value,'',document.formulario.IdLocalCobranca.value);
						}
					}else{
						while(document.formulario.IdLocalCobranca.options.length > 0){
							document.formulario.IdLocalCobranca.options[0] = null;
						}
						
						addOption(document.formulario.IdLocalCobranca," ","");
					}					
				}		
			}
			return true;
		}
		xmlhttp.send(null);	
	}
	function verificaLocalCobranca(IdLocalCobranca){
		if(IdLocalCobranca == undefined){
			IdLocalCobranca = '';
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
	    
	    url = "xml/arquivo_remessa_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
		xmlhttp.open("GET", url,true);
	    
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4){
				if(xmlhttp.status == 200){
					if(xmlhttp.responseText != 'false'){
						var nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
						var nameTextNode = nameNode.childNodes[0];
						var IdStatus = nameTextNode.nodeValue;
					}
					
					busca_arquivo_remessa(document.formulario.IdArquivoRemessa.value,IdLocalCobranca,false,document.formulario.Local.value);
				}
			}
			return true;
		}
		xmlhttp.send(null);
	}
