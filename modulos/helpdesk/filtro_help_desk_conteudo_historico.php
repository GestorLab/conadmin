<?
	if($localOrdem == ''){					$localOrdem = "IdTicket";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getParametroSistema(146,1);	}

	LimitVisualizacaoHelpDesk("filtro");

	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
	$localHelpDeskConcluido		=	$_SESSION["filtro_help_desk_concluido"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_help_desk_conteudo_historico.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_sub_tipo_temp'	value='<?=$localSubTipo?>' />
			<table>
				<tr>
					<td>Assunto</td>
					<td>Histórico</td>
					<td>Tipo</td>
					<td>SubTipo</td>
					<!--td>Data Inicio</td>
					<td>Data Fim</td-->
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localAssunto?>' name='filtro_assunto' style='width:170px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localHistorico?>' name='filtro_historico' style='width:170px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td>
						<select name='filtro_tipo' style='width:101px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="alualizar_filtro_subtipo_help_desk(this.value)" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select
											IdTipoHelpDesk,
											DescricaoTipoHelpDesk
										from 
											HelpDeskTipo 
										where
											IdStatus=1
										order by
											DescricaoTipoHelpDesk;";
								$res = @mysql_query($sql,$conCNT);
								while($lin = @mysql_fetch_array($res)){
									echo"<option value='$lin[IdTipoHelpDesk]' ".compara($localTipo,$lin[IdTipoHelpDesk],"selected='selected'","").">$lin[DescricaoTipoHelpDesk]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_sub_tipo' style='width:101px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
						</select>
					</td>
					<!--td><input type='text' value='<?=$localDataInicio?>' name='filtro_data_inicio' style='width:70px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localDataFim?>' name='filtro_data_fim' style='width:70px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown='listar(event)'/></td-->
					<td>
						<select name='filtro_status' style='width:92px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema='128' order by IdParametroSistema;";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
									
									echo "<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>
					<td><img src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_help_desk_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
				<tr>
					<td colspan='8'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:181px'>Listar Ticket Concluído</td>
									<td>
										<select name='filtro_help_desk_concluido' style='width:81px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onChange="atualiza_sessao(this.name,this.value)" onKeyDown='listar(event)'>
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 187 order by ValorParametroSistema;";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localHelpDeskConcluido,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido_help_desk(1);
		?>
	</div>
	<script>
		if(document.filtro.filtro_tipo.value!=""){
			alualizar_filtro_subtipo_help_desk(document.filtro.filtro_tipo.value,document.filtro.filtro_sub_tipo_temp.value);
		}
		enterAsTab(document.forms.filtro);
	</script>
