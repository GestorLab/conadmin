	function inicia(){	
		document.formulario.IdCabo.focus();
	}
		
	function validar(){
		if(document.formulario.TipoCabo.value==''){
			mensagens(1);
			document.formulario.TipoCabo.focus();
			return false;
		}
		
		if(document.formulario.Especificacao.value==''){
			mensagens(1);
			document.formulario.Especificacao.focus();
			return false;
		}	
		return true;
	}
	
	function excluir(IdCabo){
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
			url = "files/excluir/excluir_cabo.php?IdCabo="+IdCabo;
			call_ajax(url,function (xmlhttp){						
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";								
						url = "cadastro_cabo.php?Erro=" + document.formulario.Erro.value;						
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
							if(IdCabo == document.getElementById('tableListar').rows[i].accessKey){
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
			});
		}	
	} 
	