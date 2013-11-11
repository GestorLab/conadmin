<?
	if($localOrdem == ''){					$localOrdem = "Nome";		}
	if($localOrdemDirecao == ''){			$localOrdemDirecao = getCodigoInterno(7,6);	}
	
	LimitVisualizacao("filtro");
	
	$localQTDCaracterColunaPessoa	=	$_SESSION["filtro_QTDCaracterColunaPessoa"];
	
	if($localTipoDado == ''){
		$localTipoDado = 'text';
	}
?>
	<div id='filtroBuscar'>
		<form name='filtro' method='post' action='listar_atualizacao_versao.php'>
			<input type='hidden' name='corRegRand'				value='<?=getParametroSistema(15,1)?>' />
			<input type='hidden' name='filtro' 					value='s' />
			<input type='hidden' name='filtro_ordem'			value='<?=$localOrdem?>' />
			<input type='hidden' name='filtro_ordem_direcao'	value='<?=$localOrdemDirecao?>' />
			<input type='hidden' name='filtro_tipoDado'			value='<?=$localTipoDado?>' />
			<input type='hidden' name='IdServico'				value='<?=$localIdServico?>' />
			<input type='hidden' name='IdContrato'				value='' />
			<input type='hidden' name='Local'					value='Contrato' />
			
		</form>
	</div>
	<script>
		function atualizar_campo(){
			if(document.filtro.IdServico.value != ''){
				filtro_buscar_servico(document.filtro.IdServico.value,document.filtro.Local.value);
			}
		}
		enterAsTab(document.forms.filtro);
	</script>
