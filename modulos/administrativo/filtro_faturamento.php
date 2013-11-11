	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_faturamento.php'>
			<table>
				<tr>
					<td>Ano</td>
					<td><?= Dicionario(478) ?></td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='Ano' style='width:80px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out');" onKeyDown="listar();"/>
							<?
								$sql	=	"select Ano from (select substring(DataLancamento,1,4) Ano from ContaReceber  where IdLoja = $localIdLoja and IdStatus != 0) Ano group by Ano order by Ano";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									echo "<option value='$lin[Ano]' ".compara($localAno,$lin[Ano],"selected='selected'","").">$lin[Ano]</option>";
								}
							?>
						</select>
					</td>
					<td>
						<select  name='DataFaturamento' style='width:200px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)'>
							<option value =''></option>
							<option value ='DataLancamento' <?php echo compara($DataFaturamento,"DataLancamento","selected='selected'",""); ?>><?=dicionario(200)?></option>
							<option value ='DataVencimentoOriginal' <?php echo compara($DataFaturamento,"DataVencimentoOriginal","selected='selected'",""); ?>><?=dicionario(1063)?></option>
							<option value ='DataVencimentoAtual' <?php echo compara($DataFaturamento,"DataVencimentoAtual","selected='selected'",""); ?>><?=dicionario(1064)?></option>
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
		<BR /><BR /><img src='graficos/faturamento_anual.php?ano=<?=$localAno?>&DataFaturamento=<?=$DataFaturamento?>' />
		<!--<iframe src='graficos/faturamento_anual.php?ano=<?=$localAno?>&DataFaturamento=<?=$DataFaturamento?>' ></iframe>-->
	</div>
	<script>
		enterAsTab(document.forms.filtro);
		<?php
			if($DataFaturamento ==''){
				echo "document.filtro.DataFaturamento.options[2].selected = true";
			}
		?>
	</script>
