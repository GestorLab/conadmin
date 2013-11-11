<?
	$localModulo	=	1;

	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_ordem_servico(){
		global $con;
		global $_GET;
		
		$IdPessoa		= $_GET['IdPessoa'];
		$IdOrdemServico	= $_GET['IdOrdemServico'];
		$IdContrato		= $_GET['IdContrato'];
		$where			= "";
		$fromAux		= "";
		
		if($IdPessoa != ''){
			$where .= " and OrdemServico.IdPessoa = $IdPessoa";
		}
		
		if($IdOrdemServico != ''){
			$where .= " and OrdemServico.IdOrdemServico != $IdOrdemServico";
		}
		if($IdContrato != ""){
			$sql = "SELECT
						IdPessoa
					FROM
						Contrato
					WHERE
						IdContrato = $IdContrato";
			$resPessoa = mysql_query($sql,$con);
			$linPessoa = mysql_fetch_array($resPessoa);
			
			$where .= "  AND OrdemServico.IdPessoa = $linPessoa[IdPessoa] AND
						OrdemServico.IdStatus = 100";
		}
		
		$limit = getCodigoInterno(7,10);
		
		if($limit != '') {
			$limit = " limit $limit";
		}
		if(getCodigoInterno(58,1) != 1){
			$varStatus ="	(
								(
									OrdemServico.IdStatus > 99 and
									OrdemServico.IdStatus < 200
								) or(
									OrdemServico.IdStatus > 299 and
									OrdemServico.IdStatus < 500
								)
							) and";
		} else{
			$varStatus ="";
		}
		$sql	=	"select
						OrdemServico.IdOrdemServico,
						OrdemServico.IdContrato,
						subString(Pessoa.Nome, 1, 20) Nome,
						Pessoa.Nome NomeTitle,
						subString(OrdemServico.DescricaoOS, 1, 20) DescricaoOS,
						OrdemServico.DescricaoOS DescricaoOSTitle,
						SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
						OrdemServico.LoginAtendimento,
						OrdemServico.DataAgendamentoAtendimento,
						OrdemServico.IdStatus,
						OrdemServico.DataCriacao,
						OrdemServico.LoginCriacao,
						Servico.DetalheServico,
						Servico.DescricaoServico,
						ParametroSistema.ValorParametroSistema Status
					from
						OrdemServico left join Pessoa on 
						(
							OrdemServico.IdPessoa = Pessoa.IdPessoa
						)left join Servico on 
						(
							OrdemServico.IdLoja = Servico.IdLoja and 
							OrdemServico.IdServico = Servico.IdServico
						),
						TipoOrdemServico,
						SubTipoOrdemServico,
						ParametroSistema
						$fromAux
					where
						OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
						TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
						OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
						OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
						TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico and
						$varStatus
						ParametroSistema.IdGrupoParametroSistema = 40 and
						ParametroSistema.IdParametroSistema = OrdemServico.IdStatus
						$where
					order by 
						OrdemServico.IdOrdemServico desc,
						ParametroSistema.ValorParametroSistema asc
					$limit;";
		$res	=	@mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
			/*if(substr($lin[DataAgendamentoAtendimento],11,19) != "00:00:00"){
				$lin[DataAgendamentoAtendimento] = dataConv($lin[DataCriacao],'Y-m-d H:i:s', 'd/m/Y H:i:s');
			} else{*/
				$lin[DataAgendamentoAtendimento] = dataConv($lin[DataCriacao],'Y-m-d', 'd/m/Y');
			//}
			
			if(($lin[IdStatus] >= 0 && $lin[IdStatus] <= 99) || ($lin[IdStatus] >= 200 && $lin[IdStatus] <= 299)){
				$local_TempoAbertura = "";			
			} else{
				$local_TempoAbertura = diferencaData($lin[DataCriacao], date("Y-m-d H:i:s"));
			}
			
			if($lin[DetalheServico] != ""){
				$DescricaoTitle =  "Descrição Serviço: ".$lin[DetalheServico];
			}else{
				if($lin[DescricaoServico] != ""){
					$DescricaoTitle =  "Descrição Serviço: ".$lin[DescricaoServico];
				}
			}						
			
			if($lin[DescricaoServico] != "" && $lin[DescricaoOSTitle] != ""){
				$DescricaoTitle .=  "\n\n";
			}							
			
			if($lin[DescricaoOSTitle] != ""){
				$DescricaoTitle .=  "Descrição OS: ".$lin[DescricaoOSTitle];
			}
			
			if($lin[DescricaoServico] != ""){
				if($lin[IdContrato]){
					$lin[IdContrato] = "[CO ".$lin[IdContrato]."] ";
				} else{
					$lin[IdContrato] = "";
				}
				
				$lin[NomeTitle] .= " \n".$lin[IdContrato].$lin[DescricaoServico];
			}
			
			$CorOrdemServico = getOrdemServicoCor($lin[IdOrdemServico]);
			
			$dados	.=	"\n<IdOrdemServico>$lin[IdOrdemServico]</IdOrdemServico>";
			$dados	.=	"\n<Cliente><![CDATA[$lin[Nome]]]></Cliente>";
			$dados	.=	"\n<ClienteTitle><![CDATA[$lin[NomeTitle]]]></ClienteTitle>";
			$dados	.=	"\n<Descricao><![CDATA[$lin[DescricaoOS]]]></Descricao>";
			$dados	.=	"\n<DescricaoTitle><![CDATA[$DescricaoTitle]]></DescricaoTitle>";
			$dados	.=	"\n<DescricaoSubTipo><![CDATA[$lin[DescricaoSubTipoOrdemServico]]]></DescricaoSubTipo>";
			$dados	.=	"\n<Atendimento><![CDATA[$lin[LoginAtendimento]]]></Atendimento>";
			$dados	.=	"\n<DataAgendamentoAtendimento><![CDATA[$lin[DataAgendamentoAtendimento]]]></DataAgendamentoAtendimento>";
			$dados	.=	"\n<TempoAbertura><![CDATA[$local_TempoAbertura]]></TempoAbertura>";
			$dados	.=	"\n<Status><![CDATA[$lin[Status]]]></Status>";
			$dados	.=	"\n<Cor><![CDATA[$CorOrdemServico]]></Cor>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			
			$cont++;
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	
	echo get_ordem_servico();
?>