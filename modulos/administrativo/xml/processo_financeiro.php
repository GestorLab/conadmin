<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_processo_financeiro(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja		 					= $_SESSION["IdLoja"];
		$IdProcessoFinanceiro 			= $_GET['IdProcessoFinanceiro'];
		$IdLancamentoFinanceiro			= $_GET['IdLancamentoFinanceiro'];
		$MesReferencia					= $_GET['MesReferencia'];
		$MenorVencimento				= $_GET['MenorVencimento'];
		$IdStatus						= $_GET['IdStatus'];
		$where			= "";
		$from			= "";
		
		if($Limit != ''){
			$Limit = "limit 0,$Limit";
		}
		
		if($IdLancamentoFinanceiro != ''){	
			$where .= " and ProcessoFinanceiro.IdProcessoFinanceiro = LancamentoFinanceiro.IdProcessoFinanceiro and LancamentoFinanceiro.IdLancamentoFinanceiro=$IdLancamentoFinanceiro and LancamentoFinanceiro.IdLoja = $IdLoja";	
			$from  .= ",LancamentoFinanceiro";
		}
		
		if($IdProcessoFinanceiro != ''){
			$where .= " and ProcessoFinanceiro.IdProcessoFinanceiro=$IdProcessoFinanceiro";
		}
		
		if($MesReferencia != ''){
			$where .= " and ProcessoFinanceiro.MesReferencia like '$MesReferencia%'";
		}
		
		if($MenorVencimento != ''){
			$where .= " and ProcessoFinanceiro.MenorVencimento = '$MenorVencimento'";
		}
		
		if($IdStatus != ''){
			$where .= " and ProcessoFinanceiro.IdStatus = $IdStatus";
		}
		
		$sql = "select
					ProcessoFinanceiro.IdLoja,
					ProcessoFinanceiro.IdProcessoFinanceiro,
					ProcessoFinanceiro.MesReferencia,
					ProcessoFinanceiro.MesVencimento,
					ProcessoFinanceiro.MenorVencimento,
					ProcessoFinanceiro.DataNotaFiscal, 
					ProcessoFinanceiro.LogProcessamento,
					ProcessoFinanceiro.Filtro_IdPessoa,
					ProcessoFinanceiro.Filtro_IdLocalCobranca,
					ProcessoFinanceiro.Filtro_TipoLancamento,
					ProcessoFinanceiro.Filtro_TipoPessoa,
					ProcessoFinanceiro.Filtro_IdPaisEstadoCidade,
					ProcessoFinanceiro.Filtro_IdContrato,
					ProcessoFinanceiro.Filtro_IdAgenteAutorizado,
					ProcessoFinanceiro.Filtro_TipoCobranca,
					ProcessoFinanceiro.DataCriacao,
					ProcessoFinanceiro.LoginCriacao,
					ProcessoFinanceiro.DataAlteracao,
					ProcessoFinanceiro.LoginAlteracao,
					ProcessoFinanceiro.DataProcessamento,
					ProcessoFinanceiro.LoginProcessamento,
					ProcessoFinanceiro.DataConfirmacao,
					ProcessoFinanceiro.LoginConfirmacao,
					ProcessoFinanceiro.IdStatus,
					ProcessoFinanceiro.EmailEnviado,
					ProcessoFinanceiro.Filtro_IdStatusContrato,
					ProcessoFinanceiro.Filtro_FormaAvisoCobranca,
					ProcessoFinanceiro.Filtro_IdServico,
					ProcessoFinanceiro.Filtro_DiaCobranca,
					ProcessoFinanceiro.Filtro_IdGrupoPessoa,
					ProcessoFinanceiro.Filtro_IdTipoContrato,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.IdNotaFiscalTipo 
				from 
					ProcessoFinanceiro,
					LocalCobranca
				where
					ProcessoFinanceiro.IdLoja=$IdLoja and
					ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and 
					ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca						 
					$where $Limit";
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql5 = "select 
					count(*) Qtd
				from
					(select 
					LancamentoFinanceiro.IdLoja,
					LancamentoFinanceiro.IdProcessoFinanceiro
				from 
					LancamentoFinanceiro,
					LancamentoFinanceiroContaReceber
				where 
					LancamentoFinanceiro.IdLoja = $lin[IdLoja] and 
					LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
					LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
					LancamentoFinanceiro.IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]
				group by
					LancamentoFinanceiro.IdLoja,
					LancamentoFinanceiroContaReceber.IdContaReceber) ContaReceberQtd";
			$res5 = mysql_query($sql5,$con);
			$lin5 = mysql_fetch_array($res5);

			$QdtContasReceber = $lin5[Qtd];
		
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=29 and IdParametroSistema=$lin[IdStatus]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$sql3 = "select DescricaoLocalCobranca from LocalCobranca where IdLoja = $lin[IdLoja] and IdLocalCobranca = '$lin[Filtro_IdLocalCobranca]'";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			$sql4 = "select count(*) Qtd from LancamentoFinanceiro where IdLoja = $lin[IdLoja] and IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);	
			
			if($lin[IdStatus] == 3){
				$sql6	=	"select 
							 	 sum(Valor) ValorTotal,
							 	 LancamentoFinanceiroContaReceber.IdContaReceber
							 from 
								 LancamentoFinanceiro,
								 LancamentoFinanceiroContaReceber
							 where 
								 LancamentoFinanceiro.IdLoja = $lin[IdLoja] and 
								 LancamentoFinanceiro.IdLoja = LancamentoFinanceiroContaReceber.IdLoja and
								 LancamentoFinanceiro.IdLancamentoFinanceiro = LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro and
								 LancamentoFinanceiro.IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
				$res6	=	mysql_query($sql6,$con);
				$lin6	=	mysql_fetch_array($res6);		
			}
			
			if($lin6[ValorTotal] == "") $lin6[ValorTotal] = 0;
			
			$sql7 = "select 
						 count(*) QTDProcessoFinanceiroEnviado 
					from
						 HistoricoMensagem 
					where 
						IdLoja = $lin[IdLoja] and
						IdProcessoFinanceiro = $lin[IdProcessoFinanceiro]";
			$res7 = mysql_query($sql7,$con);
			$lin7 = mysql_fetch_array($res7);
			
			$Color	  = getCodigoInterno(19,$lin[IdStatus]);
			
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdProcessoFinanceiro>$lin[IdProcessoFinanceiro]</IdProcessoFinanceiro>";
			$dados	.=	"\n<MesReferencia><![CDATA[$lin[MesReferencia]]]></MesReferencia>";
			$dados	.=	"\n<MesVencimento><![CDATA[$lin[MesVencimento]]]></MesVencimento>";
			$dados	.=	"\n<Filtro_IdPessoa><![CDATA[$lin[Filtro_IdPessoa]]]></Filtro_IdPessoa>";
			$dados	.=	"\n<MenorVencimento><![CDATA[$lin[MenorVencimento]]]></MenorVencimento>";
			$dados	.=	"\n<DataNotaFiscal><![CDATA[$lin[DataNotaFiscal]]]></DataNotaFiscal>";
			$dados	.=	"\n<LogProcessamento><![CDATA[$lin[LogProcessamento]]]></LogProcessamento>";
			$dados	.=	"\n<QdtLancamentos><![CDATA[$lin4[Qtd]]]></QdtLancamentos>";
			$dados	.=	"\n<QdtContasReceber><![CDATA[$QdtContasReceber]]></QdtContasReceber>";
			$dados	.=	"\n<IdStatus>$lin[IdStatus]</IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<EmailEnviado><![CDATA[$lin[EmailEnviado]]]></EmailEnviado>";
			$dados	.=	"\n<Filtro_IdContrato><![CDATA[$lin[Filtro_IdContrato]]]></Filtro_IdContrato>";
			$dados	.=	"\n<IdTipoLocalCobranca><![CDATA[$lin3[IdTipoLocalCobranca]]]></IdTipoLocalCobranca>";
			$dados	.=	"\n<Filtro_IdLocalCobranca><![CDATA[$lin[Filtro_IdLocalCobranca]]]></Filtro_IdLocalCobranca>";
			$dados	.=	"\n<DescFiltro_IdLocalCobranca><![CDATA[$lin3[DescricaoLocalCobranca]]]></DescFiltro_IdLocalCobranca>";
			$dados	.=	"\n<Filtro_TipoLancamento><![CDATA[$lin[Filtro_TipoLancamento]]]></Filtro_TipoLancamento>";
			$dados	.=	"\n<Filtro_TipoPessoa><![CDATA[$lin[Filtro_TipoPessoa]]]></Filtro_TipoPessoa>";
			$dados	.=	"\n<Filtro_IdPaisEstadoCidade><![CDATA[$lin[Filtro_IdPaisEstadoCidade]]]></Filtro_IdPaisEstadoCidade>";
			$dados	.=	"\n<Filtro_IdStatusContrato><![CDATA[$lin[Filtro_IdStatusContrato]]]></Filtro_IdStatusContrato>";
			$dados	.=	"\n<Filtro_FormaAvisoCobranca><![CDATA[$lin[Filtro_FormaAvisoCobranca]]]></Filtro_FormaAvisoCobranca>";
			$dados	.=	"\n<Filtro_IdServico><![CDATA[$lin[Filtro_IdServico]]]></Filtro_IdServico>";
			$dados	.=	"\n<Filtro_IdAgenteAutorizado><![CDATA[$lin[Filtro_IdAgenteAutorizado]]]></Filtro_IdAgenteAutorizado>";	
			$dados	.=	"\n<Filtro_TipoCobranca><![CDATA[$lin[Filtro_TipoCobranca]]]></Filtro_TipoCobranca>";	
			$dados	.=	"\n<Filtro_IdTipoContrato><![CDATA[$lin[Filtro_IdTipoContrato]]]></Filtro_IdTipoContrato>";	
			$dados	.=	"\n<Filtro_VencimentoContrato><![CDATA[$lin[Filtro_DiaCobranca]]]></Filtro_VencimentoContrato>";	
			$dados	.=	"\n<Filtro_IdGrupoPessoa><![CDATA[$lin[Filtro_IdGrupoPessoa]]]></Filtro_IdGrupoPessoa>";										
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";	
			$dados	.=	"\n<ValorTotal><![CDATA[$lin6[ValorTotal]]]></ValorTotal>";
			$dados  .=  "\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";					
			$dados  .=  "\n<IdNotaFiscalTipo><![CDATA[$lin[IdNotaFiscalTipo]]]></IdNotaFiscalTipo>";					
			$dados	.=	"\n<QTDProcessoFinanceiroEnviado><![CDATA[$lin7[QTDProcessoFinanceiroEnviado]]]></QTDProcessoFinanceiroEnviado>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";
			$dados	.=	"\n<LoginConfirmacao><![CDATA[$lin[LoginConfirmacao]]]></LoginConfirmacao>";
			$dados	.=	"\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
			$dados	.=	"\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin6[IdContaReceber]]]></IdContaReceber>";
		}
			
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_processo_financeiro();
?>
