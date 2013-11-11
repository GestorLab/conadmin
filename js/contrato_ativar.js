	function inicia(){
		status_inicial();
		document.formulario.DataAtivacaoInicio.focus();
	}
	function cadastrar(){
		if(validar()==true){
			document.formulario.submit();
		}		
	}
	function editar_contrato(){
		window.location.replace('cadastro_contrato.php?IdContrato='+document.formulario.IdContrato.value+'&Redirecionar=N');
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return false;
		}
		if(isData(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(27);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#C10000';
			mensagens(0);
			return true;
		}	
	}
	function verificaDataFinal(campo,DataInicio,DataFim){
		if(DataInicio != '' && DataFim != ''){
			var dataI = formatDate(DataInicio);
			var dataF = formatDate(DataFim);
			if(dataF < dataI){
				document.getElementById(campo).style.backgroundColor = '#C10000';
				document.getElementById(campo).style.color='#FFFFFF';
				mensagens(39);
				return false;
			}else{
				colorTemp = document.getElementById(campo).style.backgroundColor;
				document.getElementById(campo).style.backgroundColor = '#FFFFFF';
				document.getElementById(campo).style.color='#C10000';
				mensagens(0);
			}
			return true;
		}
	}
	function periodicidade_parcelas(IdPeriodicidade,QtdParcelaTemp){
		if(IdPeriodicidade == ''){
			while(document.formulario.QtdParcela.options.length > 0){
				document.formulario.QtdParcela.options[0] = null;
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
			url = xmlhttp.open("GET", "xml/periodicidade.php?IdPeriodicidade="+IdPeriodicidade); 
			// Carregando...
			carregando(true);
			
			xmlhttp.onreadystatechange = function(){ 
				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(xmlhttp.responseText == 'false'){		
							/*while(document.formulario.QtdParcela.options.length > 0){
								document.formulario.QtdParcela.options[0] = null;
							}*/
						}else{	
							var nameNode, nameTextNode, IdPeriodicidade;					
							/*while(document.formulario.QtdParcela.options.length > 0){
								document.formulario.QtdParcela.options[0] = null;
							}*/
							
							//addOption(document.formulario.IdPeriodicidade,"","0");
							
							for(var i=0; i<xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade").length; i++){
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("IdPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								IdPeriodicidade = nameTextNode.nodeValue;

								nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoPeriodicidade")[i]; 
								nameTextNode = nameNode.childNodes[0];
								DescricaoPeriodicidade = nameTextNode.nodeValue;

								addOption(document.formulario.IdPeriodicidade,DescricaoPeriodicidade,IdPeriodicidade);
							}
							
							for(ii=0;ii<document.formulario.IdPeriodicidade.length;ii++){
								if(document.formulario.IdPeriodicidade[ii].value == IdPeriodicidade){
									document.formulario.IdPeriodicidade[ii].selected = true;	
								}
							}

							if(document.formulario.IdContrato.value == '' || document.formulario.Local.value != 'Vigencia'){
								addOption(document.formulario.QtdParcela,QtdParcelaTemp,QtdParcelaTemp);
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
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_confirmar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='confirmar'){			
				document.formulario.bt_confirmar.disabled 	= false;
			}
		}	
	}
	
	function validar(){
		if(document.formulario.DataAtivacaoFim.value==""){
			mensagens(1);
			document.formulario.DataAtivacaoFim.focus();
			return false;
		}else{
			if(isData(document.formulario.DataAtivacaoFim.value) == false){		
				document.formulario.DataAtivacaoFim.focus();
				mensagens(27);
				return false;
			}else{
				if(verificaDataFinal('tit_DataAtivacaoFim',document.formulario.DataAtivacaoInicio.value,document.formulario.DataAtivacaoFim.value)== false){
					document.formulario.DataAtivacaoFim.focus();
					mensagens(39);
					return false;	
				}
			}
		}
		if(document.formulario.AgruparContrato.value==0 || document.formulario.AgruparContrato.value==''){
			mensagens(1);
			document.formulario.AgruparContrato.focus();
			return false;
		}
		if(document.formulario.DataPrimeiroVenc.value==""){
			mensagens(1);
			document.formulario.DataPrimeiroVenc.focus();
			return false;
		}else{
			if(isData(document.formulario.DataPrimeiroVenc.value) == false){		
				document.formulario.DataPrimeiroVenc.focus();
				mensagens(27);
				return false;
			}
		}
		mensagens(0);
		return true;
	}
