	function validar(){
		if(document.formulario.IdNotaFiscalLayout.value == ''){
			document.formulario.IdNotaFiscalLayout.focus();
			mensagens(1);
			return false;
		}
		if(document.formulario.MesReferencia.value == ''){
			document.formulario.MesReferencia.focus();
			mensagens(1);
			return false;
		} else{
			var Temp = document.formulario.MesReferencia.value.split(/\//);
			
			if(parseInt(Temp[1]+Temp[0]) >= parseInt(document.formulario.MesAtual.value.replace(/-/i, ""))){
				document.formulario.MesReferencia.focus();
				mensagens(167);
				return false;
			}
		}
		if(document.formulario.NotaFiscalTransmitir.value == "2" && document.formulario.Acao.value == "inserir"){
			document.formulario.MesReferencia.focus();
			mensagens(182);
			return false;
		}
		if(document.formulario.IdStatusArquivoMestre.value == ''){
			document.formulario.IdStatusArquivoMestre.focus();
			mensagens(1);
			return false;
		}
		
		return true;
	}
	function inicia(){
		document.formulario.IdNotaFiscalLayout.focus();		
	}
	function verifica_mes(nome,campo){
		if(campo.value != ''){
			if(val_Mes(campo.value) == false){
				document.getElementById(nome).style.backgroundColor = '#C10000';
				document.getElementById(nome).style.color='#FFF';
				mensagens(45);			
			}else{
				document.getElementById(nome).style.backgroundColor = '#FFF';
				document.getElementById(nome).style.color='#C10000';
				mensagens(0);		
			}
		}else{	
			document.getElementById(nome).style.backgroundColor = '#FFF';
			document.getElementById(nome).style.color='#C10000';
			mensagens(0);
		}
	}
	function verifica_nota_fiscal(){
		document.formulario.NotaFiscalTransmitir.value = '2';
		
		var MesReferencia			= document.formulario.MesReferencia.value;
		var IdNotaFiscalLayout		= document.formulario.IdNotaFiscalLayout.value;
		
		if(MesReferencia != "" && IdNotaFiscalLayout != ""){
			var url = "xml/nota_fiscal.php?PeriodoApuracao="+MesReferencia+"&IdNotaFiscalLayout="+IdNotaFiscalLayout+"&Limit=1&"+Math.random();
			
			call_ajax(url, function (xmlhttp){
				if(xmlhttp.responseText != "false"){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdNotaFiscal")[0]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdNotaFiscal = nameTextNode.nodeValue;
					
					if(IdNotaFiscal != ""){
						document.formulario.NotaFiscalTransmitir.value = '1';
					}
				}
			});
		}
	}
	function cadastrar(Acao){
		if(validar()==true){
			if(Acao != ''){
				document.formulario.Acao.value	=	Acao;
			}
			document.formulario.submit();
		}
	}
	
	function verificaAcao(){
		if(document.formulario != undefined){
			switch(document.formulario.Acao.value){
				case 'inserir':
					document.formulario.bt_inserir.disabled 			= false;
					document.formulario.bt_excluir.disabled			 	= true;
					document.formulario.bt_processar.disabled 			= true;
					document.formulario.bt_confirmar.disabled 			= true;
					document.formulario.bt_enviar.disabled 				= true;
					document.formulario.bt_imprimir_nota.disabled 		= true;
					document.formulario.bt_confirmar_entrega.disabled 	= true;									
					break;			
				case 'processar':
					document.formulario.bt_inserir.disabled 			= true;
					document.formulario.bt_excluir.disabled 			= false;
					document.formulario.bt_processar.disabled 			= false;
					document.formulario.bt_confirmar.disabled		 	= true;
					document.formulario.bt_enviar.disabled 				= true;
					document.formulario.bt_imprimir_nota.disabled 		= false;
					document.formulario.bt_confirmar_entrega.disabled 	= true;
					break;
				case 'confirmar':
					document.formulario.bt_inserir.disabled 			= true;
					document.formulario.bt_excluir.disabled 			= true;
					document.formulario.bt_processar.disabled 			= true;
					document.formulario.bt_confirmar.disabled		 	= false;
					document.formulario.bt_enviar.disabled 				= true;
					document.formulario.bt_imprimir_nota.disabled 		= false;
					document.formulario.bt_confirmar_entrega.disabled 	= true;
					break;
				case 'confirmarEntrega':
					document.formulario.bt_inserir.disabled 			= true;
					document.formulario.bt_excluir.disabled 			= false;
					document.formulario.bt_processar.disabled 			= true;
					document.formulario.bt_confirmar.disabled		 	= true;
					document.formulario.bt_enviar.disabled 				= false;
					document.formulario.bt_imprimir_nota.disabled 		= false;
					document.formulario.bt_confirmar_entrega.disabled 	= false;
					break;
				case 'enviarEmail':
					document.formulario.bt_inserir.disabled 			= true;
					document.formulario.bt_excluir.disabled 			= true;
					document.formulario.bt_processar.disabled 			= true;
					document.formulario.bt_confirmar.disabled		 	= true;
					document.formulario.bt_enviar.disabled 				= false;
					document.formulario.bt_confirmar_entrega.disabled 	= true;
					break;
				default:					
					document.formulario.bt_inserir.disabled 			= false;
					document.formulario.bt_excluir.disabled 			= true;
					document.formulario.bt_processar.disabled 			= true;
					document.formulario.bt_confirmar.disabled		 	= true;
					document.formulario.bt_enviar.disabled 				= true;
					document.formulario.bt_confirmar_entrega.disabled 	= true;
					break
			}
		}	
	}
	function excluir(IdNotaFiscalLayout,MesReferencia,IdStatus,Status){
		if(IdNotaFiscalLayout == '' || IdNotaFiscalLayout == undefined){
			var IdNotaFiscalLayout = document.formulario.IdNotaFiscalLayout.value;
		}
		if(MesReferencia == '' || MesReferencia == undefined){
			var MesReferencia = document.formulario.MesReferencia.value;
		}
		if(Status == '' || Status == undefined){
			Status = document.formulario.Status.value;
		}
		if(IdStatus != 1 && IdStatus != 3){
			return false;
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
    		
   			url = "files/excluir/excluir_nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout="+IdNotaFiscalLayout+"&MesReferencia="+MesReferencia+"&Status="+Status;
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
								url = 'cadastro_nf_2_via_eletronica_remessa.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
						
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0, valor=0, key;								
								key = IdNotaFiscalLayout+"_"+MesReferencia;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(key == document.getElementById('tableListar').rows[i].accessKey){										
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
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
				}
				// Fim de Carregando
				carregando(false);
				return true;
			}
			xmlhttp.send(null);
		}
	} 
	
	function atualizaLogProcessamento(IdNotaFiscalLayout,Erro,MesReferencia,IdStatusArquivoMestre){
		if(IdNotaFiscalLayout == '' || IdNotaFiscalLayout == undefined){
			IdNotaFiscalLayout = 0;
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
	    
	    url = "xml/nf_2_via_eletronica_remessa.php?IdNotaFiscalLayout="+IdNotaFiscalLayout+"&MesReferencia="+MesReferencia+"&Status="+IdStatusArquivoMestre;
		xmlhttp.open("GET", url,true);

		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(xmlhttp.responseText == 'false'){		
						// Fim de Carregando
						carregando(false);
					}else{
						nameNode = xmlhttp.responseXML.getElementsByTagName("LogProcessamento")[0]; 
						nameTextNode = nameNode.childNodes[0];
						var LogProcessamento = nameTextNode.nodeValue;						
				
						document.formulario.LogProcessamento.value			= LogProcessamento;
						
					}
					if(window.janela != undefined){
						window.janela.close();
					}
				}
				// Fim de Carregando
				carregando(false);
			} 
			return true;
		}
		xmlhttp.send(null);
	}
	
	function imprimir_nota_fiscal(IdLayoutNotaFiscal,Periodo){
		if(IdLayoutNotaFiscal.value == ""){
			IdLayoutNotaFiscal.focus();
			mensagens(1);
			return false;
		}
		if(Periodo.value == ""){
			Periodo.focus();
			mensagens(1);
			return false;
		}
		window.open("rotinas/exportar_nota_fiscal.php?IdLayoutNotaFiscal="+IdLayoutNotaFiscal.value+"&Periodo="+Periodo.value+"&FormatoExportacao="+1+"&Local=Nf2");
	}