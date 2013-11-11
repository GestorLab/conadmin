<table width="920" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20"><img src="img/top1.png" width="20" height="20" /></td>
		<?
			$sql	=	"select count(*) QtdAcesso,max(DataHora) DataHora from LogAcessoCDA where IdPessoa = $local_IdPessoa";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			
			$QtdAcesso = $lin[QtdAcesso];
			
			$sql	=	"select DataHora from LogAcessoCDA where IdPessoa = $local_IdPessoa order by DataHora DESC limit 1,1";
			$res	=	@mysql_query($sql,$con);
			$lin	=	@mysql_fetch_array($res);
			
			$DataHora	=	dataConv($lin[DataHora],'Y-m-d H:i:s','d.m.Y H:i');

			if($DataHora == ''){
				$DataHora = '1º acesso.';
			}

			$sql = "select Nome from Pessoa where IdPessoa = $local_IdPessoa";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			
			$NomeCliente = $lin[Nome];
			  
			echo"<td class='transparente'><img src='img/bullet_seta.png' width='10' height='10' />&nbsp;<B>Cliente: </B>$NomeCliente&nbsp;<img src='img/bullet_seta.png' width='10' height='10' />&nbsp;<B>Quantidade de Acesso:</B> $QtdAcesso&nbsp;&nbsp;<img src='img/bullet_seta.png' width='10' height='10' />&nbsp;<B>Ultimo Acesso:</B> $DataHora</td>";
		?>
		<td width="20"><a href="rotinas/sair.php"><img src="img/top2_bt_sair.png" width="74" height="20" border="0" /></a></td>
	</tr>
</table>