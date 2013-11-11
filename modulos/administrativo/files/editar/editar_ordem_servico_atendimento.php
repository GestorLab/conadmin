<?
	
	$localModulo		=	1;
	$localOperacao		=	26;	
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	 
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
		function get_atendimento(){
			global $con;
			global $_SESSION;
			global $_GET;
		
			$local_IdLoja			= 	$_SESSION['IdLoja'];
			$local_Login			=	$_SESSION['Login'];
			$local_IdOrdemServico	=	$_GET['IdOrdemServico'];
			$Valor					=	$_GET['Valor'];
			
			if($Valor != ''){
				$EmAndamento = $Valor;
			}
			
			$sqlHistoricoObs = "SELECT
									*
								FROM 
									OrdemServico
								WHERE
									IdLoja			= $local_IdLoja and	
									IdOrdemServico	= $local_IdOrdemServico";
			$resHistoricoObs = mysql_query($sqlHistoricoObs, $con);
			$linHistoricoObs = mysql_fetch_array($resHistoricoObs);
			
			$local_HistoricoObs	= "";		
			if($linHistoricoObs[EmAtendimento] != $Valor){
				if($linHistoricoObs[EmAtendimento] == 1 && $Valor == 2){
					$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Ícone de atendimento (Em atendimento > Aguardando Atendimento)\n";
				}
				if($linHistoricoObs[EmAtendimento] == 2 && $Valor == 1){
					$local_HistoricoObs	.= date("d/m/Y H:i:s")." [".$local_Login."] - Ícone de atendimento (Aguardando Atendimento > Em atendimento)\n";
				}
			}
			$local_HistoricoObs .= $linHistoricoObs[Obs];
			
			$sql	=	"UPDATE OrdemServico SET 
							EmAtendimento 	= $Valor,
							Obs				= '$local_HistoricoObs'
						 WHERE 
						 	IdLoja			= $local_IdLoja and	
							IdOrdemServico	= $local_IdOrdemServico";
			if(mysql_query($sql,$con) == true){
				$sql	="	select 
								OrdemServico.EmAtendimento,
								OrdemServico.Obs
							from
								OrdemServico 
							where
								OrdemServico.IdLoja = $local_IdLoja and
								OrdemServico.IdOrdemServico = $local_IdOrdemServico";
				$res	=	mysql_query($sql,$con);
				$lin	=	@mysql_fetch_array($res);
								
			}else{
				$dados	= "false";
			}
			
			if($lin[EmAtendimento]!= ''){
				header ("content-type: text/xml");
				$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
						
				$dados	.=	"\n<reg>";
				$dados	.=	"\n<EmAtendimento><![CDATA[$lin[EmAtendimento]]]></EmAtendimento>";
				$dados	.=	"\n<HistoricoObs><![CDATA[$lin[Obs]]]></HistoricoObs>";
				$dados	.=	"\n</reg>";
			} else{
				$dados	= "false";
			}
			
			return $dados;
		}
		
		echo get_atendimento();
	}
?>
