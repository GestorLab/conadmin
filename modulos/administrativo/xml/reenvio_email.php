<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Email(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION["IdPessoa"];
		$where					= "";
		$sqlAux					= "";
				
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdEmail'] != ''){		
			$where .= " and HistoricoEmail.IdEmail = ".$_GET['IdEmail'];
		}
		
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
		
		$sql	=	"SELECT  
						 HistoricoEmail.IdLoja,
					     HistoricoEmail.IdPessoa,
					     Pessoa.Nome,
					     Pessoa.RazaoSocial,
					     HistoricoEmail.IdContaReceber,
					     HistoricoEmail.IdEmail,
					     HistoricoEmail.Email,
					     HistoricoEmail.DataEnvio,
					     HistoricoEmail.IdTipoEmail,
					     HistoricoEmail.IdProcessoFinanceiro,
					     HistoricoEmail.IdEmailReEnvio
					from
					     HistoricoEmail,
					     Pessoa left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						)  
						 $sqlAux
					where
					     HistoricoEmail.IdLoja = $IdLoja and
					     HistoricoEmail.IdPessoa = Pessoa.IdPessoa 
						 $where 
						 $Limit";
		$res	=	mysql_query($sql,$con);
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
		
			$dados	.=	"\n<IdLoja>$lin[IdLoja]</IdLoja>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
			$dados	.=	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
			$dados	.=	"\n<IdEmail><![CDATA[$lin[IdEmail]]]></IdEmail>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<IdTipoEmail><![CDATA[$lin[IdTipoEmail]]]></IdTipoEmail>";
			$dados	.=	"\n<IdEmailReEnvio><![CDATA[$lin[IdEmailReEnvio]]]></IdEmailReEnvio>";
			$dados	.=	"\n<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Email();
?>
