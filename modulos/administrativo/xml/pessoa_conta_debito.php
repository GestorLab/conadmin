<?
	$localModulo	=	0;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_PessoaContaDebito(){
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja	 				= $_SESSION['IdLoja'];
		$IdContaDebito 			= $_GET['IdContaDebito'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$IdLocalCobranca		= $_GET['IdLocalCobranca'];
		$where					= "";
		
		if($Limit != ''){
			$Limit = " LIMIT 0, $Limit";
		}
		
		if($IdContaDebito != ''){
			$where .= " AND PessoaContaDebito.IdContaDebito = $IdContaDebito";
		}
		
		if($IdPessoa != ''){
			$where .= " AND PessoaContaDebito.IdPessoa = $IdPessoa";
		}
		
		if($IdLocalCobranca != ''){
			$where .= " AND PessoaContaDebito.IdLocalCobranca = $IdLocalCobranca";
		}
		
		$sql = "select
					PessoaContaDebito.IdPessoa,
					PessoaContaDebito.IdContaDebito,
					PessoaContaDebito.DescricaoContaDebito,
					PessoaContaDebito.IdLocalCobranca,
					PessoaContaDebito.NumeroAgencia,
					PessoaContaDebito.DigitoAgencia,
					PessoaContaDebito.NumeroConta,
					PessoaContaDebito.DigitoConta,
					PessoaContaDebito.Obs,
					PessoaContaDebito.IdStatus,
					PessoaContaDebito.LoginCriacao,
					PessoaContaDebito.DataCriacao,
					PessoaContaDebito.LoginAlteracao,
					PessoaContaDebito.DataAlteracao,
					LocalCobranca.DescricaoLocalCobranca,
					LocalCobranca.AbreviacaoNomeLocalCobranca,
					LocalCobranca.IdLocalCobrancaLayout
				from 
					PessoaContaDebito,
					LocalCobranca
				where 
					PessoaContaDebito.IdLoja = '$IdLoja' and 
					PessoaContaDebito.IdLocalCobranca = LocalCobranca.IdLocalCobranca and 
					PessoaContaDebito.IdLoja = LocalCobranca.IdLoja
					$where
					$Limit;";
		$res = @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		} else{
			return "false";
		}
		while($lin = @mysql_fetch_array($res)){
			$sql2 = "select 
						ValorLocalCobrancaParametroDefault
					from
						LocalCobrancaLayoutParametro 
					where
						IdLocalCobrancaLayout = $lin[IdLocalCobrancaLayout] and
						DescricaoLocalCobrancaParametro = 'VisivelDigitoAgencia'";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$sqlContaReceber = "SELECT
									COUNT(IdContaReceber) QuantidadeTitulo
								FROM
									ContaReceber
								WHERE
									IdPessoa = $IdPessoa AND
									IdContaDebito = $IdContaDebito";
			$resContaReceber = @mysql_query($sqlContaReceber,$con);
			$linContaReceber = @mysql_fetch_array($resContaReceber);
			
			if($lin[IdStatus] != ''){
				$lin[Status] = getParametroSistema(188, $lin[IdStatus]);
			}
			
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<IdContaDebito><![CDATA[$lin[IdContaDebito]]]></IdContaDebito>";
			$dados	.=	"\n<DescricaoContaDebito><![CDATA[$lin[DescricaoContaDebito]]]></DescricaoContaDebito>";
			$dados	.=	"\n<IdLocalCobranca><![CDATA[$lin[IdLocalCobranca]]]></IdLocalCobranca>";
			$dados	.=	"\n<IdLocalCobrancaLayout><![CDATA[$lin[IdLocalCobrancaLayout]]]></IdLocalCobrancaLayout>";
			$dados	.=	"\n<DescricaoLocalCobranca><![CDATA[$lin[DescricaoLocalCobranca]]]></DescricaoLocalCobranca>";
			$dados	.=	"\n<AbreviacaoNomeLocalCobranca><![CDATA[$lin[AbreviacaoNomeLocalCobranca]]]></AbreviacaoNomeLocalCobranca>";
			$dados	.=	"\n<NumeroAgencia><![CDATA[$lin[NumeroAgencia]]]></NumeroAgencia>";
			$dados	.=	"\n<DigitoAgencia><![CDATA[$lin[DigitoAgencia]]]></DigitoAgencia>";
			$dados	.=	"\n<NumeroConta><![CDATA[$lin[NumeroConta]]]></NumeroConta>";
			$dados	.=	"\n<DigitoConta><![CDATA[$lin[DigitoConta]]]></DigitoConta>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<ValorLocalCobrancaParametroDefault><![CDATA[$lin2[ValorLocalCobrancaParametroDefault]]]></ValorLocalCobrancaParametroDefault>";
			$dados	.=	"\n<QuantidadeTitulo><![CDATA[$linContaReceber[QuantidadeTitulo]]]></QuantidadeTitulo>";
		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_PessoaContaDebito();
?>