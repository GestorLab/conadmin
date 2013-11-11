	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_recebimento_anual.php'>
			<table>
				<tr>
					<td>Ano</td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='Ano' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar();"/>
							<?
								$sql	=	"select Ano from (select substring(DataLancamento,1,4) Ano from ContaReceber  where  IdStatus != 0) Ano group by Ano order by Ano";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[Ano]' ".compara($localAno,$lin[Ano],"selected='selected'","").">$lin[Ano]</option>";
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
		<BR><BR><img src='graficos/recebimento_anual.php?ano=<?=$localAno?>' />
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
