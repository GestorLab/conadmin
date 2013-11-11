<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Ordem_Servico_Atendimento(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdOrdemServico			= $_GET['IdOrdemServico'];
		
		
		$sql	="	select 
						OrdemServico.IdStatus,
						OrdemServico.EmAtendimento 
					from
						OrdemServico 
					where
						OrdemServico.IdLoja = $IdLoja and
						OrdemServico.IdOrdemServico = $IdOrdemServico";
		$res	=	mysql_query($sql,$con);
		$lin	=	mysql_fetch_array($res);
			
		if($lin[EmAtendimento] == ""){
			$sql = "UPDATE OrdemServico SET 
						EmAtendimento = 1,
					WHERE
						IdLoja = $IdLoja and
						IdOrdemServico = $IdOrdemServico;";
			
			$lin[EmAtendimento] = '1';
		}
		if(@mysql_num_rows($res) == 1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			$dados	.=	"\n<EmAtendimento><![CDATA[$lin[EmAtendimento]]]></EmAtendimento>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_Ordem_Servico_Atendimento();
?>
