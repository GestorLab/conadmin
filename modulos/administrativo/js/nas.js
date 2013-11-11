	function excluir(id){
		if(id == ''){
			id = document.formulario.id.value;
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
    
   			url = "files/excluir/excluir_nas.php?id="+id;
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
								url = 'cadastro_nas.php?Erro='+document.formulario.Erro.value;
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
									if(id == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
										break;
									}
								}
								//document.getElementById('IdLoja'+IdLoja).bgColor = '#FF9D9D';
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
	
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= false;
			}
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_inserir.disabled 	= false;
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_excluir.disabled 	= true;
			}
		}	
	}
	function validar(){		
		if(document.formulario.nasname.value==''){
			mensagens(1);
			document.formulario.nasname.focus();
			return false;
		}
		if(document.formulario.shortname.value==''){
			mensagens(1);
			document.formulario.shortname.focus();
			return false;
		}
		if(document.formulario.type.value==''){
			mensagens(1);
			document.formulario.type.focus();
			return false;
		}
		if(document.formulario.secret.value==''){
			mensagens(1);
			document.formulario.secret.focus();
			return false;
		}
		if(document.formulario.description.value==''){
			mensagens(1);
			document.formulario.description.focus();
			return false;
		}
		return true;
	}