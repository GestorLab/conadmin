	function excluir(IdTipo,IdSubTipo){
		if(IdTipo == ''){
			var IdTipo = document.formulario.IdTipo.value;
		}
		if(IdSubTipo == ''){
			var IdSubTipo = document.formulario.IdSubTipo.value;
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
    
   			url = "files/excluir/excluir_subtipo_help_desk.php?IdTipo="+IdTipo+"&IdSubTipo="+IdSubTipo;
			xmlhttp.open("GET", url,true);
	
			xmlhttp.onreadystatechange = function(){ 

				// Carregando...
				carregando(true);

				if(xmlhttp.readyState == 4){ 
					if(xmlhttp.status == 200){
						if(document.formulario != undefined){
							//Salert(xmlhttp.responseText);
							document.formulario.Erro.value = xmlhttp.responseText;
							if(parseInt(xmlhttp.responseText) == 7){
								document.formulario.Acao.value 	= 'inserir';
								url = 'cadastro_subtipo_help_desk.php?Erro='+document.formulario.Erro.value;
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
									if(IdSubTipo == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										aux=1;
										break;
									}//if
								}//for	
								if(aux=1){
									document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
								}							
							}//if
						}//else
					}//if
					// Fim de Carregando
					carregando(false);
				}//if
				return true;
			}
			xmlhttp.send(null);
		}
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){		
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){	
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
			}
		}	
	}
	function inicia(){
		document.formulario.IdTipo.focus();
	}
	function validar(){
		if(document.formulario.IdTipo.value == ''){
			mensagens(1);
			document.formulario.IdTipo.focus();
			return false;
		}
		if(document.formulario.DescricaoSubTipo.value == ''){
			mensagens(1);
			document.formulario.DescricaoSubTipo.focus();
			return false;
		}
		if(document.formulario.IdStatusSubTipo.value == ''){				
			mensagens(1);
			document.formulario.IdStatusSubTipo.focus();
			return false;
		}
		
		mensagens(0);
		return true;
	}
	function cadastrar(acao){
		if(validar()){ 
			document.formulario.submit();
		}
	}
