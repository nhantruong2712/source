<?php

/**
 * DB PDO Driver For SA Framework DB Library
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class pdo_db_connection
{
	public $con;
	public $last_result;
	public $driver;
	
	private $host;
	private $username;
	private $password;
	private $database;
	
	
	// Construct
	// ---------------------------------------------------------------------------
	public function __construct($driver,$host,$username,$password,$database)
	{
		$this->driver = $driver;
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		
		try
		{
			$this->con = new pdo("{$this->driver}:dbname={$this->database};host={$this->host}",$this->username,$this->password);
            if (DEBUG) {
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }            
		}
		catch(PDOException $e)
		{
			sa_error(E_USER_ERROR,'DB Connection Failed. '.$e->getMessage());
		}
	}
	
	
	// Query
	// ---------------------------------------------------------------------------
	public function query($sql,$orm=FALSE)
	{
	    try {
    		// If SQL statement is a SELECT statement
    		if(preg_match('/^SELECT/is',$sql))
    		{
    			$res = $this->con->prepare($sql);
    			//$res->setFetchMode(PDO::FETCH_ASSOC);
    			
    			if($orm)
    			{
    				$z = load::orm_class($orm);
                    if($z)
    				    $res->setFetchMode(PDO::FETCH_CLASS,$orm.'_orm');
                    else
                        $res->setFetchMode(PDO::FETCH_CLASS,$orm); //for model
    			}
    			else
    			{
    				$res->setFetchMode(PDO::FETCH_CLASS,'model');
    			}    			    			
    			$res->execute();
    			
    			$this->last_result = $res->fetchAll();
    		}
    		
    		// Any other kind of statement
    		else
    		{
    			$this->last_result = $this->con->exec($sql);
    		}
        } catch (PDOException $e) {
            //$this->pdo_error($e);
            return false;
        }		
		return $this->last_result;
	}
	
	
	// Clean
	// ---------------------------------------------------------------------------
	public function clean($data)
	{
		return substr($this->con->quote($data),1,-1);
	}
	
	
	// Quote
	// ---------------------------------------------------------------------------
	public function quote($data)
	{
		return $this->con->quote($data);
	}
	
	
	// Select Table
	// ---------------------------------------------------------------------------
	public function table($table)
	{
		$t = new pdo_db_table($table);
		$t->db = $this;
		$t->name = $table;
		
		return $t;
	}
	
	
	// Truncate Table
	// ---------------------------------------------------------------------------
	public function truncate($table)
	{
		return $this->query("TRUNCATE TABLE {$this->quote($table)}");
	}
	
	
	// Drop Table
	// ---------------------------------------------------------------------------
	public function drop($table)
	{
		return $this->query("DROP TABLE {$this->quote($table)}");
	}
    
    private function pdo_error($error) {
        $trace = $error->getTrace();
        //var_dump($trace);die();
        echo "Error : " . $error->getMessage() . "\n";
        echo "SQL   : " . $trace[1]['args'][0] . "\n";
        echo "Model : " . $trace[3]['file'] . "\n";
        echo "On Line " . $trace[3]['line'] . " Function " . $trace[3]['function'] . "\n";
        echo "Controller : " . (isset($trace[4]['file'])?$trace[$x=4]['file']:$trace[$x=6]['file']) . "\n";
        echo "On Line " . $trace[$x]['line'] . " Function " . $trace[$x]['function'] . "\n";
    }    
}
 
/**
 * DB Table Class For SA Framework DB Library
 *
 * @author          Hoang Trong Hoi
 * @copyright       2011
 */

