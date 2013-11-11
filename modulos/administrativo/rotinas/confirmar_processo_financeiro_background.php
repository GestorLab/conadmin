<?
	$Vars 		= $_SERVER['argv'];
	$Path		=  substr($Vars[0],0,strlen($Vars[0])-strlen($EndFile));

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	 
	$local_Login				= $Vars[1];
	$local_IdLoja				= $Vars[2];
	$local_IdProcessoFinanceiro = $Vars[3];

	$bloqueio = 'disabled';

	$LogProcessamento = date("d/m/Y H:i:s")." [$local_Login] - Iniciado a confirmação do processo financeiro via background.";
	
	$sqlProcessoFinanceiro = "UPDATE ProcessoFinanceiro SET 
								IdStatus = 4,
								LogProcessamento = concat('$LogProcessamento','\n',LogProcessamento),
								DataConfirmacaoInicio = '".date("Y-m-d H:i:s")."'
							WHERE 
								IdLoja=$local_IdLoja AND 
								IdProcessoFinanceiro=$local_IdProcessoFinanceiro";
	mysql_query($sqlProcessoFinanceiro,$con);
	
	include ('confirmar_processo_financeiro.php');
?>