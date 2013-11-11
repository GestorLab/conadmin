<?
	if($localOrdem == ''){
		$localOrdem = "ServidorEndereco";
	}
	
	if($localOrdemDirecao == ''){
		$localOrdemDirecao = getCodigoInterno(7,6);
	}
	
	if($localTipoDado == ''){
		$localTipoDado = "text";
	}
	
	LimitVisualizacao("filtro");
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_backup_conta.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='filtro_carne'			value='<?=$local_IdCarne?>' />
			<table>
				<tr>
					<td>Endereço do Servidor (FTP)</td>
					<td>Usuário</td>
					<td>Qtd.</td>
					<td />
					<td />
					<td />
				</tr>
				<tr>
					<td><input type='text' value='<?=$localServidorEndereco?>' name='filtro_servidor_endereco' style='width:260px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>
					<td><input type='text' value='<?=$localServidorUsuario?>' name='filtro_servidor_usuario' style='width:200px' onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown='listar(event)'/></td>		
					<td><input type='text' value='<?=$localLimit?>' name='filtro_limit' style='width:34px' onKeyPress="mascara(this,event,'int')" onFocus="Foco(this,'in')"  onBlur="Foco(this,'out')" onKeyDown="listar(event)"/></td>
					<td><input type='submit' value='Buscar' class='botao'/></td>										
				</tr>		
			</table>
		</form>
	</div>
	<div id='menu_ar'>
		<?
			echo menu_acesso_rapido(54);
		?>
	</div>
	<script>
		enterAsTab(document.forms.filtro);
	</script>