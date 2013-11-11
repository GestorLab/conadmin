	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_contrato_status.php'>
			<input type='hidden' name='Local'	value='ContratoStatus' />
			<input type='hidden' name='IdPais'	value='<?=$localIdPais?>' />
			<input type='hidden' name='IdEstado' value='<?=$localIdEstado?>' />
			<input type='hidden' name='IdCidade' value='<?=$localIdCidade?>' />
			<table>
				<tr>
					<td><?=dicionario(30)?></td>
					<td><?=dicionario(367)?></td>
					<td><?=dicionario(445)?></td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value,document.filtro.Local.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:200px' readOnly="readOnly" />
					</td>
					<td>
						<select name='filtro_id_servico_grupo' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:200px' onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdServicoGrupo, DescricaoServicoGrupo from ServicoGrupo order by DescricaoServicoGrupo";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									$lin[DescricaoServicoGrupo] = url_string_xsl($lin[DescricaoServicoGrupo],'convert');
									echo "<option value='$lin[IdServicoGrupo]' ".compara($localIdServicoGrupo,$lin[IdServicoGrupo],"selected='selected'","").">$lin[DescricaoServicoGrupo]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<input type='text' value='<?=$localData?>' name='filtro_data' style='width: 120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeypress="mascara(this,event,'date')" maxlength='10' value='<?=$localDataInicio?>'/>
					</td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td><img onmousemove="quadro_alt(event, this, 'Menu Avançado')" src='../../img/estrutura_sistema/seta_baixo.gif' style='cursor:pointer' onClick=" consulta_rapida('bottom');/*Atualizar()*/" alt='Parâmetros de Consulta Rápida' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
				<tr>
					<td colspan='5'>
						<div id='filtroRapido' style='width:100%; display:none; background-color: #0065D5'>
							<table style='width:100%;'>
								<tr>
									<td><?=dicionario(256)?></td>
									<td>
										<select name='filtro_pais' style='width:240px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onchange="busca_filtro_estado(this.value,'')">
											<option value=''><?=dicionario(153)?></option>
											<?
												$sql = "select NomePais, IdPais from Pais where IdPais != '' order by IdPais ASC";
												$res = mysql_query($sql,$con);
												while($lin = mysql_fetch_array($res)){
													echo "<option value='$lin[IdPais]' ".compara($localIdEstado,$lin[IdPais],"selected='selected'","").">$lin[NomePais]</option>";
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(157)?></td>
									<td>
										<select name='filtro_estado' style='width:240px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)' onchange="busca_filtro_cidade(this.value,'')">
											<option value=''></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><?=dicionario(186)?></td>
									<td>
										<select name='filtro_cidade' style='width:240px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
											<option value=''></option>
										</select>
									</td>
								</tr>
							</table>
							<div style='width:100%; text-align:right; margin:0;'>
								<img onmousemove="quadro_alt(event, this, 'Os filtros do \'Menu Avançado\' ao serem alterados, o valor prevalece selecionado durante a sessão');" src='../../img/estrutura_sistema/seta_cima.gif' style='cursor:pointer' onClick="consulta_rapida('top');" alt='Parâmetros de Consulta Rápida [Fechar]' />
							</div>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR /><BR /><img src='./graficos/contrato_status.php?IdServico=<?=$localIdServico?>&IdServicoGrupo=<?=$localIdServicoGrupo?>&Data=<?=$localData?>&IdPais=<?=$localIdPais?>&IdEstado=<?=$localIdEstado?>&IdCidade=<?=$localIdCidade?>' />		
	</div>
	<script type="text/javascript">
	    function Atualizar(){
			if(document.filtro.IdPais.value != ""){
				document.filtro.filtro_pais.value = document.filtro.IdPais.value;
				busca_filtro_estado(document.filtro.filtro_pais.value,document.filtro.IdEstado.value);
				busca_filtro_cidade(document.filtro.IdEstado.value,document.filtro.IdCidade.value);
			}
		}
		Atualizar();
		if(document.filtro.filtro_id_servico.value !=""){
			filtro_buscar_servico(document.filtro.filtro_id_servico.value,document.filtro.Local.value);
		}
		enterAsTab(document.forms.filtro);
	</script>