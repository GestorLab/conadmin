	<head>
		<?
			if($localTituloOperacao!=''){
				$localTituloSite	=	$localTituloOperacao." - ".getParametroSistema(4,1);
			}else{
				$localTituloSite	=	getParametroSistema(4,1);
			}
		?>
		<title><?=$localTituloSite?></title>
		<link rel = 'stylesheet' type = 'text/css' href = '../default/css/index.css' />
		<link rel="shortcut icon" href="../../img/estrutura_sistema/favicon.ico" />
		<?
		if($localRelatorio == "block"){
			echo "<link rel = 'stylesheet' type = 'text/css' href = '../default/css/impress.css' media='print' />";
		}
		?>
		<script type = 'text/javascript' src = '../../js/funcoes.js'></script>
		<script type = 'text/javascript' src = '../../js/mensagens.js'></script>
		<script type = 'text/javascript' src = '../../js/incremental_search.js'></script>
		<script type = 'text/javascript' src = '../../js/val_cpf.js'></script>
		<script type = 'text/javascript' src = '../../js/val_cnpj.js'></script>
		<script type = 'text/javascript' src = '../../js/val_data.js'></script>
		<script type = 'text/javascript' src = '../../js/val_email.js'></script>
		<script type = 'text/javascript' src = '../../js/menu.js'></script>
	</head>
