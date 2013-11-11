//Verifica se string é numérica
function isNum(str)
{
var VBlnIsNum;
VIntTam = str.length;
VBlnIsNum = true;
if (VIntTam == 0)
{
return false;
}
else
{
for (i=0; i < VIntTam; i++)
{
if (str.substring(i,i+1) < '0' || str.substring(i,i+1) >
'9')
{
VBlnIsNum = false;
}
}
return VBlnIsNum;
}
}

/**
 * Testa se a String pCpf fornecida é um CPF válido.
 * Qualquer formatação que não seja algarismos é desconsiderada.
 * @param String pCpf
 * 	String fornecida para ser testada.
 * @return <code>true</code> se a String fornecida for um CPF válido.
 */
 NUM_DIGITOS_CPF  = 11;
 NUM_DIGITOS_CNPJ = 14;

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
}
function isCPF(pCpf)
{
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;

	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;

	// Valida dígitos verificadores
	if (numero != "" + base + digitos) return false;

	/* Não serão considerados válidos os seguintes CPF:
	 * 000.000.000-00, 111.111.111-11, 222.222.222-22, 333.333.333-33, 444.444.444-44,
	 * 555.555.555-55, 666.666.666-66, 777.777.777-77, 888.888.888-88, 999.999.999-99.
	 */
	algUnico = true;
	for (i=1; algUnico && i<NUM_DIGITOS_CPF; i++)
	{
		algUnico = (numero.charAt(i-1) == numero.charAt(i));
	}
	return (!algUnico);
} //isCPF

/*
//Função de validação de CPF
function isCPF(st) {
if (st == "")
return (false);

st = st.replace(".","");
st = st.replace(".","");
st = st.replace("-","");

l = st.length;

//aleterado para se usuário não digitar os zeros na frente do CPF, completar sozinho
if ((l == 9) || (l == 8))
{
for (i = l ; i < 10; i++)
{
st = '0' + st
}
}
l = st.length;
st2 = "";
for (i = 0; i < l; i++) {
caracter = st.substring(i,i+1);
if ((caracter >= '0') && (caracter <= '9'));
st2 = st2 + caracter;
}
if ((st2.length > 11) || (st2.length < 10))
return (false);
if (st2.length==10)
st2 = '0' + st2;
digito1 = st2.substring(9,10);
digito2 = st2.substring(10,11);
digito1 = parseInt(digito1,10);
digito2 = parseInt(digito2,10);
sum = 0; mul = 10;
for (i = 0; i < 9 ; i++) {
digit = st2.substring(i,i+1);
tproduct = parseInt(digit ,10) * mul;
sum += tproduct;
mul--;
}
dig1 = ( sum % 11 );
if ( dig1==0 || dig1==1 )
dig1=0;
else
dig1 = 11 - dig1;
if (dig1!=digito1)
return (false);
sum = 0;
mul = 11;
for (i = 0; i < 10 ; i++) {
digit = st2.substring(i,i+1);
tproduct = parseInt(digit ,10)*mul;
sum += tproduct;
mul--;
}
dig2 = (sum % 11);
if ( dig2==0 || dig2==1 )
dig2=0;
else
dig2 = 11 - dig2;
if (dig2 != digito2)
return (false);
return (true);
}
*/