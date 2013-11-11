	function derrubar_conexao(IdLogAcesso,IdStatus){
		if(IdStatus==2){
			if(confirm('ATENCAO!\n\nDerrubar conexao do usuario?','SIM','NAO') == true){
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
	    
	   			url = "files/editar/editar_log_acesso.php?IdLogAcesso="+IdLogAcesso;
				xmlhttp.open("GET", url,true);
		
				xmlhttp.onreadystatechange = function(){ 
	
					// Carregando...
					carregando(true);
	
					if(xmlhttp.readyState == 4){ 
						if(xmlhttp.status == 200){
							var numMsg = parseInt(xmlhttp.responseText);
							mensagens(numMsg);
							if(numMsg == 117){
								for(var i=1; i<(document.getElementById('tableListar').rows.length-1); i++){
									if(IdLogAcesso == document.getElementById('tableListar').rows[i].accessKey){
										document.getElementById('tableListar').rows[i].cells[8].innerHTML	=	"<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Derrubar conexão?'>";
										break;
									}//if
								}//for		
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
	}
