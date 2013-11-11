<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');

	function get_GrupoUsuario(){
		
		global $con;
		global $_GET;
		
		$Limit 						= $_GET['Limit'];
		$IdLoja				 		= $_SESSION["IdLoja"];
		$IdGrupoUsuario		 		= $_GET['IdGrupoUsuario'];
		$DescricaoGrupoUsuario  	= $_GET['DescricaoGrupoUsuario'];
		$Login						= $_GET['Login'];
		$Nome					  	= $_GET['Nome'];
		$OrdemServico 				= $_GET['OrdemServico'];
		$where						= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoUsuario != ''){			$where .= " and IdGrupoUsuario=$IdGrupoUsuario";	}
		if($DescricaoGrupoUsuario !=''){	$where .= " and DescricaoGrupoUsuario like '$DescricaoGrupoUsuario%'";	}
		if($Nome !=''){						$where .= " and DescricaoGrupoUsuario like '$Nome%'";	}
		if($OrdemServico !=''){				$where .= " and OrdemServico = $OrdemServico";	}
		
		if($Login != ''){ 
			$sql = "select 
						GrupoUsuario.IdGrupoUsuario,
						GrupoUsuario.DescricaoGrupoUsuario,
						Usuario.Login 
					from
						Usuario,
						GrupoUsuario,
						UsuarioGrupoUsuario 
					where 
						GrupoUsuario.IdLoja = $IdLoja and
						UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and
						UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and
						Usuario.Login = UsuarioGrupoUsuario.Login and
						UsuarioGrupoUsuario.Login = '$Login' 
					group by
						IdGrupoUsuario 
					order by
						DescricaoGrupoUsuario asc ";
			$res	=	mysql_query($sql,$con);
		}else{
			$sql	=	"select
							IdGrupoUsuario, 
							DescricaoGrupoUsuario, 
							DataCriacao, 
							OrdemServico,
							LoginSupervisor,
							LoginCriacao, 
							DataAlteracao, 
							LoginAlteracao 
						from 
							GrupoUsuario	
						where
							GrupoUsuario.IdLoja = $IdLoja $where $Limit";
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
			if($Login != ""){
				$dados	.=	"\n<IdGrupoUsuario>$lin[IdGrupoUsuario]</IdGrupoUsuario>";
				$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
				$dados	.=	"\n<Login><![CDATA[$lin[Login]]]></Login>";
			}else{
				$dados	.=	"\n<IdGrupoUsuario>$lin[IdGrupoUsuario]</IdGrupoUsuario>";
				$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
				$dados	.=	"\n<LoginSupervisor><![CDATA[$lin[LoginSupervisor]]]></LoginSupervisor>";
				$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados	.=	"\n<OrdemServico><![CDATA[$lin[OrdemServico]]]></OrdemServico>";
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
	
	echo get_GrupoUsuario();
?>
