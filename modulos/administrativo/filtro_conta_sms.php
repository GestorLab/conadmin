<?
	if($localOrdem == ''){					$localOrdem = "DataCriacao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = "descending";	}
	$localLimite = getCodigoInterno(7,5);
	
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_conta_sms.php'>
			<table>
				<tr>
					<td>Descrição</td>
					<td>Operadora</td>
					<td>Status</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_descricao' value='<?=$localDescricao?>' style='width:220px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td>
						<select name="filtro_id_operadora" style="width: 180px" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
							<option value=''>Todos</option>
							<?
								$sql = "Select
											IdOperadora,
											DescricaoOperadora
										From
											OperadoraSMS";
								$res = mysql_query($sql,$con);
								if(@mysql_num_rows($res) > 0){
									while($lin = @mysql_fetch_array($res)){
										$lin[DescricaoOperadora] = url_string_xsl($lin[DescricaoOperadora],'convert');
										echo "<option value='$lin[IdOperadora]' ".compara($localOperadora,$lin[IdOperadora],"selected='selected'","").">$lin[DescricaoOperadora]</option>";
									}
								}
							?>
						</select>
					</td>
					<td>
						<select name="filtro_status" style="width: 180px" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')">
							<option value=''>Todos</option>
							<?
								$sql = "Select 
										  IdParametroSistema,
										  ValorParametroSistema 
										From
										  ParametroSistema 
										Where
											IdGrupoParametroSistema = 245";
								$res = mysql_query($sql,$con);
								if(@mysql_num_rows($res) > 0){
									while($lin = @mysql_fetch_array($res)){
										$lin[ValorParametroSistema] = url_string_xsl($lin[ValorParametroSistema],'convert');
										echo "<option value='$lin[IdParametroSistema]' ".compara($localStatus,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
									}
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_quantidade' value='<?=$localLimite?>' style='width:34px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
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