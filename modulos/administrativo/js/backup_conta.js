	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'inserir'){
				document.formulario.bt_testar.disabled	= true;
				document.formulario.bt_inserir.disabled	= false;
				document.formulario.bt_alterar.disabled	= true;
				document.formulario.bt_excluir.disabled = true;
			} else{
				document.formulario.bt_testar.disabled	= false;
				document.formulario.bt_inserir.disabled	= true;
				document.formulario.bt_alterar.disabled	= false;
				document.formulario.bt_excluir.disabled = false;
			}
		}
	}
	function statusInicial(){
		if(document.formulario.ServidorPorta.value == ''){
			document.formulario.ServidorPorta.value = document.formulario.ServidorPortaDefault.value;
		}
	}
	function inicia(){
		statusInicial();
		
		document.formulario.IdBackupConta.focus();
	}
	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			
			switch(Acao){
				case "testar":
					testar_conta(document.formulario.IdBackupConta.value);
					break;
				default:
					document.formulario.submit();
			}
		}
	}
	function validar(){
		if(document.formulario.ServidorEndereco.value == ''){
			mensagens(1);
			document.formulario.ServidorEndereco.focus();
			return false;
		}
		
		if(document.formulario.ServidorPorta.value == ''){
			mensagens(1);
			document.formulario.ServidorPorta.focus();
			return false;
		}
		
		if(document.formulario.ServidorUsuario.value == ''){
			mensagens(1);
			document.formulario.ServidorUsuario.focus();
			return false;
		}
		
		if(document.formulario.ServidorSenha.value == ''){
			mensagens(1);
			document.formulario.ServidorSenha.focus();
			return false;
		}
		
		return true;
	}
	function excluir(IdBackupConta){
		if(IdBackupConta == '' || IdBackupConta == undefined){
			IdBackupConta = document.formulario.IdBackupConta.value;
		}
		
		if(excluir_registro()){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return false;
				}
			}
			
			var url = "./files/excluir/excluir_backup_conta.php?IdBackupConta="+IdBackupConta;
			
			call_ajax(url,function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_backup_conta.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(IdBackupConta == document.getElementById("tableListar").rows[i].accessKey){
								document.getElementById("tableListar").deleteRow(i);
								tableMultColor("tableListar", document.filtro.corRegRand.value);
								document.getElementById("tableListarTotal").innerHTML = "Total: "+(document.getElementById("tableListar").rows.length-2);
								break;
							}
						}							
					}
				}
			});
		}
	}
	function testar_conta(IdBackupConta){
		var url = "rotinas/backup_teste.php?IdBackupConta="+IdBackupConta;
		
		call_ajax(url,function (xmlhttp){
			mensagens(parseInt(xmlhttp.responseText));
		});
	}