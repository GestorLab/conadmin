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
	function cadastrar(Acao){
		if(validar()){
			document.formulario.Acao.value = Acao;
			document.formulario.submit();
		}
	}
	function validar(){
		if(document.formulario.DescricaoContaEmail.value == ''){
			mensagens(1);
			document.formulario.DescricaoContaEmail.focus();
			return false;
		}
		
		if(document.formulario.NomeRemetente.value == ''){
			mensagens(1);
			document.formulario.NomeRemetente.focus();
			return false;
		}
		
		if(document.formulario.EmailRemetente.value == ''){
			mensagens(1);
			document.formulario.EmailRemetente.focus();
			return false;
		} else if(!validar_email(document.formulario.EmailRemetente,'titEmailRemetente')){
			return false;
		}
		
		if(document.formulario.NomeResposta.value == ''){
			mensagens(1);
			document.formulario.NomeResposta.focus();
			return false;
		}
		
		if(document.formulario.EmailResposta.value == ''){
			mensagens(1);
			document.formulario.EmailResposta.focus();
			return false;
		} else if(!validar_email(document.formulario.EmailResposta,'titEmailResposta')){
			return false;
		}
		
		if(document.formulario.Usuario.value == ''){
			mensagens(1);
			document.formulario.Usuario.focus();
			return false;
		}
		
		if(document.formulario.Senha.value == ''){
			mensagens(1);
			document.formulario.Senha.focus();
			return false;
		}
		
		if(document.formulario.ServidorSMTP.value == ''){
			mensagens(1);
			document.formulario.ServidorSMTP.focus();
			return false;
		}
		
		if(document.formulario.Porta.value == ''){
			mensagens(1);
			document.formulario.Porta.focus();
			return false;
		}
		
		if(document.formulario.RequerAutenticacao.value == ''){
			mensagens(1);
			document.formulario.RequerAutenticacao.focus();
			return false;
		}
		
		if(document.formulario.IntervaloEnvio.value == ''){
			mensagens(1);
			document.formulario.IntervaloEnvio.focus();
			return false;
		}
		
		if(document.formulario.QtdTentativaEnvio.value == ''){
			mensagens(1);
			document.formulario.QtdTentativaEnvio.focus();
			return false;
		}
		
		return true;
	}
	function excluir(IdContaEmail){
		if(IdContaEmail == '' || IdContaEmail == undefined){
			IdContaEmail = document.formulario.IdContaEmail.value;
		}
		
		if(excluir_registro()){
			if(document.formulario != undefined){
				if(document.formulario.Acao.value == "inserir"){
					return false;
				}
			}
			
			var url = "./files/excluir/excluir_conta_email.php?IdContaEmail="+IdContaEmail;
			
			call_ajax(url, function (xmlhttp){
				if(document.formulario != undefined){
					document.formulario.Erro.value = xmlhttp.responseText;
					
					if(parseInt(xmlhttp.responseText) == 7){
						document.formulario.Acao.value = "inserir";
						url = "cadastro_conta_email.php?Erro=" + document.formulario.Erro.value;
						window.location.replace(url);
					} else{
						verificaErro();
					}
				} else{
					var numMsg = parseInt(xmlhttp.responseText);
					mensagens(numMsg);
					
					if(numMsg == 7){
						for(var i = 0; i < document.getElementById("tableListar").rows.length; i++){
							if(IdContaEmail == document.getElementById("tableListar").rows[i].accessKey){
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
	function validar_email(campo,id){
		if(campo.value != '' && !isEmail(campo.value)){
			document.getElementById(id).style.backgroundColor = '#c10000';
			document.getElementById(id).style.color='#fff';
			
			mensagens(12);
			campo.focus();
			return false;
		} else{
			document.getElementById(id).style.backgroundColor='#fff';
			document.getElementById(id).style.color='#c10000';
			
			mensagens(0);
			return true;
		}
	}
	function testar_email(){
		var url = "";
		
		call_ajax(url,function (xmlhttp){
		});
	}
	function buscar_email_fila_espera(IdContaEmail){
		if(IdContaEmail == undefined || IdContaEmail == "") IdContaEmail = 0;
		
	}
	