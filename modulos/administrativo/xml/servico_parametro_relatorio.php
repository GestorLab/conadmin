<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ServicoParametro(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdServico	 			= $_GET['IdServico'];
		$IdParametro1		 	= $_GET['IdParametro1'];
		$IdParametro2		 	= $_GET['IdParametro2'];
		$IdParametro3		 	= $_GET['IdParametro3'];
		$IdParametro4		 	= $_GET['IdParametro4'];
		
		if($IdParametro1 != '' && $IdParametro2==''){
			$sql	=	"select
							ServicoParametro.IdServico,
							ServicoParametro.DescricaoParametroServico
						from 
							(select IdLoja,IdServico,DescricaoParametroServico from ServicoParametro where IdLoja = $IdLoja and DescricaoParametroServico like '%$IdParametro1%') Servico,
							ServicoParametro
						where
							Servico.IdLoja = $IdLoja and
							Servico.IdServico = ServicoParametro.IdServico and
							ServicoParametro.IdLoja = Servico.IdLoja and
							ServicoParametro.IdStatus = 1 and
							ServicoParametro.DescricaoParametroServico != '$IdParametro1'		
						group by
							ServicoParametro.DescricaoParametroServico
						order by 
							ServicoParametro.DescricaoParametroServico ASC";
		}else{
			if($IdParametro1 != '' && $IdParametro2!='' && $IdParametro3 ==''){
				$sql	=	"select
								Servico.IdServico,
								ServicoParametro.DescricaoParametroServico
							from 
								(select IdLoja,IdServico,DescricaoParametroServico from ServicoParametro where IdLoja = $IdLoja and DescricaoParametroServico like '%$IdParametro2%') Servico,
								ServicoParametro
							where
								Servico.IdLoja = $IdLoja and
								Servico.IdServico = ServicoParametro.IdServico and
								ServicoParametro.IdLoja = Servico.IdLoja and
								ServicoParametro.IdStatus = 1 and
								ServicoParametro.DescricaoParametroServico !=	'$IdParametro1' and
								ServicoParametro.DescricaoParametroServico !=	'$IdParametro2' and
								Servico.IdServico in ($IdServico)	
							group by
								ServicoParametro.DescricaoParametroServico	
							order by 
								ServicoParametro.DescricaoParametroServico ASC";
			}else{
				$sql	=	"select
								Servico.IdServico,
								ServicoParametro.DescricaoParametroServico
							from 
								(select IdLoja,IdServico,DescricaoParametroServico from ServicoParametro where IdLoja = $IdLoja and DescricaoParametroServico like '%$IdParametro3%') Servico,
								ServicoParametro
							where
								Servico.IdLoja = $IdLoja and
								Servico.IdServico = ServicoParametro.IdServico and
								ServicoParametro.IdLoja = Servico.IdLoja and
								ServicoParametro.IdStatus = 1 and
								ServicoParametro.DescricaoParametroServico !=	'$IdParametro1' and
								ServicoParametro.DescricaoParametroServico !=	'$IdParametro2' and
								ServicoParametro.DescricaoParametroServico !=	'$IdParametro3' and
								Servico.IdServico in ($IdServico)	
							group by
								ServicoParametro.DescricaoParametroServico	
							order by 
								ServicoParametro.DescricaoParametroServico ASC";
			}
		}
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$lin[DescricaoParametroServicoValor] = substituir_string($lin[DescricaoParametroServico]);
		
			$dados	.=	"\n<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";
			$dados	.=	"\n<DescricaoParametroServico><![CDATA[$lin[DescricaoParametroServico]]]></DescricaoParametroServico>";
			$dados	.=	"\n<DescricaoParametroServicoValor><![CDATA[$lin[DescricaoParametroServicoValor]]]></DescricaoParametroServicoValor>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ServicoParametro();
?>
