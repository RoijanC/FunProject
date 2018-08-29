<?php

class Model{
	
    protected $_connection;
    protected $_className = null;
    protected $_classProps;
    protected $_insert;
    protected $_update;
    protected $_delete;
    protected $_selectOne;
    protected $_selectAll;
    protected $_DBName = 'test';
    protected $_additions = ['ID'];
    protected $_exclusions = ['_connection','_className','_classProps','_exclusions','_additions','_DBName','_insert','_update','_delete','_selectOne','_selectAll'];//list PDOLayer properties here
    
    public function __construct(PDO $connection = null)
    {
        $this->_connection = $connection;
        if ($this->_connection === null) {
            $this->_connection = new PDO('mysql:host=localhost;dbname=' . $this->_DBName, 'root', '');
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $this->getInfo();
    }

    public function find($ID)
    {
        $stmt = $this->_connection->prepare($this->_selectOne);
        $stmt->execute(array('ID'=>$ID));


        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->_className);
        return $stmt->fetch();
    }

    public function where($assocArray)// array('color'=>'red')
    {
        $SQL = $this->_selectAll . ' WHERE ';
        $first = true;
        foreach($assocArray as $key => $value){
            if(!$first)
                $SQL .= ' AND ';
            else
                $first = false;
            $SQL .= "$key=:$key";
        }
        $stmt = $this->_connection->prepare($SQL);
        $stmt->execute($assocArray);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->_className);
        $returnVal = [];
        while($returnVal[] = $stmt->fetch()){
          //  $returnVal[] = $rec;
        }
        return $returnVal;
    }

    public function findAll($join = [])
    {
        $select = $this->_selectAll;
        if (count($join)>0){
            $select .= " JOIN $join[joinTable] ON $join[joinField] = $join[joinTable].ID";
        }
        $stmt = $this->_connection->prepare($select);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->_className);
        $returnVal = [];
        while($rec = $stmt->fetch()){
        	$returnVal[] = $rec;
        }
        return $returnVal;
    }

	protected function toArray(){
        $data = [];
        foreach($this->_classProps as $prop)
            $data[$prop] = $this->$prop;
/*        foreach($this->_additions as $prop)
            $data[$prop] = $this->$prop;*/
		return $data;
    }

    public function insert(){
        $stmt = $this->_connection->prepare($this->_insert);
        $stmt->execute($this->toArray());
	}

	public function update(){
        $stmt = $this->_connection->prepare($this->_update);
        $stmt->execute($this->toArray());
	}

	public function delete(){
        $stmt = $this->_connection->prepare($this->_delete);
        $stmt->execute(array('ID'=>$this->ID));

echo $this->_delete;
print_r(array('ID'=>$this->ID));


	}

	protected function getInfo(){
		$this->setProps();
		$this->makeSQL();
	}
	
	protected function setProps(){
		//extract the deriving class name
        $this->_className = get_class($this);
        
        //extract the deriving class properties
        $this->_classProps = [];
		$array = get_object_vars($this);
		foreach ($array as $key => $value) {
			if(!in_array($key, $this->_exclusions))
				$this->_classProps[] = $key;
		}
	}

	protected function makeSQL(){
        //count the deriving class properties, and prepare CRUD operations as appropriate
		$num = count($this->_classProps);
		if ($num  > 0){
			$this->_insert 	= 'INSERT INTO ' . $this->_className . '(' . implode(',', $this->_classProps) . ') VALUES (:'. implode(',:', $this->_classProps) . ')';
			//update
			$setClause = [];
			foreach($this->_classProps as $item)
				$setClause[] = sprintf('%s = :%s', $item, $item);
			$setClause = implode(', ', $setClause);
			$this->_update = 'UPDATE ' . $this->_className . ' SET ' . $setClause . ' WHERE ID = :ID';
		}
		$this->_delete 		= "DELETE FROM $this->_className WHERE ID = :ID";
		$this->_selectOne 	= "SELECT * FROM $this->_className WHERE ID = :ID";
		$this->_selectAll 	= "SELECT * FROM $this->_className";
	}
}
?>