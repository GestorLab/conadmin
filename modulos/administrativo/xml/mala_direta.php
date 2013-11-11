<?
	$localModulo	= 1;

	include('../../../files/conecta.php');
	include('../../../files/funcoes.php');
	include('../../../rotinas/verifica.php');
	
	function get_MalaDireta(){
		global $con;
		global $_GET;
		
		$local_IdLoja			= $_SESSION['IdLoja'];
		$local_Limit 			= $_GET['Limit'];
		$local_IdMalaDireta		= $_GET['IdMalaDireta'];
		$local_Descricao		= $_GET['Descricao'];
		$local_IdTipoMensagem	= $_GET['IdTipoMensagem'];
		$local_IdStatus			= $_GET['IdStatus'];
		$where					= '';
		
		if($local_Limit != ''){
			$local_Limit = "limit 0, $local_Limit";
		}
		
		if($local_IdMalaDireta != ''){
			$where .= " and MalaDireta.IdMalaDireta = $local_IdMalaDireta";
		}
		
		if($local_Descricao != ''){
			$where .= " and MalaDireta.DescricaoMalaDireta like '%$local_Descricao%'";
		}
		
		if($local_IdTipoMensagem != ''){
			$where .= " and MalaDireta.IdTipoMensagem = $local_IdTipoMensagem";
		}
		
		if($local_IdStatus != ''){
			$where .= " and MalaDireta.IdStatus = $local_IdStatus";
		}
		
		$sql = "select 
					MalaDireta.IdMalaDireta, 
					MalaDireta.IdTipoMensagem, 
					MalaDireta.IdTipoConteudo,
					MalaDireta.Filtro_IdPessoa, 
					MalaDireta.Filtro_IdProcessoFinanceiro, 
					MalaDireta.Filtro_IdServico, 
					MalaDireta.Filtro_IdGrupoPessoa, 
					MalaDireta.Filtro_IdContrato, 
					MalaDireta.Filtro_IdStatusContrato, 
					MalaDireta.Filtro_IdPaisEstadoCidade, 
					MalaDireta.DescricaoMalaDireta, 
					MalaDireta.ListaEmail, 
					MalaDireta.ExtModelo, 
					MalaDireta.LogEnvio, 
					MalaDireta.IdStatus, 
					MalaDireta.LoginCriacao, 
					MalaDireta.DataCriacao, 
					MalaDireta.LoginAlteracao, 
					MalaDireta.DataAlteracao, 
					MalaDireta.LoginProcessamento, 
					MalaDireta.DataProcessamento, 
					MalaDireta.LoginEnvio, 
					MalaDireta.DataEnvio,
					TipoMensagem.IdContaEmail,
					TipoMensagem.Conteudo
				from 
					MalaDireta left join TipoMensagem on (
						MalaDireta.IdLoja = TipoMensagem.IdLoja and
						MalaDireta.IdTipoMensagem = TipoMensagem.IdTipoMensagem
					)
				where 
					MalaDireta.IdLoja = '$local_IdLoja'
					$where 
				$Limit;";
		$res = mysql_query($sql,$con);
		
		if(@mysql_num_rows($res) > 0){
			header("content-type: text/xml");
			
			$dados	= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
			$dados .= "\n<reg>";
			
			while($lin = @mysql_fetch_array($res)){
				$lin[Nome] 			= $lin[getCodigoInterno(3,24)];
				$lin[DataAgenda]	= dataConv($lin[Data],'Y-m-d','d/m/y');
				
				$lin[Status]	= getParametroSistema(201, $lin[IdStatus]);
				$lin[CorStatus]	= getCodigoInterno(41, $lin[IdStatus]);
				
				$EndeArquivo = "anexos/mala_direta/$local_IdLoja/$lin[IdMalaDireta]/";
				$LinkArquivo = getParametroSistema(6,3)."/modulos/administrativo/$EndeArquivo";
				
				if($lin[ExtModelo] != ''){
					switch(strtolower($lin[ExtModelo])){
						case "jpg":
							$CodeHTML = "<table cellspacing='0' cellpadding='0'>";
							
							for($y = 0; ; $y++){
								$CodeHTML .= "<tr>";
								
								for($x = 0; $x < 2; $x++){
									$name = md5($x."_".$y).".jpg";
									
									if(!@file_exists("../$EndeArquivo$name")){
										$CodeHTML .= "</tr>";
										break 2;
									}
									
									$CodeHTML .= "<td><img style='float:left; padding:0; margin:0;' src='$LinkArquivo$name' /></td>";
								}
								
								$CodeHTML .= "</tr>";
							}
							
							$CodeHTML .= "</table>";
							break;
						default:
							$CodeHTML = @file_get_contents($LinkArquivo.$lin[IdMalaDireta].".".$lin[ExtModelo]);
					}
				} else{
					$CodeHTML = $lin[Conteudo];
				}
				
				$dados .= "\n<IdMalaDireta>$lin[IdMalaDireta]</IdMalaDireta>";
				$dados .= "\n<IdTipoMensagem><![CDATA[$lin[IdTipoMensagem]]]></IdTipoMensagem>";
				$dados .= "\n<IdTipoConteudo><![CDATA[$lin[IdTipoConteudo]]]></IdTipoConteudo>";
				$dados .= "\n<Filtro_IdPessoa><![CDATA[$lin[Filtro_IdPessoa]]]></Filtro_IdPessoa>";
				$dados .= "\n<Filtro_IdProcessoFinanceiro><![CDATA[$lin[Filtro_IdProcessoFinanceiro]]]></Filtro_IdProcessoFinanceiro>";
				$dados .= "\n<Filtro_IdServico><![CDATA[$lin[Filtro_IdServico]]]></Filtro_IdServico>";
				$dados .= "\n<Filtro_IdGrupoPessoa><![CDATA[$lin[Filtro_IdGrupoPessoa]]]></Filtro_IdGrupoPessoa>";
				$dados .= "\n<Filtro_IdContrato><![CDATA[$lin[Filtro_IdContrato]]]></Filtro_IdContrato>";
				$dados .= "\n<Filtro_IdStatusContrato><![CDATA[$lin[Filtro_IdStatusContrato]]]></Filtro_IdStatusContrato>";
				$dados .= "\n<Filtro_IdPaisEstadoCidade><![CDATA[$lin[Filtro_IdPaisEstadoCidade]]]></Filtro_IdPaisEstadoCidade>";
				$dados .= "\n<DescricaoMalaDireta><![CDATA[$lin[DescricaoMalaDireta]]]></DescricaoMalaDireta>";
				$dados .= "\n<ListaEmail><![CDATA[$lin[ListaEmail]]]></ListaEmail>";
				$dados .= "\n<LogEnvio><![CDATA[$lin[LogEnvio]]]></LogEnvio>";
				$dados .= "\n<ExtModelo><![CDATA[$lin[ExtModelo]]]></ExtModelo>";
				$dados .= "\n<IdStatus><![CDATA[$lin[IdStatus]]]></IdStatus>";
				$dados .= "\n<Status><![CDATA[$lin[Status]]]></Status>";
				$dados .= "\n<CorStatus><![CDATA[$lin[CorStatus]]]></CorStatus>";
				$dados .= "\n<LoginCriacao><![CDATA[$lin[LoginCriacao]]]></LoginCriacao>";
				$dados .= "\n<DataCriacao><![CDATA[$lin[DataCriacao]]]></DataCriacao>";
				$dados .= "\n<LoginAlteracao><![CDATA[$lin[LoginAlteracao]]]></LoginAlteracao>";
				$dados .= "\n<DataAlteracao><![CDATA[$lin[DataAlteracao]]]></DataAlteracao>";
				$dados .= "\n<LoginProcessamento><![CDATA[$lin[LoginProcessamento]]]></LoginProcessamento>";
				$dados .= "\n<DataProcessamento><![CDATA[$lin[DataProcessamento]]]></DataProcessamento>";
				$dados .= "\n<LoginEnvio><![CDATA[$lin[LoginEnvio]]]></LoginEnvio>";
				$dados .= "\n<DataEnvio><![CDATA[$lin[DataEnvio]]]></DataEnvio>";
				$dados .= "\n<IdContaEmail><![CDATA[$lin[IdContaEmail]]]></IdContaEmail>";
				$dados .= "\n<Conteudo><![CDATA[$lin[Conteudo]]]></Conteudo>";
				$dados .= "\n<CodeHTML><![CDATA[$CodeHTML]]></CodeHTML>";
			}
			
			$dados .= "\n</reg>";
			return $dados;
		} else{
			return "false";
		}
	}
	
	echo get_MalaDireta();
?>