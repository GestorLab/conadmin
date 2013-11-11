<?
	if($localOrdem == ''){
		$localOrdem = "DescricaoContaEmail";
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
		<form name='filtro' method='post' action='listar_conta_email.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Descrição</td>
					<td>Nome da Conta</td>
					<td>E-mail do Remetente</td>
					<td>Servidor de Saída (SMTP)</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' name='filtro_descricao_conta_email' value='<?=$localDescricaoContaEmail?>' style='width:220px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_usuario' value='<?=$localUsuario?>' style='width:150px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_email_remetente' value='<?=$localEmailRemetente?>' style='width:150px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
					<td><input type='text' name='filtro_servidor_smtp' value='<?=$localServidorSMTP?>' style='width:170px' maxlength='100' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event);" /></td>
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