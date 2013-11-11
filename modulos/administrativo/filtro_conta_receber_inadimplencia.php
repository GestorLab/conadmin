	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_conta_receber_inadimplencia.php'>
			<table>
				<tr>
					<td>Ano</td>
					<td>Vencimento</td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='Ano' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar();"/>
							<?
								$sql = "select substring(DataVencimento,1,4) Ano from ContaReceberVencimento where IdLoja = $localIdLoja and subString(DataVencimento,1,4) <= subString(curdate(),1,4) group by Ano order by Ano;";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[Ano]' ".compara($localAno,$lin[Ano],"selected='selected'","").">$lin[Ano]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select name='Vencimento' style='width:121px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar();"/>
							<?
								$sql = "SELECT 
											IdParametroSistema, 
											ValorParametroSistema
										FROM 
											ParametroSistema
										WHERE 
											IdGrupoParametroSistema = 185 
										ORDER BY 
											ValorParametroSistema;";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[IdParametroSistema]' ".compara($localVencimento,$lin[IdParametroSistema],"selected='selected'","").">$lin[ValorParametroSistema]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
					<td style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_conta_receber_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<BR /><BR /><img src='graficos/conta_receber_inadimplencia.php?ano=<?=$localAno?>&vencimento=<?=$localVencimento?>' />	
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
