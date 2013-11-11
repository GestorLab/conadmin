<?
	$localModulo	=	1;
	$localMenu		=	true;

	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	include ('../../files/calendario.php');
	
	if(getParametroSistema(252,1) == 1){
		include ('../../files/conecta_cntsistemas.php');
	}

	$local_IdLoja				=	$_SESSION['IdLoja'];
	$local_Login				=	$_SESSION['Login'];
	$local_IdPessoaLogin		= 	$_SESSION['IdPessoa'];
	
	$local_CampoOrderBy			=   $_GET['CampoOrderBy'];
	$local_Order				=   $_GET['Order'];	
	
	$local_CampoOrderBy1		=   $_GET['CampoOrderBy1'];
	$local_Order1				=   $_GET['Order1'];
	
	$local_CampoOrderBy2		=   $_GET['CampoOrderBy2'];
	$local_Order2				=   $_GET['Order2'];
	
	$local_CampoOrderBy3		=   $_GET['CampoOrderBy3'];
	$local_Order3				=   $_GET['Order3'];
	
	$local_CampoOrderBy4		=   $_GET['CampoOrderBy4'];
	$local_Order4				=   $_GET['Order4'];
	
	$local_CampoOrderBy5		=   $_GET['CampoOrderBy5'];
	$local_Order5				=   $_GET['Order5'];
	
	$local_CampoOrderBy6		=   $_GET['CampoOrderBy6'];
	$local_Order6				=   $_GET['Order6'];
	
	$local_CampoOrderBy7		=   $_GET['CampoOrderBy7'];
	$local_Order7				=   $_GET['Order7'];
	
	$local_CampoOrderBy8		=   $_GET['CampoOrderBy8'];
	$local_Order8				=   $_GET['Order8'];
	
	$Restricao_Loja				= "";
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
		<meta http-equiv="refresh" content="<?=getParametroSistema(108,2)?>">
	</head>
	<body>
		<div id='carregando'>&nbsp;</div>
		<div style='padding-bottom:5px;'>
			<div class='tit'>&nbsp;</div>	
			<?
				include("alertas.php");
				@include("conteudo_personalizado.php");
			?>
			<div style='width:100%-10px; margin: 0 7px 0 5px;'>
				<table style='width:100%; margin:0;' cellpadding='0' cellspacing='0'>
					<tr>
					<?
						$table	=	0;
						
						///Permissao para viszualizar Quadro Aniversariantes Pessoa
						
						$sql = "Select 
									RestringirPessoa
								From 
									Loja
								Where
									IdLoja = $local_IdLoja";
						$res = mysql_query($sql,$con);
						$lin = mysql_fetch_array($res);
						if($lin[RestringirPessoa] == 1){
							$Restricao_Loja = " and IdLoja = $local_IdLoja";
						}else{
							$Restricao_Loja = "";
						}
						$quadro_ativo = 0;
						$sql	=	"select 
											IdParametroSistema IdQuadroAviso,
											IdParametroSistema
									from 
											ParametroSistema,
											GrupoUsuarioQuadroAviso
									where   
											GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
											GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
											IdGrupoParametroSistema = 56 and 
											IdParametroSistema = 6 and
											IdGrupoUsuario in (select 
																	UsuarioGrupoUsuario.IdGrupoUsuario 
															   from 	
																	UsuarioGrupoUsuario, 
																	GrupoUsuario, 
																	Usuario, 
																	Pessoa 
															   where 	
																	UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
																	UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
																	UsuarioGrupoUsuario.Login = Usuario.Login and 
																	UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
																	Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
																	UsuarioGrupoUsuario.Login = '$local_Login' 
																group by	
																	UsuarioGrupoUsuario.IdGrupoUsuario);";
						$res	=	mysql_query($sql,$con);
						if(mysql_num_rows($res)>=1){
							echo"<td class='quadroAviso' style='width:300px; padding-right:5px; background-color:#FFF; border:none;'>";
							$quadro_ativo = 1;
							$table++;
							
							$data		=	date("m-d");
							$data_mais	=	strftime("%m-%d", strtotime("+7 days")); 
							$sql	=	"select 
											IdPessoa,
											substr(Nome,1,30) Nome,
											substr(DataNascimento,6,5) DataNascimento 
										from 
											Pessoa 
										where 
											TipoPessoa = 2 and 
											substr(DataNascimento,6,5) >= '$data' and
											substr(DataNascimento,6,5) <= '$data_mais' and
											TipoUsuario is null
											$Restricao_Loja
										order by
											DataNascimento,
											Nome";
							$res	=	@mysql_query($sql,$con);
							
							while($lin = @mysql_fetch_array($res)){
								$dia 	= substr($lin[DataNascimento],3,2);
								$rs 	= strftime('%w',strtotime(date('Y').'-'.$lin[DataNascimento]));
								
								switch($rs) {
									case 0: $diaSemana = 'Domingo'; break;
									case 1: $diaSemana = 'Segunda-feira'; break;
									case 2: $diaSemana = 'Terça-feira'; break;
									case 3: $diaSemana = 'Quarta-feira'; break;
									case 4: $diaSemana = 'Quinta-feira'; break;
									case 5: $diaSemana = 'Sexta-feira'; break;
									case 6: $diaSemana = 'Sábado'; break;
								}
								
								$lin[Nome]	=	sem_quebra_string($lin[Nome]);
								
								$nome		=	"<a href='cadastro_pessoa.php?IdPessoa=$lin[IdPessoa]'>".$lin[Nome]."</a>";
								
								$msg		=	str_replace('$nome',$nome,getParametroSistema(22,3));
								$msg		=	str_replace('$diaSemana',$diaSemana,$msg);
								$msg		=	str_replace('$dia',$dia,$msg);
								
								echo "<div class='quadroAvisoAniversario'><p>".$msg."</p></div>";
							}
						}
						
						///Permissao para viszualizar Quadro Aniversariantes Usuário do Sistema
						$sql	=	"select 
											IdParametroSistema IdQuadroAviso,
											IdParametroSistema
									from 
											ParametroSistema,
											GrupoUsuarioQuadroAviso
									where   
											GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
											GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
											IdGrupoParametroSistema = 56 and 
											IdParametroSistema = 18 and
											IdGrupoUsuario in (select 
																	UsuarioGrupoUsuario.IdGrupoUsuario 
															   from 	
																	UsuarioGrupoUsuario, 
																	GrupoUsuario, 
																	Usuario, 
																	Pessoa 
															   where 	
																	UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
																	UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
																	UsuarioGrupoUsuario.Login = Usuario.Login and 
																	UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
																	Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and 
																	UsuarioGrupoUsuario.Login = '$local_Login' 
																group by	
																	UsuarioGrupoUsuario.IdGrupoUsuario);";
						$res	=	mysql_query($sql,$con);
						if(mysql_num_rows($res)>=1){
							if($quadro_ativo == 0)
								echo"<td class='quadroAviso' style='width:300px; padding-right:5px; background-color:#FFF; border:none;'>";
							$table++;
							
							$data		=	date("m-d");
							$data_mais	=	strftime("%m-%d", strtotime("+7 days")); 
							$sql	=	"select 
											IdPessoa,
											substr(Nome,1,30) Nome,
											substr(DataNascimento,6,5) DataNascimento 
										from 
											Pessoa 
										where 
											TipoPessoa = 2 and 
											substr(DataNascimento,6,5) >= '$data' and
											substr(DataNascimento,6,5) <= '$data_mais' and
											TipoUsuario = 1
											$Restricao_Loja
										order by
											DataNascimento,
											Nome";
							$res	=	@mysql_query($sql,$con);
							
							while($lin = @mysql_fetch_array($res)){
								$dia 	= substr($lin[DataNascimento],3,2);
								$rs 	= strftime('%w',strtotime(date('Y').'-'.$lin[DataNascimento]));
								
								switch($rs) {
									case 0: $diaSemana = 'Domingo'; break;
									case 1: $diaSemana = 'Segunda-feira'; break;
									case 2: $diaSemana = 'Terça-feira'; break;
									case 3: $diaSemana = 'Quarta-feira'; break;
									case 4: $diaSemana = 'Quinta-feira'; break;
									case 5: $diaSemana = 'Sexta-feira'; break;
									case 6: $diaSemana = 'Sábado'; break;
								}
								
								$lin[Nome]	=	sem_quebra_string($lin[Nome]);
								
								$nome		=	"<a href='cadastro_pessoa.php?IdPessoa=$lin[IdPessoa]'>".$lin[Nome]."</a>";
								
								$msg		=	str_replace('$nome',$nome,getParametroSistema(22,3));
								$msg		=	str_replace('$diaSemana',$diaSemana,$msg);
								$msg		=	str_replace('$dia',$dia,$msg);
								
								echo "<div class='quadroAvisoAniversario'><p>".$msg."</p></div>";
							}
						}
						
						///Permissao para viszualizar Quadro Calendario
						$sql	=	"select 
										IdParametroSistema IdQuadroAviso
								from 
										ParametroSistema,
										GrupoUsuarioQuadroAviso
								where   
										GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
										GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
										IdGrupoParametroSistema = 56 and 
										IdParametroSistema = 4 and
										IdGrupoUsuario in (select UsuarioGrupoUsuario.IdGrupoUsuario from 	UsuarioGrupoUsuario, GrupoUsuario, Usuario, Pessoa where UsuarioGrupoUsuario.IdLoja = $local_IdLoja and UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and UsuarioGrupoUsuario.Login = Usuario.Login and UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and UsuarioGrupoUsuario.Login = '$local_Login' group by	UsuarioGrupoUsuario.IdGrupoUsuario);";
						$res	=	mysql_query($sql,$con);
						if(mysql_num_rows($res)>=1){
							if($table == 0){
								echo"<td valign='top' class='quadroAviso' style='width:300px; padding-right:5px; background-color:#FFF; border:none;'>";
								
								$table++;
							}
							$NomeBLCalendario = "Calendario";
							echo"
								<div style='padding-top:5px;'>
									<div style='width:300px; border:1px #004492 solid;'>
										<p class='tit'>CALENDÁRIO <a href='cadastro_datas_especiais.php' style='color:#FFF'>[+]</a></p>
										<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_calendario' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLCalendario."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
										<div id='tabela".$NomeBLCalendario."'>
											<form name='calendar'>
												<input type='hidden' name='mesAnt' value=''>
												<input type='hidden' name='anoAnt' value=''>
												<input type='hidden' name='mesProx' value=''>
												<input type='hidden' name='anoProx' value=''>
												<table id='quadroAvisoCalendario'>
													<tr class='quadroAvisoCalendarioMes'>
														<th style='text-align:left; cursor:pointer' onClick=\"calendario(document.calendar.mesAnt.value,document.calendar.anoAnt.value)\" id='Ant'></th>
														<th colspan=5 id='Atual'></th>
														<th style='text-align:right; cursor:pointer' onClick=\"calendario(document.calendar.mesProx.value,document.calendar.anoProx.value)\" id='Prox'></th>
													</tr>
													<tr>
														<th>DOM</th>
														<th>SEG</th>
														<th>TER</th>
														<th>QUA</th>
														<th>QUI</th>
														<th>SEX</th>
														<th>SAB</th>
													</tr>";
												
												$month			=  date('n');
												$ano			=  date('Y');	
												$mes[$month]	=  ultimoDiaMes($month, $ano);
												$posIniSemana	=  date('w', mktime (0, 0, 0, $month,  01,  date("Y")));
												for($ii=1;	$ii<=1;	$ii++){
													$posIni	=	calendario($local_IdLoja,$mes[$month],$posIniSemana,$month);
												}
												
											echo"</table>";
												
											$anoMes	=	date("Y-m");	//$anoMes	=	"2008-06";
											$sql	=	"select ValorParametroSistema from ParametroSistema, DatasEspeciais where IdGrupoParametroSistema=52 and DatasEspeciais.TipoData = ParametroSistema.IdParametroSistema and substr(DatasEspeciais.Data,1,7) = '$anoMes' group by IdParametroSistema order by ValorParametroSistema ASC";				
											$res 	= @mysql_query($sql,$con);
											
											echo"<table id='Legenda'>";		
											
											$i	=	0;
											while($lin 	= @mysql_fetch_array($res)){
												$lin[ValorParametroSistema]	=	explode("\n",$lin[ValorParametroSistema]);
				
												$lin[Descricao]	=	$lin[ValorParametroSistema][0];
												$lin[Cor]		=	$lin[ValorParametroSistema][1];
												
												if($i%2 == 0){
													echo"<tr>
															<td style='background-color:$lin[Cor]; width: 10px'>&nbsp;</td>
															<td style='width: 155px'>$lin[Descricao]</td>";
												}else{
													echo"
														<td style='background-color:$lin[Cor]; width: 10px'>&nbsp;</td>
														<td style='width: 155px'>$lin[Descricao]</td>
													</tr>";
												}
												$i++;	
											}
												
											echo"</table>
										</form>
									</div>
								</div>
								</div>
							";
							if(empty($_SESSION["filtro_".$NomeBLCalendario])){
								$_SESSION["filtro_".$NomeBLCalendario] = 1;
							} else if($_SESSION["filtro_".$NomeBLCalendario] == 2){
								echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_calendario'), 'tabela".$NomeBLCalendario."'); </script>";
							}
							
						}
						
						if($table>0){
							echo"</td>";
						}
						
						///Permissao para visualizar Quadro Agenda
						$sql	=	"select 
										IdParametroSistema IdQuadroAviso
								from 
										ParametroSistema,
										GrupoUsuarioQuadroAviso
								where   
										GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
										GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
										IdGrupoParametroSistema = 56 and 
										IdParametroSistema = 5 and
										IdGrupoUsuario in (select UsuarioGrupoUsuario.IdGrupoUsuario from 	UsuarioGrupoUsuario, GrupoUsuario, Usuario, Pessoa where UsuarioGrupoUsuario.IdLoja = $local_IdLoja and UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and UsuarioGrupoUsuario.Login = Usuario.Login and UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and Usuario.IdPessoa = Pessoa.IdPessoa and Pessoa.TipoUsuario = 1 and UsuarioGrupoUsuario.Login = '$local_Login' group by	UsuarioGrupoUsuario.IdGrupoUsuario);";
						$res	=	mysql_query($sql,$con);
						if(mysql_num_rows($res)>=1){
							$NomeBLAgenda = "Agenda";
							echo"
							<td valign='top' style='padding-top:5px;'>
								<div style='border:1px #004492 solid; margin-right:-2px;'>
								<p class='tit' style='margin:0; padding: 5px 3px 5px 5px; cursor:normal'><a href='listar_agenda.php' style='color:#FFF;  cursor:pointer'>AGENDA</a> <a href='cadastro_agenda.php' style='color:#FFF; cursor:pointer'>[+]</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_agenda' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLAgenda."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLAgenda."'>
									<table id='quadroAvisoAgenda' cellspacing='0' style='width:100%;'>
										<tr>
											<th class='quadroAvisoAgendaData'>Data</th>
											<th class='quadroAvisoAgendaCliente' style='text-align:left'>Cliente</th>
											<th class='quadroAvisoAgendaDesc' style='text-align:left'>Descrição</th>
										</tr>";
									
										$i		=	0;	
										$sql	=	"select	IdAgenda,Data,Hora,Descricao,TipoPessoa,RazaoSocial,Nome,Status from Agenda left join Pessoa on (Agenda.IdPessoa = Pessoa.IdPessoa) where Login='".$local_Login."' and Agenda.Data <= curdate() and Status = 1 order by Data ASC,Hora ASC, IdAgenda ASC";
										$res 	= @mysql_query($sql,$con);
										while($lin 	= @mysql_fetch_array($res)){
											if($lin[TipoPessoa] == 1){
												$lin[Nome]	= $lin[getCodigoInterno(3,24)];
											}
											$lin[DataAgenda]	= dataConv($lin[Data],'Y-m-d','d/m/y');
											
											$LinkIni	=	"<a href='cadastro_agenda.php?IdAgenda=$lin[IdAgenda]'>";
											$LinkFim	= 	"</a>";
								
											if($i%2 == 0){
												$color = "#E2E7ED";
											}else{
												$color = "#FFF";
											}
											
											if($lin[Status] == 2){
												$color = "#A7E7A7";
											}else{
												$hoje	= date("Y-m-d");
												
												if($lin[Data] < $hoje){
													$color = "#FFD2D2";
												}
											}
					
											$title = "Data: " + $lin[DataAgenda] + " " + $lin[Hora].substr(0,5);
											if($lin[Nome] != ''){	$title += "\n" + "Cliente: " + $lin[Nome];	}
											$title += "\n--------------------------------------------------------------------------------";
											$title += "\n" + $lin[Descricao];
											
											$lin[Nome]		=	substr($lin[Nome],0,20);
											$lin[Descricao]	=	substr($lin[Descricao],0,50);
											
											echo"
												<tr>
													<td style='text-align:center; width:60px; background-color:$color' alt='$title'>".$LinkIni.$lin[DataAgenda].$LinkFim."</td>
													<td style='background-color:$color' alt='$title'>".$LinkIni.$lin[Nome].$LinkFim."</td>
													<td style='background-color:$color' alt='$title'>".$LinkIni.$lin[Descricao].$LinkFim."</td>
												</tr>
											";
											
											$i++;
										}
								
								echo"</table></div>";

								if(empty($_SESSION["filtro_".$NomeBLAgenda])){
									$_SESSION["filtro_".$NomeBLAgenda] = 1;
								} else if($_SESSION["filtro_".$NomeBLAgenda] == 2){
									echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_agenda'), 'tabela".$NomeBLAgenda."'); </script>";
								}
							echo"</td>";
						}
					?>
					</tr>
				</table>
			</div>
			<?
				//Permissao para visualizar Quadro de Help Desk	
				$sql	=	"select 
								IdParametroSistema IdQuadroAviso
							from 
								ParametroSistema,
								GrupoUsuarioQuadroAviso
							where   
								GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
								GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
								IdGrupoParametroSistema = 56 and 
								IdParametroSistema = 9 and
								IdGrupoUsuario 	in 
								(
									select 
										UsuarioGrupoUsuario.IdGrupoUsuario 
									from 	
										UsuarioGrupoUsuario, 
										GrupoUsuario, 
										Usuario, 
										Pessoa 
									where 
										UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
										UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
										UsuarioGrupoUsuario.Login = Usuario.Login and 
										UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
										Usuario.IdPessoa = Pessoa.IdPessoa and 
										Pessoa.TipoUsuario = 1 and 
										UsuarioGrupoUsuario.Login = '$local_Login' 
									group by	
										UsuarioGrupoUsuario.IdGrupoUsuario
								);";
				$res	=	mysql_query($sql,$con);
				if(mysql_num_rows($res)>=1){
					if($conCNT){
					echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='quadroHelpDesk'>
								<p class='tit'><a href='../../modulos/helpdesk/direciona/direciona_help_desk.php?Direciona=' target='_black' style='color:#FFF'>HELP DESK</a> <a href='../../modulos/helpdesk/direciona/direciona_help_desk.php' target='_black' style='color:#FFF'>[+]</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_aberta_quadro_help_desk' style='cursor:pointer; margin-right:4px;' onClick='aberta_quadro_help_desk(this, 1);' title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='carregandoQuadro'>carregando</div>
								<div id='tabelaHelpDesk' style='display:none;'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0;' id='tabelaQuadroHelpDesk'>
										<tr><td>&nbsp;</td></tr>
									</table>
								</div>
							</div>
						</div>";
					}
				}
				
				
				
				
				
				
				
