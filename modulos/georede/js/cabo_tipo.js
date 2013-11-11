	function inicia(){	
		document.formulario.IdCaboTipo.focus();
	}
		
	function validar(){
		if(document.formulario.Descricao.value==''){
			mensagens(1);
			document.formulario.Descricao.focus();
			return false;
		}
		
		return true;
	}
	
	function excluir(IdTipoCabo){
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
			url = "files/excluir/excluir_tipo_cabo.php?IdTipoCabo="+IdTipoCabo;
			call_ajax(url,function (xmlhttp){						
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";								
						url = "cadastro_tipo_cabo.php?Erro=" + document.formulario.Erro.value;						
						window.location.replace(url);
					} else{
						verificaErro();							
					}
				}else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					if(numMsg == 7){
						var aux = 0;
						for(var i=0; i<document.getElementById('tableListar').rows.length; i++){
							if(IdTipoCabo == document.getElementById('tableListar').rows[i].accessKey){
								document.getElementById('tableListar').deleteRow(i);
								tableMultColor('tableListar');
								break;
							}
						}								
						if(aux=1){
							document.getElementById("tableListarTotal").innerHTML	=	"Total: "+(document.getElementById('tableListar').rows.length-2);
						}
					}
				}		
			});
		}	
	} 
	