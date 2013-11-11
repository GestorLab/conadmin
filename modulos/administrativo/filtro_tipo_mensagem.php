<?
	if($localOrdem == ''){
		$localOrdem = "Titulo";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_tipo_mensagem.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Titulo</td>
					<td>Assunto</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_titulo' value='<?=$localTitulo?>' style='width:300px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_assunto' value='<?=$localAssunto?>' style='width:300px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name='filtro_id_status' onFocus="Foco(this,'in')" onBlur="Foco(this,'out');" style='width:100px' onKeyDown='listar(event)'>
							<option value=''>Todos</option>
							<?
								$sql = "select IdParametroSistema, ValorParametroSistema from ParametroSistema where IdGrupoParametroSistema = 227 order by ValorParametroSistema";
								$res = @mysql_query($sql,$con);
								while($lin = @mysql_fetch_array($res)){
									echo"<option value='$lin[IdParametroSistema]' ".compara($localIdStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(52);
		?>
	</div>
	<script type='text/javascript'>
	<!--
		enterAsTab(document.forms.filtro);
		-->
	</script>