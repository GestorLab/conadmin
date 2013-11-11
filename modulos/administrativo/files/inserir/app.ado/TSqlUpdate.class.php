<?php

/*
 * class TSqlUpdate
 * esta classe provê meios para a manipulação de uma instrução de update no banco de dados
 */
final class TSqlUpdate extends TSqlInstruction {
    
    /*
     * método setRowData
     * Atribui valores à determinadas colunas no banco de dados que serão modificadas
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
            //caso seja outro tipo de valor
            $this->columnValues[$column] = $value;
        }else{
            //caso seja NULL
            $this->columnValues[$column] = "NULL";
        }
    }
    
    /*
     * método getInstruction()
     * retorna a instrução de UPDATE em forma de string
     */
    public function getInstruction() {
        //monta a string de update
        $this->sql = "UPDATE {$this->entity}";
        //monta os pares coluna = valor
        if($this->columnValues){
            foreach($this->columnValues as $column => $value){
                $set[] = "{$column} = {$value}";
            }
        }
        $this->sql .= ' SET ' . implode(', ', $set);
        
        //retorna a cláusula where do objeto TCriteria
        if($this->criteria){
            $this->sql .= ' WHERE ' . $this->criteria->dump();
        }
        return $this->sql;
    }
    
}