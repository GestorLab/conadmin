function busca_duplicata(IdLocalRecebimento,IdDuplicataLayout,Erro,Local){
	if(IdDuplicataLayout == '' || IdDuplicataLayout == undefined){
		IdDuplicataLayout = 0;
	}
	if(Local == '' || Local == undefined){
		Local = document.formulario.Local.value;
	}
	var nameNode, nameTextNode, url;
	    
   	url = "xml/duplicata_layout.php?IdDuplicataLayout="+IdDuplicataLayout;
	
	call_ajax(url,function (xmlhttp){
		if(Erro != false){
			document.formulario.Erro.value = 0;
			verificaErro();
		}
		if(xmlhttp.responseText == "false"){		
			document.formulario.IdDuplicataLayout.value		=	"";
			document.formulario.DescricaoDuplicata.value	=	"";
			verificaAcao();
		}else{
		
			nameNode = xmlhttp.responseXML.getElementsByTagName("IdDuplicataLayout")[0];
			nameTextNode = nameNode.childNodes[0];
			IdDuplicataLayout = nameTextNode.nodeValue;
			
			nameNode = xmlhttp.responseXML.getElementsByTagName("DescricaoDuplicata")[0]; 
			nameTextNode = nameNode.childNodes[0];
			var DescricaoDuplicata = nameTextNode.nodeValue;
			
			document.formulario.IdDuplicataLayout.value 	= IdDuplicataLayout;
			document.formulario.DescricaoDuplicata.value 	= DescricaoDuplicata;
		}
		if(document.getElementById("quadroBuscaDuplicataLayout") != null){
			if(document.getElementById("quadroBuscaDuplicataLayout").style.display == "block"){
				document.getElementById("quadroBuscaDuplicataLayout").style.display =	"none";
			}
		}
	});
}