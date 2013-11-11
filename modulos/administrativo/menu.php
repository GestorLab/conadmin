<?
	$localModulo	=	1;
	$localMenu		=	true;
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
	
	$local_Login = $_SESSION['Login'];
	
	function OpcoesExtrasFinal($Opcoes){
		if($Opcoes == ''){
			return "_end";
		}
	}
	function OpcoesExtras($Opcoes){
		if($Opcoes != ''){
			$opc = explode("\n",$Opcoes);
			$qtd = count($opc);
			
			$final = "";
			
			for($i = 0; $i < $qtd; $i++){
				$opcDados = explode("#",$opc[$i]);
				
				if($qtd > 1){
					$opcDados[$i+1] = substr($opcDados[$i+1],0,-1);
				}
				
				if(($i+1) == $qtd){	$final = "_end";	}
				
				echo "<li class='n2$final' onClick=\"parent.conteudo.location='$opcDados[1]'\">$opcDados[0]</li>\n";
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/menu.css' />
		<script type = 'text/javascript' src = '../../js/menu.js'></script>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/event.js'></script>
	</head>
	<body>
		<h1><?=dicionario(24)?></h1>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_cadastro',this)"><?=dicionario(25)?></li>
		</ul>
		<ul id='mn_cadastro'>			
			<li class='n2' onClick="parent.conteudo.location='menu_pessoa.php'"><?=dicionario(26)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_contrato.php'"><?=dicionario(27)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_conta_eventual.php'"><?=dicionario(28)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_ordem_servico.php'"><?=dicionario(29)?></li>			
			<li class='n2' onClick="parent.conteudo.location='menu_servico.php'"><?=dicionario(30)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_grupo.php'"><?=dicionario(31)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_agente_autorizado.php'"><?=dicionario(32)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_terceiro.php'"><?=dicionario(33)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_mala_direta.php'"><?=dicionario(34)?></li>
			<?
				if($_SESSION['IdLicenca']=='2007A000'){
					echo"<li class='n2' onClick=\"parent.conteudo.location='menu_produto.php'\">".dicionario(36)."</li>";
				}
			
				$sql	=	"SHOW TABLE STATUS WHERE Name='Cidade'";
				$res	=	mysql_query($sql,$con);
				$lin	=	mysql_fetch_array($res);
				
				if(getCodigoInterno(12,1) == ''){
					if($lin[Comment] != 'VIEW'){
						echo "
							<li class='n2' onClick=\"parent.conteudo.location='menu_agenda.php'\">".dicionario(37)."</li>
							<li class='n2_end' onClick=\"parent.conteudo.location='menu_cidade.php'\">".dicionario(186)."</li>
						";
					}else{
						echo"<li class='n2_end' onClick=\"parent.conteudo.location='menu_agenda.php'\">".dicionario(37)."</li>";	
					}
					
				}else{
					echo "<li class='n2' onClick=\"parent.conteudo.location='menu_agenda.php'\">".dicionario(37)."</li>";
					
					if($lin[Comment] != 'VIEW'){
						echo"<li class='n2' onClick=\"parent.conteudo.location='menu_cidade.php'\">".dicionario(186)."</li>";
					}
					
					OpcoesExtras(getCodigoInterno(12,1));
				}	
			?>
		</ul>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_movimentacao',this)"><?=dicionario(39)?></li>
		</ul>
		<ul id='mn_movimentacao'>
			<li class='n2' onClick="parent.conteudo.location='menu_local_cobranca.php'"><?=dicionario(40)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_processo_financeiro.php'"><?=dicionario(41)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_arquivo_retorno.php'"><?=dicionario(42)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_arquivo_remessa.php'"><?=dicionario(43)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_centro_custo.php'"><?=dicionario(44)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_tipo_vigencia.php'"><?=dicionario(45)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_plano_conta.php'"><?=dicionario(46)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_lote_repasse.php'"><?=dicionario(47)?></li>
			<?php 
				# VERIFICA SE O CAIXA ESTA ATIVADO
				if(getCodigoInterno(66,1) == 1){
					echo "<li class='n2' onClick=\"parent.conteudo.location='menu_caixa.php'\">".dicionario(171)."</li>";
				}
			?>
			<li class='n2<?=OpcoesExtrasFinal(getCodigoInterno(12,2))?>' onClick="parent.conteudo.location='menu_agrupar_conta_receber.php'"><?=dicionario(48)?></li>
			<? OpcoesExtras(getCodigoInterno(12,2));	?>
		</ul>
		<?
			$iMenu = 0;

			if($_SESSION['TipoLicenca']==1){
				$MenuNF[$iMenu] = "<li class='n2_end' onClick=\"parent.conteudo.location='menu_nota_fiscal_entrada.php'\">".dicionario(50)."</li>";
				$iMenu++;
			}

			$sql = "select
                         IdStatus
                    from
                         NotaFiscalTipo 
					where
						IdLoja = $local_IdLoja and
						IdStatus = 1 and
						(IdNotaFiscalLayout = 1 or IdNotaFiscalLayout = 2)";
			$res = @mysql_query($sql,$con);
            
			if($lin = @mysql_fetch_array($res)){
				$MenuNF[$iMenu] = "<li class='n2' onClick=\"parent.conteudo.location='menu_nf_2_via_eletronica_remessa.php'\">".dicionario(49)."</li>";
				$iMenu++;
			}
            
			if(@mysql_num_rows($res) > 0){
    			$MenuNF[$iMenu] ="<li class='n2_end' onClick=\"parent.conteudo.location='menu_gerar_nota_fical_em_massa.php'\">".dicionario(51)."</li>";
    			$iMenu++;
            }
			
			$MenuNF[$iMenu] ="<li class='n2_end' onClick=\"parent.conteudo.location='menu_dominio_contabil_nota_fiscal.php'\">".dicionario(52)."</li>";
			$iMenu++;
			
			if($iMenu > 0){
				echo "<ul class='mn'>
						<li onClick=\"javascript:open_close('mn_nf',this)\">".dicionario(53)."</li>
					</ul>
					<ul id='mn_nf'>";
			}

			for($i=0; $i<$iMenu; $i++){

				if(($i+1) < $iMenu){
					$MenuNF[$i] = str_replace("_end", "", $MenuNF[$i]);
				}
				echo $MenuNF[$i]."\n";
			}

			if($iMenu > 0){
				echo "</ul>";
			}
		?>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_relatorio',this)"><?=dicionario(54)?></li>
		</ul>
		<ul id='mn_relatorio'>
			<?
				
				//--------------------------------------Pessoa-------------------------
				$opcInicial	=	getCodigoInterno(3,91);
				switch($opcInicial){
					case '1':	$relatorio	=	"menu_pessoa.php";					break;
					case '2':	$relatorio	=	"menu_pessoa_data_nascimento.php";	break;
					case '3':	$relatorio	=	"menu_pessoa_telefone.php";			break;
					case '4':	$relatorio	=	"menu_pessoa_solicitacao.php";		break;
					case '5':	$relatorio	=	"menu_pessoa_data_cadastro.php";	break;
					default:	$relatorio	=	"listar_pessoa_opcoes.php";			
				}
				
				echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(26)."</li>";
				
				//--------------------------------------Contrato-------------------------
				$opcInicial	=	getCodigoInterno(3,93);
				switch($opcInicial){
					case '1':	$relatorio	=	"menu_contrato.php";						break;
					case '2':	$relatorio	=	"menu_contrato_local_cobranca.php";			break;
					case '3':	$relatorio	=	"menu_contrato_tipo.php";					break;
					case '4':	$relatorio	=	"menu_contrato_cidade.php";					break;
					case '5':	$relatorio	=	"menu_contrato_sem_cobranca.php";			break;
					case '6':	$relatorio	=	"menu_contrato_tipo_vigencia.php";			break;
					case '7':	$relatorio	=	"menu_contrato_status.php";					break;
					case '8':	$relatorio	=	"menu_contrato_crescimento_anual.php";		break;
					case '9':	$relatorio	=	"menu_contrato_crescimento_mensal.php";		break;
					case '10':	$relatorio	=	"menu_contrato_desconto.php";				break;
					case '11':	$relatorio	=	"menu_contrato_novo.php";					break;
					case '12':	$relatorio	=	"menu_contrato_novo.php";					break;
					case '13':	$relatorio	=	"menu_contrato_pessoa.php";					break;
					case '14':	$relatorio	=	"menu_contrato_status_data.php";		 	break;
					case '15':	$relatorio	=	"menu_contrato_parametro_assinatura.php";	break;
					case '16':	$relatorio	=	"menu_contrato_sem_cobranca_aberto.php";	break;
					case '17':	$relatorio	=	"menu_contrato_crescimento_periodo.php";	break;
					case '18':	$relatorio	=	"menu_contrato_vigencia_irregular.php";		break;
					case '19':	$relatorio	=	"menu_contrato_data_irregular.php";			break;
					default:	$relatorio	=	"listar_contrato_opcoes.php";
				}
				
				echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(27)."</li>";
				
				//--------------------------------------Conta Receber-------------------------
				$opcInicial	=	getCodigoInterno(3,92);
				switch($opcInicial){
					case '1':	$relatorio	=	"menu_conta_receber.php";				break;
					case '2':	$relatorio	=	"menu_conta_receber_avancado.php";		break;
					case '3':	$relatorio	=	"menu_conta_receber_cidade.php";		break;
					case '4':	$relatorio	=	"menu_conta_receber_movimentacao.php";	break;
					case '5':	$relatorio	=	"menu_conta_receber_vencimento.php";	break;
					case '6':	$relatorio	=	"menu_faturamento.php";					break;
					case '7':	$relatorio	=	"menu_recebimento_anual.php";			break;
					case '8':	$relatorio	=	"menu_recebimento_mensal.php";			break;
					case '9':	$relatorio	=	"menu_local_recebimento.php";			break;
					case '10':	$relatorio	=	"menu_conta_receber_desconto.php";		break;
					case '11':	$relatorio	=	"menu_conta_receber_tipo.php";			break;
					default:	$relatorio	=	"listar_conta_receber_opcoes.php";
				}
				
				echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(56)."</li>";
				
				//--------------------------------------Conta Eventual-------------------------
				$opcInicial	=	getCodigoInterno(3,99);
				switch($opcInicial){
					case '1':	$relatorio	=	"menu_conta_eventual.php";				break;
					case '2':	$relatorio	=	"menu_conta_eventual_avancado.php";		break;
					default:	$relatorio	=	"listar_conta_eventual_opcoes.php";
				}
				
				echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(28)."</li>";
				
				//--------------------------------------Ordem Servico-------------------------
				$opcInicial	=	getCodigoInterno(3,97);
				switch($opcInicial){
					case '1':	$relatorio	=	"menu_ordem_servico.php";				break;
					case '2':	$relatorio	=	"menu_ordem_servico_avancado.php";		break;
					case '3':	$relatorio	=	"menu_ordem_servico_tipo.php";			break;
					default:	$relatorio	=	"listar_ordem_servico_opcoes.php";
				}
				
				echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(29)."</li>";
				
				if($_SESSION['IdLicenca']=='2007A000'){
					//--------------------------------------Produto-------------------------
					$opcInicial	=	getCodigoInterno(3,100);
					switch($opcInicial){
						case '1':	$relatorio	=	"menu_produto.php";					break;
						case '2':	$relatorio	=	"menu_produto_consulta.php";		break;
						default:	$relatorio	=	"listar_produto_opcoes.php";
					}
					
					echo"<li class='n2' onClick=\"parent.conteudo.location='$relatorio'\">".dicionario(36)."</li>";
				}			
			?>
			
			<li class='n2' onClick="parent.conteudo.location='listar_carne.php'"><?=dicionario(55)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_lancamento_financeiro.php'"><?=dicionario(57)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_reenvio_mensagem.php'"><?=dicionario(58)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_log_acesso.php'"><?=dicionario(76)?></li>
			<?
				$sql	=	"select IdCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno = 10000 and IdCodigoInterno = 1 and ValorCodigoInterno != ''";
				$res	=	@mysql_query($sql,$con);
				if($lin = @mysql_fetch_array($res)){
					echo"<li class='n2' onClick=\"parent.conteudo.location='listar_radius_opcoes.php'\">".dicionario(59)."</li>";
				}
			?>
			
			<li class='n2' onClick="parent.conteudo.location='listar_nota_fiscal_opcoes.php'"><?=dicionario(53)?></li>
			<li class='n2' onClick="parent.conteudo.location='listar_cda_opcoes.php'"><?=dicionario(8)?></li>
			<li class='n2' onClick="parent.conteudo.location='listar_protocolo_opcoes.php'"><?=dicionario(35)?></li>
			<li class='n2<?=OpcoesExtrasFinal(getCodigoInterno(12,3))?>' onClick="parent.conteudo.location='menu_backup.php'"><?=dicionario(60)?></li>
			<? OpcoesExtras(getCodigoInterno(12,3));	?>
		</ul>

		<?
			if($_SESSION['IdLicenca']=='2007A000'){
				echo "
					<ul class='mn'>
						<li onClick=\"javascript:open_close('mn_georede',this)\">Geo Rede</li>
					</ul>
					<ul id='mn_georede'>
						<li class='n2".OpcoesExtrasFinal(getCodigoInterno(12,6))."' onClick=\"parent.conteudo.location='../georede/index.php'\">Planta Baixa</li>
						<li class='n2".OpcoesExtrasFinal(getCodigoInterno(12,6))."' onClick=\"parent.conteudo.location='../georede/cadastro_poste.php'\">Poste</li>
						<li class='n2".OpcoesExtrasFinal(getCodigoInterno(12,6))."' onClick=\"parent.conteudo.location='../georede/cadastro_cabo.php'\">Cabo</li>
					</ul>";
			}
		?>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_atendimento',this)"><?=dicionario(581)?></li>
		</ul>
		<ul id='mn_atendimento'>
			<li class='n2<?=OpcoesExtrasFinal(getCodigoInterno(12,6))?>' onClick="parent.conteudo.location='menu_protocolo.php'"><?=dicionario(35)?></li>
		</ul>
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_exportar',this)"><?=dicionario(61)?></li>
		</ul>
		<ul id='mn_exportar'>
			<li class='n2' onClick="parent.conteudo.location='cadastro_etiqueta.php'"><?=dicionario(62)?></li>
			<li class='n2' onClick="parent.conteudo.location='cadastro_relacao_email.php'"><?=dicionario(63)?></li>
			<li class='n2' onClick="parent.conteudo.location='cadastro_sici.php'"><?=dicionario(64)?></li>
			<li class='n2' onClick="parent.conteudo.location='cadastro_nota_fiscal_exportacao.php'">Nota Fiscal</li>
			<li class='n2<?=OpcoesExtrasFinal(getCodigoInterno(12,4))?>' onClick="parent.conteudo.location='cadastro_declaracao_pagamentos.php'"><?=dicionario(66)?></li>
			<? OpcoesExtras(getCodigoInterno(12,4));	?>
		</ul>	
		<ul class='mn'>
			<li onClick="javascript:open_close('mn_sistema',this)"><?=dicionario(67)?></li>
		</ul>
		<ul id='mn_sistema'>
			<li class='n2' onClick="parent.conteudo.location='menu_monitor.php'"><?=dicionario(68)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_datas_especiais.php'"><?=dicionario(69)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_link.php'"><?=dicionario(70)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_aviso.php'"><?=dicionario(71)?></li>	
			<li class='n2' onClick="parent.conteudo.location='menu_parametro_sistema.php'"><?=dicionario(72)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_codigo_interno.php'"><?=dicionario(73)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_grupo_permissao.php'"><?=dicionario(74)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_grupo_usuario.php'"><?=dicionario(75)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_operacao.php'"><?=dicionario(77)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_usuario.php'"><?=dicionario(2)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_licenca.php'"><?=dicionario(78)?></li>
			<li class='n2' onClick="parent.conteudo.location='news.php'"><?=dicionario(79)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_mensagem.php'"><?=dicionario(212)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_device.php'"><?=dicionario(996)?></li>
			<li class='n2<?=OpcoesExtrasFinal(getCodigoInterno(12,5))?>' onClick="parent.conteudo.location='menu_backup_conta.php'"><?=dicionario(60)?></li>
			<li class='n2' onClick="parent.conteudo.location='menu_suporte.php'">Suporte</li>
			<? OpcoesExtras(getCodigoInterno(12,5));	?>
		</ul>
		<div id='codigo_barra' style='padding-top:11px; padding-bottom:2px;'>
			<form name='formulario' method='post' action='cadastro_codigo_barra.php' target='conteudo'>
				<div><?=dicionario(81)?></div>
				<table cellpadding='0' cellspacing='0' style='margin: auto'>
					<tr>
						<td><input type='text' name='codigo_barra' style='width:92px;' onChange="if(this.value != ''){ document.formulario.submit(); }" /></td>
						<td><img src='../../img/estrutura_sistema/help.gif' onClick='informacaoAtalho()' style='cursor:pointer; margin: 3px -3px 1px 7px' title='Ajuda' /></td>
					</tr>
				</table>
			</form>			
			
		</div>
	</body>
</html>
<script>
	var temp = '';
	document.formulario.codigo_barra.focus();
	
	function oi(destino,local){
		temp = local;
		if(temp == 2)	
		parent.conteudo.location=destino;
	}
	function atualizaCabecalho(){
		//if(window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML != undefined){
		if(window.parent.cabecalho.document.getElementById('cp_modulo_atual') != undefined){
			parent.cabecalho.location = 'cabecalho.php?Titulo='+window.parent.cabecalho.document.getElementById('cp_modulo_atual').innerHTML;
		}else{
			parent.cabecalho.location = 'cabecalho.php';
		}
		setTimeout("atualizaCabecalho()",<?=getParametroSistema(108,1)*1000?>);
	}
	function informacaoAtalho(){
		parent.conteudo.location = "informacao_atalho.php";
	}
	atualizaCabecalho();
</script>
