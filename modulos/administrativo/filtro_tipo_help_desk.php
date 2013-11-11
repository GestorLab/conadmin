<?
	if($localOrdem == ''){					$localOrdem = "DescricaoTipo";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getParametroSistema(146,1);	}

	LimitVisualizacao("filtro");

	if($localTipoDado == ''){				$localTipoDado = 'text';	}
	
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_tipo_help_desk.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Descrição Tipo</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localDescricaoTipo?>' name='filtro_descricao_tipo' style='width:444px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_id_status' style='width:111px'  onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select
											IdParametroSistema,
											ValorParametroSistema
										from
											ParametroSistema
										where
											IdGrupoParametroSistema = 157;";
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
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(42);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
