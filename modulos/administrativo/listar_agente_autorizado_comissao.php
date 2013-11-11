<?
	$localModulo		=	1;
	$localOperacao		=	34;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	
	$local_IdLoja					= $_SESSION['IdLoja'];	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_nome					= $_POST['filtro_nome'];
	$filtro_servico					= $_POST['filtro_servico'];
	$filtro_parcela					= $_POST['filtro_parcela'];
	$filtro_limit					= $_POST['filtro_limit'];
	
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
		
	if($filtro_servico!=''){
		$filtro_url .= "&Servico=$filtro_servico";
		$filtro_sql .=	" and (Servico.DescricaoServico like '%$filtro_servico%')";
	}
	
	if($filtro_parcela!=''){
		$filtro_url .= "&Parcela=$filtro_parcela";
		$filtro_sql .=	" and (ComissionamentoAgenteAutorizado.Parcela = '$filtro_parcela')";
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
		
		header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_agente_autorizado_comissao_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"SELECT  
					 ComissionamentoAgenteAutorizado.IdAgenteAutorizado,
					 Pessoa.TipoPessoa,
					 substr(Pessoa.Nome,1,35) Nome, 
					 substr(Pessoa.RazaoSocial,1,35) RazaoSocial, 
					 ComissionamentoAgenteAutorizado.IdServico,
					 Servico.DescricaoServico,
					 Parcela,
					 Percentual
				from
					ComissionamentoAgenteAutorizado,
					AgenteAutorizado,
					Pessoa,
					Servico
				where
					 ComissionamentoAgenteAutorizado.IdLoja = $local_IdLoja and
					 ComissionamentoAgenteAutorizado.IdLoja = Servico.IdLoja and
					 ComissionamentoAgenteAutorizado.IdLoja = AgenteAutorizado.IdLoja and
					 ComissionamentoAgenteAutorizado.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado and
					 AgenteAutorizado.IdAgenteAutorizado = Pessoa.IdPessoa and
					 ComissionamentoAgenteAutorizado.IdServico = Servico.IdServico
					$filtro_sql
				order by
					ComissionamentoAgenteAutorizado.IdAgenteAutorizado desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
	
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";	
		echo 	"<RazaoSocial><![CDATA[$lin[Nome]]]></RazaoSocial>";		
		echo 	"<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";		
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";		
		echo 	"<Parcela><![CDATA[$lin[Parcela]]]></Parcela>";		
		echo 	"<Percentual><![CDATA[$lin[Percentual]]]></Percentual>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
