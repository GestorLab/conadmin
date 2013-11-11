function busca_dados_logomarca(){
	var url = "xml/logomarca.php";
	
	call_ajax(url, function(xmlhttp){
		var nameNode, nameTextNode;
		
		if(xmlhttp.responseText != 'false'){
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("Largura")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var Largura = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("MargemEsquerda")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var MargemEsquerda = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("MargemSuperior")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var MargemSuperior = nameTextNode.nodeValue;
			
			document.formulario.Largura.value			= Largura;
			document.formulario.Margem_Esquerda.value	= MargemEsquerda;
			document.formulario.Margem_Superior.value	= MargemSuperior;
		}
	});
}