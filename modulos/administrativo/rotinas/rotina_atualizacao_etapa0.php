<?
	include("../../../rotinas/verifica.php");

	$Login = $_SESSION["Login"];

	// Inicia Log de Atualização
	$sql = "select
				max(IdAtualizacao) IdAtualizacao
			from
				Atualizacao";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	$IdAtualizacao = $lin[IdAtualizacao]+1;

	$VersaoOld = versao();
	
	$sql = "insert into Atualizacao set 
				IdAtualizacao = $IdAtualizacao, 
				IdVersao = $IdVersao,  
				DescricaoVersao = '$DescricaoVersao',
				IdVersaoOld = $VersaoOld[IdVersao], 
				Login='$Login', 
				DataInicio = concat(curdate(),' ',curtime())";
	mysql_query($sql,$con);

	include("rotina_sistema_atualizacao.php");

	header("Location: ../../../rotinas/sair.php");
?>