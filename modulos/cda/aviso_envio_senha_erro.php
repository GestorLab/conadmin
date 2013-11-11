<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');		
?>
<html>
	<head>
		<?
			include ("files/header.php");
		?>
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
		    	<td align="center">
					<table width="640" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15"><img src="img/hgr1.png" width="15" height="50" /></td>
							<td id="tit" width="387"><h1><?=$local_Descricao?></h1></td>
							<td align="right" width="223" id="titVoltar"><img src="img/ico_voltar.png" border="0" /> <a href="index.php?ctt=index.php">Página Inicial</a></td>
							<td width="15"><img src="img/hgr2.png" width="15" height="50" /></td>
						</tr>
					</table>
					<table width="640" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td colspan='4' class='coluna2main' style='font-size:12px; text-align:justify;'>
								<br />
								<p style='text-align:center'>Não foi possivel enviar o email para o destinatário informado.</p> 
								<p style='text-align:center'>Favor entrar em contato com o administrador de seu sistema.</p>
								<br />
							</td>
						</tr>
						<tr>
							<td colspan='4'><img src="img/coluna2_rp.png" width="640" height="15" /></td>
						</tr>
					</table>
				</td>
		  	</tr>
		  	<tr>
		    	<td height="20" align="center">
	    			<table id="rpL" width="100%" border="0" cellspacing="0" cellpadding="0">
		      			<tr>
		        			<td width="20"><img src="img/rp1.png" /></td>
		        			<td class="transparente">
								<?
									include("files/rodape.php");
								?>
							</td>
		       				<td width="20"><img src="img/rp2.png" /></td>
		      			</tr>
		   			</table>
				</td>
		  	</tr>
		</table>
	</body>
</html>