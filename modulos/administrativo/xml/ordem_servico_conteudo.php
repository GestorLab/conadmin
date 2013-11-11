<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
		
	function get_Ordem_Servico(){
		
		global $con;
		global $_GET;
		
		$IdLoja		 				= $_SESSION["IdLoja"];
		$IdPessoaLogin				= $_SESSION['IdPessoa'];
		$Limit 						= $_GET['Limit'];
		$LoginAtendimento			= $_SESSION['Login'];
		$Local						= $_GET['Local'];	
		$order						= $_GET['Order'];	
		$campoOrder					= $_GET['CampoOrder'];
		$IdGrupoUsuarioAtendimento	= $_GET['IdGrupoUsuarioAtendimento'];	
		$where						= "";
		$sqlAux	  					= "";	
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}	
		
		if($LoginAtendimento!= ''){
			if($Local== 'Grupo'){		
				$where	.=	" and if(OrdemServico.IdGrupoUsuarioAtendimento is NOT NULL,OrdemServico.IdGrupoUsuarioAtendimento in (select IdGrupoUsuario from UsuarioGrupoUsuario where Login = '$LoginAtendimento'),(OrdemServico.IdGrupoUsuarioAtendimento is NULL and OrdemServico.LoginCriacao = '$LoginAtendimento')) ";
				$where	.=  " and (OrdemServico.IdStatus = 3 or OrdemServico.IdStatus = 1)";
			}
			if($Local == 'Individual'){
				$where	.=  " and OrdemServico.LoginAtendimento = '$LoginAtendimento'";
				$where	.=  " and (OrdemServico.IdStatus = 3 or OrdemServico.IdStatus = 1)";
			}
			if($Local == 'Fatura'){
				$where	.=  " and OrdemServico.IdStatus = 4";
			}
		}
		
		if($order == ""){
			$order =	'DESC';
		}
		if($campoOrder == ""){
			$campoOrder	=	"OrdemServico.DataAgendamentoAtendimento";
		}
		
		if($IdGrupoUsuarioAtendimento != ""){
			$where	.=	" and OrdemServico.IdGrupoUsuarioAtendimento = $IdGrupoUsuarioAtendimento";
		}
		
		if($_SESSION["RestringirAgenteAutorizado"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdAgenteAutorizado = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		if($_SESSION["RestringirAgenteCarteira"] == true){
			$sqlAgente	=	"select 
								AgenteAutorizado.IdGrupoPessoa 
							from 
								AgenteAutorizado,
								Carteira
							where 
								AgenteAutorizado.IdLoja = $IdLoja  and 
								AgenteAutorizado.IdLoja = Carteira.IdLoja and
								AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
								Carteira.IdCarteira = '$IdPessoaLogin' and 
								AgenteAutorizado.Restringir = 1 and 
								AgenteAutorizado.IdStatus = 1 and 
								AgenteAutorizado.IdGrupoPessoa is not null";
			$resAgente	=	@mysql_query($sqlAgente,$con);
			while($linAgente	=	@mysql_fetch_array($resAgente)){
				$where    .=	" and GrupoPessoa.IdGrupoPessoa = $linAgente[IdGrupoPessoa]"; 
			}
		}
		
		$cont	=	0;	
		$sql	=	"select
						OrdemServico.IdOrdemServico,
						OrdemServico.IdTipoOrdemServico,
		   				TipoOrdemServico.DescricaoTipoOrdemServico,
		   				TipoOrdemServico.Cor CorTipo,
		   				OrdemServico.IdSubTipoOrdemServico,
		   				SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
		   				SubTipoOrdemServico.Cor CorSubTipo,
						OrdemServico.IdPessoa,
						Pessoa.TipoPessoa,
						OrdemServico.DescricaoOS,
						Pessoa.Nome,
						Pessoa.RazaoSocial,
						OrdemServico.IdStatus,
						OrdemServico.DataAgendamentoAtendimento,
						OrdemServico.LoginAtendimento,
						Cidade.NomeCidade,
						Estado.SiglaEstado,
						OrdemServico.IdMarcador,
						Servico.DescricaoServico
					from
						OrdemServico left join Pessoa on (
							OrdemServico.IdPessoa = Pessoa.IdPessoa
						) left join (
							PessoaGrupoPessoa, 
							GrupoPessoa
						) on (
							Pessoa.IdPessoa = PessoaGrupoPessoa.IdPessoa and 
							PessoaGrupoPessoa.IdLoja = '$IdLoja' and 
							PessoaGrupoPessoa.IdLoja = GrupoPessoa.IdLoja and 
							PessoaGrupoPessoa.IdGrupoPessoa = GrupoPessoa.IdGrupoPessoa
						) left join PessoaEndereco on (
							Pessoa.IdPessoa = PessoaEndereco.IdPessoa and 
							PessoaEndereco.IdPessoaEndereco = OrdemServico.IdPessoaEndereco
						) left join (
							Pais,
							Estado,
							Cidade
						) on (
							Pais.IdPais = PessoaEndereco.IdPais and
							Estado.IdPais = Pais.IdPais and 
							Estado.IdEstado = PessoaEndereco.IdEstado and
							Cidade.IdPais = Estado.IdPais and 
							Cidade.IdEstado = Estado.IdEstado and 
							Cidade.IdCidade = PessoaEndereco.IdCidade
						) left join Servico on (
							OrdemServico.IdLoja = Servico.IdLoja and 
							OrdemServico.IdServico = Servico.IdServico
						)$sqlAux,
						TipoOrdemServico,
						SubTipoOrdemServico
					where
						OrdemServico.IdLoja = $IdLoja and
						OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
						OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
						TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico $where
					order by 
						$campoOrder $order, OrdemServico.IdOrdemServico $order $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){	
			if($lin[TipoPessoa] == 1){
				$lin[Nome]	= $lin[getCodigoInterno(3,24)];
			}
			$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=40 and IdParametroSistema=$lin[IdStatus]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			if($lin[CorSubTipo] == ""){
				$lin[CorSubTipo]	=	$lin[CorTipo];
			}
			
			$vi_cidade	=	getCodigoInterno(3,96);
			$vi_tipo	=	getCodigoInterno(3,94);
			$vi_subtipo	=	getCodigoInterno(3,95);
			
			$sql4 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=120 and IdParametroSistema=$lin[IdMarcador]";
			$res4 = @mysql_query($sql4,$con);
			$lin4 = @mysql_fetch_array($res4);
			
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<IdTipoOrdemServico><![CDATA[$lin[IdTipoOrdemServico]]]></IdTipoOrdemServico>";
			$dados	.=	"\n<DescricaoTipoOrdemServico><![CDATA[$lin[DescricaoTipoOrdemServico]]]></DescricaoTipoOrdemServico>";
			$dados	.=	"\n<CorTipo><![CDATA[$lin[CorTipo]]]></CorTipo>";
			$dados	.=	"\n<IdSubTipoOrdemServico><![CDATA[$lin[IdSubTipoOrdemServico]]]></IdSubTipoOrdemServico>";
			$dados	.=	"\n<CorSubTipo><![CDATA[$lin[CorSubTipo]]]></CorSubTipo>";
			$dados	.=	"\n<DescricaoSubTipoOrdemServico><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipoOrdemServico>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdPessoa]]]></IdPessoa>";
			$dados	.=	"\n<TipoPessoa><![CDATA[$lin[TipoPessoa]]]></TipoPessoa>";
			$dados	.=	"\n<DescricaoOS><![CDATA[$lin[DescricaoOS]]]></DescricaoOS>";
			$dados	.=	"\n<DescricaoServico><![CDATA[$lin[DescricaoServico]]]></DescricaoServico>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=	"\n<RazaoSocial><![CDATA[$lin[RazaoSocial]]]></RazaoSocial>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<DataAgendamentoAtendimento><![CDATA[$lin[DataAgendamentoAtendimento]]]></DataAgendamentoAtendimento>";
			$dados	.=	"\n<LoginAtendimento><![CDATA[$lin[LoginAtendimento]]]></LoginAtendimento>";
			$dados	.=	"\n<NomeCidade><![CDATA[$lin[NomeCidade]]]></NomeCidade>";
			$dados	.=	"\n<SiglaEstado><![CDATA[$lin[SiglaEstado]]]></SiglaEstado>";
			$dados	.=	"\n<IdMarcador><![CDATA[$lin[IdMarcador]]]></IdMarcador>";
			$dados	.=	"\n<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<vi_cidade><![CDATA[$vi_cidade]]></vi_cidade>";
			$dados	.=	"\n<vi_tipo><![CDATA[$vi_tipo]]></vi_tipo>";
			$dados	.=	"\n<vi_subtipo><![CDATA[$vi_subtipo]]></vi_subtipo>";
			$dados	.=	"\n<Marcador><![CDATA[$lin4[ValorParametroSistema]]]></Marcador>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Ordem_Servico();
?>
