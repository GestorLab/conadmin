	function habilitar_camp(camp) {
		if(camp.name == "TodasRotinas") {
			document.formulario.RealizarBackup.checked			= camp.checked;
			document.formulario.ProcessarRetorno.checked		= camp.checked;
			document.formulario.StatusContrato.checked			= camp.checked;
			document.formulario.EnviarEmail.checked				= camp.checked;
			document.formulario.TratamentoLogFreeRadius.checked	= camp.checked;
			document.formulario.ApagarArquivoTemporario.checked	= camp.checked;
			document.formulario.RotinaPersonalizada.checked		= camp.checked;
			document.formulario.NotaFiscalEmitida.checked		= camp.checked;
		}
		
		document.formulario.bt_executar.disabled = (!document.formulario.RealizarBackup.checked && !document.formulario.ProcessarRetorno.checked && !document.formulario.StatusContrato.checked && !document.formulario.EnviarEmail.checked && !document.formulario.TratamentoLogFreeRadius.checked && !document.formulario.ApagarArquivoTemporario.checked && !document.formulario.RotinaPersonalizada.checked && !document.formulario.NotaFiscalEmitida.checked);
	}