<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_lancamento_financeiro(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdProcessoFinanceiro	= $_GET['IdProcessoFinanceiro'];
		$where					= "";
		$sqlAux					= "";
		
		if($_SESSION["RestringirCarteira"] == true){
			$sqlAux		.=	",(select 
									AgenteAutorizadoPessoa.IdContrato 
							   from 
									AgenteAutorizadoPessoa,
									Carteira 
							   where 
									AgenteAutorizadoPessoa.IdLoja = $IdLoja and 
									AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
									AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
									Carteira.IdCarteira = $IdPessoaLogin and 
									Carteira.Restringir = 1 and 
									Carteira.IdStatus = 1
								) ContratoCarteira";
			$where .=  " and  LancamentoFinanceiro.IdContrato = ContratoCarteira.IdContrato";
		}else{
			if($_SESSION["RestringirAgenteAutorizado"] == true){
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
										AgenteAutorizado.Restringir = 1 and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteAutorizado";
				$where .=  " and  LancamentoFinanceiro.IdContrato = ContratoAgenteAutorizado.IdContrato";
			}
			if($_SESSION["RestringirAgenteCarteira"] == true){
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado,
										Carteira
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
										AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
										Carteira.IdCarteira = $IdPessoaLogin and 
										AgenteAutorizado.Restringir = 1 and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteCarteira";
				$where .=  " and  Contrato.IdContrato = ContratoAgenteCarteira.IdContrato";
			}
		}
		
		$sql	=	"select
						LancamentoFinanceiro.IdContrato,
						LancamentoFinanceiro.IdProcessoFinanceiro,
						Servico.DescricaoServico,
						LancamentoFinanceiro.IdLancamentoFinanceiro, 
						substr(Pessoa.Nome,1,30) Nome,
						substr(Pessoa.Nome,1,30) RazaoSocial,
						LancamentoFinanceiro.DataReferenciaInicial,
						LancamentoFinanceiro.DataReferenciaFinal,					
						LancamentoFinanceiro.Valor,
						LancamentoFinanceiro.IdStatus
					from
						LancamentoFinanceiro,
						Contrato,
						Servico,
						Pessoa $sqlAux
					where
						LancamentoFinanceiro.IdLoja = $IdLoja and
						LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
						LancamentoFinanceiro.IdLoja = Servico.IdLoja and
						LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and
						LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
						Contrato.IdServico = Servico.IdServico and
						Contrato.IdPessoa = Pessoa.IdPessoa
					order by
						Pessoa.Nome,
						Pessoa.IdPessoa,
						LancamentoFinanceiro.IdLancamentoFinanceiro";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		$total = 0;
		while($lin	=	@mysql_fetch_array($res)){
			
			$total	+=	$lin[Valor];
			
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdContrato><![CDATA[$lin[IdContrato]]]></IdContrato>";
			$dados	.=	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
			$dados	.=	"\n<DataReferenciaInicial><![CDATA[$lin[DataReferenciaInicial]]]></DataReferenciaInicial>";
			$dados	.=	"\n<DataReferenciaFinal><![CDATA[$lin[DataReferenciaFinal]]]></DataReferenciaFinal>";
			$dados	.=	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
			$dados	.=	"\n<TotalValor><![CDATA[$total]]></TotalValor>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";

		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_lancamento_financeiro();
?>
