<?
	$Msg[geral]	= '';

	$sql = "select
				Nome,
				NomeRepresentante,
				TipoPessoa
			from
				Pessoa
			where
				IdPessoa = $IdPessoa";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);

	if($lin[TipoPessoa] == 1){
		$NomePessoa = $lin[NomeRepresentante]."<br>".$lin[Nome];
	}else{
		$NomePessoa = $lin[Nome];
	}

	$UrlImagem	= "layout/$LayoutAvisos/img/logo.php";

	if($Aviso0 == 1){
		$Msg[geral]	= "<table border='0' cellpadding='0' cellspacing='0' class='quadroAviso' align='center'>
						<tr>
							<td class='quadroAviso_center' align='center' valign='middle'><img src='$UrlImagem'/></td>
							<td class='quadroAviso_center' align='center' valign='middle'><h2>Prezado(a)<br><B>$NomePessoa</B></h2></td>
						</tr>
					</table>";
	}else{
		$Msg[geral]	= "<img src='$UrlImagem'/>";
	}
?>