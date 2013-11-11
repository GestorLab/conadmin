	function busca_backup_conta(IdBackupConta, Erro, Local){
		if(IdBackupConta == '' || IdBackupConta == undefined){
			IdBackupConta = 0;
		}
		
		if(Local == '' || Local == undefined){
			Local = document.formulario.Local.value;
		}
		
		var url = "./xml/backup_conta.php?IdBackupConta="+IdBackupConta;
		
		call_ajax(url, function (xmlhttp){
			if(Erro != false){
				document.formulario.Erro.value = 0;
				verificaErro();
			}
			
			if(xmlhttp.responseText == "false"){
				switch(Local){
					case "BackupConta":
						document.formulario.IdBackupConta.value		= '';
						document.formulario.ServidorEndereco.value	= '';
						document.formulario.ServidorPorta.value		= '';
						document.formulario.BackupCaminho.value		= '';
						document.formulario.ServidorUsuario.value	= '';
						document.formulario.ServidorSenha.value		= '';
						document.formulario.LoginCriacao.value		= '';
						document.formulario.DataCriacao.value		= '';
						document.formulario.LoginAlteracao.value	= '';
						document.formulario.DataAlteracao.value		= '';
						document.formulario.Acao.value				= "inserir";
						
						verificaAcao();
						inicia();
						break;
				}
			} else{
				var nameNode = xmlhttp.responseXML.getElementsByTagName("ServidorEndereco")[0]; 
				var nameTextNode = nameNode.childNodes[0];
				var ServidorEndereco = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ServidorPorta")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ServidorPorta = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("BackupCaminho")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var BackupCaminho = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ServidorUsuario")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ServidorUsuario = nameTextNode.nodeValue;
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("ServidorSenha")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var ServidorSenha = nameTextNode.nodeValue;
				
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
				
				nameNode = xmlhttp.responseXML.getElementsByTagName("HistoricoObs")[0]; 
				nameTextNode = nameNode.childNodes[0];
				var HistoricoObs = nameTextNode.nodeValue;
				
				switch(Local){
					case "BackupConta":
						document.formulario.IdBackupConta.value		= IdBackupConta;
						document.formulario.ServidorEndereco.value	= ServidorEndereco;
						document.formulario.ServidorPorta.value		= ServidorPorta;
						document.formulario.BackupCaminho.value		= BackupCaminho;
						document.formulario.HistoricoObs.value		= HistoricoObs;
						document.formulario.ServidorUsuario.value	= ServidorUsuario;
						document.formulario.ServidorSenha.value		= ServidorSenha;
						document.formulario.LoginCriacao.value		= LoginCriacao;
						document.formulario.DataCriacao.value		= dateFormat(DataCriacao);
						document.formulario.LoginAlteracao.value	= LoginAlteracao;
						document.formulario.DataAlteracao.value		= dateFormat(DataAlteracao);
						document.formulario.Acao.value				= "alterar";
						
						verificaAcao();
						inicia();
						break;
				}
			}
		});
	}
	function permissao_visualizar_senha(){
	
		var url = "./xml/backup_conta_verifica_permissao_editar.php";
		
		call_ajax(url, function (xmlhttp){
			var nameNode = xmlhttp.responseXML.getElementsByTagName("Type")[0]; 
			var nameTextNode = nameNode.childNodes[0];
			var Type = nameTextNode.nodeValue;
			
			document.formulario.ServidorSenha.type	= Type;
		});
	}