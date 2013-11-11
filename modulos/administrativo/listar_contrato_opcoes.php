<?
	$localModulo		=	1;
	$localOperacao		=	2;
	$localSuboperacao	=	"R";		
	
	//array( Contrato , Contrato Local Cob., Contrato Cidade, Contrato Desconto, Contrato sem Ccbrancça, Contrato Tipo/Venc/Assinat, Contrato Tipo Vigencia, COntrato Novo, Contrato Parametro, Contrato Pessoa, Contrato/Histórico (Status/Data)/Contrato Sem Cobrança Aberto, Contrato/Tipo Pessoa, Contrato sem Vigência, Contrato/Datas, Contrato/Serviço/CFOP, Contrato/Migração, Contrato/Loja, Contrato/Serviço, Contrato/Status/Período, Contrato/Primeiro Contrato, Contrato/Períodos Pagos, Contrato/Mapeamento de cliente
	$array_operacao = array(  "2" , "87", "88", "73", "67", "89", "40", "93", "97", "98", "100", "101", "102", "104", "105", "106","118","122", "125", "127", "129", "130", "133", "144", "168", "170", "175", "188","193", "194", "195");
	
	include ('../../files/conecta.php');
	include ('../../files/funcoes.php');
	include ('../../rotinas/verifica_menu.php');
	 
	$filtro					= $_POST['filtro'];
	$filtro_ordem			= $_POST['filtro_ordem'];
	$filtro_ordem_direcao	= $_POST['filtro_ordem_direcao'];
	
	$filtro_url	= "";
	$filtro_sql = "";
	
	if($filtro != "")
		$filtro_url		.= "&Filtro=$filtro";
		
	if($filtro_ordem != "")
		$filtro_url		.= "&Ordem=$filtro_ordem";
		
	if($filtro_ordem_direcao != "")
		$filtro_url .= "&OrdemDirecao=$filtro_ordem_direcao";
	
	if($filtro_localTipoDado != "")
		$filtro_url .= "&TipoDado=$filtro_localTipoDado";
		
	if($filtro_url != ""){
		$filtro_url	= "?f=t".$filtro_url;
		$filtro_url	= url_string_xsl($filtro_url,'convert');
	}

		
	header ("content-type: text/xml");
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_contrato_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Contrato]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";		
	echo	"<IdOperacao>2</IdOperacao>";	
	echo	"<Operacao><![CDATA[Contrato/Local de Cobrança]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_local_cobranca.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";
	echo	"<IdOperacao>3</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contrato/Tipo/Vencimento/Assinatura]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_tipo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";
	echo	"<IdOperacao>4</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contrato/Cidade]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_cidade.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";	
	echo	"<IdOperacao>5</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato sem Cobrança]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_sem_cobranca.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>6</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Tipo Vigência]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_tipo_vigencia.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";
	echo	"<IdOperacao>7</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contrato/Status]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_status.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>8</IdOperacao>";		
	echo	"<Operacao><![CDATA[Quantidade de Novos Contratos por Ano]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_crescimento_anual.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";
	echo	"<IdOperacao>9</IdOperacao>";			
	echo	"<Operacao><![CDATA[Quantidade de Novos Contratos por Mês]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_crescimento_mensal.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>10</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Desconto]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_desconto.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>11</IdOperacao>";		
	echo	"<Operacao><![CDATA[Quantidade de Novos Contratos]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_novo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>12</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Parâmetro]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_parametro.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>13</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Pessoa (Data de Cadastro)]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_pessoa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>14</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Histórico (Status/Data)]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_status_data.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
		
	echo "<reg>";	
	echo	"<IdOperacao>15</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Parametro/Assinatura]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_parametro_assinatura.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";

	echo "<reg>";	
	echo	"<IdOperacao>16</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato sem Cobrança em Aberto]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_sem_cobranca_aberto.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>17</IdOperacao>";		
	echo	"<Operacao><![CDATA[Quantidade de Novos Contratos por Período]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_crescimento_periodo.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>18</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato com Vigências Irregulares]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_vigencia_irregular.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>19</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato com Datas Irregulares]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_data_irregular.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>20</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato com Cobrança em Aberto]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_com_cobranca_aberto.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>21</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Agente Autorizado]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_agente_autorizado.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>22</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Tipo Pessoa]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_tipo_pessoa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>23</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato sem Vigência]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_sem_vigencia.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>24</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Datas]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_datas.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>25</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Detalhes]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_detalhe.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>26</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Periodicidade]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_periodiciadade.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>27</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Serviço/CFOP]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_servico_cfop.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>28</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Migração]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_migracao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>29</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Loja]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_loja.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>30</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Serviço]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_servico.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>31</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Nota Fiscal]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_nota_fiscal.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>32</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Primeiro Contrato]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_primeiro_contrato.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>33</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Períodos Pagos]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_periodo_pago.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>34</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Mapeamento]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_cliente_map.php]]></Link>";
	echo	"<Tipo><![CDATA[Mapa]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>35</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Terceiro]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_terceiro.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>36</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Pessoa (Dados de Cliente)]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_pessoa_dados_cliente.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>37</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contrato/Consumo]]></Operacao>";
	echo	"<Link><![CDATA[menu_contrato_consumo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "</db>";
?>
