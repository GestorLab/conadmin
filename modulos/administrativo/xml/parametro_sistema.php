<?
	$localModulo	=	1;
	
	include ('../../../files/conecta.php');
	include ('../../../files/funcoes.php');
	include ('../../../rotinas/verifica.php');
	
	function get_parametro_sistema(){
		
		global $con;
		global $_GET;
		
		$Limit 							= $_GET['Limit'];
		$IdGrupoParametroSistema		= $_GET['IdGrupoParametroSistema'];
		$IdParametroSistema 			= $_GET['IdParametroSistema'];
		$IdParametroSistemaFalse		= $_GET['IdParametroSistemaFalse'];
		$DescricaoParametroSistema  	= $_GET['DescricaoParametroSistema'];
		$VarStatus					  	= $_GET['VarStatus'];
		$Filtro_GrupoStatusContrato		= $_GET['Filtro_GrupoStatusContrato'];	
		$where			= "";
		
		if($Limit != ''){		$Limit = "limit 0,$Limit";	}
		
		if($IdGrupoParametroSistema != ''){				$where .= " and GrupoParametroSistema.IdGrupoParametroSistema=$IdGrupoParametroSistema";	}
		if($IdParametroSistema != ''){					$where .= " and ParametroSistema.IdParametroSistema in($IdParametroSistema)";	}
		if($DescricaoParametroSistema !=''){			$where .= " and ParametroSistema.DescricaoParametroSistema like '$DescricaoParametroSistema%'";	}
		if($IdParametroSistemaFalse != ''){				$where .= " and ParametroSistema.IdParametroSistema < $IdParametroSistemaFalse";	}
		
		
		if($Filtro_GrupoStatusContrato != ''){	
			switch($Filtro_GrupoStatusContrato){
				case '1': //Cancelados
					$where	.=	" and (ParametroSistema.IdParametroSistema >= 1 and ParametroSistema.IdParametroSistema < 100)";
					break;
				case '2': //Ativo
					$where	.=	" and (ParametroSistema.IdParametroSistema >= 200 and ParametroSistema.IdParametroSistema < 300)";
					break;
				case '3': //Bloqueado
					$where	.=	" and (ParametroSistema.IdParametroSistema >= 300 and ParametroSistema.IdParametroSistema < 400)";
					break;
			}
		}
		
		$sql	=	"select
					     ParametroSistema.IdGrupoParametroSistema,
					     GrupoParametroSistema.DescricaoGrupoParametroSistema,
					     GrupoParametroSistema.Editavel,
					     ParametroSistema.IdParametroSistema,
					     ParametroSistema.DescricaoParametroSistema,
						 ParametroSistema.ValorParametroSistema,
						 ParametroSistema.LoginCriacao,
				      	 ParametroSistema.DataCriacao,
				         ParametroSistema.LoginAlteracao,
				         ParametroSistema.DataAlteracao
					from 
					     ParametroSistema,
					     GrupoParametroSistema
					where
					     GrupoParametroSistema.IdGrupoParametroSistema = ParametroSistema.IdGrupoParametroSistema $where $Limit
					order by
						 ParametroSistema.ValorParametroSistema";
					     
		$res	=	mysql_query($sql,$con);
		if(@mysql_num_rows($res) >=1){
			header ("content-type: text/xml");
			$dados	=	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados	.=	"\n<reg>";
		}else{
			return "false";
		}
		while($lin	=	@mysql_fetch_array($res)){
		
			if($VarStatus!= ''){
				$lin[ValorParametroSistema]	=	str_replace("Temporariamente","até ".$VarStatus,$lin[ValorParametroSistema]);
			}
			
			if($IdGrupoParametroSistema == '69'){
				$temp	=	substr($lin[IdParametroSistema],0,1);
				
				$lin[Cor]	=	getCodigoInterno(15,$temp);
			}
			
			if($IdGrupoParametroSistema == '128'){
				$temp	=	substr($lin[IdParametroSistema],0,1);
				
				$lin[Cor]	=	getParametroSistema(147,$temp);
			}
			
			if($IdGrupoParametroSistema == '40'){
				$IdStatus = $lin[IdParametroSistema];
					
				if($lin[IdParametroSistema] <= 99){
					$IdStatus = 0;	
				}								
				if($IdStatus[0] == 1){
					$IdStatus = 1;
				}
				if($IdStatus[0] == 2){
					$IdStatus = 2;
				}
				if($IdStatus[0] == 3){
					$IdStatus = 3;
				}
				if($IdStatus[0] == 4){
					$IdStatus = 4;
				}
				if($IdStatus[0] == 5){
					$IdStatus = 5;
				}			
			
				$lin[Cor]	=	getCodigoInterno(16,$IdStatus);
			}
			
			if($IdGrupoParametroSistema == '46'){
				$lin[Cor]	=	getCodigoInterno(34,$lin[IdParametroSistema]);
			}
			
			if($IdGrupoParametroSistema == '51'){
				$lin[Cor]	=	getCodigoInterno(35,$lin[IdParametroSistema]);
			}
			
			$dados	.=	"\n<IdGrupoParametroSistema>$lin[IdGrupoParametroSistema]</IdGrupoParametroSistema>";
			$dados	.=	"\n<DescricaoGrupoParametroSistema><![CDATA[$lin[DescricaoGrupoParametroSistema]]]></DescricaoGrupoParametroSistema>";
			$dados	.=	"\n<Editavel><![CDATA[$lin[Editavel]]]></Editavel>";
			$dados	.=	"\n<IdParametroSistema>$lin[IdParametroSistema]</IdParametroSistema>";
			$dados	.=	"\n<DescricaoParametroSistema><![CDATA[$lin[DescricaoParametroSistema]]]></DescricaoParametroSistema>";
			$dados	.=	"\n<ValorParametroSistema><![CDATA[$lin[ValorParametroSistema]]]></ValorParametroSistema>";
			$dados	.=	"\n<Cor><![CDATA[$lin[Cor]]]></Cor>";
			$dados	.=	"\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
			$dados	.=	"\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
			$dados	.=	"\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
			$dados	.=	"\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
		}
		if(mysql_num_rows($res) >=1){
			$dados	.=	"\n</reg>";
			return $dados;
		}
	}
	echo get_parametro_sistema();
?>