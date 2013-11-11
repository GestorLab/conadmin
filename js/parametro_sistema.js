	function verificaAcao(){
		if(document.formulario != undefined){
			if(document.formulario.Acao.value == 'alterar'){
				document.formulario.bt_inserir.disabled 	= true;
				document.formulario.bt_alterar.disabled 	= false;
				document.formulario.bt_excluir.disabled 	= true;
			}
		}	
	}
	function validar(){
		if(document.formulario.IdGrupoParametroSistema.value==''){
			mensagens(1);
			document.formulario.IdGrupoParametroSistema.focus();
			return false;
		}
		if(document.formulario.IdParametroSistema.value==''){
			mensagens(1);
			document.formulario.IdParametroSistema.focus();
			return false;
		}
		if(document.formulario.DescricaoParametroSistema.value==''){
			mensagens(1);
			document.formulario.DescricaoParametroSistema.focus();
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdGrupoParametroSistema.focus();
	}
	function limpa_form_parametro_sistema(){
		document.formulario.IdParametroSistema.value			= '';
		document.formulario.DescricaoParametroSistema.value 	= '';
		document.formulario.ValorParametroSistema.value 		= '';
		document.formulario.DataCriacao.value 	  			= '';
		document.formulario.LoginCriacao.value 				= '';
		document.formulario.DataAlteracao.value 			= '';
		document.formulario.LoginAlteracao.value			= '';
	}
