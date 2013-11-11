<?
	if($_SESSION["IdPessoa"]!=""){
		$url	=	getParametroSistema(84,23).'/menu.php';
	}else{
		$url	=	getParametroSistema(84,23);
	}
?>

<div id='cabecalho'>
	<a href='<?=$url?>'>
		<img src='personalizacao/cabecalho.jpg' alt='<?=getParametroSistema(84,4)?>' id='logo'/>
	</a>
	<h1 style='padding:0' style='background-color:<?=getParametroSistema(84,11)?>'> 
		<table>
			<tr>
				<td style='width:100%'><?=$local_DescricaoEtapa?></td>
				<td style='text-align:right'>
					<?
						if($local_IdPessoa != ''){
							echo"<a href='menu.php' style='text-decoration:none'><img src='img/ico_menu_principal_text.gif' alt='Menu Principal'>";
						}	
						if($sair != "disabled"){
							echo "<a href='rotinas/sair.php'><img src='img/ico_sair_text.gif' alt='Sair'></a>";
						}
					?>
				</td>
			</tr>
		</table>
	</h1>
</div>
