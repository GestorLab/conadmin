<?php
	if($localIdPais == ""){
		$localIdPais = getCodigoInterno(3, 1);
	}
	if($localLimit == ""){
		$localLimit = getCodigoInterno(7, 5);
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_contrato_cliente_map.php'>
			<table>
				<tr>
					<td><?=dicionario(30)?></td>
					<td><?=dicionario(157)?></td>
					<td><?=dicionario(186)?></td>
					<td><?=dicionario(140)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td>
						<input type='text' name='filtro_id_servico' value='<?=$localIdServico?>' style='width:70px' maxlength='11' onChange='filtro_buscar_servico(this.value);' onkeypress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"/><input type='text' class='agrupador' name='filtro_descricao_id_servico' value='' style='width:200px' readOnly="readOnly" />
					</td>
					<td>
						<select name='filtro_estado' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select SiglaEstado, IdEstado from Estado where IdPais = $localIdPais order by SiglaEstado ASC";
								$res = mysql_query($sql,$con);
								
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdEstado]' ".compara($localIdEstado,$lin[IdEstado],"selected='selected'","").">$lin[SiglaEstado]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_cidade' value='<?=$localNomeCidade?>' style='width:120px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_status' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" style='width:130px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$i	=	0;
				
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=69 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])."#".$lin[IdParametroSistema];
									$i++;
								}
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema=113 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									$vetor[$i]	=	substituir_string($lin[ValorParametroSistema])." (Todos)#G_".$lin[IdParametroSistema];
									$i++;
								}
								
								sort($vetor);
								
								foreach ($vetor as $key => $val) {
									$vet	=	explode("#",$val);
									$id		=	trim($vet[1]);	
									$value	=	trim($vet[0]);	
									
									echo "<option value='$id' ".compara($localIdStatus,$id,"selected='selected'","").">$value</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_contrato_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<br />
	<div class='graficos' id="map_canvas" style='text-align:center; top:14px;'></div>
	<script type="text/javascript">
		filtro_buscar_servico(document.filtro.filtro_id_servico.value);
		
		enterAsTab(document.forms.filtro);
	</script>