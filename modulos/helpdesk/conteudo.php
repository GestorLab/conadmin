<?
	$localModulo	=	2;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/conecta_cntsistemas.php');
	include ('../../files/funcoes.php');
	include ('../../files/calendario.php');
	include ('rotinas/verifica.php');
	
	$local_IdLoja				=	$_SESSION['IdLojaHD'];
	$local_Login				=	$_SESSION['LoginHD'];
	
	$local_CampoOrderBy_0		=   $_GET['CampoOrderBy_0'];
	$local_Order_0				=   $_GET['Order_0'];
	
	$local_CampoOrderBy_1		=   $_GET['CampoOrderBy_1'];
	$local_Order_1				=   $_GET['Order_1'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/data.js'></script>
		<script type = 'text/javascript' src = 'js/conteudo.js'></script>
	</head>
	<body>
		<div id='carregando'>&nbsp;</div>
		<div class='tit'>&nbsp;</div>
		
		<?
			include("alertas.php");
			
			if($conCNT){
				$IdPessoa = getParametroSistema(4,7);
				$where = "";
				
				//Permissao para visualizar Quadro de Help Desk	
				if($local_Order_1 == '' || $local_Order_1 == 2){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy_1 = 'ASC';
					$local_Order_1 = 1;
				}else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
					$local_OrderBy_1  = 'DESC';
					$local_Order_1 = 2;
				}
				
				if($local_CampoOrderBy_1 == ''){
					$local_ImagemSeta_1[1] = $local_ImagemSetaDefault; 
				}
				
				$local_ImagemSeta_1[$local_CampoOrderBy_1] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy_1){
					case 1:
						$local_CampoOrderBy_1 = "HelpDesk.IdTicket";
						break;
					case 2:
						$local_CampoOrderBy_1 = "HelpDeskTipo.DescricaoTipoHelpDesk";
						break;
					case 3:
						$local_CampoOrderBy_1 = "HelpDesk.Assunto";
						break;
					case 4:
						$local_CampoOrderBy_1 = "HelpDesk.LoginResponsavel";
						break;
					case 5:
						$local_CampoOrderBy_1 = "HelpDesk.DataCriacao";
						break;
					case 6:
						$local_CampoOrderBy_1 = "HelpDesk.PrevisaoEtapa";
						break;
					case 7:
						$local_CampoOrderBy_1 = "Status";
						break;
					default:
						$local_CampoOrderBy_1 = "Status, HelpDesk.PrevisaoEtapa, HelpDesk.LoginCriacao, HelpDesk.IdTicket";
						break;
				}
				
				$IdPessoa = getParametroSistema(4,7);
					
				if(getParametroSistema(229,1) == 1){
					$where = " and HelpDesk.IdLojaAbertura = $local_IdLoja"; 
				}
				
				$sql	=	"select
								HelpDesk.IdTicket,
								HelpDesk.IdPessoa,
								HelpDesk.IdMarcador,
								concat('Assunto: (',HelpDesk.Assunto,')') Assunto,
								subString(HelpDesk.Assunto,1,40) AssuntoTemp,
								HelpDesk.IdTipoHelpDesk,
								HelpDesk.IdSubTipoHelpDesk,
								HelpDesk.IdStatus,
								HelpDesk.DataCriacao,
								HelpDesk.PrevisaoEtapa,
								HelpDesk.LoginCriacao,
								HelpDeskTipo.DescricaoTipoHelpDesk,
								HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
								subString(HelpDeskTipo.DescricaoTipoHelpDesk, 1, 12) DescricaoTipoHelpDeskTemp,
								subString(HelpDeskSubTipo.DescricaoSubTipoHelpDesk, 1, 12) DescricaoSubTipoHelpDeskTemp,
								ParametroSistema.ValorParametroSistema Status
							from
								HelpDesk,
								HelpDeskTipo,
								HelpDeskSubTipo,
								ParametroSistema
							where
								HelpDesk.IdLoja = 1 and								
								HelpDesk.IdPessoa = $IdPessoa and
								(
									HelpDesk.IdStatus < 400 or
									HelpDesk.IdStatus > 499 
								)and
								HelpDeskTipo.IdTipoHelpDesk = HelpDesk.IdTipoHelpDesk and
								HelpDeskSubTipo.IdSubTipoHelpDesk = HelpDesk.IdSubTipoHelpDesk and
								HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
								ParametroSistema.IdGrupoParametroSistema = 128 and
								ParametroSistema.IdParametroSistema = HelpDesk.IdStatus
								$where
							order by
								$local_CampoOrderBy_1 $local_OrderBy_1,
								HelpDesk.IdTicket $local_OrderBy_1	
				";	
				$res	=	mysql_query($sql,$conCNT);
				if(@mysql_num_rows($res) >=1){
					echo"
				<div class='quadroAviso' style='width:100%-10px; margin:5px 5px 0 5px' id='Fatura'>
					<p class='tit'><a href='listar_help_desk.php' style='color:#FFF'>HELP DESK</a> <a href='cadastro_help_desk.php' style='color:#FFF'>[+]</a></p>
					<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
						<tr>
							<td width='40px'><a href='conteudo.php?CampoOrderBy_1=1&Order_1=$local_Order_1' target='_self'><B>Id</B>$local_ImagemSeta_1[1]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_1=2&Order_1=$local_Order_1' target='_self'><B>Tipo/SubTipo</B>$local_ImagemSeta_1[2]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_1=3&Order_1=$local_Order_1' target='_self'><B>Assunto</B>$local_ImagemSeta_1[3]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_1=4&Order_1=$local_Order_1' target='_self'><B>Usuário Cad.</B>$local_ImagemSeta_1[4]</a></td>
							<td width='92px'><a href='conteudo.php?CampoOrderBy_1=5&Order_1=$local_Order_1' target='_self'><B>Data de Aber.</B>$local_ImagemSeta_1[5]</a></td>	
							<td width='72px'><a href='conteudo.php?CampoOrderBy_1=6&Order_1=$local_Order_1' target='_self'><B>Previsão</B>$local_ImagemSeta_1[6]</a></td>
							<td width='180px'><a href='conteudo.php?CampoOrderBy_1=7&Order_1=$local_Order_1' target='_self'><B>Status</B>$local_ImagemSeta_1[7]</a></td>							
						</tr>";
					while($lin	=	@mysql_fetch_array($res)){
						$lin[DataHoraTemp] 	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
						
						if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
							$lin[PrevisaoEtapa] = diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
						} else{
							$lin[PrevisaoEtapa] = '';
						}
						
						if($lin[IdStatus] < 400 || $lin[IdStatus] > 499){
							$local_CorHelpDesk = getParametroSistema(154,$lin[IdStatus]);
						} else{
							$local_CorHelpDesk = '#FFFFFF';
						}
						
						if($lin[DescricaoTipoHelpDesk] != '' && $lin[DescricaoSubTipoHelpDesk] != ''){
							$TipoSubTipo		= "$lin[DescricaoTipoHelpDesk]/$lin[DescricaoSubTipoHelpDesk]";
							$TipoSubTipoTemp	= "$lin[DescricaoTipoHelpDeskTemp]/$lin[DescricaoSubTipoHelpDeskTemp]";
						} else{
							$TipoSubTipo		= '';
							$TipoSubTipoTemp	= '';
						}
						
						$sql1 = "SELECT 
									Obs,
									DataCriacao
								FROM 
									HelpDeskHistorico 
								WHERE 
									IdTicket = '$lin[IdTicket]'
								ORDER BY 
									IdTicketHistorico ASC 
								LIMIT 1;";
						$res1 = @mysql_query($sql1,$conCNT);
						$lin1 = @mysql_fetch_array($res1);
						
						if($lin1[Obs] != ''){
							$lin1[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin1[Obs]));
							$lin[Assunto] .= " <br />Data: " . dataConv($lin1[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
							$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Mensagem: (", endArray(explode("<b>Escrito por:</b> ", $lin1[Obs]))));
						}
						
						$TipoSubTipoTemp = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $TipoSubTipoTemp));
						
						echo"
						<tr>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[IdTicket]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]' onmousemove=\"quadro_alt(event, this, '".str_replace('"', "&quot;", $TipoSubTipo)."');\">$TipoSubTipoTemp</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]' onmousemove=\"quadro_alt(event, this, '".str_replace('"', "&quot;", $lin[Assunto])."');\">$lin[AssuntoTemp]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[LoginCriacao]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[DataHoraTemp]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[PrevisaoEtapa]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[Status]</a></td>
						</tr>";	
					}
					echo"
					</table>	
				</div>";
				}
				
				//Permissao para visualizar Quadro de Help Desk	
				if($local_Order_0 == '' || $local_Order_0 == 2){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy_1 = 'ASC';
					$local_Order_0 = 1;
				}else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
					$local_OrderBy_1  = 'DESC';
					$local_Order_0 = 2;
				}
				
				if($local_CampoOrderBy_0 == ''){
					$local_ImagemSeta_0[1] = $local_ImagemSetaDefault; 
				}
				
				$local_ImagemSeta_0[$local_CampoOrderBy_0] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy_0){
					case 1:
						$local_CampoOrderBy_0 = "HelpDesk.IdTicket";
						break;
					case 2:
						$local_CampoOrderBy_0 = "HelpDeskTipo.DescricaoTipoHelpDesk";
						break;
					case 3:
						$local_CampoOrderBy_0 = "HelpDesk.Assunto";
						break;
					case 4:
						$local_CampoOrderBy_0 = "HelpDesk.LoginResponsavel";
						break;
					case 5:
						$local_CampoOrderBy_0 = "HelpDesk.DataCriacao";
						break;
					case 6:
						$local_CampoOrderBy_0 = "HelpDesk.PrevisaoEtapa";
						break;
					case 7:
						$local_CampoOrderBy_0 = "Status";
						break;
					default:
						$local_CampoOrderBy_0 = "Status, HelpDesk.PrevisaoEtapa, HelpDesk.LoginCriacao, HelpDesk.IdTicket";
						break;
				}
				
				$sql	=	"select
								HelpDesk.IdTicket,
								HelpDesk.IdPessoa,
								HelpDesk.IdMarcador,
								concat('Assunto: (',HelpDesk.Assunto,')') Assunto,
								subString(HelpDesk.Assunto,1,40) AssuntoTemp,
								HelpDesk.IdTipoHelpDesk,
								HelpDesk.IdSubTipoHelpDesk,
								HelpDesk.IdStatus,
								HelpDesk.DataCriacao,
								HelpDesk.PrevisaoEtapa,
								HelpDesk.LoginCriacao,
								HelpDeskTipo.DescricaoTipoHelpDesk,
								HelpDeskSubTipo.DescricaoSubTipoHelpDesk,
								subString(HelpDeskTipo.DescricaoTipoHelpDesk, 1, 12) DescricaoTipoHelpDeskTemp,
								subString(HelpDeskSubTipo.DescricaoSubTipoHelpDesk, 1, 12) DescricaoSubTipoHelpDeskTemp,
								ParametroSistema.ValorParametroSistema Status
							from
								HelpDesk,
								HelpDeskTipo,
								HelpDeskSubTipo,
								ParametroSistema
							where
								HelpDesk.IdLoja = 1 and
								HelpDesk.IdPessoa = $IdPessoa and
								HelpDesk.LoginCriacao = '$local_Login' and
								(
									HelpDesk.IdStatus < 400 or
									HelpDesk.IdStatus > 499 
								)and
								HelpDeskTipo.IdTipoHelpDesk = HelpDesk.IdTipoHelpDesk and
								HelpDeskSubTipo.IdSubTipoHelpDesk = HelpDesk.IdSubTipoHelpDesk and
								HelpDeskTipo.IdTipoHelpDesk = HelpDeskSubTipo.IdTipoHelpDesk and
								ParametroSistema.IdGrupoParametroSistema = 128 and
								ParametroSistema.IdParametroSistema = HelpDesk.IdStatus
								$where
							order by
								$local_CampoOrderBy_0 $local_OrderBy_1,
								HelpDesk.IdTicket $local_OrderBy_1	
				";	
				$res	=	mysql_query($sql,$conCNT);
				if(@mysql_num_rows($res) >=1){
					echo"
				<div class='quadroAviso' style='width:100%-10px; margin:5px 5px 0 5px' id='Fatura'>
					<p class='tit'><a href='listar_help_desk.php' style='color:#FFF'>HELP DESK - INDIVIDUAL</a> <a href='cadastro_help_desk.php' style='color:#FFF'>[+]</a></p>
					<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
						<tr>
							<td width='40px'><a href='conteudo.php?CampoOrderBy_0=1&Order_0=$local_Order_0' target='_self'><B>Id</B>$local_ImagemSeta_0[1]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_0=2&Order_0=$local_Order_0' target='_self'><B>Tipo/SubTipo</B>$local_ImagemSeta_0[2]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_0=3&Order_0=$local_Order_0' target='_self'><B>Assunto</B>$local_ImagemSeta_0[3]</a></td>
							<td><a href='conteudo.php?CampoOrderBy_0=4&Order_0=$local_Order_0' target='_self'><B>Usuário Cad.</B>$local_ImagemSeta_0[4]</a></td>
							<td width='92px'><a href='conteudo.php?CampoOrderBy_0=5&Order_0=$local_Order_0' target='_self'><B>Data de Aber.</B>$local_ImagemSeta_0[5]</a></td>	
							<td width='72px'><a href='conteudo.php?CampoOrderBy_0=6&Order_0=$local_Order_0' target='_self'><B>Previsão</B>$local_ImagemSeta_0[6]</a></td>
							<td width='180px'><a href='conteudo.php?CampoOrderBy_0=7&Order_0=$local_Order_0' target='_self'><B>Status</B>$local_ImagemSeta_0[7]</a></td>							
						</tr>";
					while($lin	=	@mysql_fetch_array($res)){
						$lin[DataHoraTemp] 	= dataConv($lin[DataCriacao],"Y-m-d","d/m/Y");
						
						if($lin[PrevisaoEtapa] != '' && $lin[IdStatus] != 200){
							$lin[PrevisaoEtapa] = diferencaDataRegressivo($lin[PrevisaoEtapa], date("Y-m-d H:i:s"));
						} else{
							$lin[PrevisaoEtapa] = '';
						}
						
						if($lin[IdStatus] < 400 || $lin[IdStatus] > 499){
							$local_CorHelpDesk = getParametroSistema(154,$lin[IdStatus]);
						} else{
							$local_CorHelpDesk = '#FFFFFF';
						}
						
						if($lin[DescricaoTipoHelpDesk] != '' && $lin[DescricaoSubTipoHelpDesk] != ''){
							$TipoSubTipo		= "$lin[DescricaoTipoHelpDesk]/$lin[DescricaoSubTipoHelpDesk]";
							$TipoSubTipoTemp	= "$lin[DescricaoTipoHelpDeskTemp]/$lin[DescricaoSubTipoHelpDeskTemp]";
						} else{
							$TipoSubTipo		= '';
							$TipoSubTipoTemp	= '';
						}
						
						$sql1 = "SELECT 
									Obs,
									DataCriacao
								FROM 
									HelpDeskHistorico 
								WHERE 
									IdTicket = '$lin[IdTicket]'
								ORDER BY 
									IdTicketHistorico ASC 
								LIMIT 1;";
						$res1 = @mysql_query($sql1,$conCNT);
						$lin1 = @mysql_fetch_array($res1);
						
						if($lin1[Obs] != ''){
							$lin1[Obs] = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $lin1[Obs]));
							$lin[Assunto] .= " <br />Data: " . dataConv($lin1[DataCriacao],"Y-m-d H:i:s","d/m/Y H:i:s");
							$lin[Assunto] .= " <br />Escrito por: (" . str_replace("</div>", ')', str_replace(" <div style=\'margin-top:6px;\'>", ") <br />Mensagem: (", endArray(explode("<b>Escrito por:</b> ", $lin1[Obs]))));
						}
						
						$TipoSubTipoTemp = str_replace(array("\r", "\n"), '', str_replace("'", "\'", $TipoSubTipoTemp));
						
						echo"
						<tr>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[IdTicket]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]' onmousemove=\"quadro_alt(event, this, '".str_replace('"', "&quot;", $TipoSubTipo)."');\">$TipoSubTipoTemp</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]' onmousemove=\"quadro_alt(event, this, '".str_replace('"', "&quot;", $lin[Assunto])."');\">$lin[AssuntoTemp]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[LoginCriacao]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[DataHoraTemp]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[PrevisaoEtapa]</a></td>
							<td style='background-color:$local_CorHelpDesk;'><a href='cadastro_help_desk.php?IdTicket=$lin[IdTicket]'>$lin[Status]</a></td>
						</tr>";	
					}
					echo"
					</table>	
				</div>";
				}
			}
			
			echo "<div style='min-width:200px; background-color:#fff3cb; border:1px solid #fdbf68; margin:5px 5px 0 5px; padding:5px;'><table cellpadding='0' cellspacing='0' style='margin-bottom:0;'>";
			$CorStatus = array();
			$Status = array();
			$cont = 0;
			$sql = "select 
						ParametroSistema.ValorParametroSistema,
						HelpDeskStatus.Status 
					from 
						ParametroSistema,
						(
							select 
								IdParametroSistema,
								ValorParametroSistema Status
							from 
								ParametroSistema
							where 
								IdGrupoParametroSistema = 128
						) HelpDeskStatus
					where 
						ParametroSistema.IdGrupoParametroSistema = 154 and
						ParametroSistema.IdParametroSistema = HelpDeskStatus.IdParametroSistema";
			$res = mysql_query($sql,$conCNT);
			while($lin = mysql_fetch_array($res)){
				if(!in_array($lin[ValorParametroSistema],$CorStatus)){
					if($lin[ValorParametroSistema] == ''){
						$lin[ValorParametroSistema] = "#ffffff";
					}
					
					$CorStatus[$cont] = $lin[ValorParametroSistema];
					$Status[$cont] = $lin[Status];
					$cont++;
				} else{
					$key = array_search($lin[ValorParametroSistema],$CorStatus);
					$Status[$key] .= "/$lin[Status]";
				}
			}
			
			for($i = 0; $i < count($Status); $i++){
				echo "<tr><td style='width:14px;'><div style='background-color:$CorStatus[$i]; height:8px; width:8px; border:1px solid #666;'></div></td><td>Indica $Status[$i]</td></tr>";
			}
			
			echo "</table></div>";
		?>
	</body>
</html>
