<?
	if($localOrdem == ''){					$localOrdem = "Descricao";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	if($Limit == '' && $localFiltro == ''){	$Limit = getCodigoInterno(7,5);	}
	if($localTipoDado == ''){				$localTipoDado = 'number';	}
	
	$localIdServidor	= $_POST['filtro_servidor'];
	$localLogin			= $_POST['filtro_login'];
	$localMAC			= $_POST['filtro_mac'];
	$localMesReferencia	= $_POST['filtro_mes_referencia'];
	
	if($localFiltro == '' && $localMesReferencia == ""){
		$localMesReferencia =	date('m/Y');
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='menu_radius_tempo_conexao.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Servidor</td>
					<td>Login</td>
					<td>MAC</td>
					<td>Mês Referência</td>
					<td />
					<td />
				</tr>
				<tr>
					<td>
						<select name='filtro_servidor' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" style='width:250px'  onKeyDown="listar(event);">
							<option value=''></option>
							<?
								$sql = "select IdCodigoInterno,DescricaoCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno = 10000 and IdCodigoInterno < 20";
								$res = mysql_query($sql,$con);
								while($lin = mysql_fetch_array($res)){
									
									$lin[DescricaoCodigoInterno] = url_string_xsl($lin[DescricaoCodigoInterno],'convert');
									
									echo "<option value='$lin[IdCodigoInterno]' ".compara($localIdServidor,$lin[IdCodigoInterno],"selected='selected'","").">$lin[DescricaoCodigoInterno]</option>";
								}
							?>
						</select>
					</td>
					<td><input type='text' name='filtro_login' value='<?=$localLogin?>' style='width:200px' maxlength='100' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event);"/></td>
					<td><input type='text' name='filtro_mac' value='<?=$localMAC?>' style='width:120px' maxlength='17' onkeypress="mascara(this,event,'mac')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')"  onKeyDown="listar(event);"/></td>
					<td><input type='text' name='filtro_mes_referencia' value='<?=$localMesReferencia?>' style='width:100px' maxlength='7' onkeypress="mascara(this,event,'mes')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>	
					<td><input type='submit' value='Buscar'  class='botao' /></td>
					<td  style='font-size:9px; font-weight:normal'><a style='color:#FFF'  href='listar_radius_opcoes.php'>Outros<BR />Relatórios</a></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>&nbsp;</div>
	<div class='graficos' style='text-align:center'>
		<?
			if($localIdServidor != "" && ($localLogin!="" || $localMAC != "") && $localMesReferencia != ""){
				echo"<style type='text/css'>
						#quadro_legenda {
							width: 181px;
							text-align: left;
							margin: auto;
							padding: 4px 8px 0 8px;
							background-color: #EBEBEB;
							border: 1px solid #000;
						}
						#quadro_legenda .cubo {
							float: left;
							width: 9px;
							height: 9px;
							margin: 1px 11px 0 0;
							border: 1px solid #444;
						}
					</style>
					<BR />
					<img src='graficos/radius_tempo_conexao.php?filtro_servidor=$localIdServidor&filtro_login=$localLogin&filtro_mac=$localMAC&filtro_mes_referencia=$localMesReferencia' />
					<BR /><BR />
					<div id='quadro_legenda'>
						<div style='margin-bottom:4px;'><div class='cubo' style='background-color:#DBB5B5;'></div> Tempo conectado.</div>
						<div style='margin-bottom:4px;'><div class='cubo' style='background-color:#FFF;'></div> Tempo desconectado.</div>
					</div>";
			}
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>
	
