<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_servico_grupo		= $_POST['filtro_servico_grupo'];
	$filtro_descricao_servico	= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_periodicidade		= $_POST['filtro_periodicidade'];
	$filtro_idstatus			= $_POST['filtro_idstatus'];
	$filtro_valor				= $_POST['filtro_valor'];
	$filtro_tipo				= $_POST['filtro_tipo'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_servico				= $_GET['IdServico'];
	$filtro_dataInicio			= $_GET['DataInicio'];
	
	$valorMax = 0;
	$valorMin = 0;
	$valorMed = 0;
	
	if($filtro_servico == ''){
		$filtro_servico			= $_POST['IdServico'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_servico!=''){
		$filtro_url .= "&IdServico=$filtro_servico";
		$filtro_sql .=	" and Servico.IdServico = $filtro_servico";
	}
	
	if($filtro_dataInicio!=''){
		$filtro_url .= "&DataInicio=$filtro_dataInicio";
		$filtro_sql .=	" and ServicoValor.DataInicio = '$filtro_dataInicio'";
	}
		
	if($filtro_servico_grupo!=''){
		$filtro_url .= "&ServicoGrupo=$filtro_servico_grupo";
		$filtro_sql .=	" and ServicoGrupo.IdServicoGrupo = $filtro_servico_grupo";
	}
		
	if($filtro_descricao_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_descricao_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_descricao_servico%'";
	}
	
	if($filtro_valor!=""){
		$filtro_url    .= "&Valor=".$filtro_valor;
		$filtro_valor	=	str_replace(".", "", $filtro_valor);	
		$filtro_valor	= 	str_replace(",", ".", $filtro_valor);
		$filtro_sql    .= " and ServicoValor.Valor = '$filtro_valor'";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and Servico.IdStatus = $filtro_idstatus";
	}
	
	if($filtro_tipo!=''){
		$filtro_url .= "&IdTipoServico=".$filtro_tipo;
		$filtro_sql .= " and Servico.IdTipoServico = $filtro_tipo";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_ticket_medio_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql	=	"SELECT
					Servico.IdServico,
					Servico.DescricaoServico,
					MAX(ContratoVigenciaAtiva.Valor) ContratoValorMax,
					AVG(ContratoVigenciaAtiva.Valor) ContratoValorMed,
					MIN(ContratoVigenciaAtiva.Valor) ContratoValorMin,
					
					MAX(OrdemServico.ValorTotal) OrdemServicoValorMax,
					AVG(OrdemServico.ValorTotal) OrdemServicoValorMed,
					MIN(OrdemServico.ValorTotal) OrdemServicoValorMin
				FROM
					Servico 
					LEFT JOIN (Contrato,ContratoVigenciaAtiva) ON 
										(
											Contrato.IdLoja = Servico.IdLoja AND 
											Contrato.IdServico = Servico.IdServico AND
											ContratoVigenciaAtiva.IdLoja = Contrato.IdLoja AND
											ContratoVigenciaAtiva.IdContrato = Contrato.IdContrato
										)
					LEFT JOIN OrdemServico ON 
										(	
											OrdemServico.IdLoja = Servico.IdLoja AND 
											OrdemServico.IdServico = Servico.IdServico
										),
										ServicoGrupo					
				WHERE
					Servico.IdLoja = $local_IdLoja and
					Servico.IdLoja = ServicoGrupo.IdLoja and
					Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo
					$filtro_sql
					GROUP BY 
						Servico.IdServico,
						Servico.IdTipoServico,
						Servico.IdServicoGrupo
				order by
					Servico.IdServico desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=17 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
		
		if($lin[Valor] == ""){
			$lin[Valor] = 0;
		}
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=71 and IdParametroSistema=$lin[IdTipoServico]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		if($lin[DataTermino]!=""){
			if($lin[DataTermino]<date('Y-m-d')){
				$lin[Valor]		= 0;
			}
		}
		
		if($lin[ContratoValorMax] > $lin[OrdemServicoValorMax]){
			$valorMax = $lin[ContratoValorMax];
		}else{
			$valorMax = $lin[OrdemServicoValorMax];
		}
		
		if($lin[ContratoValorMed] > $lin[OrdemServicoValorMed]){
			$valorMed = $lin[ContratoValorMed];
		}else{
			$valorMed = $lin[OrdemServicoValorMed];
		}
		
		if($lin[ContratoValorMin] > $lin[OrdemServicoValorMin]){
			$valorMin = $lin[ContratoValorMin];
		}else{
			$valorMin = $lin[OrdemServicoValorMin];
		}
		
		if($valorMax == "" || $valorMax == "NULL") $valorMax = 0;
		if($valorMed == "" || $valorMed == "NULL") $valorMed = 0;
		if($valorMin == "" || $valorMin == "NULL") $valorMin = 0;
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<ValorMax>$valorMax</ValorMax>";
		echo 	"<ValorMed>$valorMed</ValorMed>";
		echo 	"<ValorMin>$valorMin</ValorMin>";
		echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
		echo 	"<TipoServico><![CDATA[$lin2[ValorParametroSistema]]]></TipoServico>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
