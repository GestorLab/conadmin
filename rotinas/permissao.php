<?
	include('../files/conecta.php');
	include('../files/funcoes.php');

	$IdGrupoPermissao	= 1;
	$Login				= 'automatico';

	$sqlPermissoes = "select IdModulo, IdOperacao, IdSubOperacao from SubOperacao";
	$resPermissoes = mysql_query($sqlPermissoes,$con);
	while($linPermissoes = mysql_fetch_array($resPermissoes)){

		$sqlLoja = "select IdLoja from Loja";
		$resLoja = mysql_query($sqlLoja,$con);
		while($linLoja = mysql_fetch_array($resLoja)){

			$sqlVerifica = "select * from GrupoPermissaoSubOperacao where IdGrupoPermissao = $IdGrupoPermissao and IdLoja = $linLoja[IdLoja] and IdModulo = $linPermissoes[IdModulo] and IdOperacao = $linPermissoes[IdOperacao] and IdSubOperacao = '$linPermissoes[IdSubOperacao]'";
			$resVerifica = mysql_query($sqlVerifica,$con);

			if(mysql_num_rows($resVerifica) == 0){
				$sql = "insert into GrupoPermissaoSubOperacao(IdGrupoPermissao,IdLoja,IdModulo,IdOperacao,IdSubOperacao,LoginCriacao,DataCriacao) values ('$IdGrupoPermissao','$linLoja[IdLoja]','$linPermissoes[IdModulo]','$linPermissoes[IdOperacao]','$linPermissoes[IdSubOperacao]','$Login',concat(curdate(),' ',curtime())";
				mysql_query($sql,$con);
			}
		}
	}
?>
