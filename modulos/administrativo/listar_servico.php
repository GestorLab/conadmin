<?
	$localModulo		=	1;
	$localOperacao		=	25;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$filtro								= $_POST['filtro'];
	$filtro_ordem						= $_POST['filtro_ordem'];
	$filtro_ordem_direcao				= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado				= $_POST['filtro_tipoDado'];
	$filtro_servico_grupo				= $_POST['filtro_servico_grupo'];
	$filtro_descricao_servico			= url_string_xsl($_POST['filtro_descricao_servico'],'url',false);
	$filtro_periodicidade				= $_POST['filtro_periodicidade'];
	$filtro_idstatus					= $_POST['filtro_idstatus'];
	$filtro_valor						= $_POST['filtro_valor'];
	$filtro_tipo						= $_POST['filtro_tipo'];
	$filtro_limit						= $_POST['filtro_limit'];
	$filtro_servico						= $_GET['IdServico'];
	$filtro_dataInicio					= $_GET['DataInicio'];
	$filtro_listar_servicos_desativados	= $_POST['filtro_listar_servicos_desativados'];
	$filtro_periodicidade				= $_POST['filtro_periodicidade'];
	$filtro_tipo_pessoa					= $_POST['filtro_tipo_pessoa'];
	$filtro_terceiro					= $_POST['filtro_terceiro'];
	$filtro_tipo_nota_fiscal			= $_POST['filtro_tipo_nota_fiscal'];
	$filtro_categoria_tributaria		= $_POST['filtro_categoria_tributaria'];
	$filtro_email_cobraca				= $_POST['filtro_email_cobraca'];
	$filtro_executar_rotinas_diarias	= $_POST['filtro_executar_rotinas_diarias'];
	$filtro_estado						= $_POST['filtro_estado'];
	$filtro_cidade						= $_POST['filtro_cidade'];
	
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
	
	if($filtro_tipo_pessoa	!=''){
		$filtro_url .= "&Filtro_IdTipoPessoa=".$filtro_tipo_pessoa	;
		$filtro_sql .= " and Servico.Filtro_IdTipoPessoa = '$filtro_tipo_pessoa'";
	}
	
	if($filtro_terceiro!=''){
		$filtro_url .= "&IdTerceiro=".$filtro_terceiro;
		$filtro_sql .= " and ServicoTerceiro.IdPessoa = $filtro_terceiro and Servico.IdServico = ServicoTerceiro.IdServico ";
		$filtro_from .= ",ServicoTerceiro";
	}
	
	if($filtro_periodicidade!=''){
		$filtro_url .= "&IdPeriodicidade=".$filtro_periodicidade;
		$filtro_sql .= "and	ServicoPeriodicidade.IdPeriodicidade = '$filtro_periodicidade' and Servico.IdServico = ServicoPeriodicidade.IdServico";
		$filtro_from .= ",ServicoPeriodicidade";
	}
	if($filtro_listar_servicos_desativados !=''){
		$filtro_url .= "&ServicoDesativados=".$filtro_listar_servicos_desativados;
		if($filtro_listar_servicos_desativados == 2){
			$filtro_sql .= " and Servico.IdStatus <> $filtro_listar_servicos_desativados";
		}
	}
	
	if($filtro_tipo_nota_fiscal!=''){
		$filtro_url .= "&IdNotaFiscalTipo=".$filtro_tipo_nota_fiscal;
		$filtro_sql .= " and Servico.IdNotaFiscalTipo = '$filtro_tipo_nota_fiscal'";
	}	
	if($filtro_executar_rotinas_diarias!=''){
		$filtro_url .= "&ExecutarRotinas=".$filtro_executar_rotinas_diarias;
		$filtro_sql .= " and Servico.ExecutarRotinas = '$filtro_executar_rotinas_diarias'";
	}
	if($filtro_email_cobraca!=''){
		$filtro_url .= "&EmailCobranca=".$filtro_email_cobraca;
		$filtro_sql .= " and Servico.EmailCobranca = '$filtro_email_cobraca'";
	}
	
	if($filtro_categoria_tributaria !=''){
		$filtro_url .= "&IdCategoriaTributaria=".$filtro_categoria_tributaria;
		$filtro_sql .= " and Servico.IdCategoriaTributaria = $filtro_categoria_tributaria";
	}
	
	if($filtro_estado!=''){
		if($filtro_cidade!=''){
		$filtro_url .= "&IdCidade=".$filtro_cidade;
		$filtro_cidade = ','.$filtro_cidade;
		}		
		$filtro_url .= "&Filtro_IdPaisEstadoCidade=".$filtro_estado;	
		$filtro_sql .= " and Filtro_IdPaisEstadoCidade != '' AND
						 (Filtro_IdPaisEstadoCidade LIKE CONCAT('1,$filtro_estado$filtro_cidade%') /* Inicio */ OR 
						  Filtro_IdPaisEstadoCidade LIKE CONCAT('%^','1,$filtro_estado$filtro_cidade',',%') /* Meio */ OR 
						  Filtro_IdPaisEstadoCidade LIKE CONCAT('%^','1,$filtro_estado$filtro_cidade') /* Fim */ OR 
						  Filtro_IdPaisEstadoCidade = TRIM('1,$filtro_estado$filtro_cidade') /* Igual */)";
	
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select
					Servico.IdLoja,
				    Servico.IdServicoGrupo,
				    substr(ServicoGrupo.DescricaoServicoGrupo,1,30) DescricaoServicoGrupo,
				    Servico.IdServico,
				    substr(Servico.DescricaoServico,1,60) DescricaoServico,
				    ServicoValor.Valor,
				    subString(ServicoValor.DataTermino,1,10) DataTermino,
				    Servico.IdStatus,
				    Servico.IdTipoServico,
					Servico.Filtro_IdPaisEstadoCidade					
				from
					Servico left join (select ServicoValor.IdServico, ServicoValor.DataInicio, ServicoValor.DataTermino, ServicoValor.Valor from ServicoValor, (select ServicoValor.IdServico, max(DataInicio) DataInicio from ServicoValor where ServicoValor.IdLoja = $local_IdLoja and ServicoValor.DataInicio <= curdate() group by ServicoValor.IdServico) ServicoValorTemp where ServicoValor.IdLoja = $local_IdLoja and ServicoValor.IdServico = ServicoValorTemp.IdServico and ServicoValor.DataInicio = ServicoValorTemp.DataInicio) ServicoValor on (Servico.IdServico = ServicoValor.IdServico),
				    ServicoGrupo
					$filtro_from
				where
				    Servico.IdLoja = $local_IdLoja and
				    Servico.IdLoja = ServicoGrupo.IdLoja and
				    Servico.IdServicoGrupo = ServicoGrupo.IdServicoGrupo
					$filtro_sql				
				group by	
					Servico.IdServico
				order by
					Servico.IdServico desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	echo mysql_error();
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
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdServicoGrupo>$lin[IdServicoGrupo]</IdServicoGrupo>";
		echo 	"<DescricaoServicoGrupo><![CDATA[$lin[DescricaoServicoGrupo]]]></DescricaoServicoGrupo>";
		echo 	"<IdServico>$lin[IdServico]</IdServico>";	
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<Valor>$lin[Valor]</Valor>";
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
		echo 	"<TipoServico><![CDATA[$lin2[ValorParametroSistema]]]></TipoServico>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
