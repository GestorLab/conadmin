	function excluir(IdAgenteAutorizado,IdCarteira,IdServico,Parcela){
		if(IdAgenteAutorizado == ''){
			var IdAgenteAutorizado = document.formulario.IdAgenteAutorizado.value;
		}
		if(IdCarteira == ''){
			var IdCarteira = document.formulario.IdCarteira.value;
		}
		if(IdServico == ''){
			var IdServico = document.formulario.IdServico.value;
		}
		if(Parcela == ''){
			var Parcela = document.formulario.Parcela.value;
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
    
   			url = "files/excluir/excluir_carteira_comissao.php?IdAgenteAutorizado="+IdAgenteAutorizado+"&IdCarteira="+IdCarteira+"&IdServico="+IdServico+"&Parcela="+Parcela;
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
								url = 'cadastro_carteira_comissao.php?Erro='+document.formulario.Erro.value;
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
									if(IdAgenteAutorizado+"_"+IdCarteira+"_"+IdServico+"_"+Parcela == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
									for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
										temp	=	document.getElementById('tableListar').rows[i].cells[5].innerHTML.split(">");
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
		if(document.formulario.IdAgenteAutorizado.value==''){
			mensagens(1);
			document.formulario.IdAgenteAutorizado.focus();
			return false;
		}
		if(document.formulario.IdCarteira.value==''){
			mensagens(1);
			document.formulario.IdCarteira.focus();
			return false;
		}
		if(document.formulario.IdServico.value==''){
			mensagens(1);
			document.formulario.IdServico.focus();
			return false;
		}
		if(document.formulario.Parcela.value==''){
			mensagens(1);
			document.formulario.Parcela.focus();
			return false;
		}
		if(document.formulario.Percentual.value=='' || document.formulario.Percentual.value=='0,00'){
			mensagens(1);
			document.formulario.Percentual.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdAgenteAutorizado.focus();
		status_inicial();
	}
	function status_inicial(){
		if(document.formulario.Percentual.value==''){
			document.formulario.Percentual.value	=	'0,00';	
		}
	}
