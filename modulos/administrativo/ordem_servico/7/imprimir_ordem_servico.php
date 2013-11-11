<?
	$localModulo		=	1;
	$localOperacao		=	26;
	$localSuboperacao	=	"V";	

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			= $_SESSION["Login"];
	$local_IdLoja			= $_SESSION["IdLoja"];
	$local_IdOrdemServico	= $_POST['IdOrdemServico'];

	$sql = "select
				OrdemServico.DataCriacao,
				OrdemServico.IdTipoOrdemServico,
				TipoOrdemServico.DescricaoTipoOrdemServico,
				OrdemServico.IdSubTipoOrdemServico,
				SubTipoOrdemServico.DescricaoSubTipoOrdemServico,
				OrdemServico.DataAgendamentoAtendimento,
				OrdemServico.Obs,
				OrdemServico.DescricaoOS,
				OrdemServico.IdGrupoUsuarioAtendimento,
				OrdemServico.LoginAtendimento,
				REPLACE(OrdemServico.Valor,'.', ',') Valor,
				REPLACE(OrdemServico.ValorOutros,'.', ',') ValorOutros,
				REPLACE(OrdemServico.Valor+OrdemServico.ValorOutros,'.', ',') ValorTotal,
				OrdemServico.DescricaoOutros,
				PessoaUsuario.NomeUsuario,
				PessoaUsuario.RazaoSocialUsuario,
				Pessoa.NomeRepresentante,
				Pessoa.IdPessoa,
				Pessoa.TipoPessoa,
				Pessoa.Nome,
				Pessoa.RazaoSocial,
				PessoaEndereco.Endereco,
				PessoaEndereco.Bairro,
				PessoaEndereco.CEP,
				PessoaEndereco.Complemento,
				PessoaEndereco.Numero,
				Pais.NomePais,
				Estado.SiglaEstado,
				Cidade.NomeCidade,
				Pessoa.Telefone1,
				Pessoa.Telefone2,
				Pessoa.Telefone3,					
				Pessoa.Celular,
				OrdemServico.IdServico,
				OrdemServico.IdContrato,
				ContratoServico.IdServicoContrato,	
				ContratoServico.DescricaoServicoContrato,
				ContratoServico.IdStatus,
				ContratoServico.VarStatus,
				Servico.DescricaoServico,
				Servico.IdTipoServico,
				Servico.DetalheServico				
			from
				OrdemServico 
					LEFT JOIN Servico ON (OrdemServico.IdServico = Servico.IdServico and Servico.IdLoja = OrdemServico.IdLoja) 
					LEFT JOIN Usuario ON (OrdemServico.LoginAtendimento = Usuario.Login) 
					LEFT JOIN (select IdPessoa IdPessoaUsuario,Nome NomeUsuario, RazaoSocial RazaoSocialUsuario from Pessoa) PessoaUsuario ON (Usuario.IdPessoa = PessoaUsuario.IdPessoaUsuario) 
					LEFT JOIN (select IdContrato IdContratoServico,Contrato.IdServico IdServicoContrato, DescricaoServico DescricaoServicoContrato, Contrato.IdStatus, Contrato.VarStatus from Contrato,Servico where Servico.IdLoja = $local_IdLoja and Contrato.IdLoja = Servico.IdLoja and Contrato.IdServico = Servico.IdServico) ContratoServico ON (OrdemServico.IdContrato = ContratoServico.IdContratoServico) 
					LEFT JOIN Pessoa ON (OrdemServico.IdPessoa = Pessoa.IdPessoa) 
					LEFT JOIN PessoaEndereco ON (PessoaEndereco.IdPessoa = Pessoa.IdPessoa and PessoaEndereco.IdPessoaEndereco = OrdemServico.IdPessoaEndereco) 
					LEFT JOIN Pais ON (Pais.IdPais = PessoaEndereco.IdPais) 
					LEFT JOIN Estado ON (Pais.IdPais = Estado.IdPais and Estado.IdEstado = PessoaEndereco.IdEstado) 
					LEFT JOIN Cidade ON (Pais.IdPais = Cidade.IdPais and Cidade.IdCidade = PessoaEndereco.IdCidade and Estado.IdEstado = Cidade.IdEstado),
				TipoOrdemServico,
				SubTipoOrdemServico				
			where
				OrdemServico.IdLoja = $local_IdLoja and 
				OrdemServico.IdOrdemServico = $local_IdOrdemServico and 
				OrdemServico.IdLoja = TipoOrdemServico.IdLoja and
				TipoOrdemServico.IdLoja = SubTipoOrdemServico.IdLoja and
				OrdemServico.IdTipoOrdemServico = TipoOrdemServico.IdTipoOrdemServico and
				OrdemServico.IdSubTipoOrdemServico = SubTipoOrdemServico.IdSubTipoOrdemServico and
				TipoOrdemServico.IdTipoOrdemServico = SubTipoOrdemServico.IdTipoOrdemServico";
	$res = mysql_query($sql,$con);
	$lin = mysql_fetch_array($res);
	
	$sql4	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 69 and IdParametroSistema = $lin[IdStatus]"; 
	$res4	=	@mysql_query($sql4,$con);
	$lin4	=	@mysql_fetch_array($res4);
	
	if($lin[VarStatus] != ''){
		switch($lin[IdStatus]){
			case '201':
				$lin4[ValorParametroSistema]	=	str_replace("Temporariamente","até $lin[VarStatus]",$lin4[ValorParametroSistema]);	
				break;
		}					
	}
	
	$sql5	=	"select ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 71 and IdParametroSistema = $lin[IdTipoServico]"; 
	$res5	=	@mysql_query($sql5,$con);
	$lin5	=	@mysql_fetch_array($res5);
	
	if($lin[Nome]==""){
		$lin[Nome]	=	$lin[RazaoSocial];
	}
	
	if($lin[IdPessoa]!=""){
		if(getCodigoInterno(3,224) == '1'){
			$lin[Nome]	=	"[$lin[IdPessoa]] $lin[Nome]";
		}else{
			$lin[Nome]	=	$lin[Nome];
		}
	}
	
	if($lin[LoginAtendimento]!=""){
		if($lin[RazaoSocialUsuario]!=""){
			$lin[NomeUsuario]	=	$lin[RazaoSocialUsuario];
		}
	
		$lin[LoginAtendimento]	=	"[".$lin[LoginAtendimento]."] ".$lin[NomeUsuario];
	}
	
	$endereco			 = "";
	$fone	 			 = "";
	$contrato			 = "";
	$atendimento 		 = "";
	$dataAgendamento	 = "";
	$horaAgendamento	 = "";
	
	
	if($lin[LoginAtendimento]==""){
		$sql6	=	"select DescricaoGrupoUsuario from GrupoUsuario where IdLoja = $local_IdLoja and IdGrupoUsuario = $lin[IdGrupoUsuarioAtendimento]";
		$res6	=	@mysql_query($sql6,$con);
		$lin6	=	@mysql_fetch_array($res6);
	
		$atendimento	=	"<B>Grupo Atendimento:</B> $lin6[DescricaoGrupoUsuario]";
	}else{
		$atendimento	=	"<B>Responsável:</B> $lin[LoginAtendimento]";
	}
	
	if($lin[Endereco]!= ""){
		$endereco	.=	$lin[Endereco];
	}
	if($lin[Complemento]!=""){
		if($endereco!=""){
			$endereco	.= " - ";
		}
		$endereco	.=	$lin[Complemento];
	}
	if($lin[Numero]!=""){
		if($endereco!=""){
			$endereco	.= ", nº ";
		}
		$endereco	.=	$lin[Numero];
	}
	
	if($lin[Bairro]!=""){
		if($endereco!=""){
			$endereco	.= " - ";
		}
		$endereco	.=	$lin[Bairro];
	}
	
	if($lin[NomeCidade]!=""){
		if($endereco!=""){
			$endereco	.= " - ";
		}
		$endereco	.=	$lin[NomeCidade]."-".$lin[SiglaEstado];
	}
	
	if($lin[Telefone1]!=""){
		if($fone!=""){
			$fone	.= " / ";
		}
		$fone	.=	$lin[Telefone1];
	}
	
	if($lin[Telefone2]!=""){
		if($fone!=""){
			$fone	.= " / ";
		}
		$fone	.=	$lin[Telefone2];
	}
	
	if($lin[Telefone3]!=""){
		if($fone!=""){
			$fone	.= " / ";
		}
		$fone	.=	$lin[Telefone3];
	}
	
	if($lin[Celular]!=""){
		if($fone!=""){
			$fone	.= " / ";
		}
		$fone	.=	$lin[Celular];
	}
	
	if($lin[Fax]!=""){
		if($fone!=""){
			$fone	.= " / ";
		}
		$fone	.=	$lin[Fax];
	}
	
	$contrato	=	"[".$lin[IdContrato]."] ".$lin[DescricaoServicoContrato]." [".$lin4[ValorParametroSistema]."]";
	
	
	if($lin[DataAgendamentoAtendimento]!=""){
		$dataAgendamento	= 	dataConv($lin[DataAgendamentoAtendimento],'Y-m-d','d/m/y');
		$horaAgendamento	=	substr($lin[DataAgendamentoAtendimento],10,6);
	
		$agendado	=	"<B>Agendado:</B><BR>$dataAgendamento<BR>$horaAgendamento";
	}else{
		$agendado	=	"&nbsp;";
	}
	$sql7	=	"select
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
	$res7	=	@mysql_query($sql7,$con);
	if($lin7 = @mysql_fetch_array($res7)){
		$CabecalhoEndereco = "$lin7[RazaoSocial]<br />$lin7[Endereco], $lin7[Numero]";
		
		if($lin7[Bairro] != ""){
			$CabecalhoEndereco .= " - $lin7[Bairro]";
		}
		
		$CabecalhoEndereco .= " - $lin7[NomeCidade]-$lin7[SiglaEstado] - Cep: $lin7[CEP]<br />";
		
		if($lin7[TipoPessoa] == 1){
			$CabecalhoEndereco .= "CNPJ: $lin7[CPF_CNPJ]";
		} else{
			$CabecalhoEndereco .= "CPF: $lin7[CPF_CNPJ]";
		}
		
		if($lin7[Telefone1] != ""){
			$telefone = " - Fone: $lin7[Telefone1]";
		}
		if($lin7[Telefone2] != ""){
			if($telefone != ""){
				$telefone .= " / ";
			} else{
				$telefone .= " - Fone: ";
			}
			
			$telefone .= "$lin7[Telefone2]";
		}
		if($lin7[Telefone3] != ""){
			if($telefone != ""){
				$telefone .= " / ";
			} else{
				$telefone .= " - Fone: ";
			}
			
			$telefone .= "$lin7[Telefone3]";
		}
		
		$CabecalhoEndereco .= $telefone;
		
		if($lin7[Fax] != ""){
			$CabecalhoEndereco .= " Fax: $lin7[Fax]";
		}
	}
	
	$sqlLogoLoja = "select
						IdLoja,
						LogoPersonalizada 
					from
						Loja 
					where
						IdLoja = $local_IdLoja";
	$resLogoLoja = mysql_query($sqlLogoLoja,$con);
	$linLogoLoja = mysql_fetch_array($resLogoLoja);
	
	if($linLogoLoja[LogoPersonalizada] == 1){
		$tdlogo ="<td><img src='../../../../img/personalizacao/".$linLogoLoja[IdLoja]."/logo_cab.gif' /></td>";
	}else{
		$tdlogo ="<td><img src='../../../../img/personalizacao/logo_cab.gif' /></td>";
	}
	
	$Impress	=	"<HTML>
			<HEAD>
				<title>".getParametroSistema(4,1)."</title>
				<link REL='SHORTCUT ICON' HREF='../../../../img/estrutura_sistema/favicon.ico'>
				<style type='text/css'>
					body{	margin:0; padding:5px;	font: normal 10px Verdana, Arial, Times;	}
					p{ 	margin:0 0 1px 0; }
					table{ width:99%;	}	
					table .lateral{	width: 15px; padding: 0; border-right:1px #000 solid; text-align:center; }
					table tr td{	font: normal 10px Verdana, Arial, Times; margin:0; padding-left:5px;	}
					hr{	margin:0; padding:0; }
					#conteudo { width:100%; border: 1px #000 solid; padding:0; }
					h1{	font-size: 20px; font-weight:bold}
				</style>
			</HEAD>
			<BODY>
				<div id='conteudo'>
					<table style='width:100%;' cellspacing='0' cellpading='0'>
						<tr>
							<td style='width:10px; height: 68px'>&nbsp;</td>
							$tdlogo
							<td style='text-align:right; font-size:9px; padding-right:12px;'>
								$CabecalhoEndereco
							</td>
							<td style='text-align:center; font-size:12px; border-left:1px #000 solid; width: 90px'>
								<B>OS</B> $local_IdOrdemServico
								$agendado
							</td>
						</tr>
					</table>";
					
					if($lin[IdTipoOrdemServico] == 1){
						$Impress.="
						<table style='width:100%; border-top:1px #000 solid;' cellspacing='0' cellpading='0'>
							<tr>
								<td class='lateral' style='font-size:7px'>C<BR>L<BR>I<BR>E<BR>N<BR>T<BR>E</td>
								<td style='font-size:12px;border-right:1px solid #000;'>
									<p style='margin:0; padding-bottom:3px'><B>Nome:</B> $lin[Nome]</p>";
						
						if($lin[NomeRepresentante] != ''){
							$Impress.="<p style='margin:0; padding-bottom:3px'><B>Nome Representante:</B> $lin[NomeRepresentante]</p>";
						}
						
						$Impress.="
									<p style='margin:0; padding-bottom:3px'><B>Endereço:</B> $endereco</p>
									<p style='margin:0; padding-bottom:3px'><B>Fone(s):</B> $fone</p>
								</td>
								<td style='width:50%'>
									<table style='width:100%; margin:0'>
										<tr>
											<td style='font-size: 11px'><B>Tipo OS:</B> $lin[DescricaoTipoOrdemServico]</td>
											<td style='font-size: 11px'><B>SubTipo OS:</B> $lin[DescricaoSubTipoOrdemServico]</td>
										</tr>
										<tr>
											<td colspan='2' style='font-size: 11px'>
												<B>Data Abertura:</B> ".dataConv($lin[DataCriacao],'Y-m-d H:i:s','d/m/Y H:i:s')."
											</td>
										</tr>
										<tr>
											<td colspan='2' style='font-size: 11px'>
												$atendimento
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						";
					}
					$Impress.="
					<table style='width:100%; border-top:1px #000 solid' cellspacing='0' cellpading='0'>
						<tr>
							<td class='lateral'>O<BR>S</td>
							<td style='padding:2px 5px 2px 5px;'>
						";
					if($lin[IdContrato]!=""){
						$sql3	=	"select IdServico from Contrato where IdLoja = $local_IdLoja and IdContrato = $lin[IdContrato]";
						$res3	=	@mysql_query($sql3,$con);
						$lin3	=	@mysql_fetch_array($res3);
						
						$Impress.="<p><B>Contrato Vinculado:</B> $contrato</p>";
						
						
						$i		=	0;
						$sql2	=	"select
								    ServicoParametro.IdParametroServico,
								    ServicoParametro.DescricaoParametroServico,
								    ServicoParametro.ValorDefault,
								    ContratoParametro.Valor,
								    ServicoParametro.Obrigatorio,
								    ServicoParametro.Obs,
								    ServicoParametro.RotinaCalculo,
								    ServicoParametro.Calculavel 
								from
								    ServicoParametro left join (select * from ContratoParametro where ContratoParametro.IdContrato=$lin[IdContrato] and ContratoParametro.IdServico=$lin3[IdServico]) ContratoParametro on (ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico)
								where
									ServicoParametro.IdLoja = $local_IdLoja and
									ServicoParametro.IdStatus = 1 and
									ServicoParametro.IdServico= $lin3[IdServico] and
									ServicoParametro.VisivelOS = 1 
			                    order by 
									ServicoParametro.IdParametroServico ASC ";
			             $res2	=	@mysql_query($sql2,$con);
						 while($lin2	=	@mysql_fetch_array($res2)){
						 	if($i==0){
								$Impress.="<table cellspacing='0' cellpading='0' style='margin-left:-5px'>";
						 	}
						 	if($i%2==0){
						 		$Impress.="<tr>";
							}
						 	$Impress.="<td style='width:50%'><B>$lin2[DescricaoParametroServico]</B>: $lin2[Valor]</td>";
							if($i%2!=0){
						 		$Impress.="</tr>";
							}
						 	$i++;
						 }   
						 if($i!=0){
						 	$Impress.="</table>";
						 }
						 
						 $cont	=	0;
						 $sql5	=	"select ContratoAutomatico.IdContratoAutomatico IdContrato,Contrato.IdServico from (select	ContratoAutomatico.IdContrato,	ContratoAutomatico.IdContratoAutomatico from ContratoAutomatico where ContratoAutomatico.IdLoja = $local_IdLoja and ContratoAutomatico.IdContrato = $lin[IdContrato]) ContratoAutomatico, Contrato where Contrato.IdLoja = $local_IdLoja and Contrato.IdContrato = ContratoAutomatico.IdContratoAutomatico";
						 $res5	=	mysql_query($sql5,$con);
						 while($lin5 = mysql_fetch_array($res5)){
						 	
							$cont++;
							 
							$sql6	=	"select DescricaoServico from Servico where IdLoja = $local_IdLoja and IdServico = $lin5[IdServico]";
							$res6	=	mysql_query($sql6,$con);
							$lin6 	= 	mysql_fetch_array($res6);
							 
							$Impress.="<p><B>Servico Automático ($cont):</B> [$lin5[IdServico]] $lin6[DescricaoServico]</p>";
							 
							$i		=	0;
							$sql2	=	"select
									    ServicoParametro.IdParametroServico,
									    ServicoParametro.DescricaoParametroServico,
									    ServicoParametro.ValorDefault,
									    ContratoParametro.Valor,
									    ServicoParametro.Obrigatorio,
									    ServicoParametro.Obs,
									    ServicoParametro.RotinaCalculo,
									    ServicoParametro.Calculavel 
									from
									    ServicoParametro left join (select * from ContratoParametro where ContratoParametro.IdContrato=$lin5[IdContrato] and ContratoParametro.IdServico=$lin5[IdServico]) ContratoParametro on (ServicoParametro.IdParametroServico = ContratoParametro.IdParametroServico)
									where
										ServicoParametro.IdLoja = $local_IdLoja and
										ServicoParametro.IdStatus = 1 and
										ServicoParametro.IdServico= $lin5[IdServico] and
										ServicoParametro.VisivelOS = 1 
				                    order by 
										ServicoParametro.IdParametroServico ASC ";
				             $res2	=	@mysql_query($sql2,$con);
							 while($lin2	=	@mysql_fetch_array($res2)){
							 	if($i==0){
									$Impress.="<table cellspacing='0' cellpading='0' style='margin-left:-5px'>";
							 	}
							 	if($i%2==0){
							 		$Impress.="<tr>";
								}
								$Impress.="<td style='width:50%'><B>$lin2[DescricaoParametroServico]</B>: $lin2[Valor]</td>";
								if($i%2!=0){
							 		$Impress.="</tr>";
								}
							 	$i++;
							 }   
							 if($i!=0){
							 	$Impress.="</table>";
							 }
						 }
					}
					if($lin[IdServico]!=""){
						$Impress.="
						<table cellspacing='0' cellpading='0' style='margin-left:-5px'>
							<tr>
								<td style='width:50%'><B>Serviço:</B> [$lin[IdServico]] $lin[DescricaoServico]</td>
							</tr>
							<tr>
								<td><B>Valor (".getParametroSistema(5,1)."):</B> $lin[Valor] &nbsp; <B>Outros Valores (".getParametroSistema(5,1)."):</B> $lin[ValorOutros]</td>
								<td><B>Valor Total (".getParametroSistema(5,1)."):</B> $lin[ValorTotal]</td>
							</tr>
						</table>";
					
						$i		=	0;
						$sql2	=	"select
								    ServicoParametro.IdParametroServico,
								    ServicoParametro.DescricaoParametroServico,
								    ServicoParametro.ValorDefault,
								    OrdemServicoParametro.Valor,
								    ServicoParametro.Obrigatorio,
								    ServicoParametro.Obs,
								    ServicoParametro.RotinaCalculo,
								    ServicoParametro.Calculavel 
								from
								    ServicoParametro left join (select * from OrdemServicoParametro where OrdemServicoParametro.IdOrdemServico=$local_IdOrdemServico and OrdemServicoParametro.IdServico=$lin[IdServico]) OrdemServicoParametro on (ServicoParametro.IdParametroServico = OrdemServicoParametro.IdParametroServico)
								where
									ServicoParametro.IdLoja = $local_IdLoja and
									ServicoParametro.IdStatus = 1 and
									ServicoParametro.VisivelOS = 1 and
									ServicoParametro.IdServico= $lin[IdServico]
			                    order by 
									ServicoParametro.IdParametroServico ASC ";
			            $res2	=	mysql_query($sql2,$con);
						while($lin2	=	mysql_fetch_array($res2)){
						 	if($i==0){
								$Impress.="<table cellspacing='0' cellpading='0' style='margin-left:-5px'>";
						 	}
						 	if($i%2==0){
						 		$Impress.="<tr>";
							}
						 	$Impress.="<td style='width:50%'><B>$lin2[DescricaoParametroServico]</B>: $lin2[Valor]</td>";
							if($i%2!=0){
						 		$Impress.="</tr>";
							}
						 	$i++;
						}   
						if($i!=0){	
						 	$Impress.="</table>";
						}
						$Impress.="<p><B>Descrição do Serviço:</B> $lin[DetalheServico]</p>";
					}
					$Impress.="
								<p><B>Descrição da Ordem de Serviço:</B> $lin[DescricaoOS]</p>";
								
					if($lin[DescricaoOutros] != '' && $lin[DescricaoOutros] != 'NULL'){
						$Impress.="<p style='text-align:justify;'><B>Justificativa (Outros Valores):</B> $lin[DescricaoOutros]</p>";
					}
					$Impress.="
							</td>
						</tr>
					</table>
					<table style='width:100%; border-top:1px #000 solid;' cellspacing='0' cellpading='0'>
						<tr>
							<td class='lateral'>A<BR>T<BR>E<BR>N<BR>D<BR>I<BR>M<BR>E<BR>N<BR>T<BR>O</td>
							<td valign='top' style='margin:0; padding:0'>
								<p style='margin:3px 0 0 5px'><B>Detalhamento do Atendimento:</B></p>
								<p style='text-align:center;'>
									<table style='width:99%; margin:auto;'>
										<tr>
											<td style='border-bottom:1px #000 solid'>&nbsp;</td>
										</tr>
										<tr>
											<td style='border-bottom:1px #000 solid'>&nbsp;</td>
										</tr>
										<tr>
											<td style='border-bottom:1px #000 solid'>&nbsp;</td>
										</tr>
										<tr>
											<td style='border-bottom:1px #000 solid'>&nbsp;</td>
										</tr>
									</table>
								</p>
								<table style='width:100%; margin: 10px 0 0 0; border-top:1px #000 solid'  cellspacing='0'  cellpading='0'>
									<tr>
										<td style='width:50%; margin:0; border-right:1px #000 solid;'>
											<B style='margin-top:5px'>Data:</B>&nbsp;______ /______ /______&nbsp;&nbsp;&nbsp;_____:_____&nbsp; às &nbsp;_____:_____
											<BR><BR>
											<B style='margin-top:10px'>Status:</B><input type='checkbox'>Pendente &nbsp;&nbsp;&nbsp;<input type='checkbox'>Concluído &nbsp;&nbsp;&nbsp;<input type='checkbox'>Ausente
										</td>
										<td>";
											if($lin[IdTipoOrdemServico] == 1){
												$Impress.="
												<table style='margin-top:30px'>
													<tr>
														<td style='width:45%; border-bottom:1px #000 solid'>&nbsp;</td>
														<td style='width:10%'>&nbsp;</td>
														<td style='width:45%; border-bottom:1px #000 solid'>&nbsp;</td>
													</tr>
													<tr>
														<td style='text-align:center'>Técnico</td>
														<td>&nbsp;</td>
														<td style='text-align:center'>Cliente</td>
													</tr>
												</table>";
											}else{
												$Impress.="
												<table style='margin-top:30px'>
													<tr>
														<td style='width:5%'>&nbsp;<td/>
														<td style='border-bottom:1px #000 solid'>&nbsp;</td>
														<td style='width:5%'>&nbsp;<td/>
													</tr>
													<tr>
														<td style='width:5%'>&nbsp;<td/>
														<td style='text-align:center'>Técnico</td>
														<td style='width:5%'>&nbsp;<td/>
													</tr>
												</table>";
											}
										$Impress.="
										</td>
									</tr>
								</table>	
							</td>
						</tr>
					</table>
				</div>
			</BODY>
		</HTML>
	";
	
	$local_QtdPagina = getCodigoInterno(3,82);
	
	if($local_QtdPagina	== "") {
		$local_QtdPagina = 1;
	}
	
	echo $Impress;
	
	for($i = 1; $i < $local_QtdPagina; $i++) {
		echo "<br />";
		
		if(($i % 2) != 0) {
			echo "<div style='border-top:1px dashed #000;'><br />$Impress</div>";
		} else {
			echo "<div style='page-break-before:always;'>$Impress</div>";
		}
	}
	
	echo"	
		<HTML>
			<HEAD>
				<style type='text/css'>
					body{	margin:0; padding:5px;	font: normal 10px Verdana, Arial, Times;	}
					p{ 	margin:0 0 1px 0; }
					table{ width:99%;	}	
					table .lateral{	width: 15px; padding: 0; border-right:1px #000 solid; text-align:center; }
					table tr td{	font: normal 10px Verdana, Arial, Times; margin:0; padding-left:5px;	}
					#conteudo { border: 1px #000 solid }
					h1{	font-size: 20px; font-weight:bold}
					.boatao{ padding: 0 2px 2px 2px; cursor:pointer; border: 1px #A4A4A4 solid;	height: 20px; background-color: #FFF; }
				</style>
				<link rel = 'stylesheet' type = 'text/css' href = '../../../../css/impress.css' media='print' />
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
	
?>
