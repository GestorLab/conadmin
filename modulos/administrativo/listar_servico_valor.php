<?
	$localModulo		= 1;
	$localOperacao		= 25;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado			= $_POST['filtro_tipoDado'];
	$filtro_descricao_servico_valor	= $_POST['filtro_descricao_servico_valor'];
	$filtro_data_incicio			= $_POST['filtro_data_inicio'];
	$filtro_data_fim				= $_POST['filtro_data_fim'];
	$filtro_limit					= $_POST['filtro_limit'];
	
	if($_GET['IdServico'] != '') {
		$filtro_servico = $_GET['IdServico'];
	}
	
	if($_POST['IdServico'] != '') {
		$filtro_servico = $_POST['IdServico'];
	}
	
	$filtro_url = "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro_servico == ''){
		$filtro_sql .= " and ServicoValor.IdServico = '0'";
	}
	
	if($filtro != "")
		$filtro_url .= "&Filtro=$filtro";
	
	if($filtro_ordem != "")
		$filtro_url .= "&Ordem=$filtro_ordem";
	
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_servico != '') {
		$filtro_url .= "&IdServico=$filtro_servico";
		$filtro_sql .= " and ServicoValor.IdServico = $filtro_servico";
	}
	
	if($filtro_descricao_servico_valor != "") {
		$filtro_url .= "&DescricaoServicoValor=".$filtro_descricao_servico_valor;
		$filtro_sql .= " and ServicoValor.DescricaoServicoValor like '%$filtro_descricao_servico_valor%'";
	}
	
	if($filtro_data_inicio != "") {
		$filtro_url .= "&DataInicio=".$filtro_data_inicio;
		$filtro_data_lanc = dataConv($filtro_data_inicio,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ServicoValor.DataInicio = '$filtro_data_inicio'";
	}
	
	if($filtro_data_fim != "") {
		$filtro_url .= "&DataTermino=".$filtro_data_fim;
		$filtro_data_venc = dataConv($filtro_data_fim,'d/m/Y','Y-m-d');
		$filtro_sql .= " and ServicoValor.DataTermino = '$filtro_data_fim'";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
	
	if($filtro_url != "") {
		$filtro_url = "?f=t".$filtro_url;
		$filtro_url = url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_servico_valor_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit = " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit = " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit = " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select
				ServicoValor.IdServico,
				ServicoValor.DataInicio,
				ServicoValor.DataTermino,
				substr(ServicoValor.DescricaoServicoValor,1,40) DescricaoServicoValor,
				ServicoValor.Valor,
				ServicoValor.MultaFidelidade 
			from 
				Loja,
				Servico,
				ServicoValor 
			where 
				Servico.IdLoja = $local_IdLoja and
				Servico.IdLoja = Loja.IdLoja and
				ServicoValor.IdLoja = Servico.IdLoja and
				Servico.IdServico = ServicoValor.IdServico 
				$filtro_sql
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[DataInicioBusca]	= $lin[DataInicio];
		$lin[DataInicioTemp]	= dataConv($lin[DataInicio],"Y-m-d","d/m/Y");
		$lin[DataTerminoTemp]	= dataConv($lin[DataTermino],"Y-m-d","d/m/Y");
		$lin[DataInicio]		= dataConv($lin[DataInicio],"Y-m-d","Ymd");
		$lin[DataTermino]		= dataConv($lin[DataTermino],"Y-m-d","Ymd");
		
		
		if($lin[Valor] == "") {
			$lin[Valor] = 0;
		}
		
		if($lin[MultaFidelidade] == "") {
			$lin[MultaFidelidade] = 0;
		}
		
		echo "<reg>";
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdServico>$lin[IdServico]</IdServico>";
		echo 	"<DataTermino><![CDATA[$lin[DataTermino]]]></DataTermino>";
		echo 	"<DataTerminoTemp><![CDATA[$lin[DataTerminoTemp]]]></DataTerminoTemp>";
		echo 	"<DescricaoServicoValor><![CDATA[$lin[DescricaoServicoValor]]]></DescricaoServicoValor>";
		echo 	"<DataInicio><![CDATA[$lin[DataInicio]]]></DataInicio>";
		echo 	"<DataInicioBusca><![CDATA[$lin[DataInicioBusca]]]></DataInicioBusca>";
		echo 	"<DataInicioTemp><![CDATA[$lin[DataInicioTemp]]]></DataInicioTemp>";
		echo 	"<Valor><![CDATA[$lin[Valor]]]></Valor>";
		echo 	"<MultaFidelidade><![CDATA[$lin[MultaFidelidade]]]></MultaFidelidade>";
		echo "</reg>";
	}
	
	echo "</db>";
?>