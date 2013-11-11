<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_carne(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdAgenteAutorizado			= $_SESSION['IdAgenteAutorizado'];
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$IdContaReceber 			= $_GET['IdContaReceber'];
		$IdCarne					= $_GET['IdCarne'];
		$where						= "";
		
		if($IdContaReceber != ''){
			$where .= " and Demonstrativo.IdContaReceber=$IdContaReceber";	
		}
		
		if($IdCarne != ''){
			$where .= " and ContaReceber.IdCarne=$IdCarne";	
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
		
		$sql = "select
					ContaReceber.IdCarne,
					Demonstrativo.IdPessoa,
					Demonstrativo.Tipo,
					Demonstrativo.IdContaReceber
				from
					Demonstrativo,
					ContaReceber,
					Pessoa left join (
						PessoaGrupoPessoa, 
						GrupoPessoa
					) on (
						Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
						PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
						PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
						PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
					) 
				where
					Demonstrativo.IdLoja = $IdLoja and
					Demonstrativo.IdLoja = ContaReceber.IdLoja	and
					Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber and
					Demonstrativo.IdPessoa = Pessoa.IdPessoa and
					ContaReceber.IdCarne is not null	$where
				group by
					ContaReceber.IdCarne	
				order by
					ContaReceber.DataLancamento DESC"; 
		$res = mysql_query($sql,$con);
		if(@mysql_num_rows($res) > 0){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$LancamentoFinanceiroTipoContrato = '';
				$sql0 = "select
							Demonstrativo.IdLancamentoFinanceiro
						from
							Demonstrativo,
							ContaReceber,
							ContratoAutomatico
						where
							Demonstrativo.IdLoja = $IdLoja and
							Demonstrativo.IdContaReceber = ContaReceber.IdContaReceber and
							Demonstrativo.Tipo = 'CO' and
							ContaReceber.IdCarne = $lin[IdCarne]  and
							ContratoAutomatico.IdLoja = Demonstrativo.IdLoja and
							ContratoAutomatico.IdContrato = Demonstrativo.Codigo
						order by
							Demonstrativo.Codigo,
							Demonstrativo.DataReferenciaInicial,
							Demonstrativo.IdLancamentoFinanceiro;";
				$res0 = mysql_query($sql0,$con);
				
				while($lin0 = @mysql_fetch_array($res0)){
					if($LancamentoFinanceiroTipoContrato != ''){
						$LancamentoFinanceiroTipoContrato .= ',';
					}
					
					$LancamentoFinanceiroTipoContrato .= $lin0[IdLancamentoFinanceiro];
				}
				
				$dados	.=	"\n<IdCarne>$lin[IdCarne]</IdCarne>";
				$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
				$dados	.=	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.=	"\n<LancamentoFinanceiroTipoContrato><![CDATA[$LancamentoFinanceiroTipoContrato]]></LancamentoFinanceiroTipoContrato>";
			}
			
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_carne();
?>