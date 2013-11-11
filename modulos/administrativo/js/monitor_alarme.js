	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function inicia(){
		if(document.formulario.QtdTentativas.value == ''){
			document.formulario.QtdTentativas.value = document.formulario.QtdTentativasDefault.value;
		}
		
		if(document.formulario.IntervaloTentativa.value == ''){
			document.formulario.IntervaloTentativa.value = document.formulario.IntervaloTentativaDefault.value;
		}
		
		document.formulario.IdMonitor.focus();
	}
	function validar(){
		if(Number(document.formulario.IdMonitor.value) < 1){
			mensagens(1);
			document.formulario.IdMonitor.focus();
			return false;
		}
		
		if(Number(document.formulario.QtdTentativas.value) < 1){
			mensagens(1);
			document.formulario.QtdTentativas.focus();
			return false;
		}
		
		if(document.formulario.IntervaloTentativa.value == ''){
			mensagens(1);
			document.formulario.IntervaloTentativa.focus();
			return false;
		}
		
		if(document.formulario.IdStatus.value == ''){
			mensagens(1);
			document.formulario.IdStatus.focus();
			return false;
		}
		
		if(document.formulario.DestinatarioMensagem.value == ''){
			mensagens(1);
			document.formulario.DestinatarioMensagem.focus();
			return false;
		}
		
		if(document.formulario.Mensagem.value == ''){
			mensagens(1);
			document.formulario.Mensagem.focus();
			return false;
		}
		
		return true;
	}
	function excluir(IdMonitor, IdStatus, Local){
		if(IdMonitor == '' || IdMonitor == undefined){
			IdMonitor = document.formulario.IdMonitor.value;
		}
		
		if(IdStatus == '' || IdStatus == undefined){
			IdStatus = document.formulario.IdStatus.value;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		if(excluir_registro()){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return;
				}
			}
			
			var url = "./files/excluir/excluir_monitor_alarme.php?Local="+Local+"&IdMonitor="+IdMonitor+"&IdStatus="+IdStatus;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_monitor_alarme.php?Erro="+document.formulario.Erro.value;
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
							if(IdMonitor+"_"+IdStatus == document.getElementById("tableListar").rows[i].accessKey){
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