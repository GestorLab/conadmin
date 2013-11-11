<?
	$localModulo		=	1;
	$localOperacao		=	38;
	$localSuboperacao	=	"R";		
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_Login			= $_SESSION['Login'];

	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_IdAgenda		= $_POST['IdAgenda'];
	$filtro_data			= $_POST['filtro_data'];
	$filtro_hora			= $_POST['filtro_hora'];
	$filtro_status			= $_POST['filtro_status'];
	$filtro_IdPessoa		= $_POST['IdPessoa'];
	$filtro_descricao		= url_string_xsl($_POST['filtro_descricao'],'url',false);
	$filtro_limit			= $_POST['filtro_limit'];
	
	
	if($filtro_IdAgenda == ''&& $_GET['IdAgenda']!=''){
		$filtro_IdAgenda	= $_GET['IdAgenda'];
	}
	
	LimitVisualizacao("listar");
		
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
		
	if($filtro_descricao!=""){
		$filtro_url .= "&Descricao=".$filtro_descricao;
		$filtro_sql .= " and (Descricao like '%$filtro_descricao%')";
	}
	if($filtro_data != ''){
		$filtro_url .= "&Data=".$filtro_data;
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .= " and Data='".$filtro_data."'";
	}
	if($filtro_hora != ''){
		$filtro_url .= "&Hora=".$filtro_hora;
		$filtro_sql .= " and Hora='".$filtro_hora."'";
	}
	if($filtro_agenda!=""){
		$filtro_url	.= "&IdAgenda=".$filtro_agenda;
		$filtro_sql	.= " and IdAgenda =".$filtro_agenda;
	}
	if($filtro_status!=""){
		$filtro_url	.= "&IdStatus=".$filtro_status;
		$filtro_sql	.= " and Status =".$filtro_status;
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_agenda_xsl.php$filtro_url\"?>";
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
	
	$sql	=	"select
				      	IdAgenda, 
						Data,
						substr(Hora,1,5) as Hora,
						substr(Descricao,1,60) as Descricao,
						Agenda.IdPessoa,
						Pessoa.Nome as Nome,
						Pessoa.RazaoSocial as RazaoSocial,
						Status
					from 
						Agenda left join Pessoa on (Agenda.IdPessoa = Pessoa.IdPessoa)
					where
						Login='".$_SESSION['Login']."'
						$filtro_sql
					order by
						Agenda.IdAgenda desc
					$Limit;";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		$lin[DataTemp]	= dataConv($lin[Data],'Y-m-d','d/m/Y');
		$lin[Data]		= dataConv($lin[Data],'Y-m-d','Ymd');
		
		$lin[HoraTemp]	= $lin[Hora];
		$lin[Hora]		= str_replace(':','',$lin[Hora]);	
		
		if($lin[Status]!=''){
			$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=53 and IdParametroSistema = $lin[Status]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
		}
		
		echo "<reg>";			
		echo 	"<IdAgenda>$lin[IdAgenda]</IdAgenda>";
		echo 	"<Data><![CDATA[$lin[Data]]]></Data>";
		echo	"<DataTemp><![CDATA[$lin[DataTemp]]]></DataTemp>";
		echo 	"<Hora><![CDATA[$lin[Hora]]]></Hora>";
		echo 	"<HoraTemp><![CDATA[$lin[HoraTemp]]]></HoraTemp>";
		echo 	"<Descricao><![CDATA[$lin[Descricao]]]></Descricao>";
		echo 	"<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
		echo	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>
