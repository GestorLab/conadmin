	function excluir(IdCentroCusto){
		if(IdCentroCusto == '' || IdCentroCusto == undefined){
			var IdCentroCusto = document.formulario.IdCentroCusto.value;
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
    
   			url = "files/excluir/excluir_centro_custo.php?IdCentroCusto="+IdCentroCusto;
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
								url = 'cadastro_centro_custo.php?Erro='+document.formulario.Erro.value;
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
									if(IdCentroCusto == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').deleteRow(i);
										tableMultColor('tableListar');
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
		if(document.formulario.DescricaoCentroCusto.value==''){
			mensagens(1);
			document.formulario.DescricaoCentroCusto.focus();
			return false;
		}
		if(document.formulario.IdStatus.value == false){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;		
		}
		mensagens(0);
		return true;
	}
	
	function inicia(){
		document.formulario.IdCentroCusto.focus();
		statusInicial();
	}
