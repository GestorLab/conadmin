	<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /-->
	<title><?=getParametroSistema(95,1)?></title>
	<link rel="shortcut icon" href="../../img/estrutura_sistema/favicon.ico" />

	<?
		if(file_exists("personalizacao/index.css")){
			echo "<link rel='stylesheet' type='text/css' href='personalizacao/index.css' />";
		}else{
			echo "<link rel='stylesheet' type='text/css' href='css/index.css' />";
		}
	?>
	
	<script type="text/javascript" src="../../js/funcoes.js"></script>		
	<script type="text/javascript" src="../../js/val_cpf.js"></script>		
	<script type="text/javascript" src="../../js/val_cnpj.js"></script>	
	<script type="text/javascript" src="../../js/val_data.js"></script>	
	<script type="text/javascript" src="../../js/val_email.js"></script>	
	<script type="text/javascript" src="../../js/event.js"></script>		
	<script type="text/javascript" src="../../classes/calendar/calendar.js"></script>
	<script type="text/javascript" src="../../classes/calendar/calendar-setup.js"></script>
	<script type="text/javascript" src="../../classes/calendar/calendar-br.js"></script>
	
	<script type="text/javascript" src="js/index.js"></script>
	<script type="text/javascript" src="js/funcoes.js"></script>
	
	<link rel='stylesheet' type='text/css' href='../../classes/calendar/calendar-blue.css' />
	<?
		if(getParametroSistema(130,3) != ''){
			echo "<style>
					body{
						background: url(img/body_bg".getParametroSistema(130,3).".jpg) repeat-x top #003366;
					}
				</style>";
		}
	?>