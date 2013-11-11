<?
	if($localOrdem == ''){							$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){					$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){						$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_pessoa_data_nascimento.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='acesso'					value='<?=$acesso?>' />
			<table>
				<tr>
					<td><?=dicionario(148)?></td>
					<td><?=dicionario(188)?></td>
					<td><?=dicionario(189)?></td>
					<td><?=dicionario(152)?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value="<?=$localNome?>" name='filtro_nome' style='width:230px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup='busca_pessoa_aproximada(this,event);' /></td>
					<td>
						<select name='filtro_mes' style='width:120px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								for($i=1;$i<=12;$i++){
									$Mes = mes($i);	
									$Mes = url_string_xsl($Mes,'convert');
									echo "<option value='$i' ".compara($localMes,$i,"selected='selected'","").">$Mes</option>";
								}
							?>
							</select>
					</td>
					<td>
						<select name='filtro_cliente_ativo' style='width:100px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event)">
							<option value=''><?=dicionario(153)?></option>
							<?
								echo "<option value='1' ".compara($localClienteAtivo,'1',"selected='selected'","").">Sim</option>";
								echo "<option value='2' ".compara($localClienteAtivo,'2',"selected='selected'","").">Nao</option>";
							?>
							</select>
					</td>
					<td><input type='text' 	name='filtro_limit' value='<?=$localLimit?>' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_pessoa_opcoes.php'><?=dicionario(120)?><BR /><?=dicionario(162)?></a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(1);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
