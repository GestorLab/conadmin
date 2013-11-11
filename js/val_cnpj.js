/**
 * @author Márcio d'Ávila
 * @version 1.03, 2004-2008
 *
 * Este script foi retirado de:
 * http://www.mhavila.com.br/topicos/web/cpf_cnpj.html
 *
 * Licenciado sob os termos da licença Creative Commons,
 * Atribuição - Compartilhamento pela mesma licença 2.5:
 * http://creativecommons.org/licenses/by-sa/2.5/br/
 * Qualquer outra forma de uso requer autorização expressa do autor.
 *
 * PROTÓTIPOS:
 * método String.lpad(int pSize, char pCharPad)
 * método String.trim()
 *
 * String unformatNumber(String pNum)
 * String formatCpfCnpj(String pCpfCnpj, boolean pUseSepar, boolean pisCNPJ)
 * String dvCpfCnpj(String pEfetivo, boolean pisCNPJ)
 * boolean isCPF(String pCpf)
 * boolean isCNPJ(String pCnpj)
 * boolean isCPFCnpj(String pCpfCnpj)
 */


NUM_DIGITOS_CPF  = 11;
NUM_DIGITOS_CNPJ = 14;
NUM_DGT_CNPJ_BASE = 8;


/**
 * Adiciona método lpad() à classe String.
 * Preenche a String à esquerda com o caractere fornecido,
 * até que ela atinja o tamanho especificado.
 */
String.prototype.lpad = function(pSize, pCharPad)
{
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif>0; dif--) str = ch + str;
	return (str);
} //String.lpad


/**
 * Adiciona método trim() à classe String.
 * Elimina brancos no início e fim da String.
 */
String.prototype.trim = function()
{
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
} //String.trim


/**
 * Elimina caracteres de formatação e zeros à esquerda da string
 * de número fornecida.
 * @param String pNum
 * 	String de número fornecida para ser desformatada.
 * @return String de número desformatada.
 */
function unformatNumber(pNum)
{
	return String(pNum).replace(/\D/g, "").replace(/^0+/, "");
} //unformatNumber


/**
 * Formata a string fornecida como CNPJ ou CPF, adicionando zeros
 * à esquerda se necessário e caracteres separadores, conforme solicitado.
 * @param String pCpfCnpj
 * 	String fornecida para ser formatada.
 * @param boolean pUseSepar
 * 	Indica se devem ser usados caracteres separadores (. - /).
 * @param boolean pisCNPJ
 * 	Indica se a string fornecida é um CNPJ.
 * 	Caso contrário, é CPF. Default = false (CPF).
 * @return String de CPF ou CNPJ devidamente formatada.
 */
function formatCpfCnpj(pCpfCnpj, pUseSepar, pisCNPJ)
{
	if (pisCNPJ==null) pisCNPJ = false;
	if (pUseSepar==null) pUseSepar = true;
	var maxDigitos = pisCNPJ? NUM_DIGITOS_CNPJ: NUM_DIGITOS_CPF;
	var numero = unformatNumber(pCpfCnpj);

	numero = numero.lpad(maxDigitos, '0');

	if (!pUseSepar) return numero;

	if (pisCNPJ)
	{
		reCnpj = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/;
		numero = numero.replace(reCnpj, "$1.$2.$3/$4-$5");
	}
	else
	{
		reCpf  = /(\d{3})(\d{3})(\d{3})(\d{2})$/;
		numero = numero.replace(reCpf, "$1.$2.$3-$4");
	}
	return numero;
} //formatCpfCnpj


/**
 * Calcula os 2 dígitos verificadores para o número-efetivo pEfetivo de
 * CNPJ (12 dígitos) ou CPF (9 dígitos) fornecido. pisCNPJ é booleano e
 * informa se o número-efetivo fornecido é CNPJ (default = false).
 * @param String pEfetivo
 * 	String do número-efetivo (SEM dígitos verificadores) de CNPJ ou CPF.
 * @param boolean pisCNPJ
 * 	Indica se a string fornecida é de um CNPJ.
 * 	Caso contrário, é CPF. Default = false (CPF).
 * @return String com os dois dígitos verificadores.
 */
function dvCpfCnpj(pEfetivo, pisCNPJ)
{
	if (pisCNPJ==null) pisCNPJ = false;
	var i, j, k, soma, dv;
	var cicloPeso = pisCNPJ? NUM_DGT_CNPJ_BASE: NUM_DIGITOS_CPF;
	var maxDigitos = pisCNPJ? NUM_DIGITOS_CNPJ: NUM_DIGITOS_CPF;
	var calculado = formatCpfCnpj(pEfetivo + "00", false, pisCNPJ);
	calculado = calculado.substring(0, maxDigitos-2);
	var result = "";

	for (j = 1; j <= 2; j++)
	{
		k = 2;
		soma = 0;
		for (i = calculado.length-1; i >= 0; i--)
		{
			soma += (calculado.charAt(i) - '0') * k;
			k = (k-1) % cicloPeso + 2;
		}
		dv = 11 - soma % 11;
		if (dv > 9) dv = 0;
		calculado += dv;
		result += dv
	}

	return result;
} //dvCpfCnpj


