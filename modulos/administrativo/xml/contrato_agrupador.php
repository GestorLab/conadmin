<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Contrato_Agrupador(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdPessoa	 			= $_GET['IdPessoa'];
		$IdContratoAgrupador	= $_GET['IdContratoAgrupador'];
		$IdContrato				= $_GET['IdContrato'];
		$Nome		 			= $_GET['Nome'];
		$where					= "";
		
		if($Nome != ''){
			$where	.=	" and Servico.DescricaoServico like '$Nome%'";
		}
		if($IdContrato != ''){
			$where	.=	" and Contrato.IdContrato != $IdContrato";
		}
		if($IdContratoAgrupador != ''){
			$where	.=	" and Contrato.IdContrato = $IdContratoAgrupador";
		}
		/*
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
			$where .=  " and  Contrato.IdContrato = ContratoCarteira.IdContrato";
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
				$where .=  " and  Contrato.IdContrato = ContratoAgenteAutorizado.IdContrato";
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
		}*/
		$sqlAgente="select 
						Restringir
					from
						AgenteAutorizado 
					where 
						AgenteAutorizado.IdLoja = $IdLoja and
						AgenteAutorizado.IdAgenteAutorizado = $IdPessoaLogin and
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
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdContrato != ''){	
			$sql	=	"select
							IdContratoAgrupador
						from
							Contrato
						where
							IdLoja = $IdLoja and
							IdContratoAgrupador = $IdContrato";
			$res	=	mysql_query($sql,$con);
			
			if(mysql_num_rows($res) == 0){

				$sql = "select
							distinct Contrato.IdContratoAgrupador
						from 
							Contrato
						where 
							Contrato.IdLoja = $IdLoja and 
							Contrato.IdPessoa = $IdPessoa and
							Contrato.IdContratoAgrupador != ''";
				$res = mysql_query($sql,$con);
				while($lin = mysql_fetch_array($res)){
					$Agrupador[$lin[IdContratoAgrupador]] = ' ***';
				}
			}
		}

		$sql	=	"select
							ContratoAtivo.IdContrato,
							Servico.DescricaoServico,
							Contrato.DataInicio,
							Contrato.IdContratoAgrupador
						from 
							ContratoAtivo,
							Contrato,
							Servico,
							Pessoa $sqlAux
						where 
							ContratoAtivo.IdLoja = $IdLoja and 
							ContratoAtivo.IdLoja = Contrato.IdLoja and 
							ContratoAtivo.IdLoja = Servico.IdLoja and 
							ContratoAtivo.IdPessoa = $IdPessoa and
							ContratoAtivo.IdPessoa = Pessoa.IdPessoa and
							ContratoAtivo.IdContrato = Contrato.IdContrato and
							Contrato.IdServico = Servico.IdServico and
							Contrato.IdContratoAgrupador is null and
							Pessoa.AgruparContratos = '2' 
							$where";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin = mysql_fetch_array($res)){
			$dados	.=	"\n<IdContrato>$lin[IdContrato]</IdContrato>";
			$dados	.=	"\n<DescricaoContratoAgrupador><![CDATA[$lin[DescricaoServico]".$Agrupador[$lin[IdContrato]]."]]></DescricaoContratoAgrupador>";
			$dados	.=	"\n<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Contrato_Agrupador();
?>
