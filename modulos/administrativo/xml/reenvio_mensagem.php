<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Mensagem(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION["IdPessoa"];
		$where					= "";
		$sqlAux					= "";
		$IdHistoricoMensagem	= $_GET['IdHistoricoMensagem'];		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		/*
		if($_GET['IdHistoricoMensagem'] != ''){		
			$where .= " and HistoricoMensagem.IdHistoricoMensagem = '$IdHistoricoMensagem'";
		}*/
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		$sql2 = "select 
					HistoricoMensagem.IdTipoMensagem, 
					HistoricoMensagem.IdPessoa 
				from
					HistoricoMensagem,
					MalaDireta 
				where
					HistoricoMensagem.IdLoja = $IdLoja and
					HistoricoMensagem.IdLoja = MalaDireta.IdLoja and
					HistoricoMensagem.IdHistoricoMensagem = '$IdHistoricoMensagem'
				group by
					IdHistoricoMensagem";
		$res2 =	@mysql_query($sql2,$con);
		$lin2 =	@mysql_fetch_array($res2);
		if($lin2[IdTipoMensagem] >= 1000000){
			$from ="left join MalaDireta 
					on (
					  TipoMensagem.IdTipoMensagem = MalaDireta.IdTipoMensagem and
					  MalaDireta.IdLoja = '$IdLoja' and
					  TipoMensagem.IdLoja = MalaDireta.IdLoja 
					)";
			if($lin2[IdPessoa] != ''){
				$filtro_sql_id_pessoa = " and HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			}else{
				$filtro_sql_id_pessoa = "";
			}
			$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
			$order_group		 =	" group by HistoricoMensagem.IdHistoricoMensagem";
		}else{
			$filtro_sql_id_pessoa = " and HistoricoMensagem.IdPessoa = Pessoa.IdPessoa";
			$where				 .=	" and HistoricoMensagem.IdHistoricoMensagem = $IdHistoricoMensagem";
			$order_group		 = 	"";
		}
		
		$sql	=	"select
						HistoricoMensagem.IdLoja,
						HistoricoMensagem.IdPessoa,
						HistoricoMensagem.IdOrdemServico,
						HistoricoMensagem.Email,
						HistoricoMensagem.Celular,
						HistoricoMensagem.Assunto,
						HistoricoMensagem.Obs,
						HistoricoMensagem.Conteudo,
						HistoricoMensagem.DataEnvio,
						HistoricoMensagem.LoginCriacao,
						HistoricoMensagem.DataCriacao,
						HistoricoMensagem.IdStatus,
						HistoricoMensagem.IdProcessoFinanceiro,
						HistoricoMensagem.IdContaReceber,
						HistoricoMensagem.IdHistoricoMensagem,
						HistoricoMensagem.IdReEnvio,
						HistoricoMensagem.DataLeitura,
						HistoricoMensagem.IPLeitura,
						HistoricoMensagem.QtdTentativaEnvio,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						TipoMensagem.IdTipoMensagem,
						TipoMensagem.LimiteEnvioDiario
					from
						HistoricoMensagem,
						Pessoa left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						) ,
						TipoMensagem
						$from
					where
						HistoricoMensagem.IdLoja = $IdLoja and
						HistoricoMensagem.IdLoja = TipoMensagem.IdLoja and	
						HistoricoMensagem.IdTipoMensagem = TipoMensagem.IdTipoMensagem
						$filtro_sql_id_pessoa
						$where
						$order_group
						$Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[RazaoSocial]!='')	$lin[Nome]	=	$lin[RazaoSocial];
			if($lin[Email] =='NULL')		$lin[Email] =	'';
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdHistoricoMensagem>$lin[IdHistoricoMensagem]</IdHistoricoMensagem>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdOrdemServico><![CDATA[$lin[IdOrdemServico]]]></IdOrdemServico>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
			$dados	.=	"\n<IdReEnvio><![CDATA[$lin[IdReEnvio]]]></IdReEnvio>";
			$dados	.=	"\n<Assunto><![CDATA[$lin[Assunto]]]></Assunto>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<Mensagem><![CDATA[$lin[Conteudo]]]></Mensagem>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<Celular><![CDATA[$lin[Celular]]]></Celular>";
			$dados	.=	"\n<DataLeitura><![CDATA[$lin[DataLeitura]]]></DataLeitura>";
			$dados	.=	"\n<IPLeitura><![CDATA[$lin[IPLeitura]]]></IPLeitura>";
			$dados	.=	"\n<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
			$dados	.=	"\n<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<QtdTentativaEnvio><![CDATA[$lin[QtdTentativaEnvio]]]></QtdTentativaEnvio>";
			$dados	.=	"\n<LimiteEnvioDiario><![CDATA[$lin[LimiteEnvioDiario]]]></LimiteEnvioDiario>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Mensagem();
?>
