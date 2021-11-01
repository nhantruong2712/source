<?
/**
 * SA Framework model Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class model{
    public $table=false;
    public $connect='default';
	/**
	 * Contains model values as column_name => value
	 *
	 * @var array
	 */
	private $attributes = array();
 
    function __construct($table=false,$connect='default'){
        $this->connect = $connect;
        if($table) $this->table = $table;
        elseif(!$this->table) $this->table = get_class($this);
    }
 
    public static function load($model_array,$table=false,$accept=array()){
        $model  = false;
        
        try{
            if($table)
            $model = new $table;
        }catch(\Exception $e){
            $model  = false;
        }
        if($model===false)
            $model = new self;
            
        $check = is_array($accept)&&count($accept)>0;
        foreach($model_array as $key=>$value){
            if(self::is_primary($key)&&!is_array($value)){
                if(!$check || ($check && in_array($key,$accept)))
                    $model->$key = $value;
            } 
        }
        if($table) $model->table = $table;
         
        return $model;
    }
    
    public function connect(){
        return $con = db::connection($this->connect);
    }
    
    public function table(){
        return $this->connect()->table($this->table);
    }
    
	/**
	 * Magic method which delegates to read_attribute(). This handles firing off getter methods,
	 * as they are not checked/invoked inside of read_attribute(). This circumvents the problem with
	 * a getter being accessed with the same name as an actual attribute.
	 *
	 * You can also define customer getter methods for the model.
	 *
	 * EXAMPLE:
	 * <code>
	 * class User extends Model {
	 *
	 *   # define custom getter methods. Note you must
	 *   # prepend get_ to your method name:
	 *   function get_middle_name() {
	 *     return $this->middle_name{0};
	 *   }
	 * }
	 *
	 * $user = new User();
	 * echo $user->middle_name;  # will call $user->get_middle_name()
	 * </code>
	 *
	 * If you define a custom getter with the same name as an attribute then you
	 * will need to use read_attribute() to get the attribute's value.
	 * This is necessary due to the way __get() works.
	 *
	 * For example, assume 'name' is a field on the table and we're defining a
	 * custom getter for 'name':
	 *
	 * <code>
	 * class User extends model {
	 *
	 *   # INCORRECT way to do it
	 *   # function get_name() {
	 *   #   return strtoupper($this->name);
	 *   # }
	 *
	 *   function get_name() {
	 *     return strtoupper($this->read_attribute('name'));
	 *   }
	 * }
	 *
	 * $user = new User();
	 * $user->name = 'bob';
	 * echo $user->name; # => BOB
	 * </code>
	 *
	 *
	 * @see read_attribute()
	 * @param string $name Name of an attribute
	 * @return mixed The value of the attribute
	 */
	public function &__get($name)
	{
		// check for getter
		if (method_exists($this, "get_$name"))
		{
			$name = "get_$name";
			$value = $this->$name();
			return $value;
		}
        try{
            return $this->read_attribute($name);
        }catch(SAException $e){
            //return core::dump($e);
            $value = false;
            return $value;
        }
	}

	/**
	 * Determines if an attribute exists for this {@link Model}.
	 *
	 * @param string $attribute_name
	 * @return boolean
	 */
	public function __isset($attribute_name)
	{
		return array_key_exists($attribute_name,$this->attributes);
	}

	/**
	 * Magic allows un-defined attributes to set via $attributes.
	 *
	 * You can also define customer setter methods for the model.
	 *
	 * EXAMPLE:
	 * <code>
	 * class User extends model {
	 *
	 *   # define custom setter methods. Note you must
	 *   # prepend set_ to your method name:
	 *   function set_password($plaintext) {
	 *     $this->encrypted_password = md5($plaintext);
	 *   }
	 * }
	 *
	 * $user = new User();
	 * $user->password = 'plaintext';  # will call $user->set_password('plaintext')
	 * </code>
	 *
	 * If you define a custom setter with the same name as an attribute then you
	 * will need to use assign_attribute() to assign the value to the attribute.
	 * This is necessary due to the way __set() works.
	 *
	 * For example, assume 'name' is a field on the table and we're defining a
	 * custom setter for 'name':
	 *
	 * <code>
	 * class User extends model {
	 *
	 *   # INCORRECT way to do it
	 *   # function set_name($name) {
	 *   #   $this->name = strtoupper($name);
	 *   # }
	 *
	 *   function set_name($name) {
	 *     $this->assign_attribute('name',strtoupper($name));
	 *   }
	 * }
	 *
	 * $user = new User();
	 * $user->name = 'bob';
	 * echo $user->name; # => BOB
	 * </code>
	 *
	 * @throws {@link Exception} if $name does not exist
	 * @param string $name Name of attribute, relationship or other to set
	 * @param mixed $value The value
	 * @return mixed The value
	 */
	public function __set($name, $value)
	{
		if (method_exists($this,"set_$name"))
		{
			$name = "set_$name";
			return $this->$name($value);
		}

		//if (array_key_exists($name,$this->attributes))
			return $this->assign_attribute($name,$value);

		throw new SAException($name);
	}

	/**
	 * Assign a value to an attribute.
	 *
	 * @param string $name Name of the attribute
	 * @param mixed &$value Value of the attribute
	 * @return mixed the attribute value
	 */
	public function assign_attribute($name, $value)
	{
		if ($value instanceof DateTime)
			$value = new DateTime($value->format('Y-m-d H:i:s T'));

		if(self::is_primary($name)) {
		  $this->attributes[$name] = $value;
		  return $value;
        }else return false;
	}
 
    private static function is_primary($key){
        return substr($key,0,1) != '_';
    }
	/**
	 * Retrieves an attribute's value or a relationship object based on the name passed. If the attribute
	 * accessed is 'id' then it will return the model's primary key no matter what the actual attribute name is
	 * for the primary key.
	 *
	 * @param string $name Name of an attribute
	 * @return mixed The value of the attribute
	 * @throws {@link Exception} if name could not be resolved to an attribute, ...
	 */
	public function &read_attribute($name)
	{
		// check for attribute
		if (array_key_exists($name,$this->attributes) && self::is_primary($name))
			return $this->attributes[$name];

		//do not remove - have to return null by reference in strict mode
		$null = null;
		throw new SAException($name. ' key isn\'t exists');
	}

	/**
	 * Returns a copy of the model's attributes hash.
	 *
	 * @return array A copy of the model's attribute data
	 */
	public function attributes()
	{
		return $this->attributes;
	}
 
	/**
	 * Enables the use of dynamic finders.
	 *
	 * Dynamic finders are just an easy way to do queries quickly without having to
	 * specify an options array with conditions in it.
	 *
	 * <code>
	 * SomeModel::find_by_first_name('Tito');
	 * SomeModel::find_by_first_name_and_last_name('Tito','the Grief');
	 * SomeModel::find_by_first_name_or_last_name('Tito','the Grief');
	 * SomeModel::find_all_by_last_name('Smith');
	 * SomeModel::count_by_name('Bob')
	 * SomeModel::count_by_name_or_state('Bob','VA')
	 * SomeModel::count_by_name_and_state('Bob','VA')
	 * </code>
	 *
	 * You can also create the model if the find call returned no results:
	 *
	 * <code>
	 * Person::find_or_create_by_name('Tito');
	 *
	 * # would be the equivalent of
	 * if (!Person::find_by_name('Tito'))
	 *   Person::create(array('Tito'));
	 * </code>
	 *
	 * Some other examples of find_or_create_by:
	 *
	 * <code>
	 * Person::find_or_create_by_name_and_id('Tito',1);
	 * Person::find_or_create_by_name_and_id(array('name' => 'Tito', 'id' => 1));
	 * </code>
	 *
	 * @param string $method Name of method
	 * @param mixed $args Method args
	 * @return Model
	 * @throws {@link Exception} if invalid query
	 * @see find
	 */
	public function __call($method, $args)
	{
	   
        //if(isset($this->$method)){
        //    return call_user_func_array(array($this,$method),$args);    
        //}
          
		$create = false;
        $table = $this->table();
        $select = '*';
        
        //var_dump($method.'-');
       // var_dump(method_exists($this, $method),isset($this->$method));
        
        //var_dump(get_class($this)); 
        
        //$class = new ReflectionClass($this);
        //$methods = $class->getStaticProperties (); var_dump($methods);
         
		if (substr($method,0,7) === 'find_by')
		{
			$attributes = substr($method,8);
            $case = 0;
        }elseif (substr($method,0,11) === 'find_all_by')
		{
		    $attributes = substr($method,12);
            $case = 1;  
        }elseif (substr($method,0,8) === 'count_by')
		{
		    $attributes = substr($method,9);
            $select = 'count(*)'; 
            $case= 2;
        }
        if(!empty($attributes)){
            $attributes =  self::_build($attributes,$condition);
            
            $table = $table->select($select);
            if($condition){  
                $table = $table->where($attributes[0],'=',$args[0]);
                $table->clause($condition)->where($attributes[1],'=',$args[1]);
            }else{
                $table = $table->where($attributes,'=',$args[0]);
            }
    		$table = $table->execute(empty($this->table)?false:$this->table); //added 04/12/2020
            return $case==2?$table[0][0]:($case==0?((is_array($table)&&count($table)>0)?$table[0]:false):$table);
        }
	}
 

	/**
	 * Alias for self::find('all').
	 *
	 * @see find
	 * @return array array of records found
	 */
	public function all(/* ... */)
	{
	    $agrs = func_get_args();
		return call_user_func_array(array($this,'find'),array_merge(array('all'),$agrs));
	}

	/**
	 * Get a count of qualifying records.
	 *
	 * <code>
	 * YourModel::count( array( array('amount','>','3.14159265') ));
	 * </code>
	 *
	 * @see find
	 * @return int Number of records that matched the query
	 */
	public function count(/* ... */)
	{
		$args = func_get_args();
        $options['select'] = 'COUNT(*) as count';
        if(count($args)>0){
    		$options = array_merge($options,self::extract_and_validate_options($args));
 
    		if (!empty($args) && !is_null($args[0]) && !empty($args[0]))
    		{
    			//if (utils::is_hash($args[0]))
    				$options['conditions'] = $args[0];
    			//else
    				//throw new SAException('Hash required');
    		}
        }
		$table = $this->table();
        $table = $table->select($options['select']);
        $count = 0;
        if(count($args)>0){
            foreach($options['conditions'] as $condition){
                $table = $table->where($condition[0],$condition[1],$condition[2]);
                if($count++<count($options['conditions'])-1) $table = $table->clause('AND');
            } 
        }         
        $res = $table->execute(empty($this->table)?false:$this->table); //added 04/12/2020
		return $res?intval($res[0]->count):0; //fixed 05/27/2020
	}

	/**
	 * Determine if a record exists.
	 *
	 * @see find
	 * @return boolean
	 */
	public function exists(/* ... */)
	{
	    $args = func_get_args();
		return call_user_func_array(array($this,'count'),$args) > 0 ? true : false;
	}

	/**
	 * Alias for self::find('first').
	 *
	 * @see find
	 * @return Model The first matched record or null if not found
	 */
	public function first(/* ... */)
	{
	    $args = func_get_args();
		return call_user_func_array(array($this,'find') ,array_merge(array('first'),$args));
	}

	/**
	 * Alias for self::find('last')
	 *
	 * @see find
	 * @return Model The last matched record or null if not found
	 */
	public function last(/* ... */)
	{
	    $args = func_get_args();
		return call_user_func_array(array($this,'find'),array_merge(array('last'),$args));
	}

	/**
	 * Find records in the database.
	 *
	 * Finding by the primary key:
	 *
	 * <code>
	 * # queries for the model with id=123
	 * YourModel::find(123);
	 *
	 * # queries for model with id in(1,2,3)
	 * YourModel::find(1,2,3);
	 *
	 * # finding by pk accepts an options array
	 * YourModel::find(123,array('order' => 'name desc'));
	 * </code>
	 *
	 * Finding by using a conditions array:
	 *
	 * <code>
	 * YourModel::find('first', array('conditions' =>array( array('name','=','Tito')),
	 *   'order' => array('name','asc')))
	 * </code>
	 *
	 *
	 * @throws {@link Exception} if no options are passed or finding by pk and no records matched
	 * @return mixed An array of records found if doing a find_all otherwise a
	 *   single Model object or null if it wasn't found. NULL is only return when
	 *   doing a first/last find. If doing an all find and no records matched this
	 *   will return an empty array.
	 */
	public function find(/* $type, $options */)
	{
		$class = get_class($this);

		if (func_num_args() <= 0)
			throw new SAException("Couldn't find $class without an ID");

		$args = func_get_args();
        $options = self::extract_and_validate_options($args);
		$num_args = count($args);
		$single = true;
        $table = $this->table();
		if ($num_args > 0 && ($args[0] === 'all' || $args[0] === 'first' || $args[0] === 'last'))
		{
			switch ($args[0])
			{
				case 'all':
					$single = false;
					break;

			 	case 'last':
                    $options['order'] = array('id','desc');

			 	case 'first':
			 		$options['limit'] = 1;
			 		$options['offset'] = 0;
			 		break;
			}

			$args = array_slice($args,1);
			$num_args--;
		}

        $table = $table->select('*');
        $count = 0;
         
        if(isset($options['conditions'])){
            foreach($options['conditions'] as $condition){
                $table = $table->where($condition[0],$condition[1],$condition[2]);
                if($count++<count($options['conditions'])-1) $table = $table->clause('AND');
            }   
        }
        if(isset($options['order'])) $table = $table->order_by($options['order'][0],$options['order'][1]);
        if(isset($options['limit'])) 
            $table = $table->limit($options['limit'])->offset((array_key_exists('offset',$options))?$options['offset']:0);  
		$list = $table->execute(empty($this->table)?false:$this->table); //added 04/12/2020

		return $single ? (!empty($list) ? $list[0] : null) : $list;
	}

    public function update($where=false,$primary = 'id'){
        if(array_key_exists($primary,$this->attributes)) 
            return db($this->table)->update($this->attributes)->where($primary,'=',$this->attributes[$primary])->execute();
        elseif($where)
            return db($this->table)->update($this->attributes)->where($where[0],$where[1],$where[2])->execute();
        else throw new SAException('Condition not exists!');
    }
    
    public function insert($query=false,$primary = 'id'){
        return db($this->table)->insert($this->attributes,$query,$primary);
    }   
    /*
    public function delete(){
        if(isset($this->id)){
            return $this->table()->delete('id','=',$this->id);
        }else throw new SAException('Id not exists!');
    } */
    
    public function delete($where=false,$primary = 'id'){
        if(isset($this->$primary)){
            return $this->table()->delete($primary,'=',$this->$primary);
        }elseif($where)
            return $this->table()->delete($where[0],$where[1],$where[2]);
        else throw new SAException('Id not exists!');
    }
    
    public function select(){
        if($this->id){
            $res = $this->table()->select('id','=',$this->id);
            $res = $res[0];
            $res->table = get_class($this);
            return $res;
        }else throw new SAException('Id not exists!');        
    }
 
	/**
	 * Determines if the specified array is a valid options array.
	 *
	 * @param array $array An options array
	 * @param bool $throw True to throw an exception if not valid
	 * @return boolean True if valid otherwise valse
	 * @throws {@link Exception} if the array contained any invalid options
	 */
	public static function is_options_hash($array, $throw=true)
	{
		if (utils::is_hash($array))
		{
				return true;
		}
		return false;
	}
    
	/**
	 * Pulls out the options hash from $array if any.
	 *
	 * @internal DO NOT remove the reference on $array.
	 * @param array &$array An array
	 * @return array A valid options array
	 */
	public static function extract_and_validate_options(array &$array)
	{
		$options = array();
 
		if ($array)
		{
			$last = &$array[count($array)-1];
 
			try
			{
				if (self::is_options_hash($last))
				{
					array_pop($array);
					$options = $last;
				}
			}
			catch (SAException $e)
			{
				if (!utils::is_hash($last))
					throw $e;

				$options = array('conditions' => $last);
			}
		}
 
		return $options;
	}
 
    private static function _build($attributes,&$condition=false){
        if(strpos($attributes,'_or_')===false &&strpos($attributes,'_and_')===false){
            return $attributes;
        }else{
            preg_match("/^(.*?)\_(or|and)\_(.*?)$/",$attributes,$match);
            $res = array($match[1],$match[3]);
            $condition = strtoupper(trim($match[2]));
            return $res;
        }
    }
    
    function __toString(){
        return core::dump($this->attributes);
    }
 
    public function _exists(){
        return count($this->attributes) > 0;
    }
    
    public static function map($list_models,$val = false,$key = false){
        return is_array($list_models) ? 
                   ($key === false ?
                   array_map(
                       create_function(
                           '$list',
                           'if(!is_array($list))$res = $list->attributes();else $res=$list;'.
                           ($val===false?'':'$res = $res[\''.$val.'\'];').
                           'return $res;'
                       ),$list_models
                   ) : array_combine( 
                        ($mval = self::map($list_models,$val)),
                        $key==$val ? $mval : self::map($list_models,$key) 
                   ) 
        ): false;
    }
    
    public static function map2($list_models,$key = 'id'){
        $res = array();
        if(!$list_models) return array();
        
        foreach($list_models as $v){  
            if(!isset($v->$key) && !isset($v[$key])) return array();
            $cc = !isset($v->$key)?$v[$key]:$v->$key;
            $res[$cc] = $v;
        }
        return $res;
    }
    
    public static function walk($list_models,$callback){
        
    }
    
    public function loadFromModel($model){
        foreach($model->attributes() as $k=>$z){
            $this->$k = $z;
        }
        return $this;
    }
    
    public static function extend($model,$a,$table=false){
        if(empty($model->table)){
            if(!$table &&$a instanceof model){
                $table = $a->table;
            }
            $model = model::load($model,$table);
        }  //var_dump($model);die();
        foreach($a as $key=>$value){ 
            if($key[0]=='_') continue;
            $model->$key = $value;            
        }
        if($table) $model->table = $table;
         
        return $model;
    }
    
    public function __unset($name)
    {
        //echo "Unsetting '$name'\n";
        unset($this->attributes[$name]);
    }
     
}