<?
	$localModulo		=	1;
	$localOperacao		=	33;
	$localSuboperacao	=	"R";
		
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	 
	$local_IdLoja			= $_SESSION['IdLoja'];
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	$filtro_localTipoDado	= $_POST['filtro_tipoDado'];
	$filtro_login			= $_POST['filtro_login'];
	$filtro_nome			= $_POST['filtro_nome'];
	$filtro_email			= $_POST['filtro_email'];
	$filtro_grupo_usuario	= $_POST['filtro_grupo_usuario'];
	$filtro_status			= $_POST['filtro_status'];
	$filtro_limit			= $_POST['filtro_limit'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro_login == '' && $_GET['Login']!=''){
		$filtro_login	= $_GET['Login'];
	}
	
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
		$filtro_sql .=	" and UsuarioGrupoUsuario.Login like '%$filtro_login%'";
	}
		
	if($filtro_nome!=""){
		$filtro_url .= "&Nome=".$filtro_nome;
		$filtro_sql .= " and (Pessoa.Nome like '%$filtro_nome%' or Pessoa.RazaoSocial like '%$filtro_nome%')";
	}
	
	if($filtro_email!=''){
		$filtro_url .= "&Email=".$filtro_email;
		$filtro_sql .= " and (Pessoa.Email like '%$filtro_email%' or PessoaEndereco.EmailEndereco like '%$filtro_email%')";
	}
	
	if($filtro_grupo_usuario!=''){
		$filtro_url .= "&GrupoUsuario=".$filtro_grupo_usuario;
		$filtro_sql .= " and DescricaoGrupoUsuario like '%$filtro_grupo_usuario%'";
	}
	
	if($filtro_limit!="")
		$filtro_url .= "&Limit=".$filtro_limit;
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_usuario_grupo_usuario_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	if($filtro == "s"){
		if($filtro_limit != ""){
			$Limit	= " limit $filtro_limit";
		}
	}else{
		$Limit 	= " limit 0,".getCodigoInterno(7,5);
	}
	
	$sql	=	"select 
					UsuarioGrupoUsuario.Login,
					Pessoa.TipoPessoa,
					substr(Pessoa.Nome,1,35) Nome, 
					substr(Pessoa.RazaoSocial,1,35) RazaoSocial, 
					Pessoa.Email,
					GrupoUsuario.DescricaoGrupoUsuario,
					GrupoUsuario.IdGrupoUsuario
				 from 
				 	UsuarioGrupoUsuario,
				 	GrupoUsuario,
				    Usuario,
					Pessoa,
					PessoaEndereco
				where
					UsuarioGrupoUsuario.IdLoja= $local_IdLoja and
					UsuarioGrupoUsuario.IdLoja=GrupoUsuario.IdLoja and
					Usuario.IdPessoa = Pessoa.IdPessoa and
					UsuarioGrupoUsuario.Login = Usuario.Login and
					UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault
				    $filtro_sql
				order by
					GrupoUsuario.IdGrupoUsuario desc
				$Limit";
	$res	=	mysql_query($sql,$con);
	while($lin	=	mysql_fetch_array($res)){
		if($lin[TipoPessoa]=='1'){
			$lin[Nome]	=	$lin[trim(getCodigoInterno(3,24))];	
		}
	
		echo "<reg>";	
		echo 	"<Login><![CDATA[$lin[Login]]]></Login>";
		echo 	"<Nome><![CDATA[$lin[Nome]]]></Nome>";	
		echo 	"<Email><![CDATA[$lin[Email]]]></Email>";
		echo 	"<IdGrupoUsuario><![CDATA[$lin[IdGrupoUsuario]]]></IdGrupoUsuario>";	
		echo 	"<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";	
		echo "</reg>";	
	}
	
	echo "</db>";
?>
