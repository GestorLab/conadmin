	function busca_agendamento(IdOrdemServico,Data,Hora,Erro,Local){
		if(IdOrdemServico == '' || IdOrdemServico == undefined){
			IdOrdemServico = 0;
		}
		if(Local == '' || Local == undefined){
			Local	=	document.formulario.Local.value;
		}
		var nameNode, nameTextNode, url;
		
		var xmlhttp   = false;
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
	    	xmlhttp = new XMLHttpRequest();
	        if(xmlhttp.overrideMimeType){
	        	xmlhttp.overrideMimeType('text/xml');
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
	    
	    var DataHoraAgendamento	=	formatDate(Data)+" "+Hora+":00";
	    
	   	url = "xml/agendamento.php?IdOrdemServico="+IdOrdemServico+"&DataHoraAgendamento="+DataHoraAgendamento;

		xmlhttp.open("GET", url,true);
		xmlhttp.onreadystatechange = function(){ 
	
			// Carregando...
			carregando(true);
	
			if(xmlhttp.readyState == 4){ 
				if(xmlhttp.status == 200){
	
					if(Erro != false){
						document.formulario.Erro.value = 0;
						verificaErro();
					}
					if(xmlhttp.responseText == 'false'){		
						switch(Local){
							default:	
								document.formulario.Acao.value	=	'inserir';
								
								document.formulario.DataCriacao.value		=	"";
								document.formulario.LoginCriacao.value		=	"";
								
								addParmUrl("marAgendamento","IdOrdemServico",IdOrdemServico);
								
								status_inicial();
								break;
						}
						// Fim de Carregando
						carregando(false);
					}else{
						
						switch(Local){
							default:
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataHoraAgendamento")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataHoraAgendamento = nameTextNode.nodeValue;
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var DataCriacao = nameTextNode.nodeValue;
							
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginCriacao = nameTextNode.nodeValue;					
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("LoginResponsavel")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var LoginResponsavel = nameTextNode.nodeValue;	
								
								nameNode = xmlhttp.responseXML.getElementsByTagName("NomeUsuario")[0]; 
								nameTextNode = nameNode.childNodes[0];
								var NomeUsuario = nameTextNode.nodeValue;
								
								Data	=	dateFormat(DataHoraAgendamento.substr(0,10));
								Hora	=	DataHoraAgendamento.substr(11,5);
								
								addParmUrl("marAgendamento","IdOrdemServico",IdOrdemServico);
								
								document.formulario.Data.value							=	Data;
								document.formulario.Hora.value							=	Hora;
								document.formulario.Login.value							=	LoginResponsavel;
								document.formulario.NomeUsuario.value					=	NomeUsuario;
								document.formulario.DataCriacao.value					=	dateFormat(DataCriacao);
								document.formulario.LoginCriacao.value					=	LoginCriacao;
								
								document.formulario.Acao.value 		= 'alterar';
								break;
						}
					}
					if(window.janela != undefined){
						window.janela.close();
					}
					verificaAcao();
				}
			} 
			// Fim de Carregando
			carregando(false);
			return true;
		}
		xmlhttp.send(null);
	}
