<?php
	include ("../files/conecta.php");
	include ("../files/funcoes.php");
	
	$local_urlRetornoErro	=	getParametroSistema(6,3);
	
	$formDados[IdLoja]			= (int)$_POST['IdLoja'];
	$formDados[Login]			= trim(strtolower($_POST['Login']));
	$formDados[Senha]			= $_POST['Senha'];
	$local_Erro					= $_POST['Erro'];
	$AntiSpamSTR				= trim(strtolower($_POST["AntiSpamSTR"]));
	
	if(file_exists("atualizacao") && $local_Erro == ''){
		header("Location: atualizacao.php");
	}
	
	if($formDados[IdLoja]!='' && $formDados[Login]!='' && $formDados[Senha]!=''){

		session_start("ConAdmin_AntiSpam");

		if($AntiSpamSTR != "" && $AntiSpamSTR != strtolower($_SESSION["AntiSpamSTR"])){
			$lin = false;
		}else{
			$lin = validaAutenticacaoLogin($formDados[Login], $formDados[Senha]);
		}
		
		if($lin != false){

			$sqlForcaAlteracao = "select
									Login
								from
									Usuario
								where
									Login = '".$formDados[Login]."' and
									ForcarAlteracaoSenha = 1";
			$resForcaAlteracao = mysql_query($sqlForcaAlteracao, $con);
			if($linForcaAlteracao = mysql_fetch_array($resForcaAlteracao)){
				header("Location: ../alterar_senha.php");
				break;
			}	
			
			$sqlSolicitaAlteraSenha = "SELECT 
											SolicitacaoAlteracaoSenha 
										FROM
											Usuario 
										WHERE
											Login = '$formDados[Login]'";
			$resSolicitaAlteraSenha = mysql_query($sqlSolicitaAlteraSenha, $con);
			$linSolicitaAlteraSenha = mysql_fetch_array($resSolicitaAlteraSenha);
			
			$dataSolicitaSenha = strtotime($linSolicitaAlteraSenha[SolicitacaoAlteracaoSenha]);
			$dataAtual = strtotime(date("Y-m-d"));
			if($dataSolicitaSenha != ""){
				if($dataSolicitaSenha == $dataAtual){
					header("Location: ../alterar_senha.php");
					break;
				}else if($dataSolicitaSenha < $dataAtual){
					header("Location: ../alterar_senha.php");
					break;
				}
			}
			
			$IdLojas = explode(",",lojas_permissao_login($formDados[Login]));
			if(in_array($formDados[IdLoja], $IdLojas)){
			
			 	session_cache_expire (720);
	
				session_start("ConAdmin_session");

				// Carrega as variсveis do config
				$Vars = Vars();
				@$VarsKeys = array_keys($Vars);
				for($i=0; $i<count($VarsKeys); $i++){
					$_SESSION[$VarsKeys[$i]] = $Vars[$VarsKeys[$i]];
				}
				// Fim - Carrega as variсveis do config				
				$_SESSION["DiasLicenca"] = 50;
				if($_SESSION["DiasLicenca"] <= 0){
					session_destroy();
					header("Location: $local_urlRetornoErro");
					break;
				}

				$_SESSION["Login"]		=	$formDados[Login];
				$_SESSION["IdLoja"]		=	$formDados[IdLoja];
				$_SESSION["IdPessoa"]	=	$lin[IdPessoa];
				
				$_SESSION["aberta_quadro_help_desk"]					=	1;
				$_SESSION["filtro_cancelado"]							=	getCodigoInterno(3,63);
				$_SESSION["filtro_juros"]								=	getCodigoInterno(3,64);
				$_SESSION["filtro_soma"]								=	getCodigoInterno(3,65);
				$_SESSION["filtro_nota_fiscal"]							=	getCodigoInterno(3,66);
				$_SESSION["filtro_impressao"]							=	getCodigoInterno(3,67);
				$_SESSION["filtro_contrato_cancelado"]					=	getCodigoInterno(3,68);
				$_SESSION["filtro_contrato_soma"]						=	getCodigoInterno(3,69);				
				$_SESSION["filtro_oculta_ip"]							=	getCodigoInterno(3,107);
				$_SESSION["filtro_oculta_mac"]							=	getCodigoInterno(3,108);
				$_SESSION["filtro_carne_cancelado"]						=	getCodigoInterno(3,115);
				$_SESSION["filtro_carne_quitado"]						=	getCodigoInterno(3,116);
				$_SESSION["filtro_conta_receber_nota_fiscal"]			=	getCodigoInterno(3,130);
				$_SESSION["filtro_parametro_aproximidade"]				=	getCodigoInterno(3,134);
				$_SESSION["filtro_help_desk_concluido"]					=	getCodigoInterno(3,143);
				$_SESSION["filtro_oculta_nas"]							=	getCodigoInterno(3,145);
				$_SESSION["filtro_contrato_nota_fiscal_cda"]			=	getCodigoInterno(3,146);
				$_SESSION["filtro_conta_receber_recebimento_cancelado"]	=	getCodigoInterno(3,148);
				$_SESSION["filtro_pessoa_situacao_cadastro"]			=	getCodigoInterno(3,150);
				$_SESSION["filtro_cpf_cnpj"]							=	getCodigoInterno(3,158);
				$_SESSION["filtro_MonitorAtualizacaoAutomatica"]		=	getCodigoInterno(3,152);
				$_SESSION["filtro_QTDCaracterColunaPessoa"]				=	getCodigoInterno(3,154);
				$_SESSION["filtro_ocultar_local_abertura"]				=	getCodigoInterno(3,159);
				$_SESSION["filtro_oculta_cidade_uf"]					=	getCodigoInterno(3,162);
				$_SESSION["filtro_oculta_prioridade"]					=	getCodigoInterno(3,164);
				$_SESSION["filtro_ordenar_por"]							=	url_string_xsl(getCodigoInterno(3,169),'convert');
				$_SESSION["filtro_ordem_servico_concluido"]				=	getCodigoInterno(3,173);
				$_SESSION["filtro_soma_todos"]							=	getCodigoInterno(3,175);
				$_SESSION["filtro_abrir_registro"]						=	getCodigoInterno(3,186);
				$_SESSION["filtro_expirado"]							=	getCodigoInterno(3,190);
				$_SESSION["filtro_contrato_migrado"]					=	getCodigoInterno(3,192);
				$_SESSION["filtro_oculta_local_cobranca"]				=	getCodigoInterno(3,193);
				$_SESSION["filtro_tipo_relatorio"]						= 	getCodigoInterno(3,194);
				$_SESSION["filtro_subtrair_desconto_conceber"]			= 	getCodigoInterno(3,195);
				$_SESSION["filtro_contabiliza_recebimentos_vencimento"]	= 	getCodigoInterno(3,196);
				$_SESSION["filtro_imprimir_conta_receber"]				=	getCodigoInterno(3,197);
				$_SESSION["filtro_protocolo_concluido"]					=	getCodigoInterno(3,199);
				$_SESSION["filtro_protocolo_ocultar_local_abertura"]	=	getCodigoInterno(3,200);
				$_SESSION["filtro_lista_concluido"]						=	getCodigoInterno(3,204);
				$_SESSION["filtro_lista_cancelado"]						=	getCodigoInterno(3,217);
				$_SESSION["filtro_numero_documento_ocultar"]			=	getCodigoInterno(3,218);
				$_SESSION["filtro_codigo_NF"]							=	getCodigoInterno(3,219);
				$_SESSION["filtro_somente_terceiro"]					=	getCodigoInterno(3,222);
				$_SESSION["filtro_ocultar_local_cob"]					=	getCodigoInterno(3,227);
				$_SESSION["filtro_ocultar_num_conta_rec"]				=	getCodigoInterno(3,228);
				$_SESSION["filtro_coluna_telefone"]						=	getCodigoInterno(3,246);
				$_SESSION["filtro_pessoa_forcar_atualizacao"]			=	getCodigoInterno(3,247);
				$_SESSION["filtro_pessoa_sex"]							=	getCodigoInterno(3,248);
				$_SESSION["filtro_pessoa_estado_civil"]					=	getCodigoInterno(3,249);
				$_SESSION["filtro_pessoa_taxa_de_cobranca"]				=	getCodigoInterno(3,250);
				$_SESSION["filtro_pessoa_agrupar_contratos"]			=	getCodigoInterno(3,251);
				$_SESSION["filtro_pessoa_endereco_principal"]			=	getCodigoInterno(3,252);
				$_SESSION["filtro_pessoa_monitorar_financ"]				=	getCodigoInterno(3,253);
				
				$_SESSION["IdLogAcesso"] = LogAcesso();
				
				include("../files/rotina_log_acesso.php");

				parametrizaLojas();

				# Verifica se hс necessidade de direcionamento para atualizaчуo
				$sqlUrl = "select
							DataTermino
						from
							Atualizacao
						where
							DataTermino is not null
						order by
							IdAtualizacao DESC
						limit 0,1";
				$resUrl = @mysql_query($sqlUrl,$con);
				$linUrl = @mysql_fetch_array($resUrl);

				$sqlUrl = "select
							count(*) Qtd
						from
							LogAcesso
						where
							Login='".$_SESSION["Login"]."' and
							DataHora >= '$linUrl[DataTermino]'";
				$resUrl = mysql_query($sqlUrl,$con);
				$linUrl = mysql_fetch_array($resUrl);

				if($linUrl[Qtd] == 1){
					header("Location: ../modulos/administrativo/index.php?url=cadastro_atualizacao_concluida.php");
				}else{
					if(getParametroSistema(252,1) == 1){
						$sql = "select 
									IdGrupoUsuario
								from 
									UsuarioGrupoUsuario 			
								where 
									IdLoja = $formDados[IdLoja] and 
									Login = '$formDados[Login]'";
						$res = mysql_query($sql,$con);
						if(mysql_num_rows($res)>0){
							header("Location: ../informativo_cntsistemas.php");
						}else{
							header("Location: ../modulos/administrativo/index.php");
						}
					}else{
						// Sem conexуo com a CNT
						header("Location: ../modulos/administrativo/index.php");
					}
				}
			}else{
				header("Location: $local_urlRetornoErro");
			}
		}else{
			header("Location: $local_urlRetornoErro");			
		}
	}else{
		header("Location: $local_urlRetornoErro");
	}
?>