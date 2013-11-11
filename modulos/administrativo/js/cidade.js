	function excluir(IdPais, IdEstado, IdCidade){
		if(IdPais == ''){
			var IdPais = document.formulario.IdPais.value;
		}
		if(IdEstado == '' || IdEstado == undefined){
			var IdEstado = document.formulario.IdEstado.value;
		}
		if(IdCidade == '' || IdCidade == undefined){
			var IdCidade = document.formulario.IdCidade.value;
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
	
			url = "files/excluir/excluir_cidade.php?IdPais="+IdPais+"&IdEstado="+IdEstado+"&IdCidade="+IdCidade;
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
								url = 'cadastro_cidade.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								var aux = 0;
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdPais+"_"+IdEstado+"_"+IdCidade == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}
								}
								if(aux=1){
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
		if(document.formulario.IdPais.value==''){
			mensagens(1);
			document.formulario.IdPais.focus();
			return false;
		}
		if(document.formulario.IdEstado.value==''){
			mensagens(1);
			document.formulario.IdEstado.focus();
			return false;
		}
		if(document.formulario.Cidade.value==''){
			mensagens(1);
			document.formulario.Cidade.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdPais.focus();
	}

