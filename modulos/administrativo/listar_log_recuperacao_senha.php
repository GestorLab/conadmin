<?
	$localModulo		= 1;
	$localOperacao		= 173;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_nome					= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_login					= url_string_xsl($_POST['filtro_login'],'url',false);
	$filtro_data_solicitacao		= url_string_xsl($_POST['filtro_data_solicitacao'],'url',false);
	$filtro_limit					= $_POST['filtro_limit'];
	
	LimitVisualizacao("listar");
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_tipo_dado != "")
		$filtro_url .= "&TipoDado=$filtro_tipo_dado";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_nome != "") {
		$filtro_url .= "&Nome=".$filtro_nome;
		$filtro_sql .= " and (Pessoa.RazaoSocial like '%$filtro_nome%' or Pessoa.Nome like '%$filtro_nome%')";
	}
	
	if($filtro_login != "") {
		$filtro_url .= "&Login=".$filtro_login;
		$filtro_sql .= " and (SolicitacaoSenha.Login like '%$filtro_login%')";
	}
	
	if($filtro_data_solicitacao != "") {
		$filtro_url .= "&DataSolicitacao=".$filtro_data_solicitacao;
		$filtro_data_solicitacao 	= dataConv($filtro_data_solicitacao,"d/m/Y","Y-m-d");
		$filtro_sql .= " and (SolicitacaoSenha.DataSolicitacao like '$filtro_data_solicitacao%')";
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_log_recuperacao_senha_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s") {
		if($filtro_limit != "") {
			$Limit	= " limit $filtro_limit";
		}
	} else {
		if($filtro_limit == "") {
			$Limit 	= " limit 0,".getCodigoInterno(7,5);
		} else {
			$Limit 	= " limit 0,".$filtro_limit;
		}
	}
	
	$sql = "select
				SolicitacaoSenha.IdSolicitacao,
				SolicitacaoSenha.Login,
				SolicitacaoSenha.IP,
				SolicitacaoSenha.IdPessoa,
				SolicitacaoSenha.DataSolicitacao,
				Pessoa.TipoPessoa,
				substr(Pessoa.Nome,1,35) Nome, 
				substr(Pessoa.RazaoSocial,1,35) RazaoSocial
			from 
				SolicitacaoSenha,
				Pessoa
			where
				SolicitacaoSenha.IdPessoa = Pessoa.IdPessoa
				$filtro_sql
			order by
				SolicitacaoSenha.IdSolicitacao desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		$lin[DataSolicitacaoTemp] 	= dataConv($lin[DataSolicitacao],"Y-m-d","d/m/Y");
		$lin[DataSolicitacao] 		= dataConv($lin[DataSolicitacao],"Y-m-d","Ymd");
		$lin[IPTemp] 				= str_replace(".","",$lin[IP]);
		
		if($lin[TipoPessoa] == 1){
			$lin[Nome] = $lin[trim(getCodigoInterno(3,24))];
		}
		
		echo "<reg>";
		echo 	"<IdSolicitacao>$lin[IdSolicitacao]</IdSolicitacao>";
		echo 	"<Login><![CDATA[$lin[Login]]]></Login>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";
		echo 	"<IP><![CDATA[$lin[IP]]]></IP>";
		echo 	"<IPTemp><![CDATA[$lin[IPTemp]]]></IPTemp>";
		echo 	"<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
		echo 	"<DataSolicitacao><![CDATA[$lin[DataSolicitacao]]]></DataSolicitacao>";
		echo 	"<DataSolicitacaoTemp><![CDATA[$lin[DataSolicitacaoTemp]]]></DataSolicitacaoTemp>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>