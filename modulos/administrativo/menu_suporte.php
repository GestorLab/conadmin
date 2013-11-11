<?
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>	
	<head>
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/conteudo.css' />
		<link rel = 'stylesheet' type = 'text/css' href = '../../css/default.css' />
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/data.js'></script>
		<script type = 'text/javascript' src = 'js/conteudo.js'></script>
		<meta http-equiv="refresh" content="<?=getParametroSistema(108,1)?>">
	</head>
	<body  onLoad="ativaNome('Suporte')">
		<div class='tit'>Suporte</div>
		<table>
			<tr>
				<td>
					<!-- LiveZilla Chat Button Link Code (ALWAYS PLACE IN BODY ELEMENT) --><a href="javascript:void(window.open('http://intranet.cntsistemas.com.br/aplicacoes/livezilla/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img src="http://intranet.cntsistemas.com.br/aplicacoes/livezilla/image.php?id=05&amp;type=inlay" width="128" height="42" border="0" alt="LiveZilla Live Help" style='margin: 20px;'></a><!-- http://www.LiveZilla.net Chat Button Link Code -->
				</td>
				<td>
					<a href='skype:suporte.cntsistemas?add'><img src='../../img/estrutura_sistema/skype.jpg' alt='Skype' border=0 /></a>
				</td>
			</tr>
		</table>
		<div class='tit'>Manuais</div>
		<ul>
			<li><a href='http://www.cntsistemas.com.br/downloads/manual_transmissao_nf2viaeletronica.pdf'><B>Manual para transmiss�o de notas fiscais modelo 21/22</B> (1.5 MB)</a></li>
		</ul>
		<div class='tit'>Downloads</div>
		<ul>
			<li>
				<p><B>Suporte/Atendimento</B></p>
				<ul>
					<li><a href='http://www.cntsistemas.com.br/downloads/SkypeSetupFull.rar'><B>Skype - Vers�o 6.1 </B>(27.7 MB)<br>Software que permite comunica��o texto/audio/v�deo pela internet.</a></li>
					<BR>
					<li><a href='http://www.cntsistemas.com.br/downloads/L.z-designmp.net_3.3.2.2_Full.rar'><B>LiveZilla - Vers�o 3.3.2.2 </B>(6.9 MB)<br>� um programa de bate-papo para em tempo real para empresas, totalmente integrado com o ConAdmin.</a></li>
					<BR>
					<li><a href='http://www.cntsistemas.com.br/downloads/TeamViewer_Setup-dix.rar'><B>TeamViewer - Vers�o 8.0.17 </B>(5.6 MB)<br>� um programa quer permite acesso remoto sem complica��o.</a></li>
				</ul>
			</li>
			<li>
				<p><B>Nota Fiscal Modelo 21/22</B></p>
				<ul>
					<li><a href='http://www.cntsistemas.com.br/downloads/InstalaValidaICMS115-03_v2.08.rar'><B>Validador de Notas Fiscais Modelo 21/22 - Vers�o 2.08 </B>(540 KB)<br>Este programa verifica a integridade e a consist�ncia dos arquivos digitais gerados pelo contribuinte, calculando as chaves de codifica��o digital de cada arquivo digital, gerando o arquivo de controle e identifica��o, e permitindo a emiss�o do Recibo de Entrega dos arquivos digitais. Esta vers�o traz aperfei�oamentos nas valida��es conforme as regras de preenchimento contidas no Conv�nio 115/03.</a></li>
					<BR>
					<li><a href='http://www.cntsistemas.com.br/downloads/InstalaGeraTEDeNF_v208.rar'><B>Gera M�dia TED - Vers�o 2.08</B> (1.9 MB)<BR>Este programa realiza a convers�o dos arquivos digitais gerados e validados pelo contribuinte no padr�o aceito para transmiss�o no TED (programa Transmiss�o Eletr�nica de Documentos - TED).</a></li>
					<BR>
					<li><a href='http://www.cntsistemas.com.br/downloads/EnviaTED.rar'><B>Envia TED (Transmiss�o Eletr�nica de Documentos) - Vers�o 2.08</B> (1.9 MB)<BR>Este programa realiza a transmiss�o no TED. � o mesmo utilizado para a transmiss�o dos arquivos do Sintegra.</a></li>
				</ul>
			</li>
		</ul>
	</body>
</html>