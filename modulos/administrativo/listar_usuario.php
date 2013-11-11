<?
	$localModulo		=	1;
	$localOperacao		=	6;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
		
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_login			= url_string_xsl($_POST['filtro_login'],'url',false);
	$filtro_nome			= url_string_xsl($_POST['filtro_nome'],'url',false);
	$filtro_email			= url_string_xsl($_POST['filtro_email'],'url',false);
	$filtro_status			= $_POST['filtro_status'];
	$filtro_limit			= $_POST['filtro_limit'];
	
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
		
	if($filtro_login!=''){
		$filtro_url .= "&Login=$filtro_login";
		$filtro_sql .=	" and Usuario.Login like '%$filtro_login%'";
	}
		
	if($filtro_nome!=""){
		$filtro_url .= "&Nome=".$filtro_nome;
		$filtro_nome = str_replace("'", "\'", $filtro_nome);
		$filtro_sql .= " and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_email!=''){
		$filtro_url .= "&Email=".$filtro_email;
		$filtro_sql .= " and (Pessoa.Email like '%$filtro_email%' or PessoaEndereco.EmailEndereco like '%$filtro_email%')";
	}
	
	if($filtro_status!=''){
		$filtro_url .= "&Status=".$filtro_status;
		$filtro_sql .= " and Usuario.IdStatus=$filtro_status";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert',false);
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_usuario_xsl.php$filtro_url\"?>";
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
					Usuario.Login,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,35) Nome, 
					substr(Pessoa.RazaoSocial,1,35) RazaoSocial, 
					Pessoa.Email,
					Usuario.IdStatus,
					Usuario.DataExpiraSenha
				 from 
				    Usuario,
					Pessoa,
					PessoaEndereco
				where
					Usuario.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault
				    $filtro_sql
				order by
					Usuario.DataCriacao desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		$sql2 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=30 and IdParametroSistema = $lin[IdStatus]";
		$res2 = mysql_query($sql2,$con);
		$lin2 = mysql_fetch_array($res2);
		
		switch($lin[IdStatus]){
			case '2': 
				$Color	  =	getParametroSistema(15,2);
				break;
			case '1':
				$Color	  = getParametroSistema(15,3);		
				break;
		}
		
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
		
		if($lin[DataExpiraSenha] != ''){
			if($lin[DataExpiraSenha] < date('Y-m-d') && $lin[IdStatus] == 1){
				$lin2[ValorParametroSistema] .=' (expirado)';
			}				
		}
		
		echo "<reg>";	
		echo 	"<Login><![CDATA[$lin[Login]]]></Login>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
		echo 	"<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
		echo 	"<Color><![CDATA[$Color]]></Color>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
