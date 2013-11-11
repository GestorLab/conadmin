<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_Lote_Repasse(){
		
		global $con;
		global $_GET;
		
		$Limit 					= $_GET['Limit'];
		$Login					= $_SESSION["Login"];
		$IdLoja					= $_SESSION["IdLoja"];
		$local_IdPessoa			= $_GET['IdPessoa'];
		$local_IdLoteRepasse	= $_GET['IdLoteRepasse'];
		$where					=	"";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($local_IdPessoa != '')	{	
			$where .= " and LoteRepasseTerceiro.IdTerceiro = '$local_IdPessoa'"; 	
		}
		if($local_IdLoteRepasse!=''){			
			$where .= " and LoteRepasseTerceiro.IdLoteRepasse = '$local_IdLoteRepasse'";
		}
		
		$sql	=	"select
							IdLoteRepasse,
							LoteRepasseTerceiro.IdTerceiro, 
							Pessoa.TipoPessoa,
							Pessoa.Nome,
							Pessoa.RazaoSocial,
							ObsRepasse,
							IdStatus,
							Filtro_MesReferencia,
							Filtro_IdServico,
							Filtro_IdPessoa,
							Filtro_IdLocalRecebimento,
							Filtro_IdAgenteAutorizado,
							Filtro_IdCarteira,
							LoteRepasseTerceiro.Filtro_MenorVencimento,
							LoteRepasseTerceiro.Filtro_IdPaisEstadoCidade,
							LoteRepasseTerceiro.DataCriacao,
							LoteRepasseTerceiro.LoginCriacao,
							LoteRepasseTerceiro.DataAlteracao,
							LoteRepasseTerceiro.LoginAlteracao,
							LoteRepasseTerceiro.LoginProcessamento,
							LoteRepasseTerceiro.DataProcessamento,
							LoteRepasseTerceiro.DataConfirmacao,
							LoteRepasseTerceiro.LoginConfirmacao
						from 
							LoteRepasseTerceiro,
							Terceiro,
							Pessoa
						where
							LoteRepasseTerceiro.IdLoja = $IdLoja and
							LoteRepasseTerceiro.IdLoja = Terceiro.IdLoja and
							LoteRepasseTerceiro.IdTerceiro = Terceiro.IdPessoa and
							Terceiro.IdPessoa = Pessoa.IdPessoa
							$where 
						order by
							IdLoteRepasse $Limit";
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			if($lin[TipoPessoa]==1){
				$lin[Nome] 			= $lin[getCodigoInterno(3,24)];
			}
			
			$sql2	=	"select max(IdLoteRepasse) IdLoteRepasse from LoteRepasseTerceiro where IdLoja = $IdLoja and (IdStatus = 2 or IdStatus = 3)";
			$res2	=	mysql_query($sql2,$con);
			$lin2	=	mysql_fetch_array($res2);
			
			$sql3 = "select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=57 and IdParametroSistema=$lin[IdStatus]";
			$res3 = @mysql_query($sql3,$con);
			$lin3 = @mysql_fetch_array($res3);
			
			$Color	  = getCodigoInterno(20,$lin[IdStatus]);
			
			if($lin2[IdLoteRepasse] == $lin[IdLoteRepasse]){
				$Ultimo	=	1;
			}else{
				$Ultimo	=	2;
			}
		
			$dados	.=	"\n<IdLoteRepasse>$lin[IdLoteRepasse]</IdLoteRepasse>";
			$dados	.=	"\n<IdPessoa><![CDATA[$lin[IdTerceiro]]]></IdPessoa>";
			$dados	.=	"\n<Nome><![CDATA[$lin[Nome]]]></Nome>";
			$dados	.=  "\n<ObsRepasse><![CDATA[$lin[ObsRepasse]]]></ObsRepasse>";
			$dados	.=	"\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
			$dados	.=	"\n<Filtro_MesReferencia><![CDATA[$lin[Filtro_MesReferencia]]]></Filtro_MesReferencia>";
			$dados	.=	"\n<Filtro_MenorVencimento><![CDATA[$lin[Filtro_MenorVencimento]]]></Filtro_MenorVencimento>";
			$dados	.=	"\n<Filtro_IdServico><![CDATA[$lin[Filtro_IdServico]]]></Filtro_IdServico>";
			$dados	.=	"\n<Filtro_IdPessoa><![CDATA[$lin[Filtro_IdPessoa]]]></Filtro_IdPessoa>";
			$dados	.=	"\n<Filtro_IdLocalRecebimento><![CDATA[$lin[Filtro_IdLocalRecebimento]]]></Filtro_IdLocalRecebimento>";
			$dados	.=	"\n<Filtro_IdAgenteAutorizado><![CDATA[$lin[Filtro_IdAgenteAutorizado]]]></Filtro_IdAgenteAutorizado>";
			$dados	.=	"\n<Filtro_IdCarteira><![CDATA[$lin[Filtro_IdCarteira]]]></Filtro_IdCarteira>";
			$dados	.=	"\n<UltimoLote><![CDATA[$Ultimo]]></UltimoLote>";
			$dados	.=	"\n<Filtro_IdPaisEstadoCidade><![CDATA[$lin[Filtro_IdPaisEstadoCidade]]]></Filtro_IdPaisEstadoCidade>";
			$dados	.=	"\n<Status><![CDATA[$lin3[ValorParametroSistema]]]></Status>";
			$dados	.=	"\n<Cor><![CDATA[$Color]]></Cor>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
			$dados	.=	"\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
			$dados	.=	"\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";
			$dados	.=	"\n<DataConfirmacao><![CDATA[$lin[DataConfirmacao]]]></DataConfirmacao>";
			$dados	.=	"\n<LoginConfirmacao><![CDATA[$lin[LoginConfirmacao]]]></LoginConfirmacao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_Lote_Repasse();
?>
