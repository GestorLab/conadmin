<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		if($local_Calculavel != 1){
			$local_RotinaCalculo = '';
		}
		
		if($local_ObrigatorioStatus == ''){
			$local_ObrigatorioStatus = $local_Obrigatorio;
		}
		
		if($local_IdMascaraCampo == ""){
			$local_IdMascaraCampo	=	"NULL";
		}
		
		switch($local_IdTipoParametro){
			case '1':
				$local_ValorDefault	= $local_ValorDefaultInput;
				break;
			case '2':
				$local_ValorDefault	= $local_ValorDefaultSelect;
				break;
		}
		
		if($local_ParametroDemonstrativo == '1'){
			$sql	=	"UPDATE LocalCobrancaParametroContrato SET ParametroDemonstrativo = 2 where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca and IdLocalCobrancaParametroContrato != $local_IdLocalCobrancaParametroContrato";
			mysql_query($sql,$con);
		}
		
		
		$sql	=	"
				UPDATE LocalCobrancaParametroContrato SET 
					DescricaoParametroContrato	= '$local_DescricaoParametroContrato', 
					Obrigatorio					= '$local_ObrigatorioStatus',
					Editavel					= '$local_Editavel',
					ValorDefault				= '$local_ValorDefault',
					IdTipoParametro				= $local_IdTipoParametro,
					IdMascaraCampo				= $local_IdMascaraCampo,
					Obs							= '$local_Obs',
					Calculavel					= '$local_Calculavel',
					RotinaCalculo				= '$local_RotinaCalculo',
					ParametroDemonstrativo		= '$local_ParametroDemonstrativo',
					Visivel						= '$local_Visivel',
					VisivelOS					= '$local_VisivelOS',
					OpcaoValor					= '$local_OpcaoValor',
					IdStatus					= $local_IdStatusParametro,
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja								= $local_IdLoja and
					IdLocalCobranca						= $local_IdLocalCobranca and
					IdLocalCobrancaParametroContrato	= $local_IdLocalCobrancaParametroContrato;";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;		// Mensagem de Alteração Positiva
		}else{
			$local_Erro = 5;		// Mensagem de Alteração Negativa
		}
	}
?>
