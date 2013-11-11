<?php

/*
 * classe TRecord
 * esta classe prov� o metodos necess�rios para persistir e
 * recuperar objetos da base de dados (Active Record)
 */
abstract class TRecord {
    
    protected $data; //array contendo os dados do objeto
    
    /*
     * m�todo __construct()
     * inst�ncia um Active Record. Se passado o $id, j� carrega o objeto
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
     * m�todo __clone()
     * executado quando o objeto for clonado.
     * limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone() {
        unset($this->id);
    }
    
    /*
     * m�todo __set()
     * executa sempre que uma propriedade for atribu�da
     * 
     */
    public function __set($prop, $value) {
        //verifica se existe m�todo set_<propriedade>
        if(method_exists($this, 'set_' . $prop)){
            //executa o metodo set_<propriedade>
            call_user_func(array($this, 'set_'.$prop), $value);
        }else{
            //atribui o valor da propriedade
            $this->data[$prop] = $value;
        }
    }
    
    /*
     * m�todo __get()
     * executa sempre que um propriedade for requerida.
     */
    public function __get($prop) {
        //verifica se existe m�todo get_<propriedade>
        if(method_exists($this, 'get_'.$prop)){
            //executa o m�todo get_<propriedade>
            return call_user_func(array($this, 'get_'.$prop));
        }else{
            //retorna o valor da propriedade
            return $this->data[$prop];
        }
    }
    
    /*
     * m�todo getEntity()
     * retorna o nome da entidade (tabela)
     */
    private function getEntity() {
        //obtem o nome da classe
        $classe  = get_class($this);
        //retorna o nome da classe menos a palavra Record
        return constant("{$classe}::TABLENAME");
    }
    
    /*
     * m�todo fromArray()
     * preenche os dados do objeto com um array
     */
    public function fromArray($data) {
        $this->data = $data;
    }
    
    /*
     * m�todo toArray()
     * retorna os dados do objeto como array
     */
    public function toArray() {
        return $this->data;
    }
    
    /*
     * m�todo store()
     * armazena o objeto na base de dados e retorna
     * o n�mero de linhas afetadas pela instru��o sql (zero ou um)
     */
    public function store() {
        //verifica se tem ID ou se existe no banco de dados
        if(empty($this->data['id']) or (!$this->load($this->id))){
            //incrementa o ID
            $this->id = $this->getLast() + 1;
            //cria uma instru��o de insert
            $sql = new TSqlInsert();
            $sql->setEntity($this->getEntity());
            //percorre os dados do objeto
            foreach($this->data as $key => $value){
                //passa os dados do objeto para o sql
                $sql->setRowData($key, $value);
            }
        }else{
            //inst�ncia instru��o de update
            $sql = new TSqlUpdate();
            $sql->setEntity($this->getEntity());
            //cria um crit�rio de sele��o baseado no id
            $criteria = new TCriteria();
            $criteria->add(new TFilter('id', '=', $this->id));
            $sql->setCriteria($criteria);
            //percorre os dados do objeto
            foreach($this->data as $key => $value){
                if($key !== 'id'){ //o ID n�o precisa ir no UPDATE
                    //passa os dados do objeto para o sql
                    $sql->setRowData($key, $value);
                }
            }
        }
        //obt�m trasa��o ativa
        if($conn = TTransaction::get()){
            //exit($sql->getInstruction());
            //faz o logo e executa a sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            //retorna o resultado
            return $result;
        }else{
            //se n�o tiver transa��o retorna um exce��o
            throw new Exception('N�o h� transa��o ativa');
        }
    }
    
    /*
     * m�todo load()
     * recupera (retorna) um objeto da base de dados
     * atrav�s de seu ID e inst�ncia ele na mem�ria
     * @param $id = ID do objeto
     */
    public function load($id) {
        //inst�ncia instru��o sql
        $sql = new TSqlSelect();
        $sql->setEntity($this->getEntity());
        $sql->addColumn('*');
        
        //cria crit�rio de sele��o baseado no ID
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id', '=', $id));
        //define o crit�rio de sele��o de dados
        $sql->setCriteria($criteria);
        //obt�m transa��o ativa
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
            //se n�o tiver trasa��o, retorna uma exces�o
            throw new Exception('N�o h� transa��o ativa!!');
        }
    }
    
    /*
     * m�todo delete()
     * esclui um objeto da base de dados atrav�s de seu ID.
     * @param $id = ID do objeto
     */
    public function delete($id = NULL) {
        //o ID � o par�metro ou a propriedade ID
        $id = $id ? $id : $this->id;
        //inst�ncia um instru��o de delete
        $sql = new TSqlDelete();
        $sql->setEntity($this->getEntity());
        
        //cria crit�rio de sele��o de dados
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id', '=', $id));
        //define o crit�rio de sele��o baseado no ID
        $sql->setCriteria($criteria);
        
        
        //obt�m trasa��o ativa
        if($conn = TTransaction::get()){
            //faz o log e executa a sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            //retorna o resultado
            return $result;
        }else{
            //se n�o tiver trasa��o retorna uma exce�ao
            throw new Exception('N�o h� trasa��o ativa!!');
        }
    }
    
    /*
     * m�todo getLast()
     * retorna o �ltimo ID
     */
    private function getLast() {
        //inicia transa��o
        if($conn = TTransaction::get()){
            //inicia instru��o sql
            $sql = new TSqlSelect();
            $sql->addColumn('max(id) as id');
            $sql->setEntity($this->getEntity());
            //cria logo e executa instru��o sql
            TTransaction::log($sql->getInstruction());
            $result = $conn->query($sql->getInstruction());
            //retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        }else{
            //se n�o tiver transa��o, retorna um exce��o
            throw new Exception('N�o h� trasa��o ativa!!!');
        }
    }
    
}
