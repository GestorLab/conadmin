<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_IdLoja		= $_SESSION["IdLoja"];
	$local_IdContrato	= $_GET['IdContrato'];
	
	$sql = "select
				Contrato.IdContrato,
				Contrato.IdPessoa,
				Contrato.IdStatus,
				Contrato.IdServico,
				Contrato.DiaCobranca,
				Contrato.DataInicio,
				Contrato.DataPrimeiraCobranca,
				Contrato.DataUltimaCobranca,
				Contrato.DataTermino,
				Contrato.DataBaseCalculo,
				Pessoa.RazaoSocial,
				Pessoa.Nome,
				Pessoa.NomeRepresentante,
				Pessoa.CPF_CNPJ,
				PessoaEndereco.Endereco,
				PessoaEndereco.Numero,
				PessoaEndereco.Bairro,
				Cidade.NomeCidade,
				Estado.SiglaEstado,
				PessoaEndereco.CEP,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,
				Pessoa.Celular,
				Pessoa.Fax,
				Servico.DescricaoServico,
				LocalCobranca.DescricaoLocalCobranca
			from
				Contrato,
				Pessoa,
				PessoaEndereco,
				Estado,
				Cidade,
				Servico,
				LocalCobranca
			where
				Contrato.IdLoja = $local_IdLoja and
				Contrato.IdLoja = Servico.IdLoja and
				Contrato.IdLoja = LocalCobranca.IdLoja and
				Contrato.IdContrato = $local_IdContrato and
				Contrato.IdLocalCobranca = LocalCobranca.IdLocalCobranca and
				Contrato.IdPessoa = Pessoa.IdPessoa and
				Contrato.IdServico = Servico.IdServico and
				Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
				Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
				PessoaEndereco.IdPais = Estado.IdPais and
				PessoaEndereco.IdEstado = Estado.IdEstado and
				PessoaEndereco.IdPais = Cidade.IdPais and
				PessoaEndereco.IdEstado = Cidade.IdEstado and
				PessoaEndereco.IdCidade = Cidade.IdCidade;";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	if($lin[RazaoSocial] != ""){
		$lin[DadoPessoa] = "
			<table cellpadding='0' cellspacing='0'>
				<tr>
					<td><b>Raz�o Social:</b> [$lin[IdPessoa]] $lin[RazaoSocial]</td>
					<td><b>CNPJ:</b> $lin[CPF_CNPJ]</td>
				</tr>
				<tr>
					<td><b>Nome Fantasia:</b> $lin[Nome]</td>
					<td><b>Nome Representante:</b> $lin[NomeRepresentante]</td>
				</tr>
			</table>";
	} else{
		$lin[DadoPessoa] = "
			<table cellpadding='0' cellspacing='0'>
				<tr>
					<td><b>Nome Pessoa:</b> [$lin[IdPessoa]] $lin[Nome]</td>
					<td><b>CPF:</b> $lin[CPF_CNPJ]</td>
				</tr>
			</table>";
	}
	
	$lin[Endereco] = "<b>Endere�o:</b> $lin[Endereco], $lin[Numero]";
	
	if($lin[Bairro] != ""){
		$lin[Endereco] .= " - $lin[Bairro]";
	}
	
	$lin[Endereco] .= " - $lin[NomeCidade]-$lin[SiglaEstado] - <b>Cep:</b> $lin[CEP]";
	
	if($lin[Telefone1] != ""){
		$lin[Fone] = "<b>Fone:</b> $lin[Telefone1]";
	}
	if($lin[Telefone2] != ""){
		if($lin[Fone] != ""){
			$lin[Fone] .= " / ";
		} else{
			$lin[Fone] .= "<b>Fone:</b> ";
		}
		
		$lin[Fone] .= "$lin[Telefone2]";
	}
	if($lin[Telefone3] != ""){
		if($lin[Fone] != ""){
			$lin[Fone] .= " / ";
		} else{
			$lin[Fone] .= "<b>Fone:</b> ";
		}
		
		$lin[Fone] .= "$lin[Telefone3]";
	}
	if($lin[Celular] != ""){
		if($lin[Fone] != ""){
			$lin[Fone] .= " - <b>Celular:</b> $lin[Celular]";
		} else{
			$lin[Fone] .= "<b>Celular:</b> $lin[Celular]";
		}
	}
	
	if($lin[Fax] != ""){
		if($lin[Fone] != ""){
			$lin[Fone] .= " - <b>Fax:</b> $lin[Fax]";
		} else{
			$lin[Fone] .= "<b>Fax:</b> $lin[Fax]";
		}
	}
	
	$lin[Data] = "
		<table cellpadding='0' cellspacing='0'>
			<tr>
				<td><b>Data In�cio Cont.:</b> ".dataConv($lin[DataInicio],'Y-m-d','d/m/Y')."</td>";
	$lin[Data] .= "<td><b>Data Inicio Cob.:</b> ".dataConv($lin[DataPrimeiraCobranca],'Y-m-d','d/m/Y')."</td>";
	
	if($lin[DataBaseCalculo]!=""){
		$lin[Data] .= "<td><b>Data Base:</b> ".dataConv($lin[DataBaseCalculo],'Y-m-d','d/m/Y')."</td>";
	}
	
	if($lin[DataTermino]!=""){
		$lin[Data] .= "<td><b>Data T�rmino Cont.:</b> ".dataConv($lin[DataTermino],'Y-m-d','d/m/Y')."</td>";
	}
	
	if($lin[DataUltimaCobranca]!=""){
		$lin[Data] .= "<td><b>Data �ltima Cob.:</b> ".dataConv($lin[DataUltimaCobranca],'Y-m-d','d/m/Y')."</td>";
	}
	
	$sql1	=	"select
					Pessoa.TipoPessoa,
					Pessoa.RazaoSocial,
					PessoaEndereco.Endereco,
					PessoaEndereco.Numero,
					PessoaEndereco.Bairro,
					Cidade.NomeCidade,
					Estado.SiglaEstado,
					PessoaEndereco.CEP,
					Pessoa.CPF_CNPJ,
					Pessoa.Telefone1,
					Pessoa.Telefone2,
					Pessoa.Telefone3,
					Pessoa.Fax
				from
					Loja,
					Pessoa,
					PessoaEndereco,
					Estado,
					Cidade
				where
					Loja.IdLoja = $local_IdLoja and
					Loja.IdPessoa = Pessoa.IdPessoa and
					Pessoa.IdPessoa = PessoaEndereco.IdPessoa and
					Pessoa.IdEnderecoDefault = PessoaEndereco.IdPessoaEndereco and
					PessoaEndereco.IdPais = Estado.IdPais and
					PessoaEndereco.IdEstado = Estado.IdEstado and
					PessoaEndereco.IdPais = Cidade.IdPais and
					PessoaEndereco.IdEstado = Cidade.IdEstado and
					PessoaEndereco.IdCidade = Cidade.IdCidade";
	$res1	=	@mysql_query($sql1,$con);
	if($lin1 = @mysql_fetch_array($res1)){
		$CabecalhoEndereco = "$lin1[RazaoSocial]<br />$lin1[Endereco], $lin1[Numero]";
		
		if($lin1[Bairro] != ""){
			$CabecalhoEndereco .= " - $lin1[Bairro]";
		}
		
		$CabecalhoEndereco .= " - $lin1[NomeCidade]-$lin1[SiglaEstado] - Cep: $lin1[CEP]<br />";
		
		if($lin1[TipoPessoa] == 1){
			$CabecalhoEndereco .= "CNPJ: $lin1[CPF_CNPJ]";
		} else{
			$CabecalhoEndereco .= "CPF: $lin1[CPF_CNPJ]";
		}
		
		if($lin1[Telefone1] != ""){
			$telefone = " - Fone: $lin1[Telefone1]";
		}
		if($lin1[Telefone2] != ""){
			if($telefone != ""){
				$telefone .= " / ";
			} else{
				$telefone .= " - Fone: ";
			}
			
			$telefone .= "$lin1[Telefone2]";
		}
		if($lin1[Telefone3] != ""){
			if($telefone != ""){
				$telefone .= " / ";
			} else{
				$telefone .= " - Fone: ";
			}
			
			$telefone .= "$lin1[Telefone3]";
		}
		
		$CabecalhoEndereco .= $telefone;
		
		if($lin1[Fax] != ""){
			$CabecalhoEndereco .= " Fax: $lin1[Fax]";
		}
	}
	
	$sql1 = "select Valor from ContratoVigencia where DataInicio <= curdate() and (DataTermino is Null  or DataTermino >= curdate()) and IdLoja = $local_IdLoja and IdContrato = $lin[IdContrato] order BY DataInicio DESC LIMIT 0,1;";
	$res1 = @mysql_query($sql1,$con);
	if($lin1 = @mysql_fetch_array($res1)){
		if($lin1[Valor]!=""){
			$lin1[Valor] = "<td><b>Valor Mensal (".getParametroSistema(5,1)."):</b> ".str_replace(".",",",$lin1[Valor])."</td>";
		}
	}
	$lin[Data] .= "</tr></table>";
	
	$Impress	=	"
		<HTML>
			<head>
				<title>".getParametroSistema(4,1)."</title>
				<link REL='SHORTCUT ICON' HREF='../../img/estrutura_sistema/favicon.ico'>
				<style type='text/css'>
					body{ margin:0; padding:5px; font:normal 10px Verdana, Arial, Times; }
					#conteiner{font: normal 10px Verdana, Arial, Times; margin: auto;}
					#conteiner #cabecalho{width:100%; border-bottom:1px solid #AAA; margin-bottom:12px;}
					#conteiner #quadro{width:100%; border:1px solid #000; margin-bottom:12px; text-align: justify;}
					#conteiner #cab_titulo{font-weight:bold; font-size:13px;}
					#conteiner .bloco{width:100%; border:1px solid #000; margin-bottom:12px; text-align: justify;}
					#conteiner .titulo{margin-top:20px; border-bottom:1px solid #AAA; font-weight:bold; font-size:13px;}
					#conteiner .sub_titulo{padding:11px 11px 2px 11px; font-size:11px; font-weight:bold;}
				</style>
			</head>
			<body>
				<div id='conteiner'>
					<table id='cabecalho' cellspacing='0' cellpading='0'>
						<tr>
							<td style='width:10px; height: 68px'>&nbsp;</td>
							<td><img src='../../img/personalizacao/logo_cab.gif' /></td>
							<td style='text-align:right; font-size:9px; padding-right:11px;'>
								$CabecalhoEndereco
							</td>
						</tr>
						<tr><td style='height:2px;' /></tr>
						<tr>
							<td colspan='2' id='cab_titulo'>Contrato $lin[IdContrato]</td>
							<td style='text-align:right; font-size:9px;'>".date("d/m/Y H:m:s")."&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:13px;'><b>Status: </b>".getParametroSistema(69,$lin[IdStatus])."</span></td>
						</tr>
					</table>
					<table id='quadro' cellspacing='0' cellpadding='0'>
						<tr>
							<td colspan='3' style='padding:11px 11px 0 11px;'>$lin[DadoPessoa]</td>
						</tr>
						<tr>
							<td colspan='3' style='padding:0 11px 0 11px;'>$lin[Endereco]</td>
						</tr>
						<tr>
							<td colspan='3' style='padding:0 11px 0 11px;'>$lin[Fone]</td>
						</tr>
						<tr>
							<td colspan='3' style='padding:0 11px 0 11px;'><b>Servi�o:</b> [$lin[IdServico]] $lin[DescricaoServico]</td>
						</tr>
						<tr>
							<td style='padding:0 0 0 11px;'><b>Local de Cobran�a:</b> $lin[DescricaoLocalCobranca]</td>
							<td style='width:130px; padding:0 11px 0 0;'><b>Dia Vencimento:</b> $lin[DiaCobranca]</td>
							$lin1[Valor]
						</tr>
						<tr>
							<td style='padding:0 11px 11px 11px;' colspan='3'>$lin[Data]</td>
						</tr>
					</table>
					<div class='titulo'>Ordem de Servi�o</div>
	";
	
	$sql1 = "select
				ServicoParametro.IdServico,
				ServicoParametro.IdParametroServico,
				ServicoParametro.DescricaoParametroServico,
				ContratoParametro.Valor,
				ServicoParametro.ValorDefault,
				ServicoParametro.Obrigatorio,
				ServicoParametro.Obs,
				ServicoParametro.RotinaCalculo,
				ServicoParametro.RotinaOpcoes,
				ServicoParametro.RotinaOpcoesContrato,
				ServicoParametro.Calculavel,
				ServicoParametro.RotinaOpcoes,
				ServicoParametro.RotinaOpcoesContrato,
				ServicoParametro.CalculavelOpcoes,
				ServicoParametro.Editavel,
				ServicoParametro.IdTipoParametro,
				ServicoParametro.IdMascaraCampo,
				ServicoParametro.IdTipoTexto,
				ServicoParametro.ExibirSenha,
				ServicoParametro.TamMinimo,
				ServicoParametro.TamMaximo,
				ServicoParametro.OpcaoValor,
				ServicoParametro.Visivel,
				ServicoParametro.VisivelOS
			from 
				Loja,
				Servico,
				ServicoParametro LEFT JOIN 
						ContratoParametro ON (
							ServicoParametro.IdLoja = ContratoParametro.IdLoja and 
							ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico and
							ServicoParametro.IdServico = ContratoParametro.IdServico and
							ContratoParametro.IdContrato = $local_IdContrato)
			where
				Servico.IdLoja = $local_IdLoja and
				Servico.IdServico = ServicoParametro.IdServico and
				ServicoParametro.IdLoja = Servico.IdLoja and
				Servico.IdLoja = Loja.IdLoja and
				ServicoParametro.IdServico = $lin[IdServico] and
				ServicoParametro.IdStatus = 1 and
				ServicoParametro.VisivelOS = 1
			order by 
				ServicoParametro.IdParametroServico ASC;";
	$res1 = mysql_query($sql1,$con);
	while($lin1 = mysql_fetch_array($res1)){
		if($lin1[Valor]!=""){
			if($lin1[ExibirSenha]==2 ){
				for($i=0;$i<strlen($lin1[Valor]);$i++){
					$Valor .= "*";
				}
				$lin1[Valor] = $Valor;
			}
			
			if($ParametroContrato!=""){
				$ParametroContrato .= " - <b>$lin1[DescricaoParametroServico]:</b> $lin1[Valor]";
			}else{
				$ParametroContrato = "<b>$lin1[DescricaoParametroServico]:</b> $lin1[Valor]";
			}
		}
	}
	
	$sql2 = "select
				OrdemServico.IdOrdemServico,
				OrdemServico.IdStatus,
				OrdemServico.IdServico,
				OrdemServico.ValorTotal,
				OrdemServico.DescricaoOS,
				OrdemServico.IdGrupoUsuarioAtendimento,
				OrdemServico.LoginAtendimento,
				OrdemServico.Obs,
				subString(OrdemServico.DataAgendamentoAtendimento,1,10) DataAgendamento,
				subString(OrdemServico.DataAgendamentoAtendimento,12,5) HoraAgendamento,
				TipoOrdemServico.DescricaoTipoOrdemServico,
				SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
				Servico.DetalheServico,
				Servico.DescricaoServico
			from
				OrdemServico,
				TipoOrdemServico,
				SubTipoOrdemServico,
				Servico
			where
				OrdemServico.IdLoja = $local_IdLoja and
				OrdemServico.IdContrato = $lin[IdContrato] and
				OrdemServico.IdStatus > 99 and
				OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
				OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
				OrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
				OrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico and
				OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
				OrdemServico.IdLoja = Servico.IdLoja and
				OrdemServico.IdServico = Servico.IdServico
			order by
				OrdemServico.IdOrdemServico ASC;";
	$res2 = mysql_query($sql2,$con);
	while($lin2 = mysql_fetch_array($res2)){
		$lin2[ValorTotal] = str_replace('.',',',$lin2[ValorTotal]);
		
		if($lin2[DetalheServico]!=""){
			$lin2[DetalheServico] = "<tr><td colspan='2'  style='padding:8px 11px 0 11px;'><b>Descri��o Servi�o:</b><br /><u>$lin2[DetalheServico]</u></td></tr>";
		}
		
		$where = "";
		if($lin2[LoginAtendimento]!=""){
			$where .= " and UsuarioGrupoUsuario.Login = '$lin2[LoginAtendimento]'";
		}
		if($lin2[IdGrupoUsuarioAtendimento]!=""){
			$where .= " and UsuarioGrupoUsuario.IdGrupoUsuario = $lin2[IdGrupoUsuarioAtendimento]";
		}
		
		$sql3 = "select 
					UsuarioGrupoUsuario.IdGrupoUsuario, 
					GrupoUsuario.DescricaoGrupoUsuario,
					Usuario.Login,
					Pessoa.Nome
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
					Usuario.IdStatus = 1 and
					GrupoUsuario.OrdemServico = 1 $where;";
		$res3 = mysql_query($sql3,$con);
		if($lin3 = mysql_fetch_array($res3)){
			$Atendimento = "<tr><td colspan='2' class='sub_titulo'>Hist�rico Atual</td></tr>";
				if($lin2[Obs]!=""){
					$lin2[Obs] = str_replace("\n","<br />",$lin2[Obs]);
					$Atendimento .= "
			<tr>
				<td colspan='2' style='padding:0 11px 0 11px;'>
					<div style='padding:2px; border:1px solid #000;'>$lin2[Obs]</div>
				</td>
			</tr>";
				}
			$Atendimento .= "
			<tr>
				<td colspan='2' style='padding:2px 11px 11px 11px;'>
					<table cellpadding='0' cellspacing='0'>
						<tr>
							<td><b>Grupo Atendimento:</b> $lin3[DescricaoGrupoUsuario]</td>";
			
			if($lin3[Login]!="" && $where!=""){
				$Atendimento .= "<td><b>Usu�rio de Atendimento:</b> $lin3[Nome]</td>";
			}
			if($lin2[DataAgendamento]!=""){
				$Atendimento .= "<td><b>Data Age.:</b> ".dataConv($lin2[DataAgendamento],'Y-m-d','d/m/Y')."</td>";
			}
			if($lin2[HoraAgendamento]!="" && $lin2[HoraAgendamento]!="00:00"){
				$Atendimento .= "<td><b>Hora Age.:</b> $lin2[HoraAgendamento]</td>";
			}
			$Atendimento .= "
						</tr>
					</table>
				</td>
			</tr>";
		}
		
		$Impress .= "	<div style='margin:11px 0 11px 0;'>
							<table style='width:100%; border:1px solid #000;' cellpadding='0' cellspacing='0'>
								<tr>
									<td style='padding:11px 0 0 11px;'><b>OS $lin2[IdOrdemServico]</b></td>
									<td style='padding:11px 11px 0 0;'><b>Status OS: </b>".getParametroSistema(40,$lin2[IdStatus])."</td>
								</tr>
								<tr>
									<td style='padding:8px 0 0 11px;'><b>Tipo OS:</b> $lin2[DescricaoTipoOrdemServico]</td>
									<td style='padding:8px 11px 0 0;'><b>Sub Tipo OS:</b> $lin2[DescricaoSubTipoOrdemServico]</td>
								</tr>
								<tr>
									<td style='padding-left:11px;'><b>Servi�o:</b> [$lin2[IdServico]] $lin2[DescricaoServico]</td>
									<td style='padding-right:11px;'><b>Valor:</b> $lin2[ValorTotal]</td>
								</tr>
								$lin2[DetalheServico]
								<tr>
									<td colspan='2' style='padding:8px 11px 0 11px;'>
										<b>Descri��o OS:</b>
										<br />
										<u>$lin2[DescricaoOS]</u>
									</td>
								</tr>
								<tr><td colspan='2' class='sub_titulo'>Par�metros do Contrato</td></tr>
								<tr>
									<td colspan='2' style='padding:0 11px 0 11px;'>
										$ParametroContrato
									</td>
								</tr>
								$Atendimento
							</table>
						</div>";
	}
	$Impress	.=	"
					</div>
				</div>
			</body>
		</HTML>
		<HTML>
			<HEAD>
				<style type='text/css'>
					body{	margin:0; padding:5px;	font: normal 10px Verdana, Arial, Times;	}
					p{ 	margin:0 0 1px 0; }
					table{ width:99%;	}	
					table .lateral{	width: 15px; padding: 0; border-right:1px #000 solid; text-align:center; }
					table tr td{	font: normal 10px Verdana, Arial, Times; margin:0; }
					h1{	font-size: 20px; font-weight:bold}
					.boatao{ padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid;	height: 20px; background-color: #FFF; }
				</style>
				<link rel = 'stylesheet' type = 'text/css' href = '../../css/impress.css' media='print' />
			</HEAD>
			<BODY>
				<BR><BR>
				<table class='impress'>
					<tr>
						<td class='campo' style='text-align:center'>
							<input type='button' name='bt_imprimir' value='Imprimir' class='botao' onClick='javascript:self.print()'>
						</td>
					</tr>
				</table>
			</BODY>
		</HTML>
	";
	echo $Impress;
?>
