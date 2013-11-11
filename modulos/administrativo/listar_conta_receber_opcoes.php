<?
	$localModulo		=	1;
	$localOperacao		=	17;
	$localSuboperacao	=	"R";		
	
	//array( conta receber, conta receber excel, conta receber cidade, conta receber movimentacao, conta receber obs, conta receber venc, conta receber local recebimento, faturamento, recebimento anual, recebimneto mensal, conta Receber/TIpo, Conta a Receber/Inadimplência, Contas a Receber/Referencia, Contas a Receber/Confirmação de Pagamento, Contas a Receber/Crescimento Anual, Contas a Receber/Movimentação Diária,Contas a Receber/Pagamento/Vencimento
	$array_operacao = array(  "17", "77", "78", "69", "47", "79", "80", "59", "60", "61", "92", "99","108", "126", "128", "131", "137", "145","179","189","190");

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
	echo "<?xml-stylesheet type=\"text/xsl\" href=\"listar_conta_receber_opcoes_xsl.php$filtro_url\"?>";
	echo "<db>";
	
	echo "<reg>";			
	echo	"<IdOperacao>1</IdOperacao>";
	echo	"<Operacao><![CDATA[Contas a Receber]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>2</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber (exportar p/ formato excel)]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_avancado.php]]></Link>";
	echo	"<Tipo><![CDATA[Excel]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";	
	echo	"<IdOperacao>3</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Cidade/Local de Recebimento/Data Pagamento]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_cidade.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";	
	echo	"<IdOperacao>4</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Movimentação Diária (Recebimentos)]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_movimentacao_recebimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>5</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Vencimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_vencimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";
	echo	"<IdOperacao>6</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contas a Receber/Faturamento/Ano]]></Operacao>";
	echo	"<Link><![CDATA[menu_faturamento.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";
	echo	"<IdOperacao>7</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contas a Receber/Recebimento/Ano]]></Operacao>";
	echo	"<Link><![CDATA[menu_recebimento_anual.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";
	echo	"<IdOperacao>8</IdOperacao>";			
	echo	"<Operacao><![CDATA[Contas a Receber/Previsão de Recebimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_recebimento_mensal.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";	
	
	echo "<reg>";	
	echo	"<IdOperacao>9</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Local Recebimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_local_recebimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>10</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Desconto a Conceber]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_desconto.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>11</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Tipo]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_tipo.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>12</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Reaviso]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_reaviso.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>13</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Inadimplência]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_inadimplencia.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>14</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Referência]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_referencia.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>15</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Valores Recebidos]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_valores_recebidos.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>16</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Confirmação de Pagamento]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_confirmacao_pagamento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>17</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Crescimento Anual]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_crescimento_anual.php]]></Link>";
	echo	"<Tipo><![CDATA[Gráfico]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>18</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Movimentação Diária]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_movimentacao.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>19</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Endereço]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_endereco.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>20</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Pagamento/Vencimento]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_pagamento_vencimento.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	echo "<reg>";	
	echo	"<IdOperacao>21</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Posição da Cobrança]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_posicao_cobranca.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";
	
	/*echo "<reg>";
	echo	"<IdOperacao>22</IdOperacao>";
	echo	"<Operacao><![CDATA[Contas a Receber/Aguardando Pagamento por Pessoa]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_aguardando_pagamento_pessoa.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";*/
	
	/*echo "<reg>";	
	echo	"<IdOperacao>21</IdOperacao>";		
	echo	"<Operacao><![CDATA[Contas a Receber/Faturamento Mensal]]></Operacao>";
	echo	"<Link><![CDATA[menu_conta_receber_faturamento_mensal.php]]></Link>";
	echo	"<Tipo><![CDATA[Relatório]]></Tipo>";
	echo "</reg>";*/

	echo "</db>";
?>
