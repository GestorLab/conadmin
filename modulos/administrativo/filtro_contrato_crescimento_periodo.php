<?php
	$localMigrado								= $_SESSION["filtro_contrato_migrado"];
	$filtro_somar_contrato_cancelado			= $_SESSION["filtro_somar_contrato_cancelado"];
	$filtro_somar_contrato_cancelado_migrado	= $_SESSION["filtro_somar_contrato_cancelado_migrado"];
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_contrato_crescimento_periodo.php'>
			<input type='hidden' name='IdServicoTemp'	value='<?=$localIdServico?>' />
			<input type='hidden' name='Local'			value='ContratoCrescimentoPeriodo' />
			<table>
				<tr>
					<td>Campo para Contagem</td>
					<td>Data Inicio</td>
					<td>Data Fim</td>		
					<td>Nome Serviço</td>		
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='Filtro' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar();"/>
							<option value='DataCriacao' <?=compara($localFiltro,'DataCriacao',"selected='selected'","")?>><?=dicionario(133)?></option>
							<option value='DataInicio' <?=compara($localFiltro,'DataInicio',"selected='selected'","")?>><?=dicionario(376)?></option>
							<option value='DataPrimeiraCobranca' <?=compara($localFiltro,'DataPrimeiraCobranca',"selected='selected'","")?>><?=dicionario(378)?></option>
							<option value='DataCadastroPessoa' <?=compara($localFiltro,'DataCadastroPessoa',"selected='selected'","")?>><?=dicionario(382)?></option>
						</select>
					</td>
					<td><input type='text' name='filtro_data_inicio' value='<?=$localDataInicio?>' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_data_termino' value='<?=$localDataTermino?>' style='width:100px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onkeypress="mascara(this,event,'date')" onKeyDown="listar(event);" /></td>		
					<td><input type='text' value='<?=$localDescricaoServico?>' name='DescricaoServico' style='width:280px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>			
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick="atualizar_campo(); consulta_rapida('bottom');" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='6'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td style='width:230px'><?=dicionario(30)?></td>
									<td>
										<input type='text' name='IdServico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='DescricaoIdServico' value='' style='width:289px' readOnly="readOnly" />
									</td>
								</tr>	
								<tr>
									<td><?=dicionario(896)?></td>
									<td>
										<select name='filtro_contrato_migrado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<?
												$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=256 order by ValorParametroSistema";
												$res = @mysql_query($sql,$con);
												while($lin = @mysql_fetch_array($res)){
													
													$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
													
													echo "<option value='$lin[IdParametroSistema]' ".compara($localMigrado,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=Dicionario(1059)?></td>
									<td>
										<select name='filtro_somar_contrato_cancelado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value ='1'<?=compara($filtro_somar_contrato_cancelado,'1',"selected='selected'","")?>><?=dicionario(534)?></option>
											<option value ='2'<?=compara($filtro_somar_contrato_cancelado,'2',"selected='selected'","")?>><?=dicionario(1061)?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=Dicionario(1060)?></td>
									<td>
										<select name='filtro_somar_contrato_cancelado_migrado' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'  onChange="atualizaSessao(this.name,this.value)">
											<option value ='1'<?=compara($filtro_somar_contrato_cancelado_migrado,'1',"selected='selected'","")?>><?=dicionario(534)?></option>
											<option value ='2'<?=compara($filtro_somar_contrato_cancelado_migrado,'2',"selected='selected'","")?>><?=dicionario(1061)?></option>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, '<?=dicionario(291)?>');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top')" alt='<?=dicionario(168)?>' />
							</div>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR /><BR /><img src='graficos/contrato_crescimento_periodo.php?filtro=<?=$localFiltro?>&DataInicio=<?=$localDataInicio?>&DataTermino=<?=$localDataTermino?>&filtro_descricao_servico=<?=$localDescricaoServico?>&filtro_id_servico=<?=$localIdServico?>&filtro_somar_contrato_cancelado=<?=$filtro_somar_contrato_cancelado?>&filtro_somar_contrato_cancelado_migrado=<?=$filtro_somar_contrato_cancelado_migrado?>'/>
	</div>
	<script>
		function atualizar_campo(){
			if(document.filtro.IdServicoTemp.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
		}
		enterAsTab(document.forms.filtro);
	</script>