/**
 * Testa se a String pCpf fornecida é um CPF válido.
 * Qualquer formatação que não seja algarismos é desconsiderada.
 * @param String pCpf
 * 	String fornecida para ser testada.
 * @return <code>true</code> se a String fornecida for um CPF válido.
*/ 
/*
function isCPF(pCpf)
{
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;

	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;

	// Valida dígitos verificadores
	if (numero != "" + base + digitos) return false;

	# Não serão considerados válidos os seguintes CPF:
	# * 000.000.000-00, 111.111.111-11, 222.222.222-22, 333.333.333-33, 444.444.444-44,
	# * 555.555.555-55, 666.666.666-66, 777.777.777-77, 888.888.888-88, 999.999.999-99.
	 
	algUnico = true;
	for (i=1; algUnico && i<NUM_DIGITOS_CPF; i++)
	{
		algUnico = (numero.charAt(i-1) == numero.charAt(i));
	}
	return (!algUnico);
} *///isCPF


/**
 * Testa se a String pCnpj fornecida é um CNPJ válido.
 * Qualquer formatação que não seja algarismos é desconsiderada.
 * @param String pCnpj
 * 	String fornecida para ser testada.
 * @return <code>true</code> se a String fornecida for um CNPJ válido.
 */
function isCNPJ(pCnpj)
{
	var numero = formatCpfCnpj(pCnpj, false, true);
	if (numero.length > NUM_DIGITOS_CNPJ) return false;

	var base = numero.substring(0, NUM_DGT_CNPJ_BASE);
	var ordem = numero.substring(NUM_DGT_CNPJ_BASE, 12);
	var digitos = dvCpfCnpj(base + ordem, true);
	var algUnico;

	// Valida dígitos verificadores
	if (numero != "" + base + ordem + digitos) return false;

	/* Não serão considerados válidos os CNPJ com os seguintes números BÁSICOS:
	 * 11.111.111, 22.222.222, 33.333.333, 44.444.444, 55.555.555,
	 * 66.666.666, 77.777.777, 88.888.888, 99.999.999.
	 */
	algUnico = numero.charAt(0) != '0';
	for (i=1; algUnico && i<NUM_DGT_CNPJ_BASE; i++)
	{
		algUnico = (numero.charAt(i-1) == numero.charAt(i));
	}
	if (algUnico) return false;

	/* Não será considerado válido CNPJ com número de ORDEM igual a 0000.
	 * Não será considerado válido CNPJ com número de ORDEM maior do que 0300
	 * e com as três primeiras posições do número BÁSICO com 000 (zeros).
	 * Esta crítica não será feita quando o no BÁSICO do CNPJ for igual a 00.000.000.
	 */
	if (ordem == "0000") return false;
	return (base == "00000000"
		|| parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
} //isCNPJ


/**
 * Testa se a String pCpfCnpj fornecida é um CPF ou CNPJ válido.
 * Se a String tiver uma quantidade de dígitos igual ou inferior
 * a 11, valida como CPF. Se for maior que 11, valida como CNPJ.
 * Qualquer formatação que não seja algarismos é desconsiderada.
 * @param String pCpfCnpj
 * 	String fornecida para ser testada.
 * @return <code>true</code> se a String fornecida for um CPF ou CNPJ válido.
 */
/*function isCPFCnpj(pCpfCnpj)
{
	var numero = pCpfCnpj.replace(/\D/g, "");
	if (numero.length > NUM_DIGITOS_CPF)
		return isCNPJ(pCpfCnpj)
	else
		return isCPF(pCpfCnpj);
} *///isCPFCnpj

/*
function isCNPJ(CNPJ) {
	erro = new String;
	//look for "00.000.000/0000-00"

	CNPJ = CNPJ.replace(/[\.-\/]/g, '');
	
	if(CNPJ.length() != 14) {
		return false;
	}
	
	var a = [];
	var b = new Number;
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	for (i=0; i<12; i++){
		a[i] = CNPJ.charAt(i);
		b += a[i] * c[i+1];
	}
	if ((x = b % 11) < 2) { 
		a[12] = 0 
	} else { 
		a[12] = 11-x 
	}
	b = 0;
	for (y=0; y<13; y++) {
		b += (a[y] * c[y]); 
	}
	if ((x = b % 11) < 2) { 
		a[13] = 0; 
	} else { 
		a[13] = 11-x; 
	}
	if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
		return false;
	}
	if (erro.length > 0){
		//alert(erro);
		return false;
	} else {
		return true;
	}
}
*/