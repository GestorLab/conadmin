<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_lista_usuario_grupo_permissao(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 			= $_SESSION["IdLoja"];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$IdGrupoPermissao		= $_GET['IdGrupoPermissao'];
		$where					= "";
		$cont					= 0;
		
		if($IdGrupoPermissao != ''){
			$where = " and GrupoPermissao.IdGrupoPermissao = $IdGrupoPermissao";
		}
		
		$sql	=	"
						select 
							GrupoPermissao.IdGrupoPermissao,
							Usuario.Login,
							Pessoa.TipoPessoa,
							substr(Pessoa.Nome,1,35) Nome, 
							substr(Pessoa.RazaoSocial,1,35) RazaoSocial, 
							Pessoa.Email,
							Usuario.IdStatus,
							Usuario.DataExpiraSenha,
							UsuarioGrupoPermissao.DataCriacao
						from 
							Usuario,
							Pessoa,
							PessoaEndereco,
							GrupoPermissao,
							UsuarioGrupoPermissao
						where
							GrupoPermissao.IdGrupoPermissao = UsuarioGrupoPermissao.IdGrupoPermissao and
							UsuarioGrupoPermissao.Login like Usuario.Login and
							Usuario.IdPessoa = Pessoa.IdPessoa and
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
							PessoaEndereco.IdPessoaEndereco = Pessoa.IdEnderecoDefault $where
						order by
							UsuarioGrupoPermissao.DataCriacao desc;
		";
		$res	= @mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	= @mysql_fetch_array($res)){
			$sql1 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=30 and IdParametroSistema = $lin[IdStatus]";
			$res1 = mysql_query($sql1,$con);
			$lin1 = mysql_fetch_array($res1);
			
			switch($lin[IdStatus]){
				case '2': 
					$Color	  =	getParametroSistema(15,2);
					break;
				case '1':
					$Color	  = getParametroSistema(15,3);		
					break;
			}
			
			if($lin[DataExpiraSenha] != ''){
				if($lin[DataExpiraSenha] < date('Y-m-d') && $lin[IdStatus] == 1){
					$lin1[ValorParametroSistema] .=' (expirado)';
				}				
			}
			
			$dados	.=	"\n<IdGrupoPermissao>$lin[IdGrupoPermissao]</IdGrupoPermissao>";
			$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<Email><![CDATA[$lin[Email]]]></Email>";
			$dados	.=	"\n<Status><![CDATA[$lin1[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<Color><![CDATA[$Color]]></Color>";
			$dados	.=	"\n<Data><![CDATA[$lin[DataCriacao]]]></Data>";
			
			$cont++;
		}
		if($cont >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_lista_usuario_grupo_permissao();
?>
