<div><a href="?ctt=index.php"><img src="img/marca_conadmin3.png"  /></a></div>
<div id="coluna1main">
	<table id="marcaClienteMenu" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="middle"><a href="?ctt=index.php"><img src="<?=$Perfil[UrlLogoGIF]?>" /></a></td>
		</tr>
	</table>
	<div id="avisos" class="txtA">
		<h2>Quadro de Avisos</h2>
		<? include("avisos.php"); ?>
	</div>
	<? if($Avisos !=""){
			echo "
			<div id='avisos' class='txtA'>
				<p>Ver Todos <a href='?ctt=aviso.php&IdLimite=3&Pg=1' style='color:#C60000;'>[+]</a></p>
			</div>";
		}
	?>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15"><img src="img/lrp1.png" width="15" height="15" /></td>
		<td class="lrp"><img src="img/_Espaco.gif" /></td>
		<td width="15"><img src="img/lrp2.png" width="15" height="15" /></td>
	</tr>
</table>