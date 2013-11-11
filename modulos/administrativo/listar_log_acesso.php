<?
	$localModulo		= 1;
	$localOperacao		= 36;
	$localSuboperacao	= "R";
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$IdLoja					= $_SESSION['IdLoja'];	
	$Login					= $_SESSION['Login'];	
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_login			= $_POST['filtro_login'];
	$filtro_nome			= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_data			= url_string_xsl($_POST['filtro_data'],'url',false);
	$filtro_fechada			= $_POST['filtro_fechada'];
	$filtro_navegador		= $_POST['filtro_navegador'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	if($_GET['Login']!=''){
		$filtro_login = $_GET['Login'];
	}
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	LimitVisualizacao("listar");
	
	if($filtro != "")
		$filtro_url	.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url	.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_login!=''){
		$filtro_url .= "&Login=$filtro_login";
		$filtro_sql .=	" and LogAcesso.Login = '$filtro_login'";
	}
	
	if($filtro_nome!=''){
		$filtro_url .= "&Nome=$filtro_nome";
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .=	" and Pessoa.IdPessoa in (select IdPessoa from Pessoa where Nome like '%$filtro_nome%' or RazaoSocial like '%$filtro_nome%')";
	}
		
	if($filtro_data!=""){
		$filtro_url .= "&Data=".$filtro_data;
		$filtro_data = dataConv($filtro_data,'d/m/Y','Y-m-d');
		$filtro_sql .= " and LogAcesso.DataHora like '$filtro_data%'";
	}
	
	if($filtro_fechada!=""){
		$filtro_url .= "&Fechada=".$filtro_fechada;
		$filtro_sql .= " and LogAcesso.Fechada = '$filtro_fechada'";
	}
	
	if($filtro_navegador!=""){
		$filtro_url .= "&Navegador=".$filtro_navegador;
		$filtro_sql .= " and LogAcesso.IdNavegador = '$filtro_navegador'";
	}
	
	if($Login != 'root'){
		$filtro_sql .= " and LogAcesso.Login != 'root'";	
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_log_acesso_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	} else{
		if($filtro_limit == ""){
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else{
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select 
				LogAcesso.IdLogAcesso,
				LogAcesso.Login,
				LogAcesso.IdNavegador,
				Pessoa.TipoPessoa,
				substr(Pessoa.Nome,1,35) Nome, 
				substr(Pessoa.RazaoSocial,1,35) RazaoSocial,
				LogAcesso.IP,
				LogAcesso.DataHora,
				LogAcesso.DataUltimaAtualizacao,
				LogAcesso.Fechada,
				Navegador.Navegador
			 from 
				LogAcesso,
				Usuario,
				Pessoa,
				(
					select 
						IdParametroSistema IdNavegador, 
						ValorParametroSistema Navegador 
					from 
						ParametroSistema 
					where 
						IdGrupoParametroSistema = 89
				) Navegador
			where
				LogAcesso.IdLoja = $IdLoja and
				LogAcesso.Login = Usuario.Login and
				Usuario.IdPessoa = Pessoa.IdPessoa and
				Navegador.IdNavegador = LogAcesso.IdNavegador
				$filtro_sql	
			order by
				LogAcesso.IdLogAcesso desc,
				DataHora desc 
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){
		$lin[DataHoraTemp] = dataConv($lin[DataHora],'Y-m-d H:i:s','d/m/Y H:i:s');
		$lin[DataHora] = dataConv($lin[DataHora],'Y-m-d H:i:s','YmdHis');
		
		$lin[DataUltimaAtualizacaoTemp] = dataConv($lin[DataUltimaAtualizacao],'Y-m-d H:i:s','d/m/Y H:i:s');
		$lin[DataUltimaAtualizacao] = dataConv($lin[DataUltimaAtualizacao],'Y-m-d H:i:s','YmdHis');
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome] = $lin[trim(getCodigoInterno(3,24))];	
		}
		
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=109 and IdParametroSistema = $lin[Fechada]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		if($lin[Fechada] == 1){
			$Img = '../../img/estrutura_sistema/ico_del_c.gif';
		} else{
			$Img = '../../img/estrutura_sistema/ico_del.gif';
		}
		
		echo "<reg>";	
		echo 	"<IdLogAcesso><![CDATA[$lin[IdLogAcesso]]]></IdLogAcesso>";
		echo 	"<Login><![CDATA[$lin[Login]]]></Login>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<IP><![CDATA[$lin[IP]]]></IP>";
		echo 	"<Navegador><![CDATA[$lin[Navegador]]]></Navegador>";	
		echo 	"<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";	
		echo 	"<DataHoraTemp><![CDATA[$lin[DataHoraTemp]]]></DataHoraTemp>";
		echo 	"<DataUltimaAtualizacao><![CDATA[$lin[DataUltimaAtualizacao]]]></DataUltimaAtualizacao>";
		echo 	"<DataUltimaAtualizacaoTemp><![CDATA[$lin[DataUltimaAtualizacaoTemp]]]></DataUltimaAtualizacaoTemp>";
		echo 	"<DescricaoFechada><![CDATA[$lin2[ValorParametroSistema]]]></DescricaoFechada>";
		echo 	"<Fechada><![CDATA[$lin[Fechada]]]></Fechada>";
		echo 	"<Img><![CDATA[$Img]]></Img>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>