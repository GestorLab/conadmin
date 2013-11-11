<?
	include('../../files/conecta.php');
	include('../../files/funcoes.php');
	include('../../rotinas/verifica.php');	
	include('files/funcoes.php');
	include('classes/class.mapa.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>		
		<?		
			
			//funções de verificação do mapa (Necessárias e devem vim primeiro que todas).
			eliminaPontoPassagemTemp();
			eliminaCabosTemp();
			
			//Gerar o mapa.
			$mapa = new GeraMapa();			
			$mapa->iniciaMapa();
			$mapa->geraPoste();	
			$mapa->geraCabo();
			$mapa->fimMapa();
			
		?>
		<script type='text/javascript' src='js/funcoes.js'></script>
		<script type='text/javascript' src='../../js/funcoes.js'></script>		
		<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
		<script type='text/javascript' src='js/georede_default.js'></script>
		<script type='text/javascript' src='js/georede.js'></script>
		<script type='text/javascript' src='js/georede_infowindow.js'></script>
		<script type='text/javascript' src='js/georede_title.js'></script>
		<link rel='stylesheet' type='text/css' href='../../css/default.css'>
		<link rel='stylesheet' type='text/css' href='../../css/conteudo.css'>
		<link rel='stylesheet' type='text/css' href='css/index.css' />		
	</head>
	<body onload="inicia();">
		<div id="carregando"></div>
		<table cellspacing="0" cellpadding="0" border="0" id='ctt'>
			<tr id='ctt_l1'>
				<td id='menu' valign='top'>
					<div id='menuFixo'>
						<ul>
							<li id='Seta' onmousemove="quadro_alt(event, this, 'Ferramenta de Seleção');" onclick="repassaMetodo('Selecao')">
								<div class='ico11'><img src="img/seta.gif"></div>
							</li>
							
							<li id='Poste1' onmousemove="quadro_alt(event, this, '<?=retornaTipoPoste(1)?>');" onclick="repassaMetodo('Poste1');info_poste1();">
								<div class='ico12'><img src="img/poste1.gif" ></div>
								<div class='agrid'><img src="img/acesso_grid.gif" onclick="agrid('250px','poste1')"></div>
							</li>
							
							<li id='Poste2' onmousemove="quadro_alt(event, this, '<?=retornaTipoPoste(2)?>');" onclick="repassaMetodo('Poste2');info_poste2();">
								<div class='ico12'><img src="img/poste2.gif" ></div>
								<div class='agrid'><img src="img/acesso_grid.gif" onclick="agrid('250px','poste2')"></div>
							</li>
							
							<li id='Cabo' onmousemove="quadro_alt(event, this, 'Cabo');" onclick="repassaMetodo('Cabo')">
								<div class='ico12'><img src="img/cabo.gif" ></div>
								<!--div class='agrid'><img src="img/acesso_grid.gif" onclick="agrid('250px','cabo')"></div-->
							</li>
						</ul>
					</div>
				</td>
				<td><?include("mapa.php");?></td>
			</tr>
		</table>
		
		<form name="formulario" >				
			<input type='hidden' name='TipoPoste' />					
			<input type='hidden' name='Coordenadas' />								
			<input type='hidden' name='CaboAtual' value="<?=caboAtual()?>" />				
			<input type='hidden' name='PermitirCabo' />								
		</form>
	</body>
</html>