<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_GrupoUsuarioOpcoes(){
		global $con;
		global $_GET;
		
		$IdLoja			= $_SESSION["IdLoja"];
		$IdGrupoUsuario	= $_GET['IdGrupoUsuario'];
		$where			= "";
		
		if($IdGrupoUsuario != ''){
			$where .= " and IdGrupoUsuario=$IdGrupoUsuario";
		}
		
		$cont = 0;
		// Busca os Quadros de Avisos Configurados via parametro do Sistema
		$sql	=	"select
					 	IdParametroSistema IdQuadroAviso,
					 	ValorParametroSistema DescricaoQuadroAviso
					from
					 	ParametroSistema
					where
					 	IdGrupoParametroSistema = 56 and
					 	IdParametroSistema not in (select IdQuadroAviso from GrupoUsuarioQuadroAviso where IdLoja = $IdLoja and IdQuadroAviso >= 1 and IdQuadroAviso <= 999 $where)
					order by
						DescricaoQuadroAviso";
		$res	=	mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			
			$cont++;
		}
		
		// Busca todos os Status dos Contratos
		$sql	=	"select
						(IdParametroSistema+1000) IdQuadroAviso,
						concat('Alerta Qtd. de Contratos com Status = ',ValorParametroSistema) DescricaoQuadroAviso
					from
						ParametroSistema
					where
						IdGrupoParametroSistema = 69 and
					 	IdParametroSistema not in (select IdQuadroAviso-1000 from GrupoUsuarioQuadroAviso where IdLoja = $IdLoja and IdQuadroAviso >= 1000 and IdQuadroAviso <= 1999 $where)
					order by
						ValorParametroSistema";
		$res	=	mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			
			$cont++;
		}
		
		// Busca todos os Grupos das Ordens de Serviço
		$sql	=	"select
						(IdGrupoUsuario+2000) IdQuadroAviso,
						concat('Alerta Qtd. de OS para o Grupo = ',DescricaoGrupoUsuario) DescricaoQuadroAviso
					from
						GrupoUsuario
					where
						IdLoja = $IdLoja and
					 	IdGrupoUsuario not in (select IdQuadroAviso-2000 from GrupoUsuarioQuadroAviso where IdLoja = $IdLoja and IdQuadroAviso >= 2000 and IdQuadroAviso <= 2999 $where)
					order by
						DescricaoGrupoUsuario";
		$res	=	mysql_query($sql,$con);
		while($lin	=	@mysql_fetch_array($res)){
			$dados	.=	"\n<IdQuadroAviso>$lin[IdQuadroAviso]</IdQuadroAviso>";
			$dados	.=	"\n<DescricaoQuadroAviso><![CDATA[$lin[DescricaoQuadroAviso]]]></DescricaoQuadroAviso>";
			
			$cont++;
		}
		
		// Retorna
		if($cont > 0){
			header ("content-type: text/xml");
			$dados = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<reg>".$dados."\n</reg>";
			
			return $dados;
		}else{
			return "false";
		}
	}
	echo get_GrupoUsuarioOpcoes();
?>