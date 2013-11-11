<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_GrupoPermissao(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja				 		= $_SESSION["IdLoja"];
		$IdGrupoPermissao	 		= $_GET['IdGrupoPermissao'];
		$DescricaoGrupoPermissao  	= $_GET['DescricaoGrupoPermissao'];
		$Login						= $_GET['Login'];
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoPermissao != ''){	$where .= "IdGrupoPermissao=$IdGrupoPermissao";	}
		if($DescricaoGrupoPermissao !=''){	
			if($where!=''){
				$where .= " and ";
			}
			$where .= "DescricaoGrupoPermissao like '$DescricaoGrupoPermissao%'";	
		}
		
		if($where!=''){
			$where = "where ".$where;
		}
		
		if($Login != ''){ 
			$sql = "select 
						GrupoPermissao.IdGrupoPermissao,
						GrupoPermissao.DescricaoGrupoPermissao,
						Usuario.Login 
					from
						Usuario,
						GrupoPermissao,
						UsuarioGrupoUsuario,
						UsuarioGrupoPermissao 
					where
						UsuarioGrupoUsuario.IdLoja = $IdLoja and
						UsuarioGrupoPermissao.IdGrupoPermissao = GrupoPermissao.IdGrupoPermissao and
						Usuario.Login = UsuarioGrupoPermissao.Login and
						UsuarioGrupoPermissao.Login = '$Login'  
					group by
						IdGrupoPermissao 
					order by
						DescricaoGrupoPermissao asc ";
			$res	=	mysql_query($sql,$con);
		}else{
			$sql	=	"select
							IdGrupoPermissao, 
							DescricaoGrupoPermissao,
							LimiteVisualizacao, 
							IpAcesso,
							DataCriacao, 
							LoginCriacao, 
							DataAlteracao, 
							LoginAlteracao 
						from 
							GrupoPermissao	$where $Limit";
			$res	=	mysql_query($sql,$con);
		}
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($Login != ''){
				$dados	.=	"\n<IdGrupoPermissao>$lin[IdGrupoPermissao]</IdGrupoPermissao>";
				$dados	.=	"\n<DescricaoGrupoPermissao><![CDATA[$lin[DescricaoGrupoPermissao]]]></DescricaoGrupoPermissao>";
				$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
			}else{
				$dados	.=	"\n<IdGrupoPermissao>$lin[IdGrupoPermissao]</IdGrupoPermissao>";
				$dados	.=	"\n<DescricaoGrupoPermissao><![CDATA[$lin[DescricaoGrupoPermissao]]]></DescricaoGrupoPermissao>";
				$dados	.=	"\n<LimiteVisualizacao><![CDATA[$lin[LimiteVisualizacao]]]></LimiteVisualizacao>";
				$dados	.=	"\n<IpAcesso><![CDATA[$lin[IpAcesso]]]></IpAcesso>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			}
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_GrupoPermissao();
?>
