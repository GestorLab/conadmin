	function excluir(IdLocalCobranca){
		if(IdLocalCobranca == ''){
			IdLocalCobranca = document.formulario.IdLocalCobranca.value;
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
    
   			url = "files/excluir/excluir_local_cobranca.php?IdLocalCobranca="+IdLocalCobranca;
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
								url = 'cadastro_local_cobranca.php?Erro='+document.formulario.Erro.value;
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
									if(IdLocalCobranca == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
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
	function validar(){
		if(document.formulario.IdTipoLocalCobranca.value==''){
			mensagens(1);
			document.formulario.IdTipoLocalCobranca.focus();
			return false;
		}
		if(document.formulario.DescricaoLocalCobranca.value==''){
			mensagens(1);
			document.formulario.DescricaoLocalCobranca.focus();
			return false;
		}
		if(document.formulario.AbreviacaoNomeLocalCobranca.value==''){
			mensagens(1);
			document.formulario.AbreviacaoNomeLocalCobranca.focus();
			return false;
		}
		if(document.formulario.ValorDespesaLocalCobranca.value==''){
			mensagens(1);
			document.formulario.ValorDespesaLocalCobranca.focus();
			return false;
		}
		if(document.formulario.PercentualJurosDiarios.value==''){
			mensagens(1);
			document.formulario.PercentualJurosDiarios.focus();
			return false;
		}
		if(document.formulario.PercentualMulta.value==''){
			mensagens(1);
			document.formulario.PercentualMulta.focus();
			return false;
		}
		if(document.formulario.ValorCobrancaMinima.value==''){
			mensagens(1);
			document.formulario.ValorCobrancaMinima.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		status_inicial();
		document.formulario.IdLocalCobranca.focus();
	}
	function status_inicial(){
		if(document.formulario.ValorDespesaLocalCobranca.value==''){
			document.formulario.ValorDespesaLocalCobranca.value	=	'0,00';
		}
		if(document.formulario.PercentualJurosDiarios.value==''){
			document.formulario.PercentualJurosDiarios.value	=	'0,000';
		}
		if(document.formulario.PercentualMulta.value==''){
			document.formulario.PercentualMulta.value	=	'0,000';
		}
		if(document.formulario.ValorCobrancaMinima.value==''){
			document.formulario.ValorCobrancaMinima.value	=	'0,00';
		}
	}
