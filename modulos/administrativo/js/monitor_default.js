	function busca_monitor(IdMonitor, Erro, Local){
		if(IdMonitor == '' || IdMonitor == undefined){
			IdMonitor = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/monitor.php?IdMonitor="+IdMonitor;
		
		call_ajax(url, function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				switch(Local){
					case 'Monitor':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor","");
						addParmUrl("marMonitorAlarme","IdMonitor","");
						addParmUrl("marMapeamento","IdMonitor","");
						
						document.formulario.IdMonitor.value			= '';
						document.formulario.DescricaoMonitor.value	= '';
						document.formulario.IdStatus.value			= '';
						document.formulario.HostAddress.value		= '';
						document.formulario.Atualmente.value		= 'Off-Line';
						document.formulario.Porta.value				= '';
						document.formulario.Timeout.value			= '';
						document.formulario.LoginCriacao.value		= '';
						document.formulario.DataCriacao.value		= '';
						document.formulario.LoginAlteracao.value	= '';
						document.formulario.DataAlteracao.value		= '';
						document.formulario.Acao.value				= "inserir";
						
						listar_monitor_alarme(0,false);
						document.formulario.IdMonitor.focus();
						verificaAcao();
						break;
					case 'MonitorAlarme':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor","");
						addParmUrl("marMonitorAlarme","IdMonitor","");
						addParmUrl("marMonitorNovo","IdMonitor","");
						
						document.formulario.IdMonitor.value			= '';
						document.formulario.DescricaoMonitor.value	= '';
						document.formulario.IdStatus.value			= '';
						document.formulario.HostAddress.value		= '';
						document.formulario.Porta.value				= '';
						
						if(Erro){
							busca_monitor_alarme(0, 0, false);
						}
						
						document.formulario.IdMonitor.focus();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("IdMonitor")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				IdMonitor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoMonitor")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DescricaoMonitor = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var IdStatus = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("HostAddress")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var HostAddress = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Porta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Porta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Timeout")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Timeout = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Latitude")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Latitude = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Longitude")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Longitude = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("Atualmente")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var Atualmente = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataCriacao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var LoginAlteracao = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var DataAlteracao = nameTextNode.nodeValue;
				
				switch(Local){
					case 'Monitor':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor",IdMonitor);
						addParmUrl("marMonitorAlarme","IdMonitor",IdMonitor);
						addParmUrl("marMapeamento","IdMonitor",IdMonitor);

						if(Atualmente == 1){
							Atualmente = 'On-Line';
						}else{							
							Atualmente = 'Off-Line';
						}
						
						document.formulario.IdMonitor.value			= IdMonitor;
						document.formulario.DescricaoMonitor.value	= DescricaoMonitor;
						document.formulario.IdStatus.value			= IdStatus;
						document.formulario.HostAddress.value		= HostAddress;
						document.formulario.Porta.value				= Porta;
						document.formulario.Timeout.value			= Timeout;
						document.formulario.Atualmente.value		= Atualmente;
						document.formulario.Latitude.value			= Latitude;
						document.formulario.Longitude.value			= Longitude;
						document.formulario.LoginCriacao.value		= LoginCriacao;
						document.formulario.DataCriacao.value		= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.DataAlteracao.value		= dateFormat(DataAlteracao);
						document.formulario.Acao.value				= 'alterar';
						
						listar_monitor_alarme(IdMonitor,false);
						document.formulario.IdMonitor.focus();
						verificaAcao();
						break;
					case 'MonitorAlarme':
						addParmUrl("marMonitorAlarmeNovo","IdMonitor",IdMonitor);
						addParmUrl("marMonitorAlarme","IdMonitor",IdMonitor);
						addParmUrl("marMonitorNovo","IdMonitor",IdMonitor);
						
						document.formulario.IdMonitor.value			= IdMonitor;
						document.formulario.DescricaoMonitor.value	= DescricaoMonitor;
						document.formulario.IdStatusMonitor.value	= IdStatus;
						document.formulario.HostAddress.value		= HostAddress;
						document.formulario.Porta.value				= Porta;
						
						if(Erro){
							busca_monitor_alarme(0, 0, false);
						}
						
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