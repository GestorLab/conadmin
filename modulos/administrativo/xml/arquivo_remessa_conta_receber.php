<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Conta_Receber(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdLoja		 					= $_SESSION["IdLoja"];
		$IdPessoaLogin					= $_SESSION['IdPessoa'];
		$IdLocalCobranca				= $_GET['IdLocalCobranca'];
		$IdArquivoRemessa				= $_GET['IdArquivoRemessa'];
		
		$where							= "";
		$sqlAux							= "";
		
		if($IdLocalCobranca != ''){				$where .= " and ContaReceberDados.IdLocalCobranca=$IdLocalCobranca";	}
		if($IdArquivoRemessa != ''){			$where .= " and ContaReceberDados.IdArquivoRemessa=$IdArquivoRemessa";	}
				
		$cont	=	0;	
		$sql	=	"SELECT
						ContaReceber.IdLoja,
						ContaReceber.IdContaReceber,
						ContaReceber.DataVencimento,
						ContaReceber.DataLancamento,
						ContaReceber.ValorLancamento,
						ContaReceber.NumeroNF,
						ContaReceber.NumeroDocumento,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						ContaReceberPosicaoCobranca.IdPosicaoCobranca,
						ContaReceberPosicaoCobranca.DataRemessa,
						PosicaoCobranca.DescricaoPosicaoCobranca
					FROM
						ContaReceber,
						Pessoa,
						ContaReceberPosicaoCobranca,
						ArquivoRemessa,
						(SELECT 
							IdParametroSistema IdPosicaoCobranca, 
							ValorParametroSistema DescricaoPosicaoCobranca
						FROM
							ParametroSistema
						WHERE
							IdGrupoParametroSistema = 81)PosicaoCobranca,
						LocalCobranca
					WHERE					
						(
							(
								LocalCobranca.IdLoja = $IdLoja and
								LocalCobranca.IdLocalCobranca = $IdLocalCobranca
							) or (
								LocalCobranca.IdLojaCobrancaUnificada = $IdLoja and
								LocalCobranca.IdLocalCobrancaUnificada = $IdLocalCobranca
							)
						) and						

						ArquivoRemessa.IdArquivoRemessa = $IdArquivoRemessa AND
						
						ContaReceber.IdLoja = ContaReceberPosicaoCobranca.IdLoja AND
						ContaReceber.IdContaReceber = ContaReceberPosicaoCobranca.IdContaReceber AND
						
						ContaReceber.IdLoja = LocalCobranca.IdLoja AND
						ContaReceber.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND

						ContaReceber.IdLojaRemessa = ArquivoRemessa.IdLoja AND
						ContaReceber.IdLocalCobrancaRemessa = ArquivoRemessa.IdLocalCobranca AND
						ContaReceber.IdArquivoRemessa = ArquivoRemessa.IdArquivoRemessa AND

						(
							(ArquivoRemessa.IdStatus = 2 AND ContaReceber.IdArquivoRemessa = ArquivoRemessa.IdArquivoRemessa AND ContaReceberPosicaoCobranca.DataRemessa = '0000-00-00') 
							OR 
							(ArquivoRemessa.IdStatus = 3 AND ContaReceberPosicaoCobranca.IdArquivoRemessa = ArquivoRemessa.IdArquivoRemessa)							
							OR (
							  ArquivoRemessa.IdStatus = 4 
							  AND ContaReceberPosicaoCobranca.IdArquivoRemessa = ArquivoRemessa.IdArquivoRemessa
							)
						) AND
						ContaReceber.IdPessoa = Pessoa.IdPessoa AND
						ContaReceberPosicaoCobranca.IdPosicaoCobranca = PosicaoCobranca.IdPosicaoCobranca";
		$res	=	@mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}

		while($lin	=	@mysql_fetch_array($res)){
		
			if($lin[IdPosicaoCobranca] != ''){
				$lin[PosicaoCobrancaDescricao] = getParametroSistema(81,$lin[IdPosicaoCobranca]);
			}

			if(($lin[IdPosicaoCobranca] == 1 || $lin[IdPosicaoCobranca] == 6 || $lin[IdPosicaoCobranca] == 9) && str_replace("-","",$lin[DataVencimento]) <= date("Ymd")){
				$lin[BackgroundColor] = "#FF8080";
			}
											
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdContaReceber>$lin[IdContaReceber]</IdContaReceber>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<NumeroDocumento><![CDATA[$lin[NumeroDocumento]]]></NumeroDocumento>";
			$dados	.=	"\n<DataLancamento><![CDATA[$lin[DataLancamento]]]></DataLancamento>";
			$dados	.=	"\n<DataVencimento><![CDATA[$lin[DataVencimento]]]></DataVencimento>";
			$dados	.=	"\n<Valor><![CDATA[$lin[ValorLancamento]]]></Valor>";
			$dados	.=	"\n<BackgroundColor><![CDATA[$lin[BackgroundColor]]]></BackgroundColor>";
			$dados	.=	"\n<PosicaoCobrancaDescricao><![CDATA[$lin[DescricaoPosicaoCobranca]]]></PosicaoCobrancaDescricao>";
			$dados	.=	"\n<NumeroNF><![CDATA[$lin[NumeroNF]]]></NumeroNF>";
		
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Conta_Receber();
?>
