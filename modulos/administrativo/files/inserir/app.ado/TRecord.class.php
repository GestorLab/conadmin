<?php

/*
 * classe TRecord
 * esta classe provê o metodos necessários para persistir e
 * recuperar objetos da base de dados (Active Record)
 */
abstract class TRecord {
    
    protected $data; //array contendo os dados do objeto
    
    /*
     * método __construct()
     * instância um Active Record. Se passado o $id, já carrega o objeto
     * @param [$id] = ID do objeto
     */
    public function __construct($id = NULL) {
        if($id){ //se o ID for informado
            //carrega o objeto correspondente
            $object = $this->load($id);
            if($object){
                $this->fromArray($object->toArray());
            }
        }
    }
    
    /*
     * método __clone()
     * executado quando o objeto for clonado.
     * limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone() {
        unset($this->id);
    }
    
    /*
     * método __set()
     * executa sempre que uma propriedade for atribuída
     * 
     */
    public function __set($prop, $value) {
        //verifica se existe método set_<propriedade>
        if(method_exists($this, 'set_' . $prop)){
            //executa o metodo set_<propriedade>
            call_user_func(array($this, 'set_'.$prop), $value);
        }else{
            //atribui o valor da propriedade
            $this->data[$prop] = $value;
        }
    }
    
    /*
     * método __get()
     * executa sempre que um propriedade for requerida.
     */
    public function __get($prop) {
        //verifica se existe método get_<propriedade>
        if(method_exists($this, 'get_'.$prop)){
            //executa o método get_<propriedade>
            return call_user_func(array($this, 'get_'.$prop));
        }else{
            //retorna o valor da propriedade
            return $this->data[$prop];
        }
    }
    
    /*
     * método getEntity()
     * retorna o nome da entidade (tabela)
     */
    private function getEntity() {
        //obtem o nome da classe
        $classe  = get_class($this);
        //retorna o nome da classe menos a palavra Record
        return constant("{$classe}::TABLENAME");
    }
    
    /*
     * método fromArray()
     * preenche os dados do objeto com um array
     */
    public function fromArray($data) {
        $this->data = $data;
    }
    
    /*
     * método toArray()
     * retorna os dados do objeto como array
     */
    public function toArray() {
        return $this->data;
    }
    
    /*
     * método store()
     * armazena o objeto na base de dados e retorna
     * o número de linhas afetadas pela instrução sql (zero ou um)
     */
    public function store() {
        //verifica se tem ID ou se existe no banco de dados
        if(empty($this->data['id']) or (!$this->load($this->id))){
            //incrementa o ID
            $this->id = $this->getLast() + 1;
            //cria uma instrução de insert
            $sql = new TSqlInsert();
            $sql->setEntity($this->getEntity());
            //percorre os dados do objeto
            foreach($this->data as $key => $value){
                //passa os dados do objeto para o sql
                $sql->setRowData($key, $value);
            }
        }else{
            //instância instrução de update
            $sql = new TSqlUpdate();
            $sql->setEntity($this->getEntity());
            //cria um critério de seleção baseado no id
            $criteria = new TCriteria();
            $criteria->add(new TFilter('id', '=', $this->id));
            $sql->setCriteria($criteria);
            //percorre os dados do objeto
            foreach($this->data as $key => $value){
                if($key !== 'id'){ //o ID não precisa ir no UPDATE
                    //passa os dados do objeto para o sql
                    $sql->setRowData($key, $value);
                }
            }
        }
        //obtém trasação ativa
        if($conn = TTransaction::get()){
            //exit($sql->getInstruction());
            //faz o logo e executa a sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            //retorna o resultado
            return $result;
        }else{
            //se não tiver transação retorna um exceção
            throw new Exception('Não há transação ativa');
        }
    }
    
    /*
     * método load()
     * recupera (retorna) um objeto da base de dados
     * através de seu ID e instância ele na memória
     * @param $id = ID do objeto
     */
    public function load($id) {
        //instância instrução sql
        $sql = new TSqlSelect();
        $sql->setEntity($this->getEntity());
        $sql->addColumn('*');
        
        //cria critério de seleção baseado no ID
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id', '=', $id));
        //define o critério de seleção de dados
        $sql->setCriteria($criteria);
        //obtém transação ativa
        if($conn = TTransaction::get()){
            //cria mensagem de logo e executa a consulta
            TTransaction::log($sql->getInstruction());
            $result = $conn->query($sql->getInstruction());
            //se retornou algum resultado
            if($result){
                //retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));
            }
            return $object;
        }else{
            //se não tiver trasação, retorna uma excesão
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /*
     * método delete()
     * esclui um objeto da base de dados através de seu ID.
     * @param $id = ID do objeto
     */
    public function delete($id = NULL) {
        //o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id;
        //instância um instrução de delete
        $sql = new TSqlDelete();
        $sql->setEntity($this->getEntity());
        
        //cria critério de seleção de dados
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id', '=', $id));
        //define o critério de seleção baseado no ID
        $sql->setCriteria($criteria);
        
        
        //obtém trasação ativa
        if($conn = TTransaction::get()){
            //faz o log e executa a sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            //retorna o resultado
            return $result;
        }else{
            //se não tiver trasação retorna uma exceçao
            throw new Exception('Não há trasação ativa!!');
        }
    }
    
    /*
     * método getLast()
     * retorna o último ID
     */
    private function getLast() {
        //inicia transação
        if($conn = TTransaction::get()){
            //inicia instrução sql
            $sql = new TSqlSelect();
            $sql->addColumn('max(id) as id');
            $sql->setEntity($this->getEntity());
            //cria logo e executa instrução sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->query($sql->getInstruction());
            //retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        }else{
            //se não tiver transação, retorna um exceção
            throw new Exception('Não há trasação ativa!!!');
        }
    }
    
}
