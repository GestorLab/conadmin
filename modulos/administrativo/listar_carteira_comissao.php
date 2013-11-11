<?
	$localModulo		=	1;
	$localOperacao		=	35;
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
	$filtro_carteira				= $_POST['filtro_carteira'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($_GET['IdAgenteAutorizado']!=''){
		$filtro_idagente	= $_GET['IdAgenteAutorizado'];
	}
	
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
		$filtro_sql .=	" and (AgenteAutorizado.NomeAgenteAutorizado like '%$filtro_nome%' or AgenteAutorizado.RazaoSocialAgenteAutorizado like '%$filtro_nome%')";
	}
	
	if($filtro_carteira!=''){
		$filtro_url .= "&Carteira=$filtro_carteira";
		$filtro_sql .=	" and (Carteira.NomeCarteira like '%$filtro_carteira%' or Carteira.RazaoSocialCarteira like '%$filtro_carteira%')";
	}
		
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;	
		$filtro_url	= url_string_xsl($filtro_url, "CONVERT", false);
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_carteira_comissao_xsl.php$filtro_url\"?>";
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
	
	$sql = "SELECT  
				ComissionamentoCarteira.IdAgenteAutorizado,
				AgenteAutorizado.TipoPessoaAgenteAutorizado,
				AgenteAutorizado.NomeAgenteAutorizado,
				AgenteAutorizado.RazaoSocialAgenteAutorizado,
				ComissionamentoCarteira.IdServico,
				ComissionamentoCarteira.IdCarteira,
				Carteira.TipoPessoaCarteira,
				Carteira.NomeCarteira,
				Carteira.RazaoSocialCarteira,
				Servico.DescricaoServico,
				ComissionamentoCarteira.Parcela,
				ComissionamentoCarteira.Percentual
			from
				ComissionamentoCarteira,
				(
					select 
						AgenteAutorizado.IdLoja,
						AgenteAutorizado.IdAgenteAutorizado,
						Pessoa.TipoPessoa TipoPessoaAgenteAutorizado, 
						Pessoa.Nome NomeAgenteAutorizado, 
						Pessoa.RazaoSocial RazaoSocialAgenteAutorizado 
					from 
						Pessoa,
						AgenteAutorizado 
					where 
						Pessoa.IdPessoa = AgenteAutorizado.IdAgenteAutorizado
				) AgenteAutorizado,
				(
					select 
						Carteira.IdLoja,
						Carteira.IdCarteira,
						Pessoa.TipoPessoa TipoPessoaCarteira, 
						Pessoa.Nome NomeCarteira, 
						Pessoa.RazaoSocial RazaoSocialCarteira 
					from 
						Pessoa,
						Carteira 
					where 
						Pessoa.IdPessoa = Carteira.IdCarteira
				) Carteira,
				Servico
			where
				ComissionamentoCarteira.IdLoja = $local_IdLoja and
				ComissionamentoCarteira.IdLoja = Servico.IdLoja and
				ComissionamentoCarteira.IdLoja = AgenteAutorizado.IdLoja and
				ComissionamentoCarteira.IdLoja = Carteira.IdLoja and
				ComissionamentoCarteira.IdAgenteAutorizado = AgenteAutorizado.IdAgenteAutorizado and
				ComissionamentoCarteira.IdCarteira = Carteira.IdCarteira and
				ComissionamentoCarteira.IdServico = Servico.IdServico
				$filtro_sql
			order by
				ComissionamentoCarteira.IdCarteira desc
			$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoaCarteira]=='1'){
			$lin[NomeCarteira]	=	$lin[trim(getCodigoInterno(3,24)).'Carteira'];	
		}
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";		
		echo 	"<IdAgenteAutorizado>$lin[IdAgenteAutorizado]</IdAgenteAutorizado>";	
		echo 	"<RazaoSocialAgenteAutorizado><![CDATA[$lin[RazaoSocialAgenteAutorizado]]]></RazaoSocialAgenteAutorizado>";	
		echo 	"<IdCarteira>$lin[IdCarteira]</IdCarteira>";	
		echo 	"<NomeCarteira><![CDATA[$lin[NomeCarteira]]]></NomeCarteira>";		
		echo 	"<IdServico><![CDATA[$lin[IdServico]]]></IdServico>";		
		echo 	"<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";		
		echo 	"<Parcela><![CDATA[$lin[Parcela]]]></Parcela>";		
		echo 	"<Percentual><![CDATA[$lin[Percentual]]]></Percentual>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
