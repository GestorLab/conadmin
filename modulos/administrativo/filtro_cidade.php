<?
	if($localOrdem == ''){						$localOrdem = "NomePais";	}
	if($localOrdemDirecao == ''){				$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	if($localTipoDado == ''){					$localTipoDado = 'text';	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_cidade.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>	
				<tr>
					<td><?=dicionario(257)?></td>
					<td><?=dicionario(259)?></td>
					<td><?=dicionario(260)?></td>
					<td><?=dicionario(152)?></td>
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_idpais' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:170px' onChange="verifica_estado(this.value)" onKeyDown='listar(event)'>
							<option value=''><?=dicionario(153)?></option>
							<?
								$sql = "select IdPais, NomePais from Pais order by NomePais";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[NomePais] = url_string_xsl($lin[NomePais],'convert');
									
									echo "<option value='$lin[IdPais]' ".compara($localIdPais,$lin[IdPais],"selected='selected'","").">$lin[NomePais]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='filtro_estado' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:230px'  onKeyDown='listar(event)'>
							<option value='0'><?=dicionario(153)?></option>
							<?
								$sql	=	"select IdEstado, NomeEstado from Pais,Estado where Estado.IdPais = Pais.IdPais and Estado.IdPais=$localIdPais order by NomeEstado";
								$res	=	mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[NomeEstado] = url_string_xsl($lin[NomeEstado],'convert');
									
									echo "<option value='$lin[IdEstado]' ".compara($localEstado,$lin[IdEstado],"selected='selected'","").">$lin[NomeEstado]</option>\n";
								}
							?>
						</select>
					</td>
					<td><input type='text' value='<?=$localCidade?>' name='filtro_cidade' size='24' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='<?=dicionario(166)?>' class='botao'/></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(9);
		?>
	</div>
	<div id='carregando'>carregando</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
