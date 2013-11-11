			<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
					<td id="tit" width="387">&nbsp;</td>
					<td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
					<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
				</tr>
			</table>
			<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
						<?
							$Avisos = avisos(1,$local_IdPessoa);
							$limit = $_GET['IdLimite'];
							$pagina = $_GET['Pg'];
							if($_GET['IdAviso'] != ""){
								$local_IdAviso	=	$_GET['IdAviso'];
								
								$Avisos[$local_IdAviso][DataCriacao]	=	dataConv($Avisos[$local_IdAviso][DataCriacao],'Y-m-d','d/m/Y');
								
								echo"
									<p><B>".$Avisos[$local_IdAviso][TituloAviso]."</B> - ".$Avisos[$local_IdAviso][DataCriacao]."</p>
									<P><I>".$Avisos[$local_IdAviso][ResumoAviso]."</I></P>
									<P>".formTexto($Avisos[$local_IdAviso][Aviso])."</P>";
							}else{
								if($Avisos !=""){
									$i=1;
									$qnt_aviso = count($Avisos);
									foreach($Avisos as $key => $value) {
										if($pagina == 1){
											if($i <= $limit){
												echo"
													<div style='border-bottom:dotted black 1px; margin-bottom:5px;'>
													<p><B>".$value[TituloAviso]."</B> - ".$value[DataCriacao]."</p>
													<P><I>".$value[ResumoAviso]."</I></P>
													<P>".formTexto($value[Aviso])."</P>
													</div>";
											}
											$i++;
										}else{
											if($i > ($limit-1)){
												echo"
													<div style='border-bottom:dotted black 1px; margin-bottom:5px;'>
													<p><B>".$value[TituloAviso]."</B> - ".$value[DataCriacao]."</p>
													<P><I>".$value[ResumoAviso]."</I></P>
													<P>".formTexto($value[Aviso])."</P>
													</div>";
											}
											$i++;
										}
									}
								}
							}
						?>
					</td>
				</tr>
				<?
					if($pagina != ""){
						if($pagina > 1){
							$qnt_aviso = $qnt_aviso-3;
							echo "<br>".$qnt_aviso;
						}
						echo "
							<tr>
								<td colspan='4' id='coluna2td'>
									<table>
										<tr>";
										$num = 1 /*ndiv($qnt_aviso)*/;
										/*if($qnt_aviso > 0){
											if($qnt_aviso == 1){
												$qnt_aviso = $qnt_aviso+2;
											}
											if($qnt_aviso == 2){
												$qnt_aviso = $qnt_aviso+1;
											}
										}*/
										echo $num;
										for($i=0; $i<(2+$num); $i++){
											if($i == 1){
												echo"
													<td>
														<a href='?ctt=aviso.php&IdLimite=3&Pg=1'><div id='number'>$i</div></a>
													</td>";
											}else{
												if($i > 1){
													$limit = $limit+1;
													$pagina = $pagina+1;
													echo"
														<td>
															<a href='?ctt=aviso.php&IdLimite=$limit&Pg=$pagina'><div id='number'>$pagina</div></a>
														</td>";
												}
											}
										}
						echo"			</tr>
									</table>
								</td>
							</tr>";
					}
				?>
				<tr>
					<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
				</tr>
			</table>