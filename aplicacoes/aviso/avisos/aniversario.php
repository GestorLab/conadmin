<?
	$sql = "select
				DataNascimento
			from
				Pessoa
			where
				IdPessoa = $IdPessoa and
				substring(DataNascimento,6,5) = substring(curdate(),6,5)";
	$res = @mysql_query($sql,$con);
	if($lin = @mysql_fetch_array($res)){
	
	echo $nAviso;

		$Aviso[geral]		= true;
		$Aviso[aniversario] = true;
		$Aviso1				= 1;
	
		$Mensagem = str_replace("
	","<br>",getParametroSistema(99,45));
	
#		$Msg[aniversario]	= "<p style='text-align:center; font-weight: bold;'>Feliz Aniversário</p>\n";
		$Msg[aniversario]	= "<table>
									<tr>
										<td style='text-align:center; font-weight: bold; height:30px'>Feliz Aniversário!</td>
										<td style='width:130px; text-aling: center;' rowspan=2><img src='../../img/estrutura_sistema/baloes.gif' alt='Feliz Aniversário' /></td>
									</tr>
									<tr>
										<td style='text-align:center; padding: 0 20px 0 20px;'>$Mensagem</td>
									</tr>
								</table>
		";
	}
?>
