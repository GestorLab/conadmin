<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ordem_servico_historico(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$IdLoja					= $_SESSION["IdLoja"];
		$IdAgenteAutorizado		= $_SESSION['IdAgenteAutorizado'];
		$IdPessoaLogin			= $_SESSION['IdPessoa'];
		$where					=	"";
		$sqlAux	  				= "";		
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($_GET['IdOrdemServico'] != ''){		
			$where .= " and OrdemServicoHistorico.IdOrdemServico = ".$_GET['IdOrdemServico'];
		}
		
		if($IdAgenteAutorizado!=""){
			$sqlAux	  =	",(select IdPessoa from AgenteAutorizadoPessoa where IdLoja = $IdLoja and IdAgenteAutorizado in ($IdAgenteAutorizado) and IdCarteira = '$IdPessoaLogin') PessoaCarteira";
			$where	 .= " and  OrdemServico.IdPessoa = PessoaCarteira.IdPessoa";
		}

		$sql	=	"SELECT  
					     IdHistorico,
					     IdOrdemServico,
					     DataHora,
					     DataHoraAgendamento,
					     IdGrupoUsuarioAtendimento,
					     LoginAtendimento,
					     LoginResponsavel,
					     substr(Pessoa.Nome,1,20) Nome,
					     OrdemServicoHistorico.IdStatus,
					     OrdemServicoHistorico.Obs,
					     substr(GrupoUsuario.DescricaoGrupoUsuario,1,20) DescricaoGrupoUsuario
					from
					    OrdemServicoHistorico LEFT JOIN Usuario ON (OrdemServicoHistorico.LoginAtendimento = Usuario.Login) LEFT JOIN Pessoa ON (Pessoa.IdPessoa = Usuario.IdPessoa) LEFT JOIN GrupoUsuario ON (OrdemServicoHistorico.IdLoja = GrupoUsuario.IdLoja and OrdemServicoHistorico.IdGrupoUsuarioAtendimento = GrupoUsuario.IdGrupoUsuario) $sqlAux
					where
					     OrdemServicoHistorico.IdLoja = $IdLoja $where 
					order by DataHora DESC $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			$sql2 = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
			$res2 = @mysql_query($sql2,$con);
			$lin2 = @mysql_fetch_array($res2);
			
			$lin[Nome]					=	sem_quebra_string($lin[Nome]);
			$lin[DescricaoGrupoUsuario]	=	sem_quebra_string($lin[DescricaoGrupoUsuario]);
			
			$lin[Obs]	=	formTexto($lin[Obs]);
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<IdHistorico>$lin[IdHistorico]</IdHistorico>";
			$dados	.=	"\n<DataHora><![CDATA[$lin[DataHora]]]></DataHora>";
			$dados	.=	"\n<LoginResponsavel><![CDATA[$lin[LoginResponsavel]]]></LoginResponsavel>";
			$dados	.=	"\n<Obs><![CDATA[$lin[Obs]]]></Obs>";
			$dados	.=	"\n<DataHoraAgendamento><![CDATA[$lin[DataHoraAgendamento]]]></DataHoraAgendamento>";
			$dados	.=	"\n<IdGrupoUsuarioAtendimento><![CDATA[$lin[IdGrupoUsuarioAtendimento]]]></IdGrupoUsuarioAtendimento>";
			$dados	.=	"\n<LoginAtendimento><![CDATA[$lin[LoginAtendimento]]]></LoginAtendimento>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Status><![CDATA[$lin2[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<DescricaoGrupoUsuario><![CDATA[$lin[DescricaoGrupoUsuario]]]></DescricaoGrupoUsuario>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_ordem_servico_historico();
?>
