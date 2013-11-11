<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		# SQL PARA OBTER O PROXIMO ID
		$sql = "select 
					(max(IdMalaDireta) + 1) IdMalaDireta 
				from 
					MalaDireta 
				where 
					MalaDireta.IdLoja = '$local_IdLoja';";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdMalaDireta] != NULL){
			$local_IdMalaDireta = $lin[IdMalaDireta];
		} else{
			$local_IdMalaDireta = 1;
		}
		
		if($local_ListaEmail == ''){
			$local_ListaEmail = 'NULL';
		} else{
			$local_ListaEmail = "'$local_ListaEmail'";
		}
		
		if($local_Filtro_IdPessoa == ''){
			$local_Filtro_IdPessoa = 'NULL';
		} else{
			$local_Filtro_IdPessoa = "'$local_Filtro_IdPessoa'";
		}
		
		if($local_Filtro_IdGrupoPessoa == ''){
			$local_Filtro_IdGrupoPessoa = 'NULL';
		} else{
			$local_Filtro_IdGrupoPessoa = "'$local_Filtro_IdGrupoPessoa'";
		}
		
		if($local_Filtro_IdServico == ''){
			$local_Filtro_IdServico = 'NULL';
		} else{
			$local_Filtro_IdServico = "'$local_Filtro_IdServico'";
		}
		
		if($local_Filtro_IdContrato == ''){
			$local_Filtro_IdContrato = 'NULL';
		} else{
			$local_Filtro_IdContrato = "'$local_Filtro_IdContrato'";
		}
		
		if($local_Filtro_IdStatusContrato == ''){
			$local_Filtro_IdStatusContrato = 'NULL';
		} else{
			$local_Filtro_IdStatusContrato = "'$local_Filtro_IdStatusContrato'";
		}
		
		if($local_Filtro_IdProcessoFinanceiro == ''){
			$local_Filtro_IdProcessoFinanceiro = 'NULL';
		} else{
			$local_Filtro_IdProcessoFinanceiro = "'$local_Filtro_IdProcessoFinanceiro'";
		}
		
		if($local_Filtro_IdPaisEstadoCidade == ''){
			$local_Filtro_IdPaisEstadoCidade = 'NULL';
		} else{
			$local_Filtro_IdPaisEstadoCidade = "'$local_Filtro_IdPaisEstadoCidade'";
		}
		
		$sql = "start transaction;";
		@mysql_query($sql, $con);
		$tr_i = 0;
		$EnderecoArquivo = "./anexos/mala_direta/".$local_IdLoja;
		@mkdir($EnderecoArquivo, 0770);
		$EnderecoArquivo .= "/".$local_IdMalaDireta;
		$local_ExtArquivoAnexoAceito = explode(",",$_POST['ExtAnexoArquivo']);
		$CodeHTML = '';
		
		if($local_IdTipoConteudo == '1' || $local_IdTipoConteudo == '3'){
			$sql = "select 
						(max(IdTipoMensagem) + 1) IdTipoMensagem 
					from 
						TipoMensagem 
					where 
						TipoMensagem.IdLoja = '$local_IdLoja';";
			$res = mysql_query($sql,$con);
			$lin = @mysql_fetch_array($res);
			
			if($lin[IdTipoMensagem] != NULL && (int)$lin[IdTipoMensagem] > 999999){
				$local_IdTipoMensagem = $lin[IdTipoMensagem];
			} else{
				$local_IdTipoMensagem = 1000000;
			}
			
			if($local_IdTipoConteudo != '1'){
				$sql = "insert into TipoMensagem set
							IdLoja			= '$local_IdLoja', 
							IdTipoMensagem	= '$local_IdTipoMensagem', 
							IdTemplate		= '3', 
							IdContaEmail	= '$local_IdContaEmail',
							Titulo			= '$local_DescricaoMalaDireta',
							Assunto			= '$local_DescricaoMalaDireta',
							Conteudo		= '$local_TextoAvulso';";
			} else{
				$sql = "insert into TipoMensagem set
							IdLoja			= '$local_IdLoja', 
							IdTipoMensagem	= '$local_IdTipoMensagem', 
							IdTemplate		= '3', 
							IdContaEmail	= '$local_IdContaEmail',
							Titulo			= '$local_DescricaoMalaDireta',
							Assunto			= '$local_DescricaoMalaDireta';";
			}
			
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
		} else{
			if($local_IdTipoConteudo == '2'){
				$sql_TipoMensagem = "select 
										IdContaSMS,
										Conteudo, 
										Assinatura
									from
										TipoMensagem
									where
										IdLoja = '$local_IdLoja' and 
										IdTipoMensagem = '$local_IdTipoMensagem';";
				$res_TipoMensagem = mysql_query($sql_TipoMensagem,$con);
				$lin_TipoMensagem = @mysql_fetch_array($res_TipoMensagem);
				
				$sql = "select 
							(max(IdTipoMensagem) + 1) IdTipoMensagem 
						from 
							TipoMensagem 
						where 
							TipoMensagem.IdLoja = '$local_IdLoja';";
				$res = mysql_query($sql,$con);
				$lin = @mysql_fetch_array($res);
				
				if($lin[IdTipoMensagem] != NULL && (int)$lin[IdTipoMensagem] > 999999){
					$local_IdTipoMensagem = $lin[IdTipoMensagem];
				} else{
					$local_IdTipoMensagem = 1000000;
				}
				
				if($lin_TipoMensagem[IdContaSMS] == ''){
					$lin_TipoMensagem[IdContaSMS] = 'NULL';
				} else{
					$lin_TipoMensagem[IdContaSMS] = "'$lin_TipoMensagem[IdContaSMS]'";
				}
				
				if($lin_TipoMensagem[Conteudo] == ''){
					$lin_TipoMensagem[Conteudo] = 'NULL';
				} else{
					if(preg_match("/<img style=[\w\W]* src=/i",$lin_TipoMensagem[Conteudo])){
						$CodeHTML = "<table cellspacing='0' cellpadding='0'><tr>";
						$ConteudoTemp = explode("<img ",$lin_TipoMensagem[Conteudo]);
						$Tam = count($ConteudoTemp);
						$Erro = 0;
						@mkdir($EnderecoArquivo, 0770);
						
						for($i = 1; $i < $Tam; $i++){
							$SrcImg = str_replace("/","\/",getParametroSistema(6,3)."/modulos/administrativo/");
							$SrcImg = preg_replace("/[\w\W]*".$SrcImg."/",'',$ConteudoTemp[$i]);
							$SrcImg = preg_replace("/.jpg[\w\W]*/",".jpg",$SrcImg);
							$Nome = endArray(explode("/",$SrcImg));
							$CodeHTML .= "<td><img style='float:left; padding:0; margin:0;' src='".getParametroSistema(6,3)."/modulos/administrativo/".$EnderecoArquivo."/".$Nome."' /></td>";
							$SrcOrig = getParametroSistema(6,3)."/modulos/administrativo/".$SrcImg;
							
							if(!copy($SrcOrig,$EnderecoArquivo."/".$Nome)){
								for($x = 0; $x < 2; $x++){
									for($y = 0; ; $y++){
										if(!@unlink($EnderecoArquivo."/".md5($x."_".$y).".jpg")){
											break;
										}
									}
								}
								
								@rmdir($EnderecoArquivo);
								$local_transaction[$tr_i] = false;
								$tr_i++;
								$Erro++;
								break;
							}
							
							if(($i % 2) == 0 && $i < ($Tam - 1)){
								$CodeHTML .= "</tr><tr>";
							}
						}
						
						if($Erro == 1){
							$CodeHTML = "";
						} else{
							$CodeHTML .= "</tr></table>";
						}
						
						$lin_TipoMensagem[Conteudo] = $CodeHTML;
					}
					
					$lin_TipoMensagem[Conteudo] = "\"$lin_TipoMensagem[Conteudo]\"";
				}
				
				if($lin_TipoMensagem[Assinatura] == ''){
					$lin_TipoMensagem[Assinatura] = 'NULL';
				} else{
					$lin_TipoMensagem[Assinatura] = "\"$lin_TipoMensagem[Assinatura]\"";
				}
				
				$sql = "insert into TipoMensagem set
							IdLoja			= '$local_IdLoja', 
							IdTipoMensagem	= '$local_IdTipoMensagem', 
							IdTemplate		= '3', 
							IdContaEmail	= '$local_IdContaEmail',
							IdContaSMS		= $lin_TipoMensagem[IdContaSMS],
							Titulo			= '$local_DescricaoMalaDireta',
							Assunto			= '$local_DescricaoMalaDireta',
							Conteudo		= $lin_TipoMensagem[Conteudo],
							Assinatura		= $lin_TipoMensagem[Assinatura];";
				$local_transaction[$tr_i] = @mysql_query($sql,$con);
				$tr_i++;
			}
		}
		
	 	$sql = "insert into MalaDireta set
					IdLoja							= '$local_IdLoja', 
					IdMalaDireta					= '$local_IdMalaDireta', 
					DescricaoMalaDireta				= '$local_DescricaoMalaDireta', 
					ListaEmail						= $local_ListaEmail, 
					IdTipoMensagem					= $local_IdTipoMensagem, 
					IdStatus						= $local_IdStatus, 
					IdTipoConteudo					= $local_IdTipoConteudo,
					Filtro_IdPessoa					= $local_Filtro_IdPessoa,
					Filtro_IdGrupoPessoa			= $local_Filtro_IdGrupoPessoa,
					Filtro_IdServico				= $local_Filtro_IdServico,
					Filtro_IdContrato				= $local_Filtro_IdContrato,
					Filtro_IdStatusContrato			= $local_Filtro_IdStatusContrato,
					Filtro_IdProcessoFinanceiro		= $local_Filtro_IdProcessoFinanceiro,
					Filtro_IdPaisEstadoCidade		= $local_Filtro_IdPaisEstadoCidade,
					LoginCriacao					= '$local_Login', 
					DataCriacao						= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		if($local_IdTipoConteudo == '1' && $_POST['AltAnexoArquivo'] == '1'){
			$local_NomeOriginal	= $_FILES['EndArquivoAnexo']['name'];
			$local_ExtArquivoAnexo	= strtolower(endArray(explode(".",$local_NomeOriginal)));
			
			if(in_array($local_ExtArquivoAnexo, $local_ExtArquivoAnexoAceito)){
				$sql = "update MalaDireta set 
							ExtModelo = '$local_ExtArquivoAnexo'
						where
							IdLoja = '$local_IdLoja' and
							IdMalaDireta = '$local_IdMalaDireta';";
				$local_transaction[$tr_i] = @mysql_query($sql, $con);
				
				if($local_transaction[$tr_i]){
					if($local_ExtArquivoAnexo != ''){
						$local_ExtArquivoAnexo	= '.'.$local_ExtArquivoAnexo;
					}
					
					$LinkArquivo = getParametroSistema(6,3)."/modulos/administrativo/anexos/mala_direta/".$local_IdLoja."/".$local_IdMalaDireta;
					
					@mkdir($EnderecoArquivo, 0770);
					
					for($x = 0; $x < 2; $x++){
						for($y = 0; ; $y++){
							if(!@unlink($EnderecoArquivo."/".md5($x."_".$y).".jpg")){
								break;
							}
						}
					}
					
					for($i = 0; $i < count($local_ExtArquivoAnexoAceito); $i++){
						if($local_ExtArquivoAnexoAceito[$i] != "jpg"){
							@unlink($EnderecoArquivo."/$local_IdMalaDireta.$local_ExtArquivoAnexoAceito[$i]");
						}
					}
					
					if($local_ExtArquivoAnexo == ".jpg"){
						$CodeHTML = "<table cellspacing='0' cellpadding='0'>";
						# SCRIPT PARA CORTAR A IMAGEM NO MOMENTO DO UPLOAD, MALA DIRETA
						# BUSCAR A DIMENSÃO REAL
						list($x_ori, $y_ori) = @getimagesize($_FILES['EndArquivoAnexo']['tmp_name']);
						# RECORTA A IMAGEM
						$div_x = 2;
						$div_y = $y_ori / 103;
						$tm_x = $x_ori / $div_x;
						$som_y = $tm_y = $y_ori / $div_y;
						$im_src = @imagecreatefromjpeg($_FILES['EndArquivoAnexo']['tmp_name']);
						
						for($y = 0; $y < $div_y; $y++){
							$som_x = $tm_x;
							$CodeHTML .= "<tr>";
							
							for($x = 0; $x < $div_x; $x++){
								if(($x_ori - $som_x) < 0){
									$cor_x = $x_ori - ($som_x - $tm_x);
								} else{
									$cor_x = $tm_x;
								}
								
								if(($y_ori - $som_y) < 0){
									$cor_y = $y_ori - ($som_y - $tm_y);
								} else{
									$cor_y = $tm_y;
								}
								
								$im_dst = @imagecreatetruecolor($cor_x, $cor_y);
								$name = md5($x . "_" . $y) . $local_ExtArquivoAnexo;
								@imagecopy($im_dst, $im_src, 0, 0, ($tm_x * $x), ($tm_y * $y), $tm_x, $tm_y);
								@imagejpeg($im_dst, $EnderecoArquivo."/".$name, 100);
								$som_x += $tm_x;
								$CodeHTML .= "<td><img style='float:left; padding:0; margin:0;' src='$LinkArquivo/$name' /></td>";
							}
							
							$som_y += $tm_y;
							$CodeHTML .= "</tr>";
						}
						
						@imagedestroy($im_dst);
						@imagedestroy($im_src);
						$CodeHTML .= "</table>";
					} else{
						$EnderecoArquivo .= '/'.$local_IdMalaDireta.$local_ExtArquivoAnexo;
						
						if(!@copy($_FILES['EndArquivoAnexo']['tmp_name'], $EnderecoArquivo)){
							@rmdir("./anexos/mala_direta/".$local_IdLoja."/".$local_IdMalaDireta);
							
							$local_transaction[$tr_i] = false;
						} else{
							$CodeHTML = @file_get_contents($LinkArquivo.'/'.$local_IdMalaDireta.$local_ExtArquivoAnexo);
						}
					}
					
					$tr_i++;
					$sql = "update TipoMensagem set
								Conteudo = \"$CodeHTML\"
							where
								IdLoja = '$local_IdLoja' and 
								IdTipoMensagem = '$local_IdTipoMensagem';";
					$local_transaction[$tr_i] = @mysql_query($sql,$con);
				}
			} else{
				$local_transaction[$tr_i] = false;
			}
			
			$tr_i++;
		}
		
		for($i = 0; $i < $tr_i; $i++){
			if(!$local_transaction[$i]){
				$local_transaction = false;
				break;
			}
		}
		
		if($local_transaction){
			$sql = "commit;";
			$local_Acao = 'alterar';
			$local_Erro = 3;			# MENSAGEM DE INSERÇÃO POSITIVA
		} else{
			@rmdir("./anexos/mala_direta/".$local_IdLoja."/".$local_IdMalaDireta);
			
			$sql = "rollback;";
			$local_Acao = 'inserir';
			$local_Erro = 8;			# MENSAGEM DE INSERÇÃO NEGATIVA
		}
		
		@mysql_query($sql,$con);
	}
?>