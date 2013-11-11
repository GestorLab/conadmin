	function excluir(IdTipoEmail){
		if(IdTipoEmail == '' || IdTipoEmail == undefined){
			IdTipoEmail = document.formulario.IdTipoEmail.value;
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
    
   			url = "files/excluir/excluir_tipo_email.php?IdTipoEmail="+IdTipoEmail;
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
								url = 'cadastro_tipo_email.php?Erro='+document.formulario.Erro.value;
								window.location.replace(url);
							}else{
								verificaErro();
							}
						}else{
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 7){
								for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
									if(IdTipoEmail == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar',document.filtro.corRegRand.value);
										break;
									}
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
		if(document.formulario.DescricaoTipoEmail.value==''){
			mensagens(1);
			document.formulario.DescricaoTipoEmail.focus();
			return false;
		}
		if(document.formulario.DiasParaEnvio.value==''){
			mensagens(1);
			document.formulario.DiasParaEnvio.focus();
			return false;
		}
		if(document.formulario.AssuntoEmail.value==''){
			mensagens(1);
			document.formulario.AssuntoEmail.focus();
			return false;
		}
		if(document.formulario.EstruturaEmail.value==''){
			mensagens(1);
			document.formulario.EstruturaEmail.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdTipoEmail.focus();
	}
	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value=='inserir'){			
				document.formulario.bt_alterar.disabled 	= true;
				document.formulario.bt_visualizar.disabled 	= true;
			}
			if(document.formulario.Acao.value=='alterar'){			
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_visualizar.disabled 	= false;
			}
		}	
	}
	function visualizar(){
		IdLoja		=	document.formulario.IdLoja.value;
		IdTipoEmail	=	document.formulario.IdTipoEmail.value;
		window.parent.email.location.replace("../../visualizar_tipo_email.php?IdLoja="+IdLoja+"&IdTipoEmail="+IdTipoEmail);
	}
	function cadastrar(){
		if(validar()==true){
			window.parent.cadastro.document.formulario.submit();
		}
	}
