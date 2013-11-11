<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_contrato_status_lancamento_financeiro(){
		global $con;
		global $_GET;
		
		$IdLoja						= $_SESSION['IdLoja'];
		$IdContrato					= $_GET['IdContrato'];
		$IdStatus					= $_GET['IdStatus'];
		$where						= "";
		$sqlAux						= "";
		
		if($IdContrato != ''){
			$where .= " and LancamentoFinanceiro.IdContrato = $IdContrato";
		}
		
		if($IdStatus != ''){
			$where .= " and Demonstrativo.IdStatus in ($IdStatus)";
		}
		/*
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
		}*/
		$sqlAgente="select 
						Restringir
					from
						AgenteAutorizado 
					where 
						AgenteAutorizado.IdLoja = $IdLoja and
						AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and
						AgenteAutorizado.IdStatus = 1 ";
		$resAgente = mysql_query($sqlAgente,$con);
		if($linAgente = mysql_fetch_array($resAgente)){
			
			$sqlCarteira="	select 
								Restringir 
							from
								Carteira 
							where
								Carteira.IdLoja = $IdLoja and
								Carteira.IdCarteira = $IdPessoaLogin and
								Carteira.IdStatus = 1";
			$resCarteira = mysql_query($sqlCarteira,$con);
			$linCarteira = mysql_fetch_array($resCarteira);
			
			if($linAgente["Restringir"] == '1'){
				$restringirAgente = "AgenteAutorizado.Restringir = '$linAgente[Restringir]' and";
				if($linCarteira["Restringir"] == '1'){
					$restringirCarteira = "AgenteAutorizadoPessoa.IdCarteira = $IdPessoaLogin and";
				}else{
					$restringirCarteira = "";
				}
				$sqlAux		.=	",(select 
										AgenteAutorizadoPessoa.IdContrato
									from 
										AgenteAutorizadoPessoa,
										AgenteAutorizado,
										Carteira
									where 
										AgenteAutorizadoPessoa.IdLoja = $IdLoja  and 
										AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
										AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
										AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and 
										$restringirAgente
										$restringirCarteira
										AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and 
										AgenteAutorizado.IdStatus = 1
									) ContratoAgenteAutorizado";
				$where .=  " and Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
				//$filtro_cancelado = '';
			}
		}
		
		$cont	=	0;	
		$total	=	0;
		$sql	=	"select
						Demonstrativo.IdLoja,
						Demonstrativo.IdContaReceber,
						Demonstrativo.IdLancamentoFinanceiro,
						Demonstrativo.IdProcessoFinanceiro,
						Demonstrativo.IdPessoa,
						Pessoa.TipoPessoa,
						substr(Pessoa.Nome,1,15) Nome,
						substr(Pessoa.RazaoSocial,1,15) RazaoSocial,
						Demonstrativo.Tipo,
						Demonstrativo.Codigo,
						substr(Demonstrativo.Descricao,1,15) Descricao,
						Demonstrativo.Referencia,
						Demonstrativo.Valor,
						Demonstrativo.ValorDespesas,
						Demonstrativo.ValorDescontoAConceber,
						Demonstrativo.IdStatus
					from
						Demonstrativo,
						Pessoa left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						) ,
						LancamentoFinanceiro
					where
						Demonstrativo.IdLoja = $IdLoja and
						Demonstrativo.IdLoja = LancamentoFinanceiro.IdLoja and
						Demonstrativo.IdPessoa = Pessoa.IdPessoa and
						Demonstrativo.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro $where
					order by
						Demonstrativo.IdPessoa,Tipo,Codigo,IdLancamentoFinanceiro;";
		$res	=	mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$query = "true";
		
			if($lin[IdContrato]!=""){
				if($_SESSION["RestringirCarteira"] == true){
					$sqlTemp =	"select 
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
									Carteira.IdStatus = 1 and
									AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
					$resTemp	=	@mysql_query($sqlTemp,$con);
					if(@mysql_num_rows($resTemp) == 0){
						$query = 'false';
					}
				} else{
					if($_SESSION["RestringirAgenteAutorizado"] == true){
						$sqlTemp =	"select 
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
										AgenteAutorizado.IdStatus = 1 and
										AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					
					if($_SESSION["RestringirAgenteCarteira"] == true){
						$sqlTemp		.=	"select 
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
												AgenteAutorizado.IdStatus = 1 and
												AgenteAutorizadoPessoa.IdContrato = $lin[IdContrato]";
						$resTemp	=	@mysql_query($sqlTemp,$con);
						if(@mysql_num_rows($resTemp) == 0){
							$query = 'false';
						}
					}
					
				}
			}
			
			if($query == 'true'){
				$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=51 and IdParametroSistema=$lin[IdStatus]";
				$res3 = @mysql_query($sql3,$con);
				$lin3 = @mysql_fetch_array($res3);
				
				if($lin[TipoPessoa]=='1'){
					$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
				}
				
				if($cont == 0){
					header ("content-type: text/xml");
					$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
					$dados	.=	"\n<reg>";
				}
				
				$total	+=	$lin[Valor];
				
				$dados	.= 	"\n<IdLancamentoFinanceiro>$lin[IdLancamentoFinanceiro]</IdLancamentoFinanceiro>";
				$dados	.= 	"\n<Tipo><![CDATA[$lin[Tipo]]]></Tipo>";
				$dados	.= 	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";	
				$dados	.= 	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
				$dados	.= 	"\n<IdProcessoFinanceiro><![CDATA[$lin[IdProcessoFinanceiro]]]></IdProcessoFinanceiro>";
				$dados	.= 	"\n<IdContaReceber><![CDATA[$lin[IdContaReceber]]]></IdContaReceber>";
				$dados	.= 	"\n<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
				$dados	.=	"\n<Codigo><![CDATA[$lin[Codigo]]]></Codigo>";
				$dados	.=	"\n<Referencia><![CDATA[$lin[Referencia]]]></Referencia>";			
				$dados	.= 	"\n<Valor><![CDATA[$lin[Valor]]]></Valor>";
				$dados	.= 	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados	.= 	"\n<ValorDescontoAConceber><![CDATA[$lin[ValorDescontoAConceber]]]></ValorDescontoAConceber>";
				$dados	.= 	"\n<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
				
				$cont++;
				
				if($Limit!= ""){
					if($cont >= $Limit){
						break;
					}
				}
			}
		}
		
		if($cont >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	echo get_contrato_status_lancamento_financeiro();
?>