	function excluir(Data){
		if(Data == ''){
			var Data = document.formulario.Data.value;
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
    
   			url = "files/excluir/excluir_datas_especiais.php?Data="+formatDate(Data);
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
								url = 'cadastro_datas_especiais.php?Erro='+document.formulario.Erro.value;
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
									if(Data == document.getElementById('tableListar').rows[i].accessKey){
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
		if(document.formulario.Data.value == ''){
			mensagens(1);
			document.formulario.Data.focus();
			return false;
		}else if(document.formulario.Data.value != ''){
			if(isData(document.formulario.Data.value) == false){
				document.formulario.Data.focus();
				mensagens(27);
				return false;
			}
		}
		if(document.formulario.TipoData.value == '0' || document.formulario.TipoData.value == ''){
			mensagens(1);
			document.formulario.TipoData.focus();
			return false;
		}
		if(document.formulario.DescricaoData.value == ''){
			mensagens(1);
			document.formulario.DescricaoData.focus();
			return false;
		}
		return true;
	}
	function validar_Data(id,campo){
		if(campo.value == ''){
			document.getElementById(id).style.backgroundColor='#FFFFFF';
			if(id == 'Data'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000000';	
			}
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
			if(id == 'Data'){
				document.getElementById(id).style.color='#C10000';
			}else{
				document.getElementById(id).style.color='#000000';	
			}
			mensagens(0);
			return true;
		}	
	}
	function inicia(){
		document.formulario.Data.focus();
	}
