	function validar(){
		if(document.formulario.Filtro_IdServico.value == '' && document.formulario.Filtro_IdPessoa.value=='' && document.formulario.Filtro_IdContaReceber.value=='' && document.formulario.IdProcessoFinanceiro.value=='' && document.formulario.IdLocalCobranca.value==''){
			mensagens(66);
			return false;
		}	
		if(document.formulario.FormatoSaida.value == ''){
			document.formulario.FormatoSaida.focus();
			mensagens(1);
			return false;
		}
		return true;
	}
	function inicia(){
		document.formulario.IdPessoaFiltro.focus();
		status_inicial();
	}
	
	function addServico(){
		if(document.formulario.IdServico.value != ''){
			busca_servico(document.formulario.IdServico.value,false,'AdicionarServicoNaEtiqueta','busca');
		}else{
			document.formulario.IdServico.value					=	"";
			document.formulario.DescricaoServico.value			=	"";
			document.formulario.IdTipoServico.value				=	"";
			document.formulario.IdServico.focus();
		}
	}

