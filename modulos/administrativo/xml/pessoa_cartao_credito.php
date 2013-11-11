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
		$IdCartaoCredito		= $_GET['IdCartaoCredito'];
		$IdPessoa 				= $_GET['IdPessoa'];
		$IdContaReceber			= $_GET['IdContaReceber'];
		$Cadastro				= $_GET['Cadastro'];
		$numeroTitulos			= 0;
		$where					= "";
		$Auxfrox				= "";
		
		if($Limit != ''){
			$Limit = " LIMIT 0, $Limit";
		}
		
		if($IdCartaoCredito != ''){
			$where .= " AND PessoaCartao.IdCartao = $IdCartaoCredito";
		}
		if($IdPessoa != ''){
			if($Cadastro == true){
				$where .= "	AND PessoaCartao.IdPessoa = $IdPessoa";
			}else{
				$where .= "	AND PessoaCartao.IdStatus != 2
							AND PessoaCartao.IdPessoa = $IdPessoa";
			}
		}
		if($IdContaReceber != ""){
			$Auxfrox = ",ContaReceberPosicaoCobranca";
			$Where = "";
		}
		
		$sql = "select
					IdLoja,
					IdPessoa,
					IdCartao,
					IdBandeira,
					NomeTitular,
					NumeroCartao,
					Validade,
					CodigoSeguranca,
					DiaVencimentoFatura,
					IdStatus,
					LoginCriacao,
					DataCriacao,
					LoginAlteracao,
					DataAlteracao
				from 
					PessoaCartao
				where 
					PessoaCartao.IdLoja = '$IdLoja'
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
		
		$sql2 = "Select
					count(IdContaReceber) NumeroTitulos
				from
					ContaReceberPosicaoCobranca
				where
					IdLoja	 = '$IdLoja' and
					(IdPessoa = '$IdPessoa' and IdPessoa is not NULL) and
					(IdCartao = '$IdCartaoCredito' and IdCartao is not NULL)";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		if(mysql_num_rows($res2) > 0){
			$numeroTitulos = $lin2[NumeroTitulos]; 
		}
					
		while($lin = @mysql_fetch_array($res)){
			$sql3 = "select 
						ValorLocalCobrancaParametroDefault
					from
						LocalCobrancaLayoutParametro 
					where
						IdLocalCobrancaLayout = $lin[IdLocalCobrancaLayout] and
						DescricaoLocalCobrancaParametro = 'VisivelDigitoAgencia'";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
		
		
			if($lin[IdStatus] != ''){
				$lin[Status] = getParametroSistema(268, $lin[IdStatus]);
			}
			
			$NumeroCartaoMascarado = substr($lin[NumeroCartao],0,4)." **** **** ".substr($lin[NumeroCartao],15,19);
			
			$dados	.=	"\n<IdPessoa>$lin[IdPessoa]</IdPessoa>";
			$dados	.=	"\n<IdCartao>$lin[IdCartao]</IdCartao>";
			$dados	.=	"\n<IdBandeira><![CDATA[$lin[IdBandeira]]]></IdBandeira>";
			$dados	.=	"\n<NomeTitular><![CDATA[$lin[NomeTitular]]]></NomeTitular>";
			$dados	.=	"\n<NumeroCartao><![CDATA[$lin[NumeroCartao]]]></NumeroCartao>";
			$dados	.=	"\n<NumeroCartaoMascarado><![CDATA[$NumeroCartaoMascarado]]></NumeroCartaoMascarado>";
			$dados	.=	"\n<Validade><![CDATA[$lin[Validade]]]></Validade>";
			$dados	.=	"\n<CodigoSeguranca><![CDATA[$lin[CodigoSeguranca]]]></CodigoSeguranca>";
			$dados	.=	"\n<DiaVencimentoFatura><![CDATA[$lin[DiaVencimentoFatura]]]></DiaVencimentoFatura>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<NumeroTitulo><![CDATA[$numeroTitulos]]></NumeroTitulo>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";

		}
		
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_PessoaContaDebito();
?>