class pdo_db_table
{
	public $db;
	public $table;
	public $name;
	public $_orm;
	
	
	// ORM
	// ---------------------------------------------------------------------------
	public function orm($orm)
	{
		$this->_orm = $orm;
		return $this;
	}
	
	
	// All
	// ---------------------------------------------------------------------------
	public function all()
	{
		return $this->select('*')->execute();
	}
	
	
	// Total
	// ---------------------------------------------------------------------------
	public function total()
	{
		return $this->count()->execute();
	}
	
	
	// Select
	// ---------------------------------------------------------------------------
	public function select()
	{
		$query = new SAQuery('select');
		$query->table = $this;
		$args = func_get_args();
		if((func_num_args() == 3) AND (!empty($args[1])) AND in_array($args[1],array('=','!=','<','>','>=','<=','LIKE','IN','NOT IN')))
		{
			$query->columns[] = '*';  
			return $query->where($args[0],$args[1],$args[2])->execute(empty($this->name)?false:$this->name); //fixed 04/13/2020
		}
		else
		{
			$query->columns = $args;
			return $query;
		}
	}
	
	
	// Count
	// ---------------------------------------------------------------------------
	public function count()
	{
		$query = new SAQuery('count');
		$query->table = $this;
		return $query;
	}
	
	
	// Select Distinct
	// ---------------------------------------------------------------------------
	public function distinct()
	{
		$cols = func_get_args();
		
		return $this->db->query(SASQL::build_distinct($cols,$this->name));
	}
	
	
	// Update
	// ---------------------------------------------------------------------------
	public function update($cols)
	{
		$query = new SAQuery('update');
		$query->table = $this;
		
		// Clean the data
		foreach($cols as $col=>$val)
		{
			$query->columns[$col] = $this->db->clean($val);
		}
		
		return $query;
	}
	
	
	// Delete
	// ---------------------------------------------------------------------------
	public function delete()
	{
		$query = new SAQuery('delete');
		$query->table = $this;
		
		if(func_num_args() == 3)
		{
			$args = func_get_args();
			return $query->where($args[0],$args[1],$args[2])->execute();
		}
		else
		{
			return $query;
		}
	}
	
	
	// Insert
	// ---------------------------------------------------------------------------
	public function insert($data,$query=TRUE,$primary='id')
	{
		if(!is_array($data))
		{
			trigger_error('DB Error: Incorrect data type passed to insert function. You must supply an associative array',E_USER_ERROR);
		}
		else
		{
			// Clean data before inserting
			$clean = array();
             
			$select = $this->select('*');
			$x=0;
			 
			foreach($data as $key=>$val)
			{
				$clean[$key] = $this->db->clean($val);
				 
				if($x > 0)
				{
					$select->clause('AND')
					       ->where($key,'=',$val);
				}
				else
				{
					$select->where($key,'=',$val);
				}
				
				$x++;                 
			}
			
			// Build and run SQL query
			$sql = SASQL::build_insert($clean,$this->name,$this->db->driver);
            //var_dump($sql); 
            //file_put_contents('log.txt',$sql);
			if(!($result = $this->db->query($sql))){
                return false;			 
			}			
			// Return Select
			//var_dump($result); 
			if($query)
			{
			     $sel = $select->execute();
                 //var_dump($sel);
                 if(!$sel){
                    $x = (db::query('SELECT LAST_INSERT_ID() as last'));
                    $data[$primary] = $x[0]->last;
                    return (object) $data;
                 }
			     //$row = array_values(array_reverse($sel));
                 $row = array_reverse($sel);
			     return $row[0];
			}else //return true;
                return $result;
            //else return $this->db->con->insert_id;
		}
	}
	
	
	// Execute
	// ---------------------------------------------------------------------------
	public function execute($query, $model = false) //add model 04/12/2020
	{
	    if($model) $this->_orm = $model;
		// Selects
		if($query->type == 'select')
		{
			$data = $this->db->query(SASQL::build_select($query,$this->db->driver),$this->_orm);
			
			// Combos
			if(!empty($query->_combos))
			{
				$r = 0;
				foreach($data as $row)
				{
					foreach($query->_combos as $combo)
					{
						// No Limit
						if(!$combo['limit'])
						{
							$data[$r][$combo['key']] = $this->db->table($combo['table'])
																->select($combo['where'][0],$combo['where'][1],$row[$combo['where'][2]]);
						}
						
						// Limit
						else
						{
							$data[$r][$combo['key']] = $this->db->table($combo['table'])
																->select('*')
																->where($combo['where'][0],$combo['where'][1],$row[$combo['where'][2]])
																->limit($combo['limit'])
																->execute();
						}
					}
					
					$r++;
				}
			}
		}
		// Counts
		elseif($query->type == 'count')
		{
		    $tmp = $this->db->query(SASQL::build_count($query,$this->db->driver));
			//$data = $tmp[0]->num;
			$data = $tmp[0][0];
		}
		// Updates
		elseif($query->type == 'update')
		{
			$data = $this->db->query(SASQL::build_update($query,$this->db->driver));
		}
		// Deletes
		elseif($query->type == 'delete')
		{
			$data = $this->db->query(SASQL::build_delete($query,$this->db->driver));
		}
		
		return $data;
	}
}