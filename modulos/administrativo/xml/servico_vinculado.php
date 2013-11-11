<?
	$localModulo = 1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_servico_vinculado(){
		global $con;
		global $_GET;
		
		$local_IdLoja		= $_SESSION["IdLoja"];
		$local_IdServico	= $_GET['IdServico'];
		
		$sql = "SELECT
					Servico.IdServico,
					Servico.DescricaoServico,
					subString(Servico.DescricaoServico, 1, 44) DescricaoServicoTemp,
					Servico.IdTipoServico,
					Servico.IdStatus,
					ServicoGrupo.DescricaoServicoGrupo
				FROM 
					ServicoAgrupado,
					Servico,
					ServicoGrupo
				WHERE 
					ServicoAgrupado.IdLoja = '$local_IdLoja' AND 
					ServicoAgrupado.IdServicoAgrupador = '$local_IdServico' AND 
					ServicoAgrupado.IdLoja = Servico.IdLoja AND 
					ServicoAgrupado.IdServico = Servico.IdServico AND 
					Servico.IdStatus = 1 AND
					Servico.IdLoja = ServicoGrupo.IdLoja AND 
					Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo
				ORDER BY 
					Servico.IdTipoServico,
					Servico.IdServico;";
		$res = @mysql_query($sql, $con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		
		while($lin = @mysql_fetch_array($res)){
			$sql0 = "SELECT 
						Valor
					FROM 
						ServicoValor 
					WHERE 
						DataInicio <= curdate() AND 
						(DataTermino is Null  or DataTermino >= curdate()) AND 
						IdLoja = '$local_IdLoja' AND 
						IdServico = '$lin[IdServico]' 
					ORDER BY 
						DataInicio DESC 
					LIMIT 0,1"; 
			$res0 = @mysql_query($sql0, $con);
			$lin0 = @mysql_fetch_array($res0);
			
			$lin[Status] = getParametroSistema(17, $lin[IdStatus]);
			$lin[TipoServico] = getParametroSistema(71, $lin[IdTipoServico]);
			
			$dados	.=	"\n<IdServico>$lin[IdServico]</IdServico>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<DescricaoServicoTemp><![CDATA[$lin[DescricaoServicoTemp]]]></DescricaoServicoTemp>";
			$dados	.=	"\n<Valor><![CDATA[$lin0[Valor]]]></Valor>";
			$dados	.=	"\n<IdTipoServico><![CDATA[$lin[IdTipoServico]]]></IdTipoServico>";
			$dados	.=	"\n<TipoServico><![CDATA[$lin[TipoServico]]]></TipoServico>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_servico_vinculado();
?>