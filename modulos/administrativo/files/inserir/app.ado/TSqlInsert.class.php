<?php

/*
 * class TSqlInsert
 * Esta classe provê meios para manipulação de uma instrução de INSERT no banco de dados
 */
final class TSqlInsert extends TSqlInstruction {
    
    /*
     * método setRowData()
     * Atribui valores à determinadas colunas no banco de dados que serão inseridos
     * @param $column = coluna da tabela
     * @param $value = valor a ser armazenado
     */
    public function setRowData($column, $value) {
        //monta um array indexado pelo nome da coluna
        if(is_string($value)){
            //adiciona \ em aspas
            $value = addslashes($value);
            //caso seja um string
            $this->columnValues[$column] = "'$value'";
        }elseif(is_bool($value)){
            //caso seja um boolean
            $this->columnValues[$column] = $value ? 'TRUE' : 'FALSE';
        }elseif(isset($value)){
            //caso seja outro tipo de dado
            $this->columnValues[$column] = $value;
        }else{
            //caso seja NULL
            $this->columnValues[$column] = "NULL";
        }
    }
    
    /*
     * método setCriteria()
     * não existe no contexto dessa classe, logo, irá lançar um erro se for executado 
     */
    public function setCriteria($criteria) {
        //lança o erro
        throw new Exception('Connot call setCriteria from' . __CLASS__);
    }
    
    /*
     * método getInstruction()
     * retorna a instrução de insert em forma de string
     */
    public function getInstruction() {
        $this->sql = "INSERT INTO {$this->entity} (";
        //monta uma string contendo os nomes de colunas
        $columns = implode(", ", array_keys($this->columnValues));
        //monta uma string contendo os valores
        $values = implode(", ", array_values($this->columnValues));
        $this->sql .= $columns . ") ";
        $this->sql .= "VALUES ({$values})";
        return $this->sql;
    }
}