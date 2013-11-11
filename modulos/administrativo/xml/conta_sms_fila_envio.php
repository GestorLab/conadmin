<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_EmailFilaEspera(){

		global $con;
		global $_GET;

		$local_IdLoja		= $_SESSION['IdLoja'];
		$local_IdContaSMS	= $_GET['IdContaSMS'];
		$Color 				= "";

		$sql ="SELECT 
					HistoricoMensagem.IdHistoricoMensagem,
					HistoricoMensagem.Celular,
					HistoricoMensagem.DataEnvio,
					HistoricoMensagem.DataCriacao,
					HistoricoMensagem.IdStatus,
					SUBSTRING(HistoricoMensagem.Titulo, 1, 26) Titulo,
					Pessoa.Nome,
					Pessoa.TipoPessoa
				FROM
					HistoricoMensagem,
					TipoMensagem,
					Pessoa
				WHERE 
					HistoricoMensagem.IdLoja = $local_IdLoja 
					AND TipoMensagem.IdLoja = HistoricoMensagem.IdLoja 
					AND TipoMensagem.IdTipoMensagem = HistoricoMensagem.IdTipoMensagem
					AND Pessoa.IdPessoa = HistoricoMensagem.IdPessoa 
					AND HistoricoMensagem.Celular != 'NULL'
					AND TipoMensagem.IdContaSMS = $local_IdContaSMS
					AND HistoricoMensagem.IdStatus <> 2
					AND HistoricoMensagem.IdStatus <> 3
					AND HistoricoMensagem.IdStatus <> 4
					AND HistoricoMensagem.IdStatus <> 6 order by HistoricoMensagem.IdHistoricoMensagem";		
		$res	= @mysql_query($sql,$con);
		$Total	= mysql_num_rows($res);
		
		if($Total > 0){
			header ("content-type: text/xml");	
			echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			echo "<db>";		
			echo "<reg>";
	
			while($lin	=	@mysql_fetch_array($res)){
				$lin[DataEnvioTemp] 	= dataConv($lin[DataEnvio],"Y-m-d H:i:s","d/m/Y H:i:s");
				$lin[DataEnvio] 		= dataConv($lin[DataEnvio],"Y-m-d H:i:s","YmdHis");
				$lin[DataCriacaoTemp] 	= dataConv($lin[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
				$lin[DataCriacao] 		= dataConv($lin[DataCriacao],"Y-m-d H:i:s","YmdHis");

				$sql2	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 193 and IdParametroSistema = $lin[IdStatus]";
				$res2	=	@mysql_query($sql2,$con);
				$lin2	=	@mysql_fetch_array($res2);

				$Status = $lin2[ValorParametroSistema];

				if($lin[TipoPessoa]=='1'){
					$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];
				}
				if($lin[Email] == "NULL"){
					$lin[Email] = "";
				}

				echo 	"<IdLoja><![CDATA[$lin[IdLoja]]]></IdLoja>";
				echo 	"<IdHistoricoMensagem><![CDATA[$lin[IdHistoricoMensagem]]]></IdHistoricoMensagem>";
				echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
				echo 	"<Titulo><![CDATA[$lin[Titulo]]]></Titulo>";
				echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				echo 	"<Status><![CDATA[$Status]]></Status>";
				echo 	"<DataEnvioTemp><![CDATA[$lin[DataEnvioTemp]]]></DataEnvioTemp>";
				echo 	"<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
				echo 	"<DataCriacaoTemp><![CDATA[$lin[DataCriacaoTemp]]]></DataCriacaoTemp>";
				echo 	"<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				echo 	"<Celular><![CDATA[$lin[Celular]]]></Celular>";
				echo 	"<Total><![CDATA[$Total]]></Total>";
				switch($lin[IdStatus]){
					case 1:
						$Color	= getParametroSistema(15,7);
						break;
					case 2:
						$Color	= getParametroSistema(15,3);
						break;
					case 5:
						$Color	= getParametroSistema(15,7);
						break;
					case 6:
						$Color	= getParametroSistema(15,2);
						break;			
				}
				echo 	"<Color><![CDATA[$Color]]></Color>";
			}
			echo "</reg>";
			echo "</db>";
		}else{
			echo "false";
		}
	}
	get_EmailFilaEspera();
?>