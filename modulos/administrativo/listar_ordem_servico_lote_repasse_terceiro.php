<?
	$localModulo		=	1;
	$localOperacao		=	132;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja				= $_SESSION['IdLoja']; 
	$local_Login				= $_SESSION['Login']; 
	$local_IdPessoaLogin		= $_SESSION['IdPessoa'];
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_servico				= $_POST['filtro_servico'];
	$filtro_nome				= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_idstatus			= $_POST['filtro_idstatus'];
	$filtro_limit				= $_POST['filtro_limit'];
	$filtro_campo				= $_POST['filtro_campo'];
	$filtro_valor				= url_string_xsl($_POST['filtro_valor'],'url',false);
	$filtro_lista_concluido		= $_POST['filtro_lista_concluido'];	
	$filtro_lista_cancelado		= $_POST['filtro_lista_cancelado'];
	$filtro_pessoa				= $_GET['IdPessoa'];
	$filtro_os					= $_GET['IdOrdemServico'];
	$filtro_lancamento			= $_GET['IdLancamentoFinanceiro'];
	$filtro_contrato			= $_GET['IdContrato'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_percentual_repasse_terceiro 		= $_POST["filtro_percentual_repasse_terceiro"];
	$filtro_percentual_repasse_terceiro_outros	= $_POST["filtro_percentual_repasse_terceiro_outros"];

	$filtro_url	= "";
	$filtro_sql = "";
	$sqlAux		= "";
	
	$filtro_forma_cobranca	=	$_SESSION["filtro_forma_cobranca"];
	$filtro_terceiro		=	$_SESSION["filtro_terceiro"];
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_descricao_os!=''){
		$filtro_url .= "&DescricaoOS=$filtro_descricao_os";
		$filtro_sql .=	" and OrdemServico.DescricaoOS like '%$filtro_descricao_os%'";
	}
		
	if($filtro_servico!=""){
		$filtro_url .= "&DescricaoServico=".$filtro_servico;
		$filtro_sql .= " and Servico.DescricaoServico like '%$filtro_servico%'";
	}

	if($filtro_lancamento!=""){
		$filtro_url .= "&IdLancamentoFinanceiro=".$filtro_lancamento;
		$filtro_sql .= " and OrdemServico.IdOrdemServico in (
			select distinct
				IdOrdemServico
			from
				LancamentoFinanceiro 
			where 
				IdLoja = '$local_IdLoja' and 
				IdOrdemServico = '$filtro_lancamento' 
		)";
	}
	
	if($filtro_idstatus!=''){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus = $filtro_idstatus";
	}

	if($filtro_pessoa!=''){
		$filtro_url .= "&IdPessoa=".$filtro_pessoa;
		$filtro_sql .= " and OrdemServico.IdPessoa = $filtro_pessoa";
	}
	
	if($filtro_contrato!=''){
		$filtro_url .= "&IdConrato=".$filtro_contrato;
		$filtro_sql .= " and OrdemServico.IdContrato = $filtro_contrato";
		
		$filtro_os = "";
	}
	
	if($filtro_os!=''){
		$filtro_url .= "&IdOrdemServico=$filtro_os";
		$filtro_sql .=	" and OrdemServico.IdOrdemServico = '$filtro_os'";
	}
	
	if($filtro_forma_cobranca!=''){
		$filtro_url .= "&FormaCobranca=$filtro_forma_cobranca";
		$filtro_sql .= " and OrdemServico.FormaCobranca = $filtro_forma_cobranca";	
	} 
	
	if($filtro_terceiro!=''){
		$filtro_url .= "&Terceiro=$filtro_terceiro";
		$filtro_sql .= " and OrdemServico.IdTerceiro = $filtro_terceiro";	
	}
	
	if($filtro_lista_concluido == 2 && $filtro_idstatus != 200){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 200";
	}
	
	if($filtro_lista_cancelado == 2 && $filtro_idstatus != "0"){
		$filtro_url .= "&IdStatus=".$filtro_idstatus;
		$filtro_sql .= " and OrdemServico.IdStatus != 0";
	}
	
	if($filtro_percentual_repasse_terceiro!=''){
		$filtro_url .= "&PercentualRepasseTerceiro=$filtro_percentual_repasse_terceiro";
	}
	
	if($filtro_percentual_repasse_terceiro_outros!=''){
		$filtro_url .= "&PercentualRepasseTerceiroOutros=$filtro_percentual_repasse_terceiro_outros";	
	}

	if($filtro_campo!=''){
		$filtro_url .= "&Campo=$filtro_campo";
		$filtro_url .= "&Valor=$filtro_valor";
		switch($filtro_campo){		
			case 'DescricaoOS':
				$filtro_sql .=	" and OrdemServico.DescricaoOS like '%$filtro_valor%'";
				break;
			case 'IdGrupoUsuarioAtendimento':
				$filtro_sql .=	" and GrupoUsuario.DescricaoGrupoUsuario like '%$filtro_valor%'";
				break;
			case 'LoginResponsavel':
				$filtro_sql .=	" and (OrdemServico.LoginCriacao like '%$filtro_valor%')";
				break;
			case 'LoginAtendimento':
				$filtro_sql .=	" and (OrdemServico.LoginAtendimento like '%$filtro_valor%')";
				break;
			case 'DataCadastro':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.DataCriacao like '$filtro_valor%'";
				}						
				break;
			case 'DataFatura':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServico.IdStatus = 500 and OrdemServico.DataAlteracao like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServico.DataAlteracao is NULL";
				}	
				break;
			case 'DataVencimento':
				if($filtro_valor!=""){
					$filtro_valor	=	dataConv($filtro_valor,'d/m/Y','Y-m-d');
					$filtro_sql    .=	" and OrdemServicoParcela.IdOrdemServicoParcela = 1 and OrdemServicoParcela.Vencimento like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServicoParcela.Vencimento is NULL";
				}	
				break;
			case 'MesCadastro':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql  .= " and substring(OrdemServico.DataCriacao,1,7) = '$filtro_valor'";
				}								
				break;
			case 'MesFatura':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');
					$filtro_sql  .= " and substring(OrdemServico.DataAlteracao,1,7) = '$filtro_valor'";
				}											
				break;			
			case 'MesVencimento':
				if($filtro_valor!=""){
					$filtro_valor = dataConv($filtro_valor,'m/Y','Y-m');					
					$filtro_sql    .=	" and OrdemServicoParcela.IdOrdemServicoParcela = 1 and substring(OrdemServicoParcela.Vencimento,1,7) like '$filtro_valor%'";
				}else{
					$filtro_sql    .=	" and OrdemServicoParcela.Vencimento is NULL";
				}		
				break;
		}
	}else{
		$filtro_valor	=	"";
	}
	
	
	if($_SESSION["RestringirAgenteAutorizado"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdAgenteAutorizado = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	if($_SESSION["RestringirAgenteCarteira"] == true){
		$sqlAgente	=	"select 
							AgenteAutorizado.IdGrupoPessoa 
						from 
							AgenteAutorizado,
							Carteira
						where 
							AgenteAutorizado.IdLoja = $local_IdLoja  and 
							AgenteAutorizado.IdLoja = Carteira.IdLoja and
							AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
							Carteira.IdCarteira = '$local_IdPessoaLogin' and 
							AgenteAutorizado.Restringir = 1 and 
							AgenteAutorizado.IdStatus = 1 and 
							AgenteAutorizado.IdGrupoPessoa is not null";
		$resAgente	=	@mysql_query($sqlAgente,$con);
		while($linAgente	=	@mysql_fetch_array($resAgente)){
			$filtro_sql    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
		}
	}
	
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_ordem_servico_lote_repasse_terceiro_xsl.php$filtro_url\"?>";
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
	
	$cont	=	0;
	$sql	=	"select
				    OrdemServico.IdOrdemServico,				    
				    substr(OrdemServico.DescricaoOS,1,12) DescricaoOS,
					Pessoa.TipoPessoa,
				    substr(Pessoa.Nome,1,12) Nome,
				    substr(Pessoa.RazaoSocial,1,12) RazaoSocial,
				    substr(Servico.DescricaoServico,1,12) DescricaoServico,				    
				    OrdemServico.IdServico,				    
				    OrdemServico.Valor,
				    OrdemServico.ValorOutros,
				    OrdemServico.LoginCriacao,
					OrdemServico.DataFaturamento,
				    OrdemServico.LoginAtendimento,
				    OrdemServico.IdStatus,
					OrdemServico.DataAgendamentoAtendimento,
					OrdemServico.IdContrato,
					OrdemServico.IdMarcador,
					OrdemServico.DataCriacao,
					OrdemServico.FormaCobranca,
					OrdemServico.IdTerceiro								
				from    
				    OrdemServico LEFT JOIN Pessoa ON (
						OrdemServico.IdPessoa = Pessoa.IdPessoa
					) left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$local_IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					) LEFT JOIN OrdemServicoParcela ON (
						OrdemServico.IdLoja = OrdemServicoParcela.IdLoja and 
						OrdemServico.IdOrdemServico = OrdemServicoParcela.IdOrdemServico
					) $sqlAux,
					Servico,				
					TipoOrdemServico,
					SubTipoOrdemServico		
				where
					OrdemServico.IdLoja = $local_IdLoja and
					OrdemServico.IdLoja = Servico.Idloja and				
					OrdemServico.IdServico = Servico.IdServico and
					OrdemServico.IdStatus > 99 and
					OrdemServico.IdTerceiro	is not null and
					(OrdemServico.Faturado = 1 or OrdemServico.IdStatus = 400) $filtro_sql
				group by
					OrdemServico.IdOrdemServico
				order by 
					OrdemServico.DataAgendamentoAtendimento DESC, OrdemServico.IdOrdemServico DESC $Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
		$res3 = @mysql_query($sql3,$con);
		$lin3 = @mysql_fetch_array($res3);
				
		$sql2	=	"select 
						Nome NomeTerceiro
					from 
						Pessoa 
					where 
						IdPessoa = $lin[IdTerceiro]"; 
		$res2	=	@mysql_query($sql2,$con);
		$lin2	=	@mysql_fetch_array($res2);
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		if($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99){			
			#$Color	  =	getParametroSistema(15,2);
			if($local_Login == $lin[LoginCriacao]){		
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del.gif";
			}else{
				$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
			}
		}
		if($lin[IdStatus] >= 400 && $lin[IdStatus] <= 499){			
			#$Color	  = "";		
			$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
		}
		if($lin[IdStatus] >= 500 && $lin[IdStatus] <= 599){						
			#$Color	  		= getParametroSistema(15,3);
			$ImgExc	  		= "../../img/estrutura_sistema/ico_del_c.gif";
		}		
		if($lin[IdStatus] > 99 && $lin[IdStatus] < 400){
			#$Color	  = "";		
			$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";	
		}
		
		$Color = getOrdemServicoCor($lin[IdOrdemServico]);
		$lin[DataHora] 		= dataConv($lin[DataAgendamentoAtendimento],"Y-m-d H:i:s","YmdHis");
		
		$lin[DataFaturamentoTemp] 	= dataConv($lin[DataFaturamento],"Y-m-d","d/m/Y");
		$lin[DataFaturamento] 		= dataConv($lin[DataFaturamento],"Y-m-d H:i:s","YmdHis");
				
		if($lin[ValorTotal] == '')	$lin[ValorTotal] = 0;
		
		$local_Marcador		= '';
		$local_CorMarcador	= '';
		
		if($lin[IdMarcador] > 0 && $lin[IdMarcador] < 4){ //seleciona o marcador
			$local_CorMarcador = getParametroSistema(155, $lin[IdMarcador]);
		}
		
		if($local_CorMarcador!=''){
			$local_Marcador = '&#8226;';
		}
		if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
			$local_TempoAbertura = "";			
		}else{
			$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
		}
	
		$DiaAbertura = nDiasIntervalo($lin[DataCriacao],Date("Y-m-d H:i:s"));
				
		$lin[DataCriacaoTemp]	 = dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");	
		$lin[DataCriacao] 		 = dataConv($lin[DataCriacao],"Y-m-d","Ymd");	
		
		$lin[FormaCobranca] 	 = getParametroSistema(50, $lin[FormaCobranca]); 
		
		
		$sql4	=	"select 		
						ServicoTerceiro.ValorRepasseTerceiro,
						ServicoTerceiro.PercentualRepasseTerceiro,
						ServicoTerceiro.PercentualRepasseTerceiroOutros,
						ServicoTerceiro.IdPessoa,
						OrdemServico.IdTerceiro
					from 
						ServicoTerceiro,
						ServicoValor,
						OrdemServico 
					where 
						ServicoValor.IdLoja = $local_IdLoja and
						ServicoTerceiro.IdLoja = $local_IdLoja and
						ServicoValor.DataInicio <= curdate() and
						(	
							ServicoValor.DataTermino is Null or 
							ServicoValor.DataTermino >= curdate()
						)and 
						ServicoTerceiro.IdServico = $lin[IdServico] and
						ServicoValor.IdServico = $lin[IdServico] and
						OrdemServico.IdOrdemServico = $lin[IdOrdemServico]
					order BY 
						ServicoValor.DataInicio DESC 
					LIMIT 0,1"; 
		$res4	=	@mysql_query($sql4,$con);
		$lin4	=	@mysql_fetch_array($res4);
		
		//Tratamento para campos que nao tem valor na tabela
        if($lin4[ValorRepasseTerceiro] == "") $lin4[ValorRepasseTerceiro] = 0;
		if($lin4[PercentualRepasseTerceiro] == "") $lin4[PercentualRepasseTerceiro]	= 0;
		if($lin4[PercentualRepasseTerceiroOutros] == "") $lin4[PercentualRepasseTerceiroOutros]	= 0;

		
		if ($lin4[ValorRepasseTerceiro] > $lin4[PercentualRepasseTerceiro]){
			
			$repasseTerceiroPercentual  = ($lin4[ValorRepasseTerceiro] * 100 ) / $lin[Valor];
			
			$repasseTerceiroOutrosPercentual = $lin4[PercentualRepasseTerceiroOutros];
						
			$valorTotal		= 	 $lin[Valor] + $lin[ValorOutros];
			
			if ($lin4[PercentualRepasseTerceiro] > 0){
				$repasseTerceiroMoeda	= 	($lin4[PercentualRepasseTerceiro] * $lin[Valor] ) / 100 ;
			} else{
				$repasseTerceiroMoeda	=	$lin4[ValorRepasseTerceiro];
			}	
			
			if ($lin[ValorOutros] > 0){ 
				if ($lin4[PercentualRepasseTerceiroOutros] > 0){
					$repasseTerceiroOutrosMoeda		=  ($lin4[PercentualRepasseTerceiroOutros] * $lin[ValorOutros]) / 100;
				}
			}else{
					$repasseTerceiroOutrosMoeda 	=  $lin[ValorOutros];
			}
			
			$valorTotalRepasseTerceiroMoeda  =  $repasseTerceiroMoeda	+	$repasseTerceiroOutrosMoeda;
		
		}else{
		
			$repasseTerceiroPercentual   =  $lin4[PercentualRepasseTerceiro];
			
			$repasseTerceiroOutrosPercentual = $lin4[PercentualRepasseTerceiroOutros];
			
			$valorTotal 	=	 $lin[Valor] + $lin[ValorOutros];
			
			if ( $lin4[PercentualRepasseTerceiro] > 0){
				$repasseTerceiroMoeda	=  ($lin4[PercentualRepasseTerceiro] * $lin[Valor] ) / 100 ;
			} else{
				$repasseTerceiroMoeda	=  $lin4[ValorRepasseTerceiro];
			}	
			
			if ($lin[ValorOutros] > 0){ 
				if ($lin4[PercentualRepasseTerceiroOutros] > 0){
					$repasseTerceiroOutrosMoeda		=  ($lin4[PercentualRepasseTerceiroOutros] * $lin[ValorOutros]) / 100;
				}
			}else{
					$repasseTerceiroOutrosMoeda 	=  $lin[ValorOutros];
			}
			
			$valorTotalRepasseTerceiroMoeda 	= $repasseTerceiroMoeda		+	$repasseTerceiroOutrosMoeda;
		}
		if($lin[IdStatus] == 100){
			$lin3[ValorParametroSistema] .= " (Faturado)";
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";	
		echo 	"<IdStatus>$lin[IdStatus]</IdStatus>";
		echo 	"<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
		echo 	"<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<NomeTerceiro><![CDATA[$lin2[NomeTerceiro]]]></NomeTerceiro>";
		echo 	"<Valor><![CDATA[$lin[Valor]]]></Valor>";
		echo 	"<ValorTotal><![CDATA[$valorTotal]]></ValorTotal>";
		echo 	"<ValorOutros><![CDATA[$lin[ValorOutros]]]></ValorOutros>";
		echo 	"<DataFaturamento><![CDATA[$lin[DataFaturamento]]]></DataFaturamento>";
		echo 	"<DataFaturamentoTemp><![CDATA[$lin[DataFaturamentoTemp]]]></DataFaturamentoTemp>";
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
		echo 	"<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
		echo 	"<CorMarcador>$local_CorMarcador</CorMarcador>";
		echo 	"<Marcador>$local_Marcador</Marcador>";
		echo 	"<FormaCobranca><![CDATA[$lin[FormaCobranca]]]></FormaCobranca>";
		echo 	"<PercentualRepasseTerceiro><![CDATA[$repasseTerceiroPercentual]]></PercentualRepasseTerceiro>";
		echo 	"<PercentualRepasseTerceiroOutros><![CDATA[$repasseTerceiroOutrosPercentual]]></PercentualRepasseTerceiroOutros>";
		echo 	"<ValorRepasseTerceiro><![CDATA[$repasseTerceiroMoeda]]></ValorRepasseTerceiro>";
		echo 	"<ValorRepasseTerceiroOutros><![CDATA[$repasseTerceiroOutrosMoeda]]></ValorRepasseTerceiroOutros>";
		echo 	"<ValorTotalRepasseTerceiro><![CDATA[$valorTotalRepasseTerceiroMoeda]]></ValorTotalRepasseTerceiro>";
		echo 	"<Color><![CDATA[$Color]]></Color>";
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";
	}
	
	echo "</db>";
?>
