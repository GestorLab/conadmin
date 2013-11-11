<?
	if(!file_exists("../../atualizacao")){
		header("Location: index.php");
	}
?>

<html>
	<head>
		<title>ConAdmin - Sistema Administrativo de Qualidade</title>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/index.css' />
		<link rel = 'stylesheet' type = 'text/css' href = 'css/atualizacao.css' />
		<meta http-equiv="refresh" content="20">
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
		    	<td align="center">
					<table width="592" border="0" cellpadding="0" cellspacing="0">
				      	<tr>
				        	<td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
				          			<tr>
				            			<td width="15"><img src="img/lgr1.png" width="15" height="50" /></td>
				            			<td id="titl"><h1><img src="img/icones/6.png" width="32" height="32" />&nbsp;Central de Avisos</h1></td>
				            			<td width="15"><img src="img/lgr2.png" width="15" height="50" /></td>
				          			</tr>
				        		</table>
							</td>
				      	</tr>
					    <tr valign="top">
					    	<td id="colunaLmain" style="width: 100%;">
								<table border='0' cellpadding='0' cellspacing='0' class='quadroAvisoCab' align='center'> 
									<tr> 
										<td class='quadroAviso_esq'>&nbsp;</td> 
										<td class='quadroAviso_center'>&nbsp;</td> 
										<td class='quadroAviso_dir'>&nbsp;</td> 
									</tr> 
								</table> 
								<table border='0' cellpadding='0' cellspacing='0' class='quadroAviso' align='center'> 
									<tr> 
										<td class='quadroAviso_center'>&nbsp;</td> 
										<td class='quadroAviso_center'>
											<br><img src='../../img/personalizacao/logo_princ.gif'><br><br>
											<h1 style='text-align:center; font-weight: bold; color: red'>Sistema em Manutenção!</h1> 
											<p style='text-align:center;'>Por favor, tente novamente mais tarde.<br>Dúvidas entre em contato com o administrador da empresa.</p> 
										</td> 
										<td class='quadroAviso_center'>&nbsp;</td> 
									</tr> 
								</table>
								<table border='0' cellpadding='0' cellspacing='0' class='quadroAvisoRod' align='center'> 
									<tr> 
										<td class='quadroAviso_esq'>&nbsp;</td> 
										<td class='quadroAviso_center'>&nbsp;</td> 
										<td class='quadroAviso_dir'>&nbsp;</td> 
									</tr> 
								</table> 
							</td>
						</tr>
					    <tr>
					        <td>
								<table id="floatleft" width="100%" border="0" cellspacing="0" cellpadding="0">
					          		<tr>
					            		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
					            		<td class="lrp"><img src="img/_Espaco.gif" /></td>
					            		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
					          		</tr>
					        	</table>
							</td>
			      		</tr>
			    	</table>
				</td>
		  	</tr>
		</table>
	</body>
</html>
