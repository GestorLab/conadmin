<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$local_DataAgendamento	=	$local_Data." ".$local_Hora.":00";
		$local_DataAgendamento	=	dataConv($local_DataAgendamento,'d/m/Y H:i:s','Y-m-d H:i:s');
		
		// Sql de Inser��o de Codigo Interno
		$sql	=	"
				INSERT INTO AgendamentoOrdemServico SET
						IdLoja				='$local_IdLoja', 
						LoginResponsavel	='$local_LoginResponsavel',
						IdOrdemServico		='$local_IdOrdemServico',
						DataHoraAgendamento	='$local_DataAgendamento',
						LoginCriacao		='$local_Login', 
						DataCriacao			=(concat(curdate(),' ',curtime()));";
			// Executa a Sql de Inser��o de Usuario
		if(mysql_query($sql,$con) == true){				
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inser��o Positiva
		}else{
			// Muda a a��o para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 30;			// Mensagem de Inser��o Negativa
		}
	}
?>
