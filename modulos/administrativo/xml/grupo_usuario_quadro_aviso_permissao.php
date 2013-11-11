<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_grupo_usuario_quadro_aviso_permissao(){
		global $con;
		global $_GET;
		
		$IdLoja				= $_SESSION['IdLoja'];
		$IdGrupoUsuario		= $_GET['IdGrupoUsuario'];
		$IdGrupoPermissao	= $_GET['IdGrupoPermissao'];
		$where				= '';
		$dados				= '';
	
		if($IdGrupoUsuario != ''){
			$where .= " AND GrupoUsuarioQuadroAviso.IdGrupoUsuario = $IdGrupoUsuario";
		}
		
		if($IdGrupoPermissao != ''){
			
			$where .= " ORDER BY GrupoUsuarioQuadroAviso.DataCriacao DESC LIMIT 0,1";
		}
		
		$cont = 0;
		// Busca os Quadros de Avisos Configurados via parametro do Sistema
		$sql	=	"select 
						IdParametroSistema IdQuadroAviso,
						ValorParametroSistema DescricaoQuadroAviso,
						GrupoUsuarioQuadroAviso.DataCriacao,
						GrupoUsuarioQuadroAviso.LoginCriacao
					from 
						ParametroSistema,GrupoUsuarioQuadroAviso
					where   
						GrupoUsuarioQuadroAviso.IdLoja = $IdLoja and
						GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
						IdGrupoParametroSistema = 56 and 
						IdQuadroAviso >= 1 and 
						IdQuadroAviso <= 999 
						$where
						AND (
								  ParametroSistema.ValorParametroSistema LIKE 'Alerta de%' 
								  OR ParametroSistema.ValorParametroSistema LIKE 'Alerta Q%'
							 ) 
					ORDER BY DescricaoQuadroAviso ASC ;";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		
		// Busca todos os Status dos Contratos
		$sql = "
			SELECT 
				(ParametroSistema.IdParametroSistema+1000) IdQuadroAviso,
				CONCAT('Alerta Qtd. de Contratos com Status = ', ParametroSistema.ValorParametroSistema) DescricaoQuadroAviso,
				GrupoUsuarioQuadroAviso.DataCriacao,
				GrupoUsuarioQuadroAviso.LoginCriacao
			FROM 
				ParametroSistema,
				GrupoUsuarioQuadroAviso
			WHERE 
				GrupoUsuarioQuadroAviso.IdLoja = $IdLoja AND
				(GrupoUsuarioQuadroAviso.IdQuadroAviso-1000) = ParametroSistema.IdParametroSistema AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso >= 1000 AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso <= 1999 AND
				ParametroSistema.IdGrupoParametroSistema = 69  
				$where
			ORDER BY
				DescricaoQuadroAviso;";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		
		// Busca todos os Grupos das Ordens de Serviço
		$sql = "
			SELECT 
				(GrupoUsuario.IdGrupoUsuario+2000) IdQuadroAviso,
				CONCAT('Alerta Qtd. de OS para o Grupo = ', GrupoUsuario.DescricaoGrupoUsuario) DescricaoQuadroAviso,
				GrupoUsuarioQuadroAviso.DataCriacao,
				GrupoUsuarioQuadroAviso.LoginCriacao
			FROM
				GrupoUsuario,
				GrupoUsuarioQuadroAviso
			WHERE   
				GrupoUsuarioQuadroAviso.IdLoja = $IdLoja AND
				(GrupoUsuarioQuadroAviso.IdQuadroAviso-2000) = GrupoUsuario.IdGrupoUsuario AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso >= 2000 AND 
				GrupoUsuarioQuadroAviso.IdQuadroAviso <= 2999
				$where
			ORDER BY
				DescricaoQuadroAviso;";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		// Busca os Quadros de Avisos Configurados via parametro do Sistema
		$sql	=	"select 
						IdParametroSistema IdQuadroAviso,
						ValorParametroSistema DescricaoQuadroAviso,
						GrupoUsuarioQuadroAviso.DataCriacao,
						GrupoUsuarioQuadroAviso.LoginCriacao
					from 
						ParametroSistema,GrupoUsuarioQuadroAviso
					where   
						GrupoUsuarioQuadroAviso.IdLoja = $IdLoja and
						GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
						IdGrupoParametroSistema = 56 and 
						IdQuadroAviso >= 1 and 
						IdQuadroAviso <= 999 
						$where and 
						ParametroSistema.ValorParametroSistema like 'Quadro%'
					order by DescricaoQuadroAviso asc  ;";
		$res	= @mysql_query($sql,$con);
		while($lin = @mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		
		if($cont > 0){
			header ("content-type: text/xml");
			$dados = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<reg>".$dados."\n</reg>";
			
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_grupo_usuario_quadro_aviso_permissao();
?>