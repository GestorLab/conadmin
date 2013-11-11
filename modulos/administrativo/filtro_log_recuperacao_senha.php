<?
	if($localOrdem == ''){
		$localOrdem = "Nome";
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
		<form name='filtro' method='post' action='listar_log_recuperacao_senha.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<table>
				<tr>
					<td>Login</td>
					<td>Nome Usuário</td>
					<td>Data da Solicitação</td>
					<td>Qtd.</td>
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localLogin?>' name='filtro_login' style='width:200px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' /></td>
					<td><input type='text' value='<?=$localNome?>' name='filtro_nome' style='width:280px' onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown='listar(event)' onkeyup="busca_pessoa_aproximada(this,event);" /></td>
					<td><input type='text' value='<?=$localDataSolicitacao?>' name='filtro_data_solicitacao' style='width:111px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event);" onkeypress="mascara(this,event,'date')" maxlength='10' /></td>
					<td><input type='text' value='<?=$Limit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')" onBlur="Foco(this,'out')" onKeyDown="listar(event)" /></td>
					<td><input type='submit' value='Buscar' class='botao' /></td>
				</tr>	
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(6);
		?>
	</div>
	<script type='text/javascript'>
	<!--
		enterAsTab(document.forms.filtro);
		-->
	</script>