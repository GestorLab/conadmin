	function verificaErro(){
		var nerro = parseInt(document.formulario.Erro.value);
		
		mensagens(nerro,document.formulario.Local.value);
	}
	function cadastrar(acao){
		document.formulario.Acao.value = acao;
		
		switch(acao){
			case "voltar":
				document.formulario.action = "cadastro_sici.php?PeriodoApuracao="+document.formulario.PeriodoApuracao.value;
				document.formulario.submit();
				break;
			case "exportar":
				if(validar()){
					if(Number(document.formulario.IdMetodoExportacao.value) == 1){
						document.formulario.action = "rotinas/exportar_sici_"+document.formulario.FormatoArquivo.value+".php?PeriodoApuracao="+document.formulario.PeriodoApuracao.value;
					} else{
						document.formulario.action = "cadastro_sici_exporta.php";
					}
					
					document.formulario.submit();
				}
		}
	}
	function validar(){
		if(document.formulario.FormatoArquivo.value == ""){
			document.formulario.FormatoArquivo.focus();
			mensagens(1);
			return false;
		}
		
		if(document.formulario.IdMetodoExportacao.value == ""){
			document.formulario.IdMetodoExportacao.focus();
			mensagens(1);
			return false;
		}
		
		if(Number(document.formulario.IdMetodoExportacao.value) == 2 && document.formulario.Email.value == ""){
			document.formulario.Email.focus();
			mensagens(1);
			return false;
		}
		
		return true;
	}
	function inicia(){
		document.formulario.FormatoArquivo.focus();
	}
	function verificar_metodo_exportacao(Valor){
		if(Number(Valor) == 1){
			document.getElementById("tit_MetodoExportacao").style.display = "none";
			document.formulario.Email.style.display = "none";
		} else{
			document.getElementById("tit_MetodoExportacao").style.display = "block";
			document.formulario.Email.style.display = "block";
		}
	}