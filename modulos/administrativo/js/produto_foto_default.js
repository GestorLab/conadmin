function busca_produto_foto(IdProduto, IdProdutoFoto, Erro, Local, pos){
	if(IdProduto == ''){
		IdProduto = 0;
	}
	if(IdProdutoFoto == ''){
		IdProdutoFoto = 0;
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
    
   	url = "../administrativo/xml/produto_foto.php?IdProduto="+IdProduto+"&IdProdutoFoto="+IdProdutoFoto;
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
					document.formulario.IdProdutoFoto.value				= "";
					document.formulario.Redimensionar.value				= "";
					document.formulario.DescricaoFoto.value				= "";
					document.formulario.ExtFoto.value					= "";
					document.formulario.EndFoto.value					= "";
					document.formulario.NomeArquivo.value				= "";
					
					document.formulario.DataCriacao.value 				= "";
					document.formulario.LoginCriacao.value 				= "";
					document.formulario.DataAlteracao.value 			= "";
					document.formulario.LoginAlteracao.value			= "";
					
					document.formulario.Foto.disabled					= false;
					document.formulario.Redimensionar.disabled			= false;
					document.formulario.Acao.value						= "inserir";
					
					addParmUrl("marProduto","IdProduto",IdProduto);
					addParmUrl("marProdutoTabelaPreco","IdProduto",IdProduto);
					addParmUrl("marProdutoFoto","IdProduto",IdProduto);
					addParmUrl("marProdutoFoto","IdProdutoFoto","");
					addParmUrl("marProdutoFotoNovo","IdProduto",IdProduto);
					
					ativa_imagem('');
					
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdLoja")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdLoja = nameTextNode.nodeValue;
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdProduto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					IdProduto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoProduto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoProduto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoFoto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoFoto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("IdProdutoFoto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var IdProdutoFoto = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("ExtFoto")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var ExtFoto = nameTextNode.nodeValue;
					
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
					
					var pathHost = "../../";
					var endFoto = "img/produtos/"+IdLoja+"/"+IdProduto+"/"+IdProdutoFoto+"."+ExtFoto;
					
					EndFoto = pathHost+endFoto;
					
					document.formulario.IdProduto.value					= IdProduto;
					document.formulario.DescricaoProduto.value	 		= DescricaoProduto;
					document.formulario.IdProdutoFoto.value				= IdProdutoFoto;
					document.formulario.DescricaoFoto.value				= DescricaoFoto;
					document.formulario.ExtFoto.value					= ExtFoto;
					//document.formulario.Redimensionar.disabled			= true;
					//document.formulario.Redimensionar.value				= 1;
					document.formulario.DataCriacao.value 				= dateFormat(DataCriacao);
					document.formulario.LoginCriacao.value 				= LoginCriacao;
					document.formulario.DataAlteracao.value 			= dateFormat(DataAlteracao);
					document.formulario.LoginAlteracao.value			= LoginAlteracao;
					document.formulario.EndFoto.value					= EndFoto;
					document.formulario.NomeArquivo.value				= endFoto;
					document.formulario.Foto.disabled					= false;
					document.formulario.Redimensionar.disabled			= false;
					document.formulario.Acao.value						= "alterar";
					
					ativa_imagem(EndFoto);
					
					addParmUrl("marProduto","IdProduto",IdProduto);
					addParmUrl("marProdutoTabelaPreco","IdProduto",IdProduto);
					addParmUrl("marProdutoFoto","IdProduto",IdProduto);
					addParmUrl("marProdutoFotoNovo","IdProduto",IdProduto);
					addParmUrl("marProdutoFoto","IdProdutoFoto",IdProdutoFoto);
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


