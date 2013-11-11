<?
	$localModulo		= 1;
	$localOperacao		= 165;
	$localSuboperacao	= "R";
	
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');
	
	$local_IdLoja					= $_SESSION['IdLoja'];
	$local_Login					= $_SESSION["Login"];
	$filtro							= $_POST['filtro'];
	$filtro_ordem					= $_POST['filtro_ordem'];
	$filtro_ordem_direcao			= $_POST['filtro_ordem_direcao'];
	$filtro_tipo_dado				= $_POST['filtro_tipoDado'];
	$filtro_descricao_conta_email	= url_string_xsl($_POST['filtro_descricao_conta_email'],'url',false);
	$filtro_usuario					= url_string_xsl($_POST['filtro_usuario'],'url',false);
	$filtro_email_remetente			= url_string_xsl($_POST['filtro_email_remetente'],'url',false);
	$filtro_servidor_smtp			= url_string_xsl($_POST['filtro_servidor_smtp'],'url',false);
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
		
	if($filtro_descricao_conta_email != "") {
		$filtro_url .= "&DescricaoContaEmail=".$filtro_descricao_conta_email;
		$filtro_sql .= " and (ContaEmail.DescricaoContaEmail like '%$filtro_descricao_conta_email%')";
	}
	
	if($filtro_usuario != "") {
		$filtro_url .= "&Usuario=".$filtro_usuario;
		$filtro_sql .= " and (ContaEmail.Usuario like '%$filtro_usuario%')";
	}
	
	if($filtro_email_remetente != "") {
		$filtro_url .= "&EmailRemetente=".$filtro_email_remetente;
		$filtro_sql .= " and (ContaEmail.EmailRemetente like '%$filtro_email_remetente%')";
	}
	
	if($filtro_servidor_smtp != "") {
		$filtro_url .= "&ServidorSMTP=".$filtro_servidor_smtp;
		$filtro_sql .= " and (ContaEmail.ServidorSMTP like '%$filtro_servidor_smtp%')";
	}
	if($local_Login != 'root'){
		$loginAutorizado .= "ContaEmail.IdContaEmail > 0 ";
	}else{
		$loginAutorizado .= "ContaEmail.IdContaEmail >= 0";	
	}
	
	if($filtro_limit != "")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}
	
	header("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_email_xsl.php$filtro_url\"?>";
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
				ContaEmail.IdContaEmail,
				substring(ContaEmail.DescricaoContaEmail,1,32) DescricaoContaEmail,
				ContaEmail.Usuario,
				ContaEmail.EmailRemetente,
				ContaEmail.ServidorSMTP,
				ContaEmail.Porta
			from 
				ContaEmail
			where
				ContaEmail.IdLoja = $local_IdLoja and
				$loginAutorizado			
				$filtro_sql
			order by
				ContaEmail.IdContaEmail desc
			$Limit;";
	$res = mysql_query($sql,$con);
	while($lin = @mysql_fetch_array($res)) {
		echo "<reg>";			
		echo 	"<IdContaEmail>$lin[IdContaEmail]</IdContaEmail>";
		echo 	"<DescricaoContaEmail><![CDATA[$lin[DescricaoContaEmail]]]></DescricaoContaEmail>";
		echo 	"<Usuario><![CDATA[$lin[Usuario]]]></Usuario>";
		echo 	"<EmailRemetente><![CDATA[$lin[EmailRemetente]]]></EmailRemetente>";
		echo 	"<ServidorSMTP><![CDATA[$lin[ServidorSMTP]]]></ServidorSMTP>";
		echo 	"<Porta><![CDATA[$lin[Porta]]]></Porta>";
		echo 	"<LimiteEnvioDiario><![CDATA[$lin[LimiteEnvioDiario]]]></LimiteEnvioDiario>";
		echo "</reg>";	
	}
	
	echo "</db>";
?>