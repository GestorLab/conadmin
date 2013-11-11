	function inicia(){
		if(document.formulario != undefined){			
			document.formulario.IdRadIdPool.focus();
		}
	}
	
	function validar(){
		if(document.formulario.PoolName.value == ''){
			mensagens(1);
			document.formulario.PoolName.focus();
			return false;
		}
		if(document.formulario.FrameIpAddress.value == ''){
			mensagens(1);
			document.formulario.FrameIpAddress.focus();
			return false;
		}
		if(document.formulario.NasIpAddress.value == ''){
			mensagens(1);
			document.formulario.NasIpAddress.focus();
			return false;
		}
		return true;
	}
	
	function excluir(id){
		if(excluir_registro() == true){		
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

			url = "files/excluir/excluir_radippool.php?Id="+id;
			call_ajax(url,function (xmlhttp){						
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_radippool.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				}else{
					var aux = 0;
					for(var i=0; i<document.getElementById('tableListar').rows.length; i++){							
						if(id == i){
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
			});
		}
	
	} 