<?
	if($localOrdem == ''){					$localOrdem = "DataInicio";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
	if($localFiltro == '' && $localMesReferencia == ""){
		$localMesReferencia =	date('m/Y');
	}
	
	$localOcultaIP			=	$_SESSION["filtro_oculta_ip"];
	$localOcultaMAC			=	$_SESSION["filtro_oculta_mac"];
	$localOcultaNAS			=	$_SESSION["filtro_oculta_nas"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_radius_usuario_periodo.php' onSubmit='return validar()'>
			<input type='hidden' name='corRegRand'					value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 						value='s' />
			<input type='hidden' name='filtro_ordem'				value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'		value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'				value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Servidor</td>
					<td>Login</td>
					<td>IP</td>
					<td>MAC</td>
					<td>Data e Hora Início</td>
					<td>Data e Hora Fim</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_servidor' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:130px'  onKeyDown="listar_radius(event);">
							<option value=''></option>
							<?
								$sql = "select IdCodigoInterno,DescricaoCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno = 10000 and IdCodigoInterno < 20";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoCodigoInterno] = url_string_xsl($lin[DescricaoCodigoInterno],'convert');
									
									echo "<option value='$lin[IdCodigoInterno]' ".compara($localIdServidor,$lin[IdCodigoInterno],"selected='selected'","").">$lin[DescricaoCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_login' value='<?=$localLogin?>' style='width:90px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar_radius(event);"/></td>
					<td><input type='text' name='filtro_ip' value='<?=$localIP?>' style='width:90px' maxlength='20' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar_radius(event);"/></td>
					<td><input type='text' name='filtro_mac' value='<?=$localMAC?>' style='width:90px' maxlength='17' onkeypress="mascara(this,event,'mac')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar_radius(event);"/></td>
					<td><input type='text' name='filtro_data_hora_inicio' value='<?=$localDataHoraInicio?>' style='width:120px' maxlength='19' onkeypress="mascara(this,event,'dateHora')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar_radius(event);" /></td>
					<td><input type='text' name='filtro_data_hora_fim' value='<?=$localDataHoraFim?>' style='width:120px' maxlength='19' onkeypress="mascara(this,event,'dateHora')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar_radius(event);" /></td>
					<td><input type='text' name='filtro_limit' value='<?=$Limit?>' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado');" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="consulta_rapida('bottom')" alt='Parâmetros de Consulta Rápida' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_radius_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
				<tr>
					<td colspan='9'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:150px'>Ocultar coluna IP</td>
									<td>
										<select name='filtro_oculta_ip' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=125 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localOcultaIP,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Ocultar coluna MAC</td>
									<td>
										<select name='filtro_oculta_mac' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=126 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localOcultaMAC,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Ocultar coluna NAS</td>
									<td>
										<select name='filtro_oculta_nas' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=189 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localOcultaNAS,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>NAS</td>
									<td>
										<input type='text' name='filtro_nas' value='<?=$localNAS?>' style='width:245px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onChange="atualizaSessao(this.name,this.value)" onKeyDown="listar(event);" />
									</td>
								</tr>
								<tr>
									<td>Data e Hora Específica</td>
									<td>
										<input type='text' name='filtro_data_hora_especifica' value='<?=$localDataHoraEspecifica?>' style='width:120px' maxlength='19' onkeypress="mascara(this,event,'dateHora')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" />
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
					<td />
					<td />
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'></div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
	
	
	
