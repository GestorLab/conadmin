<?
	$Patch			= "layout/$LayoutAvisos";
	$PatchSimples	= $Patch;
	$PatchCSS		= $Patch."/css/index.css";

	if(!file_exists($PatchCSS)){
		$Patch			= "../gera_file.php?Url=layout/$LayoutAvisos";
		$PatchSimples	= "../gera_file.php?Url=../layout/$LayoutAvisos";
		$PatchCSS		= $Patch."/css/index.css";
	}
?>
<html>
	<head>
		<title><?="ConAdmin - ".$Titulo?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '<?=$PatchCSS?>' />
		<style>
			body{
				background-image: url(<?=$PatchSimples?>/img/body_bg<?=getParametroSistema(130,3)?>.jpg);
				<?
					switch(getParametroSistema(130,3)){
						case 1:
							$color = "#007098";
							break;
						case 2:
							$color = "#003060";
							break;
						case 3:
							$color = "#606060";
							break;
						case 4:
							$color = "#908f5f";
							break;
						case 5:
							$color = "#5ca82a";
							break;
						case 6:
							$color = "#efefef";
							break;
						case 7:
							$color = "#dddbcc";
							break;
						case 8:
							$color = "#adbccf";
							break;
						case 9:
							$color = "#cfdfef";
							break;
						default:
							$color = "#007098";
					}
					
					echo "background-color: $color;";
				?>
			}
		</style>
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
		    	<td align="center">
					<table border="0" cellpadding="0" cellspacing="0">
				      	<tr>
				        	<td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
				          			<tr>
				            			<td width="15"><img src="<?=$Patch?>/img/lgr1.png" width="15" height="50" /></td>
				            			<td id="titl"><h1><img src="<?=$Patch?>/img/icones/6.png" width="32" height="32" />&nbsp;Central de Avisos</h1></td>
				            			<td width="15"><img src="<?=$Patch?>/img/lgr2.png" width="15" height="50" /></td>
				          			</tr>
				        		</table>
							</td>
				      	</tr>
					    <tr valign="top">
					       <td id="colunaLmain" width='573'><?=$Conteudo?></td>
						</tr>
					    <tr>
					        <td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					          		<tr>
					            		<td width="15"><img src="<?=$Patch?>/img/lrp1.png" width="15" height="15" /></td>
					            		<td class="lrp"><img src="<?=$Patch?>/img/_Espaco.gif" /></td>
					            		<td width="15"><img src="<?=$Patch?>/img/lrp2.png" width="15" height="15" /></td>
					          		</tr>
					        	</table>
							</td>
			      		</tr>
			    	</table>
				</td>
		  	</tr>
		  	<tr>
		    	<td height="20" align="center">
					<br>
	    			<table id="rpL" width="100%" border="0" cellspacing="0" cellpadding="0">
		      			<tr>
		        			<td class="borda"><img src="<?=$Patch?>/img/rp1.png" /></td>
		        			<td rowspan='2' class="transparente">
								<?
									$Empresa	=	getParametroSistema(95,38);
									$Telefone	=	getParametroSistema(95,37); 
									$Emails		=	getParametroSistema(95,39);

									if($Empresa == ''){		
										$Empresa	=	getParametroSistema(95,3);		
									}else{
										if(strpos($Empresa, ':')){
											$Empresas = explode("\n",$Empresa);
											for($i=0; $i<count($Empresas);$i++){
												$PortaNome = explode(':',trim($Empresas[$i]));
												if($_SERVER['SERVER_PORT'] == $PortaNome[0]){
													$Empresa = $PortaNome[1];
												}
											}
										}
									}

									if($Telefone == ''){	
										$Telefone	=	getParametroSistema(95,4);		
									}else{
										if(strpos($Telefone, ':')){
											$Telefones = explode("\n",$Telefone);
											for($i=0; $i<count($Telefones);$i++){
												$PortaTelefone = explode(':',trim($Telefones[$i]));
												if($_SERVER['SERVER_PORT'] == $PortaTelefone[0]){
													$Telefone = $PortaTelefone[1];
												}
											}
										}
									}

									if($Emails == ''){		
										$Emails		=	getParametroSistema(95,5);		
									}else{
										if(strpos($Emails, ':')){
											$Emailss = explode("\n",$Emails);
											for($i=0; $i<count($Emailss);$i++){
												$PortaEmails = explode(':',trim($Emailss[$i]));
												if($_SERVER['SERVER_PORT'] == $PortaEmails[0]){
													$Emails = $PortaEmails[1];
												}
											}
										}
									}

									$Email		=	"";									
									
									$aux	=	explode(';',$Emails);
									$tam	=	sizeof($aux);
									
									$i=0;
									while($i < $tam){
										if($Email != ""){
											$Email	.=	";";	
										}	
										$Email	.=	"<a href='mailto:".$aux[$i]."'>".$aux[$i]."</a>";
										$i++;
									}
								
									echo $Empresa." - tel: ".$Telefone." - email: ".$Email;
								?>
							</td>
		       				<td class="borda"><img src="<?=$Patch?>/img/rp2.png" /></td>
		      			</tr>
						<tr>
							<td class="borda-bottom">&nbsp;</td>
							<td class="borda-bottom">&nbsp;</td>
						</tr>
		   			</table>
				</td>
		  	</tr>
		</table>
	</body>
</html>