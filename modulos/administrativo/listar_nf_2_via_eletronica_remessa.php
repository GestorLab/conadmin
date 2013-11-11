<?
	$localModulo		=	1;
	$localOperacao		=	110;
	$localSuboperacao	=	"R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja				= $_SESSION["IdLoja"]; 
		
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_nota_fiscal_layout	= $_POST['filtro_nota_fiscal_layout'];
	$filtro_mes_referencia		= $_POST['filtro_mes_referencia'];
	$filtro_status				= $_POST['filtro_status'];
	
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nota_fiscal_layout!=""){
		$filtro_url .= "&IdNotaFiscalLayout=".$filtro_nota_fiscal_layout;
		$filtro_sql .= " and (NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout = '$filtro_nota_fiscal_layout')";
	}
	
	if($filtro_mes_referencia!=""){
		$filtro_url .= "&MesReferencia=".$filtro_mes_referencia;
		$filtro_sql .= " and (NotaFiscal2ViaEletronicaArquivo.MesReferencia like '$filtro_mes_referencia')";
	}
	
	if($filtro_status!=""){
		$filtro_url .= "&IdStatus=".$filtro_status;
		$filtro_sql .= " and (NotaFiscal2ViaEletronicaArquivo.IdStatus = '$filtro_status')";
	}

	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_nf_2_via_eletronica_remessa_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		}else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$TotalValorTotal = 0;
	
	$sql	=	"select
						NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout, 
						NotaFiscal2ViaEletronicaArquivo.MesReferencia,
						NotaFiscal2ViaEletronicaArquivo.Status,
						NotaFiscal2ViaEletronicaArquivo.QtdNF,
						(NotaFiscal2ViaEletronicaArquivo.ValorTotal - NotaFiscal2ViaEletronicaArquivo.ValorTotalCancelado) ValorTotal,
						(NotaFiscal2ViaEletronicaArquivo.ValorTotalBaseCalculo - NotaFiscal2ViaEletronicaArquivo.ValorTotalBaseCalculoCancelado) ValorTotalBaseCalculo,
						NotaFiscal2ViaEletronicaArquivo.ValorTotalICMS,						
						substr(DataCriacao,1,10) DataCriacao,
						substr(DescricaoNotaFiscalLayout,1,40) DescricaoNotaFiscalLayout,
						NotaFiscal2ViaEletronicaArquivo.IdStatus						
					from 
						NotaFiscal2ViaEletronicaArquivo,
						NotaFiscalLayout						
					where
						NotaFiscal2ViaEletronicaArquivo.IdLoja = $local_IdLoja and
						NotaFiscal2ViaEletronicaArquivo.IdNotaFiscalLayout = NotaFiscalLayout.IdNotaFiscalLayout
						$filtro_sql
					order by
						NotaFiscal2ViaEletronicaArquivo.ValorTotalICMS desc
					$Limit;";
	$res	=	mysql_query($sql,$con);	
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=78 and IdParametroSistema=$lin[IdTipoLocalCobranca]";
		$res2 = @mysql_query($sql2,$con);
		$lin2 = @mysql_fetch_array($res2);
		
		$lin[MesReferenciaTemp]		 		= dataConv($lin[MesReferencia],"m/Y","Ym");
		$lin[DataResponsavel] 				= dataConv($lin[DataCriacao],"Y-m-d","Ymd");
		$lin[DataResponsavelTemp] 			= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
		
		if($lin[ValorTotal] == ""){
			$lin[ValorTotal] = 0.00;
		} else{
			$lin[ValorTotal] = $lin[ValorTotal];
		}
		if($lin[ValorTotalICMS] == ""){
			$lin[ValorTotalICMS] = 0.00;
		}else{
			$lin[ValorTotalICMS] = $lin[ValorTotalICMS];
		}
		if($lin[ValorTotalBaseCalculo] == ""){
			$lin[ValorTotalBaseCalculo] = 0.00;
		}
		$lin[ValorTotalICMSTemp]			= $lin[ValorTotalICMS];
		$lin[ValorTotalTemp]				= $lin[ValorTotal];
		$lin[ValorTotalBaseCalculoTemp]		= $lin[ValorTotalBaseCalculo];
		
		switch($lin[IdStatus]){
			case '1': //Cadastrado
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";
				break;
			case '3': //Confirmado
				$ImgExc	  = "../../img/estrutura_sistema/ico_del.gif";
				break;	
			default: 
				$ImgExc	  = "../../img/estrutura_sistema/ico_del_c.gif";
				break;
		}		

		$TotalValorTotal += $lin[ValorTotal];	
		
		$Status							= getParametroSistema(137,$lin[IdStatus]);

		$sql = "select 				
					DescricaoParametroSistema 
				from 
					ParametroSistema 
				where 
					IdGrupoParametroSistema=140 and
					ValorParametroSistema = '$lin[Status]'";
		$resStatusMestre = @mysql_query($sql,$con);
		$linStatusMestre = @mysql_fetch_array($resStatusMestre);

		$StatusMestreDescricao = explode("-",$linStatusMestre[DescricaoParametroSistema]);
		$StatusMestreDescricao = $StatusMestreDescricao[2];

		$lin[StatusMestreDescricao]		= getParametroSistema(137,$lin[IdStatus]);

		echo "<reg>";
		echo 	"<IdNotaFiscalLayout><![CDATA[$lin[IdNotaFiscalLayout]]]></IdNotaFiscalLayout>";			
		echo 	"<DescricaoNotaFiscalLayout><![CDATA[$lin[DescricaoNotaFiscalLayout]]]></DescricaoNotaFiscalLayout>";
		echo 	"<MesReferencia><![CDATA[$lin[MesReferencia]]]></MesReferencia>";		
		echo 	"<StatusMestre><![CDATA[$lin[Status]]]></StatusMestre>";
		echo 	"<StatusMestreDescricao><![CDATA[$StatusMestreDescricao]]></StatusMestreDescricao>";
		echo 	"<MesReferenciaTemp><![CDATA[$lin[MesReferenciaTemp]]]></MesReferenciaTemp>";
		echo 	"<QtdNF><![CDATA[$lin[QtdNF]]]></QtdNF>";
		echo 	"<ValorTotal><![CDATA[$lin[ValorTotal]]]></ValorTotal>";
		echo 	"<ValorTotalTemp><![CDATA[$lin[ValorTotalTemp]]]></ValorTotalTemp>";
		echo 	"<ValorTotalBaseCalculo><![CDATA[$lin[ValorTotalBaseCalculo]]]></ValorTotalBaseCalculo>";		
		echo 	"<ValorTotalBaseCalculoTemp><![CDATA[$lin[ValorTotalBaseCalculoTemp]]]></ValorTotalBaseCalculoTemp>";		
		echo 	"<ValorTotalICMS><![CDATA[$lin[ValorTotalICMS]]]></ValorTotalICMS>";		
		echo 	"<ValorTotalICMSTemp><![CDATA[$lin[ValorTotalICMSTemp]]]></ValorTotalICMSTemp>";		
		echo 	"<DataResponsavel><![CDATA[$lin[DataResponsavel]]]></DataResponsavel>";		
		echo 	"<DataResponsavelTemp><![CDATA[$lin[DataResponsavelTemp]]]></DataResponsavelTemp>";		
		echo 	"<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";	
		echo 	"<Status><![CDATA[$Status]]></Status>";	
		echo 	"<ImgExc><![CDATA[$ImgExc]]></ImgExc>";
		echo "</reg>";			
	}
		echo "<valortotal>";
		echo 	"<TotalValorTotal><![CDATA[$TotalValorTotal]]></TotalValorTotal>";
		echo "</valortotal>";
	echo "</db>";
?>