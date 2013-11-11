<?
	include("../../files/conecta_conadmin.php");
?>
<ul>
	<?
		$Versao = null;
		$i=0;

		$sql = "SELECT
					IdVersaoOld
				FROM
					Atualizacao
				WHERE
					IdVersao = $IdVersao";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$sql = "SELECT
					DataVersao
				FROM
					conadmin.Versao
				WHERE
					IdSysbuild >= $lin[IdVersaoOld]
				ORDER BY
					IdSysbuild
				LIMIT 0,1";
		$res = mysql_query($sql,$conConAdmin);
		if($lin = @mysql_fetch_array($res)){

			$DataChangeLogInicio = $lin[DataVersao];

			$sql = "SELECT
						DataVersao DataChangeLogFim
					FROM
						conadmin.Versao
					WHERE
						IdSysbuild <= $IdVersao 
					ORDER BY
						IdSysbuild DESC
					LIMIT 0,1";
			$res = mysql_query($sql,$conConAdmin);
			$lin = mysql_fetch_array($res);

			$DataChangeLogFim = $lin[DataChangeLogFim];
			
			include("../../files/conecta_cntsistemas.php");

			$sql = "SELECT
						HelpDesk.IdTicket,
						HelpDesk.ChangeLog,
						HelpDeskHistoricoTemp.DataCriacao
					FROM
						HelpDesk,
						(SELECT
							IdTicket,
							MIN(DataCriacao) DataCriacao
						FROM
							HelpDeskHistorico
						WHERE
							IdStatusTicket = 400 OR 
							IdStatusTicket = 600
						GROUP BY
							IdTicket) HelpDeskHistoricoTemp
					WHERE
						trim(HelpDesk.ChangeLog) != '' AND
						HelpDesk.ChangeLog != 'false' AND
						HelpDesk.IdTicket = HelpDeskHistoricoTemp.IdTicket AND
						HelpDeskHistoricoTemp.DataCriacao >= '$DataChangeLogInicio' AND
						HelpDeskHistoricoTemp.DataCriacao <= '$DataChangeLogFim'
					ORDER BY
						HelpDeskHistoricoTemp.DataCriacao DESC";
			$res = mysql_query($sql,$conCNT);
			if(mysql_num_rows($res) == 0){
				echo "<li>Não houve atualizações relevantes.</li>";
			}else{
				while($lin = mysql_fetch_array($res)){
					$lin[DataCriacaoTemp] = dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
					echo "<li>$lin[ChangeLog] ($lin[DataCriacaoTemp])</li>";
				}
			}
		}else{
			echo "<li>Não foi possível conectar no servidor da CNTSistemas.</li>";
		}
	?>
</ul>