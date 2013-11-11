	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function inicia(){
		document.formulario.IdMonitor.focus();
	}
	function validar(){
		if(document.formulario.DescricaoMonitor.value == ''){
			mensagens(1);
			document.formulario.DescricaoMonitor.focus();
			return false;
		}
		
		if(document.formulario.HostAddress.value == ''){
			mensagens(1);
			document.formulario.HostAddress.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == ''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
	
		return true;
	}
	function excluir(IdMonitor){
		if(IdMonitor == '' || IdMonitor == undefined){
			IdMonitor = document.formulario.IdMonitor.value;
		}
		
		if(excluir_registro()){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return;
				}
			}
			
			var url = "./files/excluir/excluir_monitor.php?IdMonitor="+IdMonitor;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_monitor.php?Erro="+document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						var aux = 0, valor = 0;
						
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(IdMonitor == document.getElementById("tableListar").rows[i].accessKey){
								document.getElementById("tableListar").deleteRow(i);
								tableMultColor("tableListar", document.filtro.corRegRand.value);
								aux = 1;
								break;
							}
						}
						
						if(aux == 1){
							document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById("tableListar").rows.length-2);
						}								
					}
				}
			});
		}
	}
	function listar_monitor_alarme(IdMonitor,Erro){
		while(document.getElementById("tabelaMonitorAlarme").rows.length > 2){
			document.getElementById("tabelaMonitorAlarme").deleteRow(1);
		}
		
		if(IdMonitor == ''){
			IdMonitor = 0;
		}
		
		var url = "xml/monitor_alarme.php?IdMonitor="+IdMonitor;
		
		call_ajax(url,function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == 'false'){
				document.getElementById("tabelaMonitorAlarmeTotal").innerHTML = "Total: 0";
			} else{
				for(var i = 0; i < xmlhttp.responseXML.getElementsByTagName("IdMonitor").length; i++){
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IdStatus")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IdStatus = nameTextNode.nodeValue;
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("DestinatarioMensagem")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var DestinatarioMensagem = nameTextNode.nodeValue;
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("QtdTentativas")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var QtdTentativas = nameTextNode.nodeValue;
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("IntervaloTentativa")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var IntervaloTentativa = nameTextNode.nodeValue;
					
					var nameNode = xmlhttp.responseXML.getElementsByTagName("Status")[i]; 
					var nameTextNode = nameNode.childNodes[0];
					var Status = nameTextNode.nodeValue;
					
					var tam = document.getElementById("tabelaMonitorAlarme").rows.length;
					var linha = document.getElementById("tabelaMonitorAlarme").insertRow(tam-1);
					
					if((i%2) != 0){
						linha.style.backgroundColor = "#E2E7ED";
					}
					
					linha.accessKey = IdMonitor+"_"+IdStatus; 
					
					var c0	= linha.insertCell(0);
					var c1	= linha.insertCell(1);
					var c2	= linha.insertCell(2);
					var c3	= linha.insertCell(3);
					var c4	= linha.insertCell(4);
					
					var linkIni = "<a href=\"cadastro_monitor_alarme.php?IdMonitor="+IdMonitor+"&IdStatus="+IdStatus+"\">";
					var linkFim = "</a>";
					
					DestinatarioMensagem = DestinatarioMensagem.split(/[\|,:;\n]/g);
					
					if(DestinatarioMensagem.length > 1){
						DestinatarioMensagem = DestinatarioMensagem[0]+"..."
					}
					
					c0.innerHTML = linkIni+Status+linkFim;
					c0.style.padding = "0 0 0 5px";
					c1.innerHTML = linkIni+QtdTentativas+linkFim;
					c2.innerHTML = linkIni+IntervaloTentativa+linkFim;
					c3.innerHTML = linkIni+DestinatarioMensagem+linkFim;
					c4.innerHTML = "<img src='../../img/estrutura_sistema/ico_del_c.gif' alt='Excluir?'>";
					c4.style.textAlign = "center";
					c4.style.cursor = "pointer";
				}
				
				document.getElementById("tabelaMonitorAlarmeTotal").innerHTML = "Total: "+i;
			}
		});
	}
	function busca_ponto_monitoramento(IdMonitor, loading, Key){
		if(Key == undefined)
			Key = "";
		
		var url = "xml/monitor.php?IdMonitor="+IdMonitor+"&Key="+Key+"&"+Math.random();
		
		call_ajax(url,function (xmlhttp){
			if(xmlhttp.responseText != "false"){
				var nameNode = xmlhttp.responseXML.getElementsByTagName("Latencia")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var Latencia = nameTextNode.nodeValue;
				
				if(Latencia > 0.00){
					nameNode = xmlhttp.responseXML.getElementsByTagName("HostAddress")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var HostAddress = nameTextNode.nodeValue;
					
					var img = "<a href='http://"+HostAddress+"' target='_blank'><img src='../../img/estrutura_sistema/semafaro_1.jpg' alt='ON-LINE'></a>";
					var Latencia = "Latência: "+Latencia+"ms";
				} else{
					var img = "<img id='ponto_monitoramento_img_"+IdMonitor+"' src='../../img/estrutura_sistema/semafaro_0.jpg' alt='OFF-LINE'>";
					var Latencia = "<B>OFF-LINE</B>";
				}
				
				document.getElementById("ponto_monitoramento_img_"+IdMonitor).innerHTML = img;
				document.getElementById("ponto_monitoramento_latencia_"+IdMonitor).innerHTML = Latencia;
			}
			
			setTimeout(function () {
				busca_ponto_monitoramento(IdMonitor, false, Key); 
			}, 5000);
		},{id: "ponto_monitoramento_"+IdMonitor, loading: loading});
	}