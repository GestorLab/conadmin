function janela_busca_datas_especiais(){
	janelas('busca_datas_especiais.php',360,283,250,100,'');
}
function busca_datas_especiais(Data,Erro,Local){
	if(Data == '' || Data == undefined){
		Data = 0;
	}
	if(Local == '' || Local == undefined){
		Local	=	document.formulario.Local.value;
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

   	url = "xml/datas_especiais.php?Data="+formatDate(Data);
   	
   	xmlhttp.open("GET", url,true);

	xmlhttp.onreadystatechange = function(){ 

		// Carregando...
		carregando(true);

		if(xmlhttp.readyState == 4){ 
			if(xmlhttp.status == 200){

				if(Erro != false){
					document.formulario.Erro.value = 0;
				//	verificaErro();
				}
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText == 'false'){
					if(Local == 'DatasEspeciais'){ 
								
							document.formulario.TipoData[0].value			=	"";
							document.formulario.DescricaoData.value			=	"";
							document.formulario.DataCriacao.value 			= 	"";
							document.formulario.LoginCriacao.value 			= 	"";
							document.formulario.DataAlteracao.value 		= 	"";
							document.formulario.LoginAlteracao.value		= 	"";
							document.formulario.Acao.value 					= 	'inserir';
							
							addParmUrl("marDatasEspeciais","Data",'');
							
							verificaAcao();
				
					}// Fim do if de Local == 'Datas Especiais'
					// Fim de Carregando
					carregando(false);
				}else{
					nameNode = xmlhttp.responseXML.getElementsByTagName("Data")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var Data = nameTextNode.nodeValue;	
				
					nameNode = xmlhttp.responseXML.getElementsByTagName("TipoData")[0];
					nameTextNode = nameNode.childNodes[0];
					var TipoData = nameTextNode.nodeValue;
					
					nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoData")[0]; 
					nameTextNode = nameNode.childNodes[0];
					var DescricaoData = nameTextNode.nodeValue;
					
					if(Local == 'DatasEspeciais'){

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
							
							document.formulario.Data.value					=	dateFormat(Data);	
							document.formulario.TipoData.value				=	TipoData;
							document.formulario.DescricaoData.value			=	DescricaoData;
							document.formulario.DataCriacao.value 			= 	dateFormat(DataCriacao);
							document.formulario.LoginCriacao.value 			= 	LoginCriacao;
							document.formulario.DataAlteracao.value 		= 	dateFormat(DataAlteracao);
							document.formulario.LoginAlteracao.value		= 	LoginAlteracao;
							document.formulario.Acao.value 					= 	'alterar';
							
							addParmUrl("marDatasEspeciais","Data",'');
							verificaAcao();
					}
				}	
				if(window.janela != undefined){
					window.janela.close();
				}
			}
			// Fim de Carregando
			carregando(false);
		} 
		return true;
	}
	xmlhttp.send(null);
}

