<?php
	$localModulo		=	1;
	$localOperacao		=	62;
	$localSuboperacao	=	"V";
	$localCadComum		=	true;
	
	include ("../../../files/conecta.php");
	include ("../../../files/funcoes.php");
	include ('../../../rotinas/verifica.php');
	include ("../../../classes/jpgraph/src/jpgraph.php");
	include ("../../../classes/jpgraph/src/jpgraph_pie.php");
	include ("../../../classes/jpgraph/src/jpgraph_pie3d.php");
	
	clearstatcache();
	
	$IdLoja				= $_SESSION['IdLoja'];
	$IdPessoaLogin		= $_SESSION['IdPessoa'];
	$IdServico			= $_GET['IdServico'];
	$IdServicoGrupo		= $_GET['IdServicoGrupo'];
	$Data				= $_GET['Data'];
	$filtro_pais		= $_GET['IdPais'];
	$filtro_estado		= $_GET['IdEstado'];
	$filtro_cidade		= $_GET['IdCidade'];
	$where				= "";
	
	if($IdServico != '') {
		$where .= " and Servico.IdServico = $IdServico";
	}
	
	if($IdServicoGrupo != '') {
		$where .= " and Servico.IdServicoGrupo = $IdServicoGrupo";
	}
	
	if($filtro_pais!=""){
		$filtro_url  .= "&IdPais=".$filtro_pais;
		$where .= " and PessoaEndereco.IdPais = $filtro_pais";
	}
	
	if($filtro_estado!=""){
		$filtro_url  .= "&IdEstado=".$filtro_estado;
		$where .= " and PessoaEndereco.IdEstado = $filtro_estado";
	}
	
	if($filtro_cidade!=""){
		$filtro_url  .= "&IdCidade=".$filtro_cidade;
		$where .= " and PessoaEndereco.IdCidade = $filtro_cidade";
	}
	
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
								) PessoaAgenteAutorizado";
			$where .=  " and Contrato.IdContrato = PessoaAgenteAutorizado.IdContrato";
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
	
	if($Data != ""){
	
		$IdTitulo = 867;
		$StringTitulo = " ".$Data;
		$Data = dataConv($Data,"d/m/Y","Y-m-d");
		
		$sql="	SELECT
					IdStatus,
					COUNT(*) Qtd
				FROM
					(
						SELECT
							IF(
								ContratoStatusTemp.IdStatus IS NULL,
								Contrato.IdStatus,
								ContratoStatusTemp.IdStatus
							) IdStatus
						FROM
							Servico,
							Contrato LEFT JOIN (
								SELECT
									ContratoStatusUltimo.IdLoja,
									ContratoStatusUltimo.IdContrato,
									ContratoStatus.IdStatus
								FROM
									(
										SELECT
											IdLoja,
											IdContrato,
											MAX(IdMudancaStatus) IdMudancaStatus
										FROM
											ContratoStatus
										WHERE
											IdLoja =  $IdLoja and
											DataAlteracao <= '$Data'
										GROUP BY
											IdLoja,
											IdContrato
									) ContratoStatusUltimo,
									ContratoStatus
								WHERE
									ContratoStatusUltimo.IdLoja =  $IdLoja and
									ContratoStatusUltimo.IdLoja = ContratoStatus.IdLoja AND
									ContratoStatusUltimo.IdContrato = ContratoStatus.IdContrato AND
									ContratoStatusUltimo.IdMudancaStatus = ContratoStatus.IdMudancaStatus
							) ContratoStatusTemp ON Contrato.IdLoja = ContratoStatusTemp.IdLoja AND
								Contrato.IdContrato = ContratoStatusTemp.IdContrato,
								Pessoa,
								PessoaEndereco,
								Pais,
								Cidade,
								Estado
							$sqlAux
						WHERE
							Contrato.IdLoja =  $IdLoja and
							Contrato.DataCriacao <= '$Data' and
							Contrato.IdLoja = Servico.IdLoja and
							Contrato.IdServico = Servico.IdServico and
							PessoaEndereco.IdPessoa = Pessoa.IdPessoa and
							PessoaEndereco.IdPessoaEndereco = Contrato.IdPessoaEndereco and
							Pais.IdPais = PessoaEndereco.IdPais and
							Estado.IdEstado = PessoaEndereco.IdEstado and
							Cidade.IdCidade = PessoaEndereco.IdCidade and
							Pais.IdPais = Estado.IdPais and
							Pais.IdPais = Cidade.IdPais and
							Estado.IdEstado = Cidade.IdEstado and
							Contrato.IdPessoa = Pessoa.IdPessoa and
							Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco 
							$where
					) ContratoStatusQtd
				WHERE
					IdStatus > 0
				GROUP BY
					IdStatus
				ORDER BY							
						IdStatus";
	}else{	
		$IdTitulo = 368;
		$sql	=	"select
						Contrato.IdStatus,
						count(*) Qtd
					from
						Contrato,
						Servico,
						Pessoa,
						PessoaEndereco,
						Pais,
						Cidade,
						Estado 
						$sqlAux
					where
						Contrato.IdLoja = $local_IdLoja AND 
						Contrato.IdStatus >= 200 AND 
						Contrato.IdLoja = Servico.IdLoja AND 
						Contrato.IdServico = Servico.IdServico AND
						PessoaEndereco.IdPessoa = Pessoa.IdPessoa AND
						PessoaEndereco.IdPessoaEndereco = Contrato.IdPessoaEndereco AND
						Pais.IdPais = PessoaEndereco.IdPais AND
						Estado.IdEstado = PessoaEndereco.IdEstado AND
						Cidade.IdCidade = PessoaEndereco.IdCidade AND
						Pais.IdPais = Estado.IdPais AND
						Pais.IdPais = Cidade.IdPais AND
						Estado.IdEstado = Cidade.IdEstado AND
						Contrato.IdPessoa = Pessoa.IdPessoa AND
						Contrato.IdPessoaEndereco = PessoaEndereco.IdPessoaEndereco 
						$where
					group by
						Contrato.IdStatus
					order by
						Contrato.IdStatus";
	}
	$res = mysql_query($sql,$con);
	$i = 0;
	$QtdTotal = 0;
	while($lin = mysql_fetch_array($res)){		
		$Status[$i] = getParametroSistema(69,$lin[IdStatus])." (".$lin[Qtd].")";
		$Qtd[$i]	= $lin[Qtd];
		$QtdTotal	+= (int)$lin[Qtd];
		$i++;
	}
	$Total = array_sum($Qtd);
	for($ii = 0; $ii < $i; $ii++){
		$Percentual[$ii] = 100*$Qtd[$ii]/$Total;
	}

	$graph = new PieGraph(820,400);
	
	$theme_class= new OceanTheme;
	$graph->SetTheme($theme_class);
	
	$graph->title->Set("".dicionario($IdTitulo).$StringTitulo);
	$graph->title->SetColor("#444");
	$graph->subtitle->Set("Total de contratos: ".$QtdTotal);
	$graph->subtitle->SetColor("#444");
	$graph->subsubtitle->Set("".dicionario(369)." ".date("d/m/Y H:i:s"));
	$graph->subsubtitle->SetColor("#444");
	
	$p1 = new PiePlot3D($Percentual);
	$graph->Add($p1);
	
	$p1->ExplodeSlice(1);
	$p1->SetSize(150);
	$p1->SetCenter(0.29, 0.49);
	$p1->SetLegends($Status);
	
	$graph->legend->Pos(0.54,0.22,"left","top");
	$graph->SetShadow();
	$graph->Stroke();
	
?>