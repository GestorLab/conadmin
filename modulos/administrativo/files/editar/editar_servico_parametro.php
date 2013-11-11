<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		switch($local_IdTipoParametro){
			case '1':
				$local_Editavel					=	$local_Editavel_Texto;		
				$local_Obrigatorio				=	$local_ObrigatorioStatus_Texto;
				$local_Calculavel				=	$local_Calculavel_Texto;				
				$local_Visivel					=	$local_Visivel_Texto;						
				$local_VisivelOS				=	$local_VisivelOS_Texto;					
				$local_VisivelCDA				=	$local_VisivelCDA_Texto;					
				$local_AcessoCDA				=	$local_AcessoCDA_Texto;
				$local_Unico					=	$local_Unico_Texto;					
				$local_BotaoAuxiliar			=	$local_BotaoAuxiliar_Texto;
				$local_ParametroDemonstrativo	=	$local_ParametroDemonstrativo_Texto;
				$local_ValorDefault				= 	$local_ValorDefaultInput;
				$local_CalculavelOpcoes			=	"2";
				
				switch($local_IdTipoTexto){
					case 2:
						$local_ExibirSenha		=	$local_IdMascaraCampo;
						$local_IdMascaraCampo	=	"";
						break;
					case 3:
						$local_Editavel = 2;
				}
				
				break;
			case '2':
				$local_Editavel					=	$local_Editavel_Selecao;		
				$local_Obrigatorio				=	$local_ObrigatorioStatus_Selecao;
				$local_Calculavel				=	$local_Calculavel_Selecao;					
				$local_Visivel					=	$local_Visivel_Selecao;						
				$local_VisivelOS				=	$local_VisivelOS_Selecao;				
				$local_VisivelCDA				=	$local_VisivelCDA_Selecao;	
				$local_AcessoCDA				=	$local_AcessoCDA_Selecao;				
				$local_Unico					=	$local_Unico_Selecao;					
				$local_BotaoAuxiliar			=	"";
				$local_ParametroDemonstrativo	=	$local_ParametroDemonstrativo_Selecao;
				$local_ValorDefault				= 	$local_ValorDefaultSelect;
				$local_IdMascaraCampo			=	"";
				$local_ExibirSenha				=	"";
				$local_TamMinimo 				=	"";
				$local_TamMaximo				=	"";
				$local_IdTipoTexto				=	"";
				break;
		}
		
		if($local_Calculavel != 1){
			$local_RotinaCalculo = '';
		}
		if($local_CalculavelOpcoes != 1){
			$local_RotinaOpcoes 		= '';
			$local_RotinaOpcoesContrato = '';
		}else{
			$local_OpcaoValor			= '';
		}
		
		if($local_VisivelCDA != 1){
			$local_DescricaoParametroServicoCDA	=	"";
		}
		
		if($local_BotaoAuxiliar == ""){			$local_BotaoAuxiliar	=	"NULL";		}
		if($local_IdMascaraCampo == ""){		$local_IdMascaraCampo	=	"NULL";		}
		if($local_CalculavelOpcoes == ""){		$local_CalculavelOpcoes	=	"NULL";		}
		if($local_IdTipoTexto == ""){			$local_IdTipoTexto		=	"NULL";		}
		if($local_TamMaximo == ""){				$local_TamMaximo		=	"NULL";		}
		if($local_TamMinimo == ""){				$local_TamMinimo		=	"NULL";		}
		if($local_ExibirSenha == ""){			$local_ExibirSenha		=	"NULL";		}
		if($local_IdTipoAcesso == ""){			$local_IdTipoAcesso		=	"NULL";		}
		
		if($local_IdGruposUsuario == "") {
			$local_IdGruposUsuario = "NULL";
		} else {
			$local_IdGruposUsuario = "'$local_IdGruposUsuario'";
		}
		
		if($local_ParametroDemonstrativo == '1'){
			$sql	=	"UPDATE ServicoParametro SET ParametroDemonstrativo = 2 where IdLoja = $local_IdLoja and IdServico = $local_IdServico and IdParametroServico != $local_IdParametroServico";
			mysql_query($sql,$con);
		}
		
		$local_ValorDefault	=	trim($local_ValorDefault);
		
		if($local_IdTipoTexto == 3){
			$local_IdMascaraCampo = "NULL";
		}
		
		$sql = "UPDATE ServicoParametro SET 
					IdTipoParametro				= $local_IdTipoParametro,
					IdTipoAcesso				= $local_IdTipoAcesso,
					SalvarHistorico				= $local_SalvarHistorico,
					IdTipoTexto					= $local_IdTipoTexto,
					IdMascaraCampo				= $local_IdMascaraCampo,
					ExibirSenha					= $local_ExibirSenha,
					Editavel					= '$local_Editavel',
					DescricaoParametroServico	= '$local_DescricaoParametroServico', 
					Obrigatorio					= '$local_Obrigatorio',
					ValorDefault				= '$local_ValorDefault',
					IdGrupoUsuario				= $local_IdGruposUsuario,
					BotaoAuxiliar				= $local_BotaoAuxiliar,
					Calculavel					= '$local_Calculavel',
					CalculavelOpcoes			= $local_CalculavelOpcoes,
					RotinaCalculo				= '$local_RotinaCalculo',
					RotinaOpcoes				= '$local_RotinaOpcoes',
					RotinaOpcoesContrato		= '$local_RotinaOpcoesContrato',
					OpcaoValor					= '$local_OpcaoValor',
					ParametroDemonstrativo		= '$local_ParametroDemonstrativo',
					Visivel						= '$local_Visivel',
					VisivelOS					= '$local_VisivelOS',
					VisivelCDA					= '$local_VisivelCDA',
					AcessoCDA					= '$local_AcessoCDA',
					Unico						= '$local_Unico',
					Obs							= '$local_Obs',
					TamMaximo					= $local_TamMaximo,
					TamMinimo					= $local_TamMinimo,
					DescricaoParametroServicoCDA= '$local_DescricaoParametroServicoCDA',
					IdStatus					= $local_IdStatusParametro,
					DataAlteracao				= (concat(curdate(),' ',curtime())),
					LoginAlteracao				= '$local_Login'
				WHERE 
					IdLoja						= $local_IdLoja and
					IdServico					= $local_IdServico and
					IdParametroServico			= $local_IdParametroServico;";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;		// Mensagem de Alteraчуo Positiva
		}else{
			echo mysql_error();
			$local_Erro = 5;		// Mensagem de Alteraчуo Negativa
		}
	}
?>