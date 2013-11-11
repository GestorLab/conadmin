<?
	if($localOrdem == ''){					$localOrdem = "DescricaoFormaAvisoCobranca";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){				$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_forma_aviso_cobranca.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td><?=dicionario(125)?></td>
					<td><?=dicionario(75)?></td>				
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_descricao_forma_aviso_cobranca' value='<?=$localDescricaoFormaAvisoCobranca?>' style='width:220px' maxlength='10' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_grupo_usuario_monitor' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:200px' onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdGrupoUsuario, DescricaoGrupoUsuario from GrupoUsuario order by DescricaoGrupoUsuario";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoGrupoUsuario] = url_string_xsl($lin[DescricaoGrupoUsuario],'convert');
									
									echo "<option value='$lin[IdGrupoUsuario]' ".compara($localIdGrupoUsuarioMonitor,$lin[IdGrupoUsuario],"selected='selected'","").">$lin[DescricaoGrupoUsuario]</option>";
								}
							?>
						</select>
					</td>					
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>'  class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(3);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
