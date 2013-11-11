<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Paramêtro Contrato
		$sql	=	"select (max(IdLocalCobrancaParametroContrato)+1) IdLocalCobrancaParametroContrato from LocalCobrancaParametroContrato where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdLocalCobrancaParametroContrato]!=NULL){
			$local_IdLocalCobrancaParametroContrato	=	$lin[IdLocalCobrancaParametroContrato];
		}else{
			$local_IdLocalCobrancaParametroContrato	=	1;
		}
		
		if($local_Calculavel != 1){
			$local_RotinaCalculo = '';
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
			$sql	=	"UPDATE LocalCobrancaParametroContrato SET ParametroDemonstrativo = 2 where IdLoja = $local_IdLoja and IdLocalCobranca = $local_IdLocalCobranca";
			mysql_query($sql,$con);
		}
		
		
		$sql	=	"
				INSERT INTO LocalCobrancaParametroContrato SET 
					IdLoja								= $local_IdLoja,
					IdLocalCobranca						= $local_IdLocalCobranca,
					IdLocalCobrancaParametroContrato	= $local_IdLocalCobrancaParametroContrato,
					IdTipoParametro						= $local_IdTipoParametro,
					IdMascaraCampo						= $local_IdMascaraCampo,
					Editavel							= '$local_Editavel',
					DescricaoParametroContrato			= '$local_DescricaoParametroContrato', 
					Obrigatorio							= '$local_ObrigatorioStatus',
					ValorDefault						= '$local_ValorDefault',
					Calculavel							= '$local_Calculavel',
					RotinaCalculo						= '$local_RotinaCalculo',
					OpcaoValor							= '$local_OpcaoValor',
					ParametroDemonstrativo				= '$local_ParametroDemonstrativo',
					Visivel								= '$local_Visivel',
					VisivelOS							= '$local_VisivelOS',
					Obs									= '$local_Obs',
					IdStatus							= $local_IdStatusParametro,
					DataCriacao							= (concat(curdate(),' ',curtime())),
					LoginCriacao						= '$local_Login'";
		if(mysql_query($sql,$con) == true){
			$local_Acao	= 'alterar';
			$local_Erro = 3;		// Mensagem de Alteração Positiva
		}else{	
			$local_Acao = 'inserir';
			$local_Erro = 8;		// Mensagem de Alteração Negativa
		}
	}
?>
