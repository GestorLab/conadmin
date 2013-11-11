								<?
									$local_IdParametroSistema	=	$_GET['IdParametroSistema'];
									
									$sql	=	"select DescricaoParametroSistema from ParametroSistema where IdGrupoParametroSistema = 96 and IdParametroSistema = $local_IdParametroSistema ";
								    $res	=	@mysql_query($sql,$con);
								    $lin	=	@mysql_fetch_array($res);
								    
								    $local_Descricao	=	$lin[DescricaoParametroSistema];
								?>
								<table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							      		<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
									    <td id="tit" width="387"><h1><img src="img/icones/<?=$local_IdParametroSistema?>.png" /> <?=$local_Descricao?></h1></td>
									    <td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="?ctt=index.php">Página Inicial</a></td>
									    <td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
							    	</tr>
							    </table>
							    <table width="640" id="floatleft" border="0" cellspacing="0" cellpadding="0">
							    	<tr>
							    		<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
							    			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris bibendum pulvinar turpis. Nam vestibulum ullamcorper pede. Nulla ut dolor tempus augue commodo rutrum. Curabitur pellentesque lacus in erat. In elit enim, fringilla at, ultrices sed, feugiat quis, lorem. Sed nec augue. Donec aliquam sapien at lorem. Praesent congue massa quis metus. Nam vitae ante vitae nibh interdum aliquet. Donec leo. Pellentesque mollis ipsum a metus. Integer metus. Mauris hendrerit sagittis libero. Mauris placerat eros ac lorem. Maecenas bibendum consequat orci. Suspendisse justo dolor, volutpat faucibus, fringilla at, pharetra ut, elit. Aenean nec tortor. Fusce et quam a erat lobortis porta. </p>
										    <p>Integer non magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In arcu. Vivamus imperdiet consectetuer tellus. Nulla neque metus, interdum et, lacinia ac, feugiat nec, erat. Nullam congue. Phasellus mi mi, feugiat eu, facilisis at, suscipit at, eros. Cras consectetuer sapien vel lorem. Etiam ullamcorper eleifend nisl. Praesent tellus. Aenean lorem. In volutpat porttitor nunc. Phasellus adipiscing porta ligula. Duis urna augue, dignissim a, eleifend in, sagittis vel, nisl. Donec in ligula sit amet mi lacinia pulvinar. Donec vestibulum pretium turpis. </p>
										    <p>Aliquam odio. In vitae magna eu nulla consequat tincidunt. Aenean eros. Curabitur feugiat tellus ut dolor. Cras dui felis, lobortis id, pulvinar eu, luctus quis, elit. Morbi libero. Pellentesque tincidunt, ante id laoreet porttitor, dolor augue commodo arcu, eget rutrum urna mi sed velit. Aliquam venenatis facilisis justo. Vestibulum cursus. Vivamus lobortis, mi pellentesque porttitor sagittis, urna lacus varius odio, quis sodales justo mauris sed orci. Vivamus dui leo, faucibus consectetuer, interdum non, auctor ut, purus.</p>
										</td>
									</tr>
									<tr>
							    		<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
							    	</tr>
								</table>
