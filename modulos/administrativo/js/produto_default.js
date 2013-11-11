function janela_busca_produto(pos){
	if(pos != '' && pos != undefined){
		janelas('busca_produto.php?pos='+pos,530,350,250,100,'');
	}else{
		janelas('busca_produto.php',530,350,250,100,'');
	}
}

function busca_produto(IdProduto, Erro, Local, pos){
	if(IdProduto == ''){
		IdProduto = 0;
	}
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
	}
	if(pos == undefined){
		pos = '';
	}
	var nameNode, nameTextNode, url;
	
	var xmlhttp   = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
    	xmlhttp = new XMLHttpRequest();
        if(xmlhttp.overrideMimeType){
        	xmlhttp.overrideMimeType('text/xml');
		}
	}else if (window.ActiveXObject){ // IE
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }
    
   	url = "xml/produto.php?IdProduto="+IdProduto;
	
	xmlhttp.open("GET", url,true);
	
	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){
				if(Erro != false){
					document.formulario.Erro.value = 0;
					verificaErro();
				}
				if(xmlhttp.responseText == 'false'){
					
					switch(Local){				
						case 'ProdutoFoto':
							document.formulario.IdProduto.value					= "";
							document.formulario.DescricaoProduto.value 			= "";
							document.formulario.IdProdutoFoto.value				= "";
							document.formulario.NomeArquivo.value				= "";
							document.formulario.Redimensionar.value				= 0;
							document.formulario.DescricaoFoto.value				= "";
							document.formulario.ExtFoto.value					= "";
							document.formulario.EndFoto.value					= "";
					
							document.formulario.DataCriacao.value 				= "";
							document.formulario.LoginCriacao.value 				= "";
							document.formulario.DataAlteracao.value 			= "";
							document.formulario.LoginAlteracao.value			= "";
					
							document.formulario.Foto.disabled					= false;
							document.formulario.Redimensionar.disabled			= false;
							document.formulario.Acao.value						= "inserir";
							
							document.formulario.IdProduto.focus();
								
							addParmUrl("marProduto","IdProduto","");
							addParmUrl("marProdutoTabelaPreco","IdProduto","");
							addParmUrl("marProdutoFoto","IdProduto","");
							addParmUrl("marProdutoFotoNovo","IdProduto","");
							
							ativa_imagem('');
							break;					
						case 'ProdutoTabelaPreco':
							document.formulario.IdProduto.value					= "";
							document.formulario.DescricaoProduto.value 			= "";
							document.formulario.DataCriacao.value 				= "";
							document.formulario.LoginCriacao.value 				= "";
							document.formulario.DataAlteracao.value 			= "";
							document.formulario.LoginAlteracao.value			= "";
							document.formulario.IdUltimoFornecedor.value		= "";
							document.formulario.NomeFornecedor.value			= "";
							document.formulario.ValorPrecoMedio.value			= "";
							document.formulario.ValorPrecoUltimaCompra.value	= "";
							document.formulario.DataUltimaCompra.value			= "";
							
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,10) == 'ValorPreco'){
									document.formulario[i].value	=	document.formulario.ValorMinimo.value;	
								} 
							}
							
							addParmUrl("marProduto","IdProduto",'');
							addParmUrl("marProdutoTabelaPreco","IdProduto","");
							addParmUrl("marProdutoFoto","IdProduto","");
							addParmUrl("marProdutoFotoNovo","IdProduto","");
							break;
						case 'NotaFiscalEntrada':
							var i=0,posIni=0,posFim=0;
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
									if(posIni==0){
										posIni = i;
										posFim = i;
									}else{
										posFim = i;
									}
								}
							}
							
							var id;
							
							for(i=posIni;i<=posFim;i=i+9){
								if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
									id	=	document.formulario[i].name.split('_');
									
									if(id[1] == pos){
										//alert(document.formulario[i].value);
										document.formulario[i].value	=	'';
										document.formulario[i+1].value	=	'';
										document.formulario[i+2].value	=	'';
										document.formulario[i+3].value	=	'';
										document.formulario[i+4].value	=	'';
										document.formulario[i+5].value	=	'';
										document.formulario[i+6].value	=	'';
										document.formulario[i+7].value	=	'';
										document.formulario[i+8].value	=	'';
										
										document.formulario[i].focus();
										
										calcula_somatorio_produto();
										
										i = posFim;
									}
								}
							} 
							
							posIni=0,posFim=0;
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
									if(posIni==0){
										posIni = i;
										posFim = i;
									}else{
										posFim = i;
									}
								}
							}
							
							for(i=posIni;i<=posFim;i=i+3){
								if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
									id	=	document.formulario[i].name.split('_');
									
									if(id[1] == pos){
										document.formulario[i].value	=	'';
										document.formulario[i+1].value	=	'';
										document.formulario[i+2].value	=	'';
										
										document.getElementById('icoNSerie'+pos).src	=	'../../img/estrutura_sistema/ico_serie.gif';
										i = posFim;
									}
								}
							} 
						
							var posIni=0,posFim=0,i;
							for(i=0;i<document.formulario.length;i++){
								if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
									if(posIni==0){
										posIni	=	i;
										posFim	=	i;
									}else{
										posFim	=	i;
									}
								}
							}
							document.formulario.Produtos.value	=	'';	
							for(i=posIni;i<=posFim;i=i+9){
								if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
									var temp	=	document.formulario[i].name.split('_');	
									if(temp[1] != pos){
										if(document.formulario.Produtos.value != ''){
											if(document.formulario[i].value!=''){
												document.formulario.Produtos.value	+=	'#';	
											}
										}
										if(document.formulario[i].value!=''){
											document.formulario.Produtos.value	+=	document.formulario[i].value;
										}
									}
								}
							}
							break;
						case 'Produto':
							document.formulario.IdProduto.value 				= '';
							document.formulario.DescricaoProduto.value 			= '';
							document.formulario.IdFabricante.value				= '';
							document.formulario.DescricaoFabricante.value		= '';
							document.formulario.QtdMinima.value					= '';
							document.formulario.QtdMaxima.value					= '';
							document.formulario.PesoKG.value					= '';
							document.formulario.CodigoBarra.value				= '';
							document.formulario.qtdFoto.value					= '';
							document.formulario.Garantia.value					= '';
							document.formulario.IdUnidade[0].selected			= true;
							document.formulario.IdUnidadeGarantia[0].selected	= true;
							document.formulario.IdTipoGarantia[0].selected		= true;
							document.formulario.NumeroSerie[0].selected			= true;
							document.formulario.NumeroSerieObrigatorio[0].selected	= true;
							document.formulario.IdGrupoProduto[0].selected	= true;
							//document.formulario.SubGrupoProduto.value			= "";
							document.formulario.ObsProduto.value				= "";
							document.formulario.DataCriacao.value 				= "";
							document.formulario.LoginCriacao.value 				= "";
							document.formulario.DataAlteracao.value 			= "";
							document.formulario.LoginAlteracao.value			= "";
							document.formulario.EspecificacaoProduto.value		= "";
							document.formulario.DescricaoReduzidaProduto.value	= '';
							document.formulario.IdUltimoFornecedor.value 		= "";
							document.formulario.NomeFornecedor.value			= "";
							document.formulario.ValorPrecoUltimaCompra.value	= "";
							document.formulario.DataUltimaCompra.value			= '';
							document.formulario.ValorPrecoMedio.value			= '';
							document.formulario.Acao.value 						= 'inserir';
							
							addParmUrl("marProduto","IdProduto",'');
							addParmUrl("marProdutoTabelaPreco","IdProduto","");
							addParmUrl("marProdutoFoto","IdProduto","");
							addParmUrl("marProdutoFotoNovo","IdProduto","");
							
							while(document.getElementById('tabelaSubGrupo').rows.length > 2){
								document.getElementById('tabelaSubGrupo').deleteRow(1);
							}
							
							
							document.getElementById('totaltabelaSubGrupo').innerHTML			=	"Total: 0";
							
							ativa_imagem('','','');
							subgrupo_produto('',false,Local);
							verificaObrigatoriedade('');
							verificaNumeroSerie('');
							
							
							document.formulario.IdProduto.focus();
							break;
					}
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdProduto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProduto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoProduto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoReduzidaProduto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoReduzidaProduto = nameTextNode.nodeValue;
					
					switch(Local){
						case 'ProdutoTabelaPreco':
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdUltimoFornecedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdUltimoFornecedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeFornecedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeFornecedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrecoMedio")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorPrecoMedio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrecoUltimaCompra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorPrecoUltimaCompra = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataUltimaCompra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataUltimaCompra = nameTextNode.nodeValue;
							
							if(ValorPrecoUltimaCompra!=""){
								ValorPrecoUltimaCompra = formata_float(Arredonda(ValorPrecoUltimaCompra,2),2).replace('.',',');
							}
							if(ValorPrecoMedio!=""){
								ValorPrecoMedio = formata_float(Arredonda(ValorPrecoMedio,2),2).replace('.',',');
							}
							
							document.formulario.IdProduto.value					= IdProduto;
							document.formulario.DescricaoProduto.value	 		= DescricaoProduto;
							document.formulario.IdUltimoFornecedor.value		= IdUltimoFornecedor;
							document.formulario.NomeFornecedor.value			= NomeFornecedor;
							document.formulario.ValorPrecoMedio.value			= ValorPrecoMedio;
							document.formulario.ValorPrecoUltimaCompra.value	= ValorPrecoUltimaCompra;
							document.formulario.DataUltimaCompra.value			= dateFormat(DataUltimaCompra);
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							
							addParmUrl("marProduto","IdProduto",IdProduto);
							addParmUrl("marProdutoTabelaPreco","IdProduto",IdProduto);
							addParmUrl("marProdutoFoto","IdProduto",IdProduto);
							addParmUrl("marProdutoFotoNovo","IdProduto",IdProduto);
							
							busca_produto_tabela_preco(IdProduto,false);
							break;
						case 'NotaFiscalEntrada':
							nameNode = xmlhttp.responseXML.getElementsByTagName("Unidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Unidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerie")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroSerie = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerieObrigatorio")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroSerieObrigatorio = nameTextNode.nodeValue;
							
							var novo = 0;
							temp = 0;
							if(document.formulario.Produtos.value!=''){
								var Produto	 =	document.formulario.Produtos.value.split('#');
								while(temp<Produto.length){
									if(Produto[temp] == IdProduto){
										novo++;
									}
									temp++;
								}											
							}
							
							if(novo == 0){
								var ii=0,posIni=0,posFim=0;
								for(ii=0;ii<document.formulario.length;ii++){
									if(document.formulario[ii].name.substr(0,10) == 'IdProduto_'){
										if(posIni==0){
											posIni = ii;
											posFim = ii;
										}else{
											posFim = ii;
										}
									}
								}
								
								var id;
								
								for(ii=posIni;ii<=posFim;ii=ii+9){
									if(document.formulario[ii].name.substr(0,10) == 'IdProduto_'){
										id	=	document.formulario[ii].name.split('_');
										if(id[1] == pos){
											document.formulario[ii].value	=	IdProduto;
											document.formulario[ii+1].value	=	DescricaoReduzidaProduto;
											document.formulario[ii+2].value	=	Unidade;
											ii = posFim;
										}
									}
								} 
								
								posIni=0,posFim=0;
								for(ii=0;ii<document.formulario.length;ii++){
									if(document.formulario[ii].name.substr(0,7) == 'NSerie_'){
										if(posIni==0){
											posIni = ii;
											posFim = ii;
										}else{
											posFim = ii;
										}
									}
								}
								
								for(ii=posIni;ii<=posFim;ii=ii+3){
									if(document.formulario[ii].name.substr(0,7) == 'NSerie_'){
										id	=	document.formulario[ii].name.split('_');
										if(id[1] == pos){
											document.formulario[ii].value	=	NumeroSerie;
											document.formulario[ii+1].value	=	NumeroSerieObrigatorio;
											document.formulario[ii+2].value	=	'';
											
											if(NumeroSerie == 2){
												document.getElementById('icoNSerie'+pos).src	=	'../../img/estrutura_sistema/ico_serie_c.gif';
											}else{
												document.getElementById('icoNSerie'+pos).src	=	'../../img/estrutura_sistema/ico_serie.gif';
											}
											ii = posFim;
										}
									}
								} 
								
								if(document.formulario.Produtos.value !=''){
									document.formulario.Produtos.value	+=	'#';
								}
								document.formulario.Produtos.value	+=	IdProduto;	
							}else{
								var i=0,posIni=0,posFim=0;
								for(i=0;i<document.formulario.length;i++){
									if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
										if(posIni==0){
											posIni = i;
											posFim = i;
										}else{
											posFim = i;
										}
									}
								}
								
								var id;
								
								for(i=posIni;i<=posFim;i=i+9){
									if(document.formulario[i].name.substr(0,10) == 'IdProduto_'){
										id	=	document.formulario[i].name.split('_');
										
										if(id[1] == pos){
											//alert(document.formulario[i].value);
											document.formulario[i].value	=	'';
											document.formulario[i+1].value	=	'';
											document.formulario[i+2].value	=	'';
											document.formulario[i+3].value	=	'';
											document.formulario[i+4].value	=	'';
											document.formulario[i+5].value	=	'';
											document.formulario[i+6].value	=	'';
											document.formulario[i+7].value	=	'';
											document.formulario[i+8].value	=	'';
											
											document.formulario[i].focus();
											
											calcula_somatorio_produto();
											
											i = posFim;
										}
									}
								} 
								
								posIni=0,posFim=0;
								for(i=0;i<document.formulario.length;i++){
									if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
										if(posIni==0){
											posIni = i;
											posFim = i;
										}else{
											posFim = i;
										}
									}
								}
								
								for(i=posIni;i<=posFim;i=i+3){
									if(document.formulario[i].name.substr(0,7) == 'NSerie_'){
										id	=	document.formulario[i].name.split('_');
										
										if(id[1] == pos){
											document.formulario[i].value	=	'';
											document.formulario[i+1].value	=	'';
											document.formulario[i+2].value	=	'';
											
											document.getElementById('icoNSerie'+pos).src	=	'../../img/estrutura_sistema/ico_serie.gif';
											i = posFim;
										}
									}
								} 
							}
							break;
						case 'Produto':
							nameNode = xmlhttp.responseXML.getElementsByTagName("EspecificacaoProduto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var EspecificacaoProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdUnidade")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdUnidade = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdFabricante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdFabricante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFabricante")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DescricaoFabricante = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("Garantia")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var Garantia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdUnidadeGarantia")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdUnidadeGarantia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdTipoGarantia")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdTipoGarantia = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMinima")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdMinima = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("QtdMaxima")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var QtdMaxima = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("PesoKG")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var PesoKG = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ObsProduto")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ObsProduto = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("IdUltimoFornecedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var IdUltimoFornecedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NomeFornecedor")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NomeFornecedor = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrecoMedio")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorPrecoMedio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("ValorPrecoUltimaCompra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var ValorPrecoUltimaCompra = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataUltimaCompra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataUltimaCompra = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoBarra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CodigoBarra = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerie")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroSerie = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("NumeroSerieObrigatorio")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var NumeroSerieObrigatorio = nameTextNode.nodeValue;
							
							nameNode = xmlhttp.responseXML.getElementsByTagName("CodigoBarra")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var CodigoBarra = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataCriacao = nameTextNode.nodeValue;
					
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginCriacao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginCriacao = nameTextNode.nodeValue;					
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("DataAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var DataAlteracao = nameTextNode.nodeValue;
						
							nameNode = xmlhttp.responseXML.getElementsByTagName("LoginAlteracao")[0]; 
							nameTextNode = nameNode.childNodes[0];
							var LoginAlteracao = nameTextNode.nodeValue;
							
							if(QtdMaxima != ''){
								QtdMaxima = formata_float(Arredonda(QtdMaxima,2),2).replace('.',',');
							}
							if(PesoKG != ''){
								PesoKG = formata_float(Arredonda(PesoKG,2),2).replace('.',',');
							}
							if(ValorPrecoUltimaCompra!=""){
								ValorPrecoUltimaCompra = formata_float(Arredonda(ValorPrecoUltimaCompra,2),2).replace('.',',');
							}
							if(ValorPrecoMedio!=""){
								ValorPrecoMedio = formata_float(Arredonda(ValorPrecoMedio,2),2).replace('.',',');
							}
							
							document.formulario.IdProduto.value					= IdProduto;
							document.formulario.DescricaoProduto.value	 		= DescricaoProduto;
							document.formulario.IdUnidade.value					= IdUnidade;						
							document.formulario.IdFabricante.value				= IdFabricante;
							document.formulario.DescricaoFabricante.value		= DescricaoFabricante;
							document.formulario.DescricaoReduzidaProduto.value	= DescricaoReduzidaProduto;
							document.formulario.Garantia.value					= Garantia;
							document.formulario.IdUnidadeGarantia.value			= IdUnidadeGarantia;
							document.formulario.IdTipoGarantia.value			= IdTipoGarantia;
							document.formulario.QtdMinima.value					= formata_float(Arredonda(QtdMinima,2),2).replace('.',',');
							document.formulario.QtdMaxima.value					= QtdMaxima;
							document.formulario.PesoKG.value					= PesoKG;
							document.formulario.CodigoBarra.value				= CodigoBarra;
							document.formulario.ObsProduto.value				= ObsProduto;
							document.formulario.IdUltimoFornecedor.value		= IdUltimoFornecedor;
							document.formulario.NomeFornecedor.value			= NomeFornecedor;
							document.formulario.NumeroSerie.value				= NumeroSerie;
							document.formulario.ValorPrecoMedio.value			= ValorPrecoMedio;
							document.formulario.ValorPrecoUltimaCompra.value	= ValorPrecoUltimaCompra;
							document.formulario.DataUltimaCompra.value			= dateFormat(DataUltimaCompra);
							document.formulario.EspecificacaoProduto.value		= EspecificacaoProduto;
							document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 				= LoginCriacao;
							document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value			= LoginAlteracao;
							document.formulario.qtdFoto.value 					= 0;
							document.formulario.Acao.value 						= 'alterar';
							document.formulario.SubGrupoProduto.value			= "";
							
							verificaObrigatoriedade(Garantia);
							verificaNumeroSerie(NumeroSerie);
							subgrupo_produto('',false,Local);
							document.formulario.IdGrupoProduto[0].selected	= true;
							busca_produto_foto(IdProduto);
							busca_subgrupo(IdProduto);
							
							addParmUrl("marProduto","IdProduto",IdProduto);
							addParmUrl("marProdutoTabelaPreco","IdProduto",IdProduto);
							addParmUrl("marProdutoFoto","IdProduto",IdProduto);
							addParmUrl("marProdutoFotoNovo","IdProduto",IdProduto);
							
							document.formulario.NumeroSerieObrigatorio.value	= NumeroSerieObrigatorio;
							break;
						case 'ProdutoFoto':
							document.formulario.IdProduto.value					= IdProduto;
							document.formulario.DescricaoProduto.value 			= DescricaoProduto;
							document.formulario.IdProdutoFoto.value				= "";
							document.formulario.NomeArquivo.value				= "";
							document.formulario.Redimensionar.value				= 0;
							document.formulario.DescricaoFoto.value				= "";
							document.formulario.ExtFoto.value					= "";
							document.formulario.EndFoto.value					= "";
					
							document.formulario.DataCriacao.value 				= "";
							document.formulario.LoginCriacao.value 				= "";
							document.formulario.DataAlteracao.value 			= "";
							document.formulario.LoginAlteracao.value			= "";
					
							document.formulario.Foto.disabled					= false;
							document.formulario.Redimensionar.disabled			= false;
							document.formulario.Acao.value						= "inserir";
							
							document.formulario.IdProdutoFoto.focus();
							
							addParmUrl("marProduto","IdProduto",IdProduto);
							addParmUrl("marProdutoTabelaPreco","IdProduto",IdProduto);
							addParmUrl("marProdutoFoto","IdProduto",IdProduto);
							addParmUrl("marProdutoFotoNovo","IdProduto",IdProduto);
							
							ativa_imagem('');
							break;	
					}					
				}
				if(document.getElementById("quadroBuscaProduto") != null){
					if(document.getElementById("quadroBuscaProduto").style.display == "block"){
						document.getElementById("quadroBuscaProduto").style.display =	"none";
					}
				}
				verificaAcao();
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}


