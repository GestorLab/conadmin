var DaysofWeek = new Array()
  DaysofWeek[0]="Domingo"
  DaysofWeek[1]="Segunda"
  DaysofWeek[2]="Terça"
  DaysofWeek[3]="Quarta"
  DaysofWeek[4]="Quinta"
  DaysofWeek[5]="Sexta"
  DaysofWeek[6]="Sábado"

var Months = new Array()
  Months[0]="Janeiro"
  Months[1]="Fevereiro"
  Months[2]="Março"
  Months[3]="Abril"
  Months[4]="Maio"
  Months[5]="Junho"
  Months[6]="Julho"
  Months[7]="Agosto"
  Months[8]="Setembro"
  Months[9]="Outubro"
  Months[10]="Novembro"
  Months[11]="Dezembro"

function data(campo){
	  
	var dte 	= new Date();
	var date 	= dte.getDate()
	var month 	= Months[dte.getMonth()]
	var year 	= dte.getFullYear()
	
	var data = date + " de " + month + " de " + year;
	
	document.getElementById(campo).innerHTML = data;
	
}
