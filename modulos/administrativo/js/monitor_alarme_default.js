	function busca_monitor_alarme(IdMonitor, IdStatus, Erro, Local){
		if(IdMonitor == '' || IdMonitor == undefined){
			IdMonitor = 0;
		}
		
		if(IdStatus == '' || IdStatus == undefined){
			IdStatus = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/monitor_alarme.php?IdMonitor="+IdMonitor+"&IdStatus="+IdStatus;
		
		call_ajax(url, function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				switch(Local){
					case 'MonitorAlarme':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor","");
						addParmUrl("marMonitorAlarme","IdMonitor","");
						
						document.formulario.IdStatus.value				= IdStatus;
						document.formulario.QtdTentativas.value			= '';
						document.formulario.IntervaloTentativa.value	= '';
						document.formulario.DestinatarioMensagem.value	= '';
						document.formulario.Mensagem.value				= '';
						document.formulario.Acao.value					= "inserir";
						
						document.formulario.IdMonitor.focus();
						verificaAcao();
						inicia();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdMonitor")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdMonitor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("QtdTentativas")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var QtdTentativas = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IntervaloTentativa")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IntervaloTentativa = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DestinatarioMensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DestinatarioMensagem = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Mensagem")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Mensagem = nameTextNode.nodeValue;
				
				switch(Local){
					case 'MonitorAlarme':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor",IdMonitor);
						addParmUrl("marMonitorAlarme","IdMonitor",IdMonitor);
						
						document.formulario.IdStatus.value				= IdStatus;
						document.formulario.QtdTentativas.value			= QtdTentativas;
						document.formulario.IntervaloTentativa.value	= IntervaloTentativa;
						document.formulario.DestinatarioMensagem.value	= DestinatarioMensagem;
						document.formulario.Mensagem.value				= Mensagem;
						document.formulario.Acao.value					= 'alterar';
						
						verificaAcao();
						inicia();
						break;
				}
			}
			
			if(document.getElementById("quadroBuscaMonitor") != null){
				if(document.getElementById("quadroBuscaMonitor").style.display == "block"){
					document.getElementById("quadroBuscaMonitor").style.display = "none";
				}
			}
		});
	}