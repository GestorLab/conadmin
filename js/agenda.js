	function excluir(IdAgenda){
		if(IdAgenda == ''){
			var IdAgenda = document.formulario.IdAgenda.value;
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
    
   			url = "files/excluir/excluir_agenda.php?IdAgenda="+IdAgenda;
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
								url = 'cadastro_agenda.php?Erro='+document.formulario.Erro.value;
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
									if(IdAgenda == document.getElementById('tableListar').rows[i].accessKey){
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
	function validar(){
		if(document.formulario.Data.value == ''){
			mensagens(1);
			document.formulario.Data.focus();
			return false;
		}else{
			if(isData(document.formulario.Data.value) == false){		
				mensagens(27);
				document.formulario.Data.focus();
				return false;
			}
		}
		if(document.formulario.Hora.value != ''){
			if(isTime(document.formulario.Hora.value) == false){		
				mensagens(28);
				document.formulario.Hora.focus();
				return false;
			}
		}
		if(document.formulario.IdStatus.value == '0'){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		if(document.formulario.Descricao.value == ''){
			mensagens(1);
			document.formulario.Descricao.focus();
			return false;
		}
		return true;
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
	function validar_Hora(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#000';
			
			mensagens(0);
			return false;
		}
		if(isTime(campo.value) == false){		
			document.getElementById(id).style.backgroundColor = '#C10000';
			document.getElementById(id).style.color='#FFFFFF';
			mensagens(28);
			return false;
		}else{
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			document.getElementById(id).style.color='#000';
			
			mensagens(0);
			return true;
		}	
	}