/*				
				
				// Quadro de Pontos em Monitoramento
				if($local_CampoOrderBy7 == ''){
					$local_CampoOrderBy7 = 3; // posição inicial da seta de ordenação
				}
				
				if($local_Order7 == '' || $local_Order7 == 1){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					$local_OrderBy7  = 'DESC';
					$local_Order7 = 2;
				} else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy7 = 'ASC';
					$local_Order7 = 1;
				}
				
				$local_ImagemSeta7[$local_CampoOrderBy7] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy7){
					case 1:
						$local_CampoOrdemBy7 = 'Protocolo.IdProtocolo';
						break;
					case 2:
						$local_CampoOrdemBy7 = 'Protocolo.Assunto';
						break;
					case 3:
						$local_CampoOrdemBy7 = 'Pessoa.Nome';
						break;
					case 4:
						$local_CampoOrdemBy7 = 'ProtocoloTipo.DescricaoProtocoloTipo';
						break;
					case 5:
						$local_CampoOrdemBy7 = 'Protocolo.DataCriacao';
						break;
					case 6:
						$local_CampoOrdemBy7 = 'Protocolo.DataCriacao';
						break;
				}
				//Permissao para visualizar Quadro de Pontos em Monitoramento
				$sql = "select 
							IdParametroSistema IdQuadroAviso
						from 
							ParametroSistema,
							GrupoUsuarioQuadroAviso
						where   
							GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
							GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
							IdGrupoParametroSistema = 56 and 
							IdParametroSistema = 19 and
							IdGrupoUsuario 	in 
							(
								select 
									UsuarioGrupoUsuario.IdGrupoUsuario 
								from 	
									UsuarioGrupoUsuario, 
									GrupoUsuario, 
									Usuario, 
									Pessoa 
								where 
									UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
									UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
									UsuarioGrupoUsuario.Login = Usuario.Login and 
									UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
									Usuario.IdPessoa = Pessoa.IdPessoa and 
									Pessoa.TipoUsuario = 1 and 
									UsuarioGrupoUsuario.Login = '$local_Login' 
								group by	
									UsuarioGrupoUsuario.IdGrupoUsuario
							);";
				$res = mysql_query($sql,$con);
				
				if(mysql_num_rows($res) >= 1){
					$i = 0;
					$visivel = false;
					$sql = "select
								Protocolo.IdProtocolo,
								Protocolo.IdProtocoloTipo,
								substring(Protocolo.Assunto,1,80) Assunto,
								Protocolo.IdStatus,
								Protocolo.LoginResponsavel,
								Protocolo.DataCriacao,
								Protocolo.LoginCriacao,
								Protocolo.LoginConclusao,
								ProtocoloTipo.DescricaoProtocoloTipo,
								Pessoa.Nome
							from
								Protocolo,
								ProtocoloTipo,
								Pessoa
							where
								Protocolo.IdLoja = $local_IdLoja and
								Protocolo.IdLoja = ProtocoloTipo.IdLoja and
								Protocolo.IdStatus = 101 and
								Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo and
								Protocolo.IdPessoa = Pessoa.IdPessoa
							order by
								$local_CampoOrdemBy7 $local_OrderBy7,
								Protocolo.IdProtocolo $local_OrderBy7;";
					$res = mysql_query($sql,$con);
					
					if(@mysql_num_rows($res) >= 1){
						$i = 0;
						
						while($lin = @mysql_fetch_array($res)){
							$query = "true";
							$Protocolo[IdProtocolo][$i]				= $lin[IdProtocolo];
							$Protocolo[Assunto][$i]					= $lin[Assunto];
							$Protocolo[Nome][$i]					= $lin[Nome];
							$Protocolo[IdStatus][$i]				= $lin[IdStatus];
							$Protocolo[Status][$i]					= getParametroSistema(239, $lin[IdStatus]);
							$Protocolo[LoginResponsavel][$i]		= $lin[LoginResponsavel];
							$Protocolo[DataCriacao][$i]				= $lin[DataCriacao];
							$Protocolo[DescricaoProtocoloTipo][$i]	= $lin[DescricaoProtocoloTipo];
							$Protocolo[Restringir][$i]				= $query;
							
							if($Protocolo[Restringir][$i] == 'true'){
								$visivel = true;
							}
							
							$i++;
						}
						
						$qtd_cr = $i;
					}	
					
					if($visivel == true){
						$NomeBLProtocoloPreCadastrado = "ProtocoloPreCadastrado";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;'>
								<p class='tit'><a href='listar_protocolo.php' target='_top' style='color:#FFF'>Protocolo (Pré-cadastrado)</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_protocolo_pre_cadastrado' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLProtocoloPreCadastrado."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLProtocoloPreCadastrado."'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom:0;'>
										<tr>
											<td width='40px'><a href='conteudo.php?CampoOrderBy7=1&Order7=$local_Order7' target='_self'><B>Id</B>$local_ImagemSeta7[1]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=2&Order7=$local_Order7' target='_self'><B>Assunto</B>$local_ImagemSeta7[2]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=3&Order7=$local_Order7' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta7[3]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=4&Order7=$local_Order7' target='_self'><B>Tipo Protocolo</B>$local_ImagemSeta7[4]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=5&Order7=$local_Order7' target='_self'><B>Data de Aber.</B>$local_ImagemSeta7[5]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=6&Order7=$local_Order7' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta7[6]</a></td>
										</tr>";
						
						for($i = 0; $i < $qtd_cr; $i++){
							if($Protocolo[Restringir][$i] == 'true'){
								$temp = explode("\n", getCodigoInterno(49, $Protocolo[IdStatus][$i][0]));
								$local_CorContaReceber = str_replace("\r", "", $temp[1]);
								$local_Status = getParametroSistema(239, $Protocolo[IdStatus][$i]);
								$local_DataCriacao = dataConv($Protocolo[DataCriacao][$i],'Y-m-d','d/m/Y');
								$local_TempoAbertura = diferencaData($Protocolo[DataCriacao][$i], date("Y-m-d H:i:s"));
								
								$sql = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = '53' and IdCodigoInterno = '".$Protocolo[IdStatus][$i]."';";
								$res = mysql_query($sql, $con);
								$lin = mysql_fetch_array($res);
								$temp = explode("\n", $lin[ValorCodigoInterno]);
								
								if(!empty($temp[1])){
									$local_CorContaReceber = str_replace("\r", "", $temp[1]);
								}
								
								echo"
									<tr>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[IdProtocolo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Assunto][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Nome][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[DescricaoProtocoloTipo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_DataCriacao."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_TempoAbertura."</a></td>
									</tr>";
							}
						}
						
						echo"
									</table>
								</div>
							</div>
						</div>";
						
						if(empty($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado])){
							$_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] = 1;
						} else if($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_protocolo_pre_cadastrado'), 'tabela".$NomeBLProtocoloPreCadastrado."'); </script>";
						}
					}
				}
*/				
				
				
				
				
				
				// Quadro de Protocolo (Pré-cadastrado)
				if($local_CampoOrderBy7 == ''){
					$local_CampoOrderBy7 = 3; // posição inicial da seta de ordenação
				}
				
				if($local_Order7 == '' || $local_Order7 == 1){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					$local_OrderBy7  = 'DESC';
					$local_Order7 = 2;
				} else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy7 = 'ASC';
					$local_Order7 = 1;
				}
				
				$local_ImagemSeta7[$local_CampoOrderBy7] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy7){
					case 1:
						$local_CampoOrdemBy7 = 'Protocolo.IdProtocolo';
						break;
					case 2:
						$local_CampoOrdemBy7 = 'Protocolo.Assunto';
						break;
					case 3:
						$local_CampoOrdemBy7 = 'Pessoa.Nome';
						break;
					case 4:
						$local_CampoOrdemBy7 = 'ProtocoloTipo.DescricaoProtocoloTipo';
						break;
					case 5:
						$local_CampoOrdemBy7 = 'Protocolo.DataCriacao';
						break;
					case 6:
						$local_CampoOrdemBy7 = 'Protocolo.DataCriacao';
						break;
					case 7:
						$local_CampoOrdemBy7 = 'Protocolo.PrevisaoEtapa';
						break;
				}
				//Permissao para visualizar Quadro de Protocolo (Pré-cadastrado)
				$sql = "select 
							IdParametroSistema IdQuadroAviso
						from 
							ParametroSistema,
							GrupoUsuarioQuadroAviso
						where   
							GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
							GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
							IdGrupoParametroSistema = 56 and 
							IdParametroSistema = 19 and
							IdGrupoUsuario 	in 
							(
								select 
									UsuarioGrupoUsuario.IdGrupoUsuario 
								from 	
									UsuarioGrupoUsuario, 
									GrupoUsuario, 
									Usuario, 
									Pessoa 
								where 
									UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
									UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
									UsuarioGrupoUsuario.Login = Usuario.Login and 
									UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
									Usuario.IdPessoa = Pessoa.IdPessoa and 
									Pessoa.TipoUsuario = 1 and 
									UsuarioGrupoUsuario.Login = '$local_Login' 
								group by	
									UsuarioGrupoUsuario.IdGrupoUsuario
							);";
				$res = mysql_query($sql,$con);
				
				if(mysql_num_rows($res) >= 1){
					$i = 0;
					$visivel = false;
					$sql = "select
								Protocolo.IdProtocolo,
								Protocolo.IdProtocoloTipo,
								substring(Protocolo.Assunto,1,80) Assunto,
								Protocolo.IdStatus,
								Protocolo.LoginResponsavel,
								Protocolo.DataCriacao,
								Protocolo.PrevisaoEtapa,
								Protocolo.LoginCriacao,
								Protocolo.LoginConclusao,
								ProtocoloTipo.DescricaoProtocoloTipo,
								Pessoa.Nome
							from
								Protocolo,
								ProtocoloTipo,
								Pessoa
							where
								Protocolo.IdLoja = $local_IdLoja and
								Protocolo.IdLoja = ProtocoloTipo.IdLoja and
								Protocolo.IdStatus = 101 and
								Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo and
								Protocolo.IdPessoa = Pessoa.IdPessoa
							order by
								$local_CampoOrdemBy7 $local_OrderBy7,
								Protocolo.IdProtocolo $local_OrderBy7;";
					$res = mysql_query($sql,$con);
					
					if(@mysql_num_rows($res) >= 1){
						$i = 0;
						
						while($lin = @mysql_fetch_array($res)){
							$query = "true";
							$Protocolo[IdProtocolo][$i]				= $lin[IdProtocolo];
							$Protocolo[Assunto][$i]					= $lin[Assunto];
							$Protocolo[Nome][$i]					= $lin[Nome];
							$Protocolo[IdStatus][$i]				= $lin[IdStatus];
							$Protocolo[Status][$i]					= getParametroSistema(239, $lin[IdStatus]);
							$Protocolo[LoginResponsavel][$i]		= $lin[LoginResponsavel];
							$Protocolo[DataCriacao][$i]				= $lin[DataCriacao];
							$Protocolo[PrevisaoEtapa][$i]				= $lin[PrevisaoEtapa];
							$Protocolo[DescricaoProtocoloTipo][$i]	= $lin[DescricaoProtocoloTipo];
							$Protocolo[Restringir][$i]				= $query;
							
							if($Protocolo[Restringir][$i] == 'true'){
								$visivel = true;
							}
							
							$i++;
						}
						
						$qtd_cr = $i;
					}	
					
					if($visivel == true){
						$NomeBLProtocoloPreCadastrado = "ProtocoloPreCadastrado";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;'>
								<p class='tit'><a href='listar_protocolo.php' target='_top' style='color:#FFF'>Protocolo (Pré-cadastrado)</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_protocolo_pre_cadastrado' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLProtocoloPreCadastrado."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLProtocoloPreCadastrado."'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom:0;'>
										<tr>
											<td width='40px'><a href='conteudo.php?CampoOrderBy7=1&Order7=$local_Order7' target='_self'><B>Id</B>$local_ImagemSeta7[1]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=2&Order7=$local_Order7' target='_self'><B>Assunto</B>$local_ImagemSeta7[2]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=3&Order7=$local_Order7' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta7[3]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=4&Order7=$local_Order7' target='_self'><B>Tipo Protocolo</B>$local_ImagemSeta7[4]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=5&Order7=$local_Order7' target='_self'><B>Data de Aber.</B>$local_ImagemSeta7[5]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=6&Order7=$local_Order7' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta7[6]</a></td>
											<td><a href='conteudo.php?CampoOrderBy7=7&Order7=$local_Order7' target='_self'><B>Previsão</B>$local_ImagemSeta7[7]</a></td>
										</tr>";
						
						for($i = 0; $i < $qtd_cr; $i++){
							if($Protocolo[Restringir][$i] == 'true'){
								$temp = explode("\n", getCodigoInterno(49, $Protocolo[IdStatus][$i][0]));
								$local_CorContaReceber = str_replace("\r", "", $temp[1]);
								$local_Status = getParametroSistema(239, $Protocolo[IdStatus][$i]);
								$local_DataCriacao = dataConv($Protocolo[DataCriacao][$i],'Y-m-d','d/m/Y');
								$local_PrevisaoEtapa = dataConv($Protocolo[PrevisaoEtapa][$i],'Y-m-d','d/m/Y');
								$local_TempoAbertura = diferencaData($Protocolo[DataCriacao][$i], date("Y-m-d H:i:s"));
								
								$sql = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = '53' and IdCodigoInterno = '".$Protocolo[IdStatus][$i]."';";
								$res = mysql_query($sql, $con);
								$lin = mysql_fetch_array($res);
								$temp = explode("\n", $lin[ValorCodigoInterno]);
								
								if(!empty($temp[1])){
									$local_CorContaReceber = str_replace("\r", "", $temp[1]);
								}
								
								echo"
									<tr>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[IdProtocolo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Assunto][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Nome][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[DescricaoProtocoloTipo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_DataCriacao."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_TempoAbertura."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_PrevisaoEtapa."</a></td>
									</tr>";
							}
						}
						
						echo"
									</table>
								</div>
							</div>
						</div>";
						
						if(empty($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado])){
							$_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] = 1;
						} else if($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_protocolo_pre_cadastrado'), 'tabela".$NomeBLProtocoloPreCadastrado."'); </script>";
						}
					}
				}
				// Quadro de Protocolo (Em atendimento)
				if($local_CampoOrderBy8 == ''){
					$local_CampoOrderBy8 = 3; // posição inicial da seta de ordenação
				}
				
				if($local_Order8 == '' || $local_Order8 == 1){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					$local_OrderBy8  = 'DESC';
					$local_Order8 = 2;
				} else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy8 = 'ASC';
					$local_Order8 = 1;
				}
				
				$local_ImagemSeta8[$local_CampoOrderBy8] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy8){
					case 1:
						$local_CampoOrdemBy8 = 'Protocolo.IdProtocolo';
						break;
					case 2:
						$local_CampoOrdemBy8 = 'Protocolo.Assunto';
						break;
					case 3:
						$local_CampoOrdemBy8 = 'Pessoa.Nome';
						break;
					case 4:
						$local_CampoOrdemBy8 = 'ProtocoloTipo.DescricaoProtocoloTipo';
						break;
					case 5:
						$local_CampoOrdemBy8 = 'Protocolo.LoginResponsavel';
						break;
					case 6:
						$local_CampoOrdemBy8 = 'Protocolo.DataCriacao';
						break;
					case 7:
						$local_CampoOrdemBy8 = 'Protocolo.DataCriacao';
						break;
					case 8:
						$local_CampoOrdemBy8 = 'Protocolo.DataCriacao';
						break;
				}
				//Permissao para visualizar Quadro de Protocolo (Em atendimento)
				$sql = "select 
							IdParametroSistema IdQuadroAviso
						from 
							ParametroSistema,
							GrupoUsuarioQuadroAviso
						where   
							GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
							GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
							IdGrupoParametroSistema = 56 and 
							IdParametroSistema = 20 and
							IdGrupoUsuario 	in 
							(
								select 
									UsuarioGrupoUsuario.IdGrupoUsuario 
								from 	
									UsuarioGrupoUsuario, 
									GrupoUsuario, 
									Usuario, 
									Pessoa 
								where 
									UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
									UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
									UsuarioGrupoUsuario.Login = Usuario.Login and 
									UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
									Usuario.IdPessoa = Pessoa.IdPessoa and 
									Pessoa.TipoUsuario = 1 and 
									UsuarioGrupoUsuario.Login = '$local_Login' 
								group by	
									UsuarioGrupoUsuario.IdGrupoUsuario
							);";
				$res = mysql_query($sql,$con);
				
				if(mysql_num_rows($res) >= 1){
					$i = 0;
					$visivel = false;
					$sql = "SELECT
								Protocolo.IdProtocolo,
								Protocolo.IdProtocoloTipo,
								SUBSTRING(Protocolo.Assunto,1,80) Assunto,
								Protocolo.IdStatus,
								Protocolo.LoginResponsavel,
								Protocolo.DataCriacao,
								Protocolo.PrevisaoEtapa,
								Protocolo.LoginCriacao,
								Protocolo.LoginConclusao,
								ProtocoloTipo.DescricaoProtocoloTipo,
								Pessoa.Nome
							FROM
								Protocolo LEFT JOIN ProtocoloTipo ON (Protocolo.IdLoja = ProtocoloTipo.IdLoja AND	
													Protocolo.IdProtocoloTipo = ProtocoloTipo.IdProtocoloTipo )
									  LEFT JOIN Pessoa ON (Protocolo.IdPessoa = Pessoa.IdPessoa)
							WHERE
								Protocolo.IdLoja = $local_IdLoja AND	
								Protocolo.IdStatus = 100
							order by
								$local_CampoOrdemBy8 $local_OrderBy8,
								Protocolo.IdProtocolo $local_OrderBy8;";
					$res = mysql_query($sql,$con);
					
					if(@mysql_num_rows($res) >= 1){
						$i = 0;
						
						while($lin = @mysql_fetch_array($res)){
							$query = "true";
							$Protocolo[IdProtocolo][$i]				= $lin[IdProtocolo];
							$Protocolo[Assunto][$i]					= $lin[Assunto];
							$Protocolo[Nome][$i]					= $lin[Nome];
							$Protocolo[IdStatus][$i]				= $lin[IdStatus];
							$Protocolo[Status][$i]					= getParametroSistema(239, $lin[IdStatus]);
							$Protocolo[LoginResponsavel][$i]		= $lin[LoginResponsavel];
							$Protocolo[DataCriacao][$i]				= $lin[DataCriacao];
							$Protocolo[PrevisaoEtapa][$i]			= $lin[PrevisaoEtapa];
							$Protocolo[DescricaoProtocoloTipo][$i]	= $lin[DescricaoProtocoloTipo];
							$Protocolo[Restringir][$i]				= $query;
							
							if($Protocolo[Restringir][$i] == 'true'){
								$visivel = true;
							}
							
							$i++;
						}
						
						$qtd_cr = $i;
					}	
					
					if($visivel == true){
						$NomeBLProtocoloPreCadastrado = "ProtocoloEmAtendimento";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;'>
								<p class='tit'><a href='listar_protocolo.php' target='_top' style='color:#FFF'>Protocolo (Em atendimento)</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_protocolo_em_atendimento' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLProtocoloPreCadastrado."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLProtocoloPreCadastrado."'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom:0;'>
										<tr>
											<td width='40px'><a href='conteudo.php?CampoOrderBy8=1&Order8=$local_Order8' target='_self'><B>Id</B>$local_ImagemSeta8[1]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=2&Order8=$local_Order8' target='_self'><B>Assunto</B>$local_ImagemSeta8[2]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=3&Order8=$local_Order8' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta8[3]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=4&Order8=$local_Order8' target='_self'><B>Tipo Protocolo</B>$local_ImagemSeta8[4]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=5&Order8=$local_Order8' target='_self'><B>Responsável</B>$local_ImagemSeta8[5]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=6&Order8=$local_Order8' target='_self'><B>Data de Aber.</B>$local_ImagemSeta8[6]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=7&Order8=$local_Order8' target='_self'><B>Tempo Aber.</B>$local_ImagemSeta8[7]</a></td>
											<td><a href='conteudo.php?CampoOrderBy8=8&Order8=$local_Order8' target='_self'><B>Previsão</B>$local_ImagemSeta8[8]</a></td>
										</tr>";
						
						for($i = 0; $i < $qtd_cr; $i++){
							if($Protocolo[Restringir][$i] == 'true'){
								$temp = explode("\n", getCodigoInterno(49, $Protocolo[IdStatus][$i][0]));
								$local_CorContaReceber 	= str_replace("\r", "", $temp[1]);
								$local_Status			= getParametroSistema(239, $Protocolo[IdStatus][$i]);
								$local_DataCriacao		= dataConv($Protocolo[DataCriacao][$i],'Y-m-d','d/m/Y');
								$local_PrevisaoEtapa	= dataConv($Protocolo[PrevisaoEtapa][$i],'Y-m-d','d/m/Y');
								$local_TempoAbertura 	= diferencaData($Protocolo[DataCriacao][$i], date("Y-m-d H:i:s"));
								
								$sql = "select ValorCodigoInterno from CodigoInterno where IdGrupoCodigoInterno = '53' and IdCodigoInterno = '".$Protocolo[IdStatus][$i]."';";
								$res = mysql_query($sql, $con);
								$lin = mysql_fetch_array($res);
								$temp = explode("\n", $lin[ValorCodigoInterno]);
								
								if(!empty($temp[1])){
									$local_CorContaReceber = str_replace("\r", "", $temp[1]);
								}
								
								
								echo"
									<tr>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[IdProtocolo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Assunto][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[Nome][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[DescricaoProtocoloTipo][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$Protocolo[LoginResponsavel][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_DataCriacao."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_TempoAbertura."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_protocolo.php?IdProtocolo=".$Protocolo[IdProtocolo][$i]."'>".$local_PrevisaoEtapa."</a></td>
									</tr>";
							}
						}
						echo"
									</table>
								</div>
							</div>
						</div>";
						
						if(empty($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado])){
							$_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] = 1;
						} else if($_SESSION["filtro_".$NomeBLProtocoloPreCadastrado] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_protocolo_em_atendimento'), 'tabela".$NomeBLProtocoloPreCadastrado."'); </script>";
						}
					}
				}
				
				include("conteudo_quadro_os.php"); // Visualiza os quadros das OS
				
				// Quadro de Atualização de Vencimento
				if($local_CampoOrderBy3 == ''){
					$local_CampoOrderBy3 = 6; // posição inicial da seta de ordenação
				}
				
				if($local_Order3 == '' || $local_Order3 == 1){										
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
					$local_OrderBy3  = 'DESC';
					$local_Order3 = 2;																
				}else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>"; 																	
					$local_OrderBy3 = 'ASC';
					$local_Order3 = 1;																
				}
				
				$local_ImagemSeta3[$local_CampoOrderBy3] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy3){
					case 1:
						$local_CampoOrdemBy3 = 'IdContaReceber';
						break;
					case 2:
						$local_CampoOrdemBy3 = 'Nome';
						break;
					case 3:
						$local_CampoOrdemBy3 = 'LoginAlteracao';
						break;
					case 4:
						$local_CampoOrdemBy3 = 'AbreviacaoNomeLocalCobranca';
						break;
					case 5:
						$local_CampoOrdemBy3 = 'Valor';
						break;
					case 6:
						$local_CampoOrdemBy3 = 'ValorLancamento';
						break;
					case 7:
						$local_CampoOrdemBy3 = 'DataVencimentoAnterior';
						break;
					case 8:
						$local_CampoOrdemBy3 = 'DataVencimento';
						break;
					case 9:
						$local_CampoOrdemBy3 = 'IdStatus';
						break;																		
				}			
				
				//Permissao para visualizar Quadro de Atualização de Vencimento
				$sql	=	"select 
								IdParametroSistema IdQuadroAviso
							from 
								ParametroSistema,
								GrupoUsuarioQuadroAviso
							where   
								GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
								GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
								IdGrupoParametroSistema = 56 and 
								IdParametroSistema = 8 and
								IdGrupoUsuario 	in 
								(
									select 
										UsuarioGrupoUsuario.IdGrupoUsuario 
									from 	
										UsuarioGrupoUsuario, 
										GrupoUsuario, 
										Usuario, 
										Pessoa 
									where 
										UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
										UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
										UsuarioGrupoUsuario.Login = Usuario.Login and 
										UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
										Usuario.IdPessoa = Pessoa.IdPessoa and 
										Pessoa.TipoUsuario = 1 and 
										UsuarioGrupoUsuario.Login = '$local_Login' 
									group by	
										UsuarioGrupoUsuario.IdGrupoUsuario
								);";
				$res	=	mysql_query($sql,$con);
				if(mysql_num_rows($res)>=1){
					$where = "";
					$i = 0;
					$visivel = false;
					
					if(getCodigoInterno(3,114) != ""){				
						$UsuariosPreConfigurados = explode("\n",getCodigoInterno(3,114));
						
						while($UsuariosPreConfigurados[$i] != ""){
							if($i==0){
								$ListaUsuariosPreConfigurados = "'".trim($UsuariosPreConfigurados[$i])."'";				
							}else{
								$ListaUsuariosPreConfigurados .= ","."'".trim($UsuariosPreConfigurados[$i])."'";				
							}					
							$i++;
						}
						
						$where .= " and ContaReceberDados.LoginCriacaoVencimento in ($ListaUsuariosPreConfigurados)";
					}
					
					$where .= " and ContaReceberDados.IdStatus in (1, 3";
					
					if(getCodigoInterno(3,113) != 1){ // remove os conta receber quitados
						$where .= ", 2";	
					}
					
					$where .= ")";
					
					$QtdDias 			= getCodigoInterno(7,8);				
					$DataDiaAnterior 	= incrementaData(date('Y-m-d'),-$QtdDias);
					
					$sql	=	"select
									*
								from
									(
										select									
											ContaReceberDados.IdLoja,
											ContaReceberDados.IdContaReceber,				
											ContaReceberDados.ValorLancamento,				
											(ContaReceberDados.ValorFinal) Valor,
											ContaReceberDados.DataVencimento,
											ContaReceberDataVencimentoAnterior(ContaReceberDados.IdLoja,ContaReceberDados.IdContaReceber) DataVencimentoAnterior,
											ContaReceberDados.IdStatus,
											ContaReceberDados.LoginAlteracao,
											substr(Pessoa.Nome,1,20) Nome,
											Pessoa.Nome NomeTitle,		
											LocalCobranca.AbreviacaoNomeLocalCobranca								
										from
											ContaReceberDados,
											Pessoa,
											LocalCobranca
										where							
											ContaReceberDados.IdLoja = $local_IdLoja and
											ContaReceberDados.IdLoja = LocalCobranca.IdLoja and
											ContaReceberDados.DataCriacaoVencimento >= '$DataDiaAnterior' and								
											ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
											ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
											substring(ContaReceberDados.DataCriacaoVencimento,1,10) != ContaReceberDados.DataLancamento 
											$where
									) Temp
								order by
									$local_CampoOrdemBy3 $local_OrderBy3,
									IdContaReceber $local_OrderBy3";	
					$res	=	mysql_query($sql,$con);
					if(@mysql_num_rows($res) >=1){	
						$i = 0;
						
						while($lin	=	@mysql_fetch_array($res)){

							$sqlLancamentoFinanceiroDados = "
											select
												LancamentoFinanceiroDados.IdContrato										
											from
												LancamentoFinanceiroDados
											where
												IdLoja = $local_IdLoja and
												IdContaReceber = $lin[IdContaReceber]";
							$resLancamentoFinanceiroDados = mysql_query($sqlLancamentoFinanceiroDados,$con);
							$linLancamentoFinanceiroDados = mysql_fetch_array($resLancamentoFinanceiroDados);
					
							$lin[IdContrato]	= $linLancamentoFinanceiroDados[IdContrato];					
					
							$query = 'true';
							
							if($lin[IdContrato]!=''){
								if($_SESSION["RestringirCarteira"] == true){
									$sqlTemp =	"select 
													AgenteAutorizadoPessoa.IdContrato 
												from 
													AgenteAutorizadoPessoa,
													Carteira 
												where 
													AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
													AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
													AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
													Carteira.IdCarteira = $local_IdPessoaLogin and 
													Carteira.Restringir = 1 and 
													Carteira.IdStatus = 1 and
													AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
									$resTemp	=	@mysql_query($sqlTemp,$con);
									if(@mysql_num_rows($resTemp) == 0){
										$query = 'false';
									}
								}else{
									if($_SESSION["RestringirAgenteAutorizado"] == true){
										$sqlTemp =	"select 
														AgenteAutorizadoPessoa.IdContrato
													from 
														AgenteAutorizadoPessoa,
														AgenteAutorizado
													where 
														AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
														AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
														AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
														AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
														AgenteAutorizado.Restringir = 1 and 
														AgenteAutorizado.IdStatus = 1 and
														AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
										$resTemp	=	@mysql_query($sqlTemp,$con);
										if(@mysql_num_rows($resTemp) == 0){
											$query = 'false';
										}
									}
									if($_SESSION["RestringirAgenteCarteira"] == true){
										$sqlTemp		=	"select 
																AgenteAutorizadoPessoa.IdContrato
															from 
																AgenteAutorizadoPessoa,
																AgenteAutorizado,
																Carteira
															where 
																AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
																AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
																AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
																AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
																AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
																Carteira.IdCarteira = $local_IdPessoaLogin and 
																AgenteAutorizado.Restringir = 1 and 
																AgenteAutorizado.IdStatus = 1 and
																AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
										$resTemp	=	@mysql_query($sqlTemp,$con);
										if(@mysql_num_rows($resTemp) == 0){
											$query = 'false';
										}
									}
								}
							}					
							
							$sql1	= "select
											ContaReceberVencimento.LoginCriacao
										from
											ContaReceberVencimento
										where 
											ContaReceberVencimento.IdLoja = $local_IdLoja and
											ContaReceberVencimento.IdContaReceber = $lin[IdContaReceber] and
											ContaReceberVencimento.DataVencimento = '$lin[DataVencimento]'
										order by
											ContaReceberVencimento.DataCriacao DESC
										limit 
											0,1;";
							$res1	= mysql_query($sql1,$con);
							$lin1	= @mysql_fetch_array($res1);
						
							$ContaReceber[IdContaReceber][$i] 			   = $lin[IdContaReceber];
							$ContaReceber[Nome][$i] 		 			   = $lin[Nome];
							$ContaReceber[NomeTitle][$i] 		 		   = $lin[NomeTitle];
							$ContaReceber[Valor][$i] 		 			   = str_replace(".",",",$lin[Valor]);
							$ContaReceber[ValorLancamento][$i] 		 	   = str_replace(".",",",$lin[ValorLancamento]);
							$ContaReceber[DataVencimentoAnterior][$i]	   = $lin[DataVencimentoAnterior];
							$ContaReceber[DataVencimento][$i]			   = $lin[DataVencimento];
							$ContaReceber[AbreviacaoNomeLocalCobranca][$i] = $lin[AbreviacaoNomeLocalCobranca];
							$ContaReceber[IdStatus][$i] 				   = $lin[IdStatus];
							$ContaReceber[LoginAlteracao][$i] 			   = $lin1[LoginCriacao];
							$ContaReceber[Restringir][$i]				   = $query;
							if($ContaReceber[Restringir][$i] == 'true'){					
								$visivel = 	true;					
							}
							$i++;												
						}
						$qtd_cr = $i;
					}	

					if($visivel == true){			
						$NomeBLContaReceberAtualizados = "ContaReceberAtualizado";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
								<p class='tit'><a href='listar_conta_receber.php' style='color:#FFF'>CONTAS A RECEBER ATUALIZADOS NOS ÚLTIMOS ".getCodigoInterno(7,8)." DIAS</a> <a href='cadastro_conta_receber.php' style='color:#FFF'>[+]</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_conta_receber_atualizado' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLContaReceberAtualizados."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLContaReceberAtualizados."'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
										<tr>
											<td width='40px'><a href='conteudo.php?CampoOrderBy3=1&Order3=$local_Order3' target='_self'><B>Id</B>$local_ImagemSeta3[1]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=2&Order3=$local_Order3' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta3[2]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=3&Order3=$local_Order3' target='_self'><B>Usuário Atualização</B>$local_ImagemSeta3[3]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=4&Order3=$local_Order3' target='_self'><B>Local Cob.</B>$local_ImagemSeta3[4]</a></td>
											<td style='text-align:right; padding-right:6px'><a href='conteudo.php?CampoOrderBy3=5&Order3=$local_Order3' target='_self'><B>Valor</B>$local_ImagemSeta3[5]</a></td>
											<td style='text-align:right; padding-right:6px'><a href='conteudo.php?CampoOrderBy3=6&Order3=$local_Order3' target='_self'><B>Valor Original</B>$local_ImagemSeta3[6]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=7&Order3=$local_Order3' target='_self'><B>Venc. Original</B>$local_ImagemSeta3[7]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=8&Order3=$local_Order3' target='_self'><B>Vencimento</B>$local_ImagemSeta3[8]</a></td>
											<td><a href='conteudo.php?CampoOrderBy3=9&Order3=$local_Order3' target='_self'><B>Status</B>$local_ImagemSeta3[9]</a></td>
										</tr>";

							for($i=0; $i<$qtd_cr; $i++){
								
								if($ContaReceber[Restringir][$i] == 'true'){										
									switch($ContaReceber[IdStatus][$i]){
										case '0': 
											$local_CorContaReceber	= getParametroSistema(15,2);
											break;							
										case '2':									
											$local_CorContaReceber	= getParametroSistema(15,3);								
											break;							
										case '7':
											$local_CorContaReceber	= getParametroSistema(15,2);
											break;
										default:
											$local_CorContaReceber 	= "";							
											break;								
									}							
									
									$local_Status 					= 	getParametroSistema(35,$ContaReceber[IdStatus][$i]);
									$local_DataVencimento 			=	dataConv($ContaReceber[DataVencimento][$i],'Y-m-d','d/m/Y');								
									$local_DataVencimentoAnterior 	=	dataConv($ContaReceber[DataVencimentoAnterior][$i],'Y-m-d','d/m/Y');	
									
									echo"
									<tr>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$ContaReceber[IdContaReceber][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."' title='".$ContaReceber[NomeTitle][$i]."'>".$ContaReceber[Nome][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$ContaReceber[LoginAlteracao][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$ContaReceber[AbreviacaoNomeLocalCobranca][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber; text-align:right; padding-right:6px'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$ContaReceber[Valor][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber; text-align:right; padding-right:6px'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$ContaReceber[ValorLancamento][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$local_DataVencimentoAnterior."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$local_DataVencimento."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceber[IdContaReceber][$i]."'>".$local_Status."</a></td>
									</tr>";						
								}			
							}					
							echo"
								</table>	
							</div>
						</div>
						</div>";
						if(empty($_SESSION["filtro_".$NomeBLContaReceberAtualizados])){
							$_SESSION["filtro_".$NomeBLContaReceberAtualizados] = 1;
						} else if($_SESSION["filtro_".$NomeBLContaReceberAtualizados] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_conta_receber_atualizado'), 'tabela".$NomeBLContaReceberAtualizados."'); </script>";
						}
					}
				}
				// Quadro de Contratos cancelados nos últimos x dias
				if($local_CampoOrderBy6 == ''){
					$local_CampoOrderBy6 = 5; // posição inicial da seta de ordenação
				}
				
				if($local_Order6 == '' || $local_Order6 == 1){
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";
					$local_OrderBy6  = 'DESC';
					$local_Order6 = 2;
				} else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>";
					$local_OrderBy6 = 'ASC';
					$local_Order6 = 1;
				}
				
				$local_ImagemSeta6[$local_CampoOrderBy6] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy6){
					case 1:
						$local_CampoOrdemBy6 = 'Contrato.IdContrato';
						break;
					case 2:
						$local_CampoOrdemBy6 = 'Pessoa.Nome';
						break;
					case 3:
						$local_CampoOrdemBy6 = 'Servico.DescricaoServico';
						break;
					case 4:
						$local_CampoOrdemBy6 = 'Contrato.DataInicio';
						break;
					case 5:
						$local_CampoOrdemBy6 = 'Contrato.DataTermino';
						break;
					case 6:
						$local_CampoOrdemBy6 = 'Contrato.DataUltimaCobranca';
						break;
					case 7:
						$local_CampoOrdemBy6 = 'ValorFinal';
						break;
				}
				
				//Permissao para visualizar de Contratos cancelados nos últimos x dias
				$sql = "select 
							IdParametroSistema IdQuadroAviso
						from 
							ParametroSistema,
							GrupoUsuarioQuadroAviso
						where   
							GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
							GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
							IdGrupoParametroSistema = 56 and 
							IdParametroSistema = 17 and
							IdGrupoUsuario in 
							(
								select 
									UsuarioGrupoUsuario.IdGrupoUsuario 
								from 
									UsuarioGrupoUsuario, 
									GrupoUsuario, 
									Usuario, 
									Pessoa 
								where 
									UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
									UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
									UsuarioGrupoUsuario.Login = Usuario.Login and 
									UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
									Usuario.IdPessoa = Pessoa.IdPessoa and 
									Pessoa.TipoUsuario = 1 and 
									UsuarioGrupoUsuario.Login = '$local_Login' 
								group by
									UsuarioGrupoUsuario.IdGrupoUsuario
							);";
				$res = mysql_query($sql,$con);
				if(mysql_num_rows($res) > 0){
					$i = 0;
					$visivel = false;
					$QtdDias = getCodigoInterno(7,9);
					
					$sql = "select
								Contrato.IdContrato,
								Contrato.IdStatus,
								Contrato.DataInicio,
								Contrato.DataTermino,
								Contrato.DataUltimaCobranca,
								Contrato.TipoContrato,
								ContratoVigenciaAtiva.Valor,
								ContratoVigenciaAtiva.ValorDesconto,
								(ContratoVigenciaAtiva.Valor - ContratoVigenciaAtiva.ValorDesconto) ValorFinal,
								substring(Pessoa.Nome,1,36) Nome,
								substring(Servico.DescricaoServico,1,36) DescricaoServico,
								LocalCobranca.AbreviacaoNomeLocalCobranca
							from
								Contrato left join ContratoVigenciaAtiva on 
								(
									Contrato.IdLoja = ContratoVigenciaAtiva.IdLoja and 
									Contrato.IdContrato = ContratoVigenciaAtiva.IdContrato
								),
								Pessoa,
								Servico,
								LocalCobranca
							where
								Contrato.DataTermino >= SUBDATE(CURDATE(), '$QtdDias') and
								Contrato.IdLoja	= $local_IdLoja and
								Contrato.IdStatus < 200 and 
								Contrato.IdPessoa = Pessoa.IdPessoa and 
								Contrato.IdLoja = Servico.IdLoja and
								Contrato.IdServico = Servico.IdServico and
								Contrato.IdLoja = LocalCobranca.IdLoja and 
								Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca 
							order by
								$local_CampoOrdemBy6 $local_OrderBy6,
								Contrato.IdContrato $local_OrderBy6";
					$res = mysql_query($sql,$con);
					if(@mysql_num_rows($res) > 0){
						$i = 0;
						
						while($lin = @mysql_fetch_array($res)){
							$Contrato[IdContrato][$i] 			= $lin[IdContrato];
							$Contrato[Nome][$i] 		 		= $lin[Nome];
							$Contrato[DescricaoServico][$i] 	= $lin[DescricaoServico];
							$Contrato[DataInicio][$i] 			= dataConv($lin[DataInicio],'Y-m-d','d/m/Y');
							$Contrato[DataTermino][$i] 			= dataConv($lin[DataTermino],'Y-m-d','d/m/Y');
							$Contrato[DataUltimaCobranca][$i] 	= dataConv($lin[DataUltimaCobranca],'Y-m-d','d/m/Y');
							$Contrato[ValorFinal][$i]			= $lin[ValorFinal];
							$Contrato[Restringir][$i]			= 'true';
							
							if($Contrato[Restringir][$i] == 'true'){
								$visivel = true;
							}
							
							$i++;
						}
						
						$qtd_cr = $i;
					}	
					
					if($visivel == true){
						$NomeBLContratoCancelado = "ContratoCancelado";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
								<p class='tit'><a href='listar_conta_receber.php' style='color:#FFF'>CONTRATOS CANCELADOS NOS ÚLTIMOS $QtdDias DIAS</a> <a href='cadastro_contrato.php' style='color:#FFF'>[+]</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_contrato_cancelado' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLContratoCancelado."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLContratoCancelado."'>
									<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
										<tr>
											<td width='40px'><a href='conteudo.php?CampoOrderBy6=1&Order6=$local_Order6' target='_self'><B>Id</B>$local_ImagemSeta6[1]</a></td>
											<td><a href='conteudo.php?CampoOrderBy6=2&Order6=$local_Order6' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta6[2]</a></td>
											<td><a href='conteudo.php?CampoOrderBy6=3&Order6=$local_Order6' target='_self'><B>Nome Serviço</B>$local_ImagemSeta6[3]</a></td>
											<td><a href='conteudo.php?CampoOrderBy6=4&Order6=$local_Order6' target='_self'><B>Início</B>$local_ImagemSeta6[4]</a></td>
											<td><a href='conteudo.php?CampoOrderBy6=5&Order6=$local_Order6' target='_self'><B>Términio</B>$local_ImagemSeta6[5]</a></td>
											<td><a href='conteudo.php?CampoOrderBy6=6&Order6=$local_Order6' target='_self'><B>Ult. Cobrança</B>$local_ImagemSeta6[6]</a></td>
											<td style='text-align:right; padding-right:6px'><a href='conteudo.php?CampoOrderBy6=7&Order6=$local_Order6' target='_self'><B>Valor Final (".getParametroSistema(5,1).")</B>$local_ImagemSeta6[7]</a></td>
										</tr>";
							
						for($i=0; $i<$qtd_cr; $i++){
							if($Contrato[Restringir][$i] == 'true'){
								switch($Contrato[IdStatus][$i]){
									case '1':
										$local_CorContrato	  = "";
										break;
									case '2':
										$local_CorContrato	  = getParametroSistema(15,3);
										break;
									case '3':
										$local_CorContrato	  = getParametroSistema(15,2);
										break;
								}
								
								echo"
								<tr>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".$Contrato[IdContrato][$i]."</a></td>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."' title='".$Contrato[NomeTitle][$i]."'>".$Contrato[Nome][$i]."</a></td>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".$Contrato[DescricaoServico][$i]."</a></td>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".$Contrato[DataInicio][$i]."</a></td>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".$Contrato[DataTermino][$i]."</a></td>
									<td style='background-color: $local_CorContrato'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".$Contrato[DataUltimaCobranca][$i]."</a></td>
									<td style='background-color: $local_CorContrato; text-align:right; padding-right:6px'><a href='cadastro_contrato.php?IdContrato=".$Contrato[IdContrato][$i]."'>".number_format($Contrato[ValorFinal][$i],2,',','')."</a></td>
								</tr>";
							}
						}
						
						echo"
								</table>
							</div>
						</div>
						</div>";

						if(empty($_SESSION["filtro_".$NomeBLContratoCancelado])){
							$_SESSION["filtro_".$NomeBLContratoCancelado] = 1;
						} else if($_SESSION["filtro_".$NomeBLContratoCancelado] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_contrato_cancelado'), 'tabela".$NomeBLContratoCancelado."'); </script>";
						}
					}
				}
				
				// Quadro de Aviso - Contas a receber Devolvido				
				
				if($local_CampoOrderBy2 == ''){
					$local_CampoOrderBy2 = 6; // posição inicial da seta de ordenação
				}
				
				if($local_Order2 == '' || $local_Order2 == 1){										
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_descending.gif'>";																		
					$local_OrderBy2  = 'DESC';
					$local_Order2 = 2;																
				}else{
					$local_ImagemSetaDefault = "<img src='../../img/estrutura_sistema/seta_ascending.gif'>"; 																	
					$local_OrderBy2 = 'ASC';
					$local_Order2 = 1;																
				}
				
				$local_ImagemSeta2[$local_CampoOrderBy2] = $local_ImagemSetaDefault; 
				
				switch($local_CampoOrderBy2){
					case 1:
						$local_CampoOrdemBy2 = 'IdContaReceber';
						break;
					case 2:
						$local_CampoOrdemBy2 = 'Nome';
						break;
					case 3:
						$local_CampoOrdemBy2 = 'AbreviacaoNomeLocalCobranca';
						break;
					case 4:
						$local_CampoOrdemBy2 = 'Valor';
						break;
					case 5:
						$local_CampoOrdemBy2 = 'DataVencimentoAnterior';
						break;
					case 6:
						$local_CampoOrdemBy2 = 'DataVencimento';
						break;
					case 7:
						$local_CampoOrdemBy2 = 'IdStatus';
						break;																		
				}			
				
				//Permissao para visualizar Quadro de Contas a receber Devolvido	
				$sql	=	"select 
								IdParametroSistema IdQuadroAviso
							from 
								ParametroSistema,
								GrupoUsuarioQuadroAviso
							where   
								GrupoUsuarioQuadroAviso.IdLoja = $local_IdLoja and
								GrupoUsuarioQuadroAviso.IdQuadroAviso = ParametroSistema.IdParametroSistema and 
								IdGrupoParametroSistema = 56 and 
								IdParametroSistema = 10 and
								IdGrupoUsuario 	in 
								(
									select 
										UsuarioGrupoUsuario.IdGrupoUsuario 
									from 	
										UsuarioGrupoUsuario, 
										GrupoUsuario, 
										Usuario, 
										Pessoa 
									where 
										UsuarioGrupoUsuario.IdLoja = $local_IdLoja and 
										UsuarioGrupoUsuario.IdLoja = GrupoUsuario.IdLoja and 
										UsuarioGrupoUsuario.Login = Usuario.Login and 
										UsuarioGrupoUsuario.IdGrupoUsuario = GrupoUsuario.IdGrupoUsuario and 
										Usuario.IdPessoa = Pessoa.IdPessoa and 
										Pessoa.TipoUsuario = 1 and 
										UsuarioGrupoUsuario.Login = '$local_Login' 
									group by	
										UsuarioGrupoUsuario.IdGrupoUsuario
								);";
				$res	=	mysql_query($sql,$con);
				if(mysql_num_rows($res)>=1){			
					$i = 0;
					$visivel = false;
					
					$sql	=	"select
									*
								from
									(
										select									
											ContaReceberDados.IdLoja,
											ContaReceberDados.IdContaReceber,				
											(ContaReceberDados.ValorFinal) Valor,
											ContaReceberDados.DataVencimento,
											ContaReceberDataVencimentoAnterior(ContaReceberDados.IdLoja,ContaReceberDados.IdContaReceber) DataVencimentoAnterior,
											ContaReceberDados.IdStatus,
											substr(Pessoa.Nome,1,70) Nome,		
											LocalCobranca.AbreviacaoNomeLocalCobranca								
										from
											ContaReceberDados,
											Pessoa,
											LocalCobranca
										where							
											ContaReceberDados.IdLoja = $local_IdLoja and
											ContaReceberDados.IdLoja = LocalCobranca.IdLoja and								
											ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
											ContaReceberDados.IdPessoa = Pessoa.IdPessoa and
											ContaReceberDados.IdStatus = 6
									) Temp
								order by
									$local_CampoOrdemBy2 $local_OrderBy2,
									IdContaReceber $local_OrderBy2
					";	
					$res	=	mysql_query($sql,$con);
					if(@mysql_num_rows($res) >= 1){
						$i = 0;
						
						while($lin	=	@mysql_fetch_array($res)){
							$sqlLancamentoFinanceiroDados = "
											select
												LancamentoFinanceiroDados.IdContrato										
											from
												LancamentoFinanceiroDados
											where
												IdLoja = $local_IdLoja and
												IdContaReceber = $lin[IdContaReceber]";
							$resLancamentoFinanceiroDados = mysql_query($sqlLancamentoFinanceiroDados,$con);
							$linLancamentoFinanceiroDados = mysql_fetch_array($resLancamentoFinanceiroDados);
					
							$lin[IdContrato]	= $linLancamentoFinanceiroDados[IdContrato];					
					
							$query = 'true';
							
							if($lin[IdContrato]!=''){
								if($_SESSION["RestringirCarteira"] == true){
									$sqlTemp =	"select 
													AgenteAutorizadoPessoa.IdContrato 
												from 
													AgenteAutorizadoPessoa,
													Carteira 
												where 
													AgenteAutorizadoPessoa.IdLoja = $local_IdLoja and 
													AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and 
													AgenteAutorizadoPessoa.IdCarteira = Carteira.IdCarteira and 
													Carteira.IdCarteira = $local_IdPessoaLogin and 
													Carteira.Restringir = 1 and 
													Carteira.IdStatus = 1 and
													AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
									$resTemp	=	@mysql_query($sqlTemp,$con);
									if(@mysql_num_rows($resTemp) == 0){
										$query = 'false';
									}
								}else{
									if($_SESSION["RestringirAgenteAutorizado"] == true){
										$sqlTemp =	"select 
														AgenteAutorizadoPessoa.IdContrato
													from 
														AgenteAutorizadoPessoa,
														AgenteAutorizado
													where 
														AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
														AgenteAutorizado.IdLoja = AgenteAutorizadoPessoa.IdLoja and 
														AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
														AgenteAutorizado.IdAgenteAutorizado = $local_IdPessoaLogin and 
														AgenteAutorizado.Restringir = 1 and 
														AgenteAutorizado.IdStatus = 1 and
														AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
										$resTemp	=	@mysql_query($sqlTemp,$con);
										if(@mysql_num_rows($resTemp) == 0){
											$query = 'false';
										}
									}
									if($_SESSION["RestringirAgenteCarteira"] == true){
										$sqlTemp		=	"select 
																AgenteAutorizadoPessoa.IdContrato
															from 
																AgenteAutorizadoPessoa,
																AgenteAutorizado,
																Carteira
															where 
																AgenteAutorizadoPessoa.IdLoja = $local_IdLoja  and 
																AgenteAutorizadoPessoa.IdLoja = AgenteAutorizado.IdLoja  and 
																AgenteAutorizadoPessoa.IdLoja = Carteira.IdLoja and
																AgenteAutorizado.IdAgenteAutorizado = AgenteAutorizadoPessoa.IdAgenteAutorizado and 
																AgenteAutorizado.IdAgenteAutorizado = Carteira.IdAgenteAutorizado and
																Carteira.IdCarteira = $local_IdPessoaLogin and 
																AgenteAutorizado.Restringir = 1 and 
																AgenteAutorizado.IdStatus = 1 and
																AgenteAutorizadoPessoa.IdContrato = $lin[IdContaReceber]";
										$resTemp	=	@mysql_query($sqlTemp,$con);
										if(@mysql_num_rows($resTemp) == 0){
											$query = 'false';
										}
									}
								}
							}					
						
							$ContaReceberDevolvido[IdContaReceber][$i] 			   = $lin[IdContaReceber]; 						
							$ContaReceberDevolvido[Nome][$i] 		 			   = $lin[Nome];
							$ContaReceberDevolvido[Valor][$i] 		 			   = $lin[Valor];
							$ContaReceberDevolvido[DataVencimentoAnterior][$i]	   = $lin[DataVencimentoAnterior];
							$ContaReceberDevolvido[DataVencimento][$i]			   = $lin[DataVencimento];
							$ContaReceberDevolvido[AbreviacaoNomeLocalCobranca][$i] = $lin[AbreviacaoNomeLocalCobranca];						
							$ContaReceberDevolvido[IdStatus][$i] 				   = $lin[IdStatus];
							$ContaReceberDevolvido[Restringir][$i]				   = $query;
							if($ContaReceberDevolvido[Restringir][$i] == 'true'){					
								$visivel = 	true;					
							}
							$i++;												
						}
						$qtd_cr = $i;
					}			

					if($visivel == true){
						$NomeBLContaReceberDevolvido = "ContaReceberDevolvido";
						echo"
						<div style='padding-top:5px;'>
							<div class='quadroAviso' style='width:100%-10px; margin:0 5px 0 5px;' id='Fatura'>
								<p class='tit'><a href='listar_conta_receber.php' style='color:#FFF'>CONTAS A RECEBER - DEVOLVIDO</a> <a href='cadastro_conta_receber.php' style='color:#FFF'>[+]</a></p>
								<div style='text-align:right; margin-top:-18px; padding-bottom:2px; cursor:move;'><img id='botao_conta_receber_devolvido' style='cursor:pointer; margin-right:4px;' onClick=\"ocultarQuadro(this, 'tabela".$NomeBLContaReceberDevolvido."');\" title='Minimizar' alt='Minimizar' src='../../img/estrutura_sistema/ico_seta_up.gif' /></div>
								<div id='tabela".$NomeBLContaReceberDevolvido."'>
								<table cellpadding='0' cellspacing='0' style='width:100%; margin-bottom: 0' id='Fatura'>
									<tr>
										<td width='40px'><a href='conteudo.php?CampoOrderBy2=1&Order2=$local_Order2' target='_self'><B>Id</B>$local_ImagemSeta2[1]</a></td>					
										<td><a href='conteudo.php?CampoOrderBy2=2&Order2=$local_Order2' target='_self'><B>Nome Pessoa</B>$local_ImagemSeta2[2]</a></td>					
										<td><a href='conteudo.php?CampoOrderBy2=3&Order2=$local_Order2' target='_self'><B>Local Cob.</B>$local_ImagemSeta2[3]</a></td>					
										<td><a href='conteudo.php?CampoOrderBy2=4&Order2=$local_Order2' target='_self'><B>Valor</B>$local_ImagemSeta2[4]</a></td>					
										<td><a href='conteudo.php?CampoOrderBy2=5&Order2=$local_Order2' target='_self'><B>Vencimento Anterior</B>$local_ImagemSeta2[5]</a></td>
										<td><a href='conteudo.php?CampoOrderBy2=6&Order2=$local_Order2' target='_self'><B>Vencimento</B>$local_ImagemSeta2[6]</a></td>				
										<td><a href='conteudo.php?CampoOrderBy2=7&Order2=$local_Order2' target='_self'><B>Status</B>$local_ImagemSeta2[7]</a></td>							
									</tr>";

							for($i=0; $i<$qtd_cr; $i++){
								
								if($ContaReceberDevolvido[Restringir][$i] == 'true'){										
									switch($ContaReceberDevolvido[IdStatus][$i]){
										case '0': 
											$local_CorContaReceber	= getParametroSistema(15,2);
											break;							
										case '2':									
											$local_CorContaReceber	= getParametroSistema(15,3);								
											break;							
										case '7':
											$local_CorContaReceber	= getParametroSistema(15,2);
											break;
										default:
											$local_CorContaReceber 	= "";							
											break;								
									}							
									
									$local_Status 					= 	getParametroSistema(35,$ContaReceberDevolvido[IdStatus][$i]);
									$local_DataVencimento 			=	dataConv($ContaReceberDevolvido[DataVencimento][$i],'Y-m-d','d/m/Y');								
									$local_DataVencimentoAnterior 	=	dataConv($ContaReceberDevolvido[DataVencimentoAnterior][$i],'Y-m-d','d/m/Y');	
									
									echo"
									<tr>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$ContaReceberDevolvido[IdContaReceber][$i]."</a></td>								
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$ContaReceberDevolvido[Nome][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$ContaReceberDevolvido[AbreviacaoNomeLocalCobranca][$i]."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$ContaReceberDevolvido[Valor][$i]."</a></td>						
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$local_DataVencimentoAnterior."</a></td>
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$local_DataVencimento."</a></td>							
										<td style='background-color: $local_CorContaReceber'><a href='cadastro_conta_receber.php?IdContaReceber=".$ContaReceberDevolvido[IdContaReceber][$i]."'>".$local_Status."</a></td>																												
									</tr>";						
								}			
							}					
							echo"
								</table>	
							</div>
						</div>
						</div>";
						
						if(empty($_SESSION["filtro_".$NomeBLContaReceberDevolvido])){
							$_SESSION["filtro_".$NomeBLContaReceberDevolvido] = 1;
						} else if($_SESSION["filtro_".$NomeBLContaReceberDevolvido] == 2){
							echo "<script type=\"text/javascript\"> ocultarQuadro(document.getElementById('botao_conta_receber_devolvido'), 'tabela".$NomeBLContaReceberDevolvido."'); </script>";
						}
					}
				}
			?>
		</div>
	</body>
</html>
<script>
	if(document.getElementById('tabelaQuadroHelpDesk') != null){
		buscar_quadro_help_desk('<?=getParametroSistema(4,7)?>', 'ASC', '');
	}
	if(document.getElementById('quadroAvisoOrdem') != null){
		tableMultColor('quadroAvisoOrdem','<?=getParametroSistema(15,1)?>');
	}
	if(document.getElementById('quadroAvisoOrdemUsuario') != null){
		tableMultColor('quadroAvisoOrdemUsuario','<?=getParametroSistema(15,1)?>');
	}
	if(document.getElementById('quadroAvisoCalendario') != null){
		inicia();
	}
	
	function calendario(m,ano){
		while(document.getElementById('quadroAvisoCalendario').rows.length > 2){
			document.getElementById('quadroAvisoCalendario').deleteRow(2);
		}
		
		var dte		= new Date();
		
		if(m=='' || ano==''){
			m 			= dte.getMonth();
			ano 		= dte.getFullYear();
		}
		
		var qtdDias = 	new Array();
		var mes 	=	new Array();
		var mesP 	=	new Array();
		var c	 	=	new Array();
		
		
		qtdDias		=	[31,(ano%4 == 0) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];	
		mes	  		= ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
		mesP	 	= ["jan","fev","mar","abr","mai","jun","jul","ago","set","out","nov","dez"];
	
		
		var data 		= new Date(ano,m,1);
		
		var iniSemana	= data.getDay();
		var quantDias	= qtdDias[m];
		
		day		= dte.getDate();
		month	= dte.getMonth();
		year 	= dte.getFullYear();
		
		if(day < 10)	day 	= '0'+day;
		if(month < 10)	month 	= '0'+month;
		
		hoje	=	day+'/'+month+'/'+year;
	
		pos	=	1;
		for(i=1; i<=6; i++){
			if(i ==	1){	pos	=	(-1*iniSemana)+1;	}
			tam 	= document.getElementById('quadroAvisoCalendario').rows.length;
			linha	= document.getElementById('quadroAvisoCalendario').insertRow(tam);
		
			for(j=0;j<=6;j++){
				c[j] =	linha.insertCell(j);	
			}			
			
			for(ii=pos, iii=0;	ii<=pos+6;	ii++){
				iii++;
				if(ii	<=	0){
					c[(iii-1)].innerHTML = '';
				}else{
					var dia = diaMax(quantDias,ii);
					
					if(dia < 10) day = '0'+dia;
					else		 day = dia;
					
					c[(iii-1)].innerHTML = day;
					
					if(dia != '' && dia != '&nbsp;'){
						m2	=	m;
						mesTemp	=	parseInt(m)+1;
						
						if(dia < 10) dia = '0'+dia;
						if(mesTemp < 10)	mesTemp = '0'+mesTemp;
						if(m2 < 10)  m2  = '0'+m2;
						
						if(dia+'/'+m2+'/'+ano == hoje){ 
							c[(iii-1)].style.backgroundColor	=	'<?=getParametroSistema(15,6)?>';	
						}
						
						dataT	=	dia+'/'+mesTemp+'/'+ano;
						
						busca_datas_especiais(dataT,c[(iii-1)]);
						busca_compromisso(dataT,c[(iii-1)],dia);
					}
				}
				if(ii	==	quantDias){
					diaFinal	=	iii-1;
				}										
			}
			pos	=	pos	+	7;			
		}
		
		var mesAnt	= parseInt(m), mesProx=parseInt(m), anoAnt=parseInt(ano), anoProx=parseInt(ano);
		
		mesAnt	=	parseInt(mesAnt)-1;	
		mesProx	=	parseInt(mesProx)+1;
		
		if(mesAnt < 0){		
			mesAnt  = 11;	
			anoAnt  = parseInt(anoAnt)-1;		
		}
		
		if(mesProx > 11){	
			mesProx = 0;	
			anoProx = parseInt(anoProx)+1;	
		}
		
		document.calendar.mesAnt.value	=	mesAnt;
		document.calendar.anoAnt.value	=	anoAnt;
		document.calendar.mesProx.value	=	mesProx;
		document.calendar.anoProx.value	=	anoProx;
		
		document.getElementById('Ant').innerHTML	=	mesP[mesAnt];
		document.getElementById('Atual').innerHTML	=	mes[m]+'/'+ano;
		document.getElementById('Prox').innerHTML	=	mesP[mesProx];
		
		legenda((parseInt(m)+1),ano);
	}

//	data('data');
</script>
