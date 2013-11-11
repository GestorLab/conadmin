<?
	function formata_CPF_CNPJ($CPF_CNPJ){
		$CPF_CNPJ = str_replace(".", "", $CPF_CNPJ);
		$CPF_CNPJ = str_replace("/", "", $CPF_CNPJ);
		$CPF_CNPJ = str_replace("-", "", $CPF_CNPJ);
		$CPF_CNPJ = str_replace(" ", "", $CPF_CNPJ);

		return preenche_tam($CPF_CNPJ, 14, 'N');
	}

	function formata_IE($RG_IE){
			
		$RG_IE = str_replace(".", "", $RG_IE);
		$RG_IE = str_replace("-", "", $RG_IE);
		$RG_IE = str_replace(" ", "", $RG_IE);

		if($RG_IE == ''){
			$RG_IE = 'ISENTO';
		}

		return preenche_tam($RG_IE, 14, 'X');
	}

	function formata_valor($Valor){
		$Valor = str_replace("-", "", $Valor);
		$Valor = str_replace(".", "", $Valor);
		$Valor = str_replace(",", "", $Valor);
		$Valor = str_replace("(", "", $Valor);
		$Valor = str_replace(")", "", $Valor);
		$Valor = str_replace(" ", "", $Valor);
		return $Valor;
	}

	function formata_status($IdStatus){
		switch($IdStatus){
			case 0:
				// Cancelado
				return 'S';
				break;
			case 1:
				// Ativo
				return 'N';
				break;
			case 2:
				// Substituiηγo
				return 'R';
				break;
		}
		return $IdStatus;
	}

	function formata_cfop($CFOP){
		$CFOP = str_replace(".", "", $CFOP);
		$CFOP = substr($CFOP,0,4);

		return preenche_tam($CFOP, 4, 'N');
	}

	function formata_unidade($Unidade){
		if($Unidade != ''){
			$Unidade = getParametroSistema(66,$Unidade);
		}
		return preenche_tam($Unidade, 6, 'X');
	}

	function numero_endereco($Numero){

		$Numero	= trim($Numero);
		$Numero	= $Numero+0;

		if($Numero == 0){
			return 1;
		}
		return $Numero;
	}
?>