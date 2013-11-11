<?
	$localModulo		=	1;
	$localOperacao		=	28;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$filtro						= $_POST['filtro'];
	$filtro_ordem				= $_POST['filtro_ordem'];
	$filtro_ordem_direcao		= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado		= $_POST['filtro_tipoDado'];
	$filtro_ordem_servico		= $_POST['filtro_ordem_servico'];
	$filtro_data				= $_POST['filtro_data'];
	$filtro_hora				= $_POST['filtro_hora'];
	$filtro_login				= $_POST['filtro_login'];
	$filtro_limit				= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
		
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
	
	if($filtro_ordem_servico == '' && $_GET['IdOrdemServico']!=''){
		$filtro_ordem_servico = $_GET['IdOrdemServico'];
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_sql .=	" and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_data!=''){
		$filtro_url .= "&Data=$filtro_data";
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .=	" and AgendamentoOrdemServico.DataHoraAgendamento like '$filtro_data%'";
	}
		
	$filtro_url .= "&IdOrdemServico=$filtro_ordem_servico";
	$filtro_sql .=	" and AgendamentoOrdemServico.IdOrdemServico = '$filtro_ordem_servico'";
		
	if($filtro_hora!=""){
		$filtro_url .= "&Hora=".$filtro_hora;
		$filtro_sql .= " and AgendamentoOrdemServico.DataHoraAgendamento like '%$filtro_hora%'";
	}
	
	if($filtro_login!=''){
		$filtro_url .= "&LoginResponsavel=".$filtro_login;
		$filtro_sql .= " and (Usuario.NomeUsuario like '%$filtro_login%' or AgendamentoOrdemServico.LoginResponsavel like '%$filtro_login%')";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}
	
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_agendamento_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select
					AgendamentoOrdemServico.IdLoja,
					AgendamentoOrdemServico.IdOrdemServico,
				    AgendamentoOrdemServico.LoginResponsavel,
				    AgendamentoOrdemServico.DataHoraAgendamento,
				    Usuario.NomeUsuario
				from
					AgendamentoOrdemServico,
					Usuario
				where
				    AgendamentoOrdemServico.LoginResponsavel = Usuario.Login and
				    AgendamentoOrdemServico.IdLoja = $local_IdLoja 
					$filtro_sql
				order by
					AgendamentoOrdemServico.DataHoraAgendamento desc
				$Limit";
	$res	=	@mysql_query($sql,$con);
	while($lin	=	@mysql_fetch_array($res)){
		$lin[Data]	=	dataConv(substr($lin[DataHoraAgendamento],0,10),'Y-m-d','Ymd');
		$lin[Hora]  = 	substr($lin[DataHoraAgendamento],12,5);
		
		$lin[DataTemp]	=	dataConv(substr($lin[DataHoraAgendamento],0,10),'Y-m-d','d/m/Y');
		$lin[HoraTemp]  = 	substr($lin[DataHoraAgendamento],11,5);
		
		echo "<reg>";	
		echo 	"<IdLoja>$lin[IdLoja]</IdLoja>";
		echo 	"<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";	
		echo 	"<NomeUsuario><![CDATA[$lin[NomeUsuario]]]></NomeUsuario>";
		echo 	"<Data><![CDATA[$lin[Data]]]></Data>";
		echo 	"<DataTemp><![CDATA[$lin[DataTemp]]]></DataTemp>";
		echo 	"<Hora><![CDATA[$lin[Hora]]]></Hora>";
		echo 	"<HoraTemp><![CDATA[$lin[HoraTemp]]]></HoraTemp>";
		echo 	"<DataHoraAgendamento><![CDATA[$lin[DataHoraAgendamento]]]></DataHoraAgendamento>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
