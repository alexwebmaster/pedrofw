<?php if (!defined('SYS_ROOT')) exit;

abstract class DBObject
{
	protected $relationships= array();
	protected $vars			= array();
	
	function addVar($vName, $vValue, $flagInsert, $flagUpdate, $flagIdentity, $vClass = '')
	{
		$newVar = array(
			// Properties
			'value'				=> $vValue,
			'flagInsert'		=> $flagInsert,
			'flagUpdate'		=> $flagUpdate,
			'flagIdentity'		=> $flagIdentity
		);
		
		$this->vars[$vName] = $newVar;
		
		if ($vClass) {
			$this->addRelationship($vName, $vClass);
		}
	}
	
// RELATIONSHIPS
	
	function addRelationship($rName, $rClass)
	{
		$newRelation = array(
			'class'		=> $rClass,
			'reload'	=> false
		);
		
		$this->relationships[$rName] = $newRelation;
	}
	
	function isRelationship($attr) {
		return (array_key_exists($attr, $this->relationships));
	}
	
	function setRelationshipValue($attr, $value) {
		$rel = &$this->relationships[$attr];
		
		$this->setValue($attr, $value);
		if (!($value instanceof $rel['class'])) {
			$rel['reload'] = true;
		}
	}
	
	function loadRelationshipValue($attr) {
		$rel = $this->relationships[$attr];

		$value = $this->getValue($attr);
		if (!is_null($value) && $rel['reload']) {
			$this->setValue($attr, call_user_func_array(array($rel['class'], 'getInstance'), array((string)$value)));
			$rel['reload'] = false;
		}
	}
	
// SETs & GETs
	
	function __set($attr, $value)
	{
		if ($this->isRelationship($attr)) {
			$this->setRelationshipValue($attr, $value);
		} else {
			$this->setValue($attr, $value);
		}
	}
	
	function setValue($attr, $value)
	{
		if (isset($this->vars[$attr])) {
			$var = &$this->vars[$attr];
			
			$var['value'] = $value;
			
			return true;
		}
	}
	
	function __get($attr) {
		if ($this->isRelationship($attr)) {
			$this->loadRelationshipValue($attr);
		}
		
		return $this->getValue($attr);
	}
	
	function getValue($attr) {
		if (array_key_exists($attr, $this->vars)) {
			return $this->vars[$attr]['value'];
		} else {
			return null;
		}
	}
	
	function __toString() {
		return (string)$this->id;
	}
	
// DATABASE
	
	function insertQuery($table, $escapeVars = true, $fields = false)
	{
		$values = array();
		
		if (!is_array($fields)) {
			$fields = array();
			foreach ($this->vars as $varName => $var) {
				if ($var['flagInsert']) {
					// $fields[$varName] = $var['value'];
					$fields[$varName] = $this->$varName;
				}
			}
		}
		
		foreach ($fields as $key => $value) {
		// foreach (array_keys($fields) as $key) {
			// $value =  $this->$key;
			
			if ($escapeVars && !is_null($value) && !get_magic_quotes_gpc()) {
				escapeVars($value);
			}
			
			$values[] = "$key = ".(!is_null($value) ? "'$value'" : "NULL");
		}
		
		$sql  = "INSERT INTO $table SET ";
		$sql .= implode(", ", $values);

		return $sql;
	}
	
	function updateQuery($table, $escapeVars = true, $fields = false, $condition = '1=1')
	{
		$values = array();
		
		if (!is_array($fields)) {
			$fields = array();
			foreach ($this->vars as $varName => $var) {
				if ($var['flagUpdate']) {
					// $fields[$varName] = $var['value'];
					$fields[$varName] = $this->$varName;
				}
				if ($var['flagIdentity']) {
					// $condition .= " AND $varName = '".$var['value']."' ";
					$condition .= " AND $varName = '".$this->$varName."' ";
				}
			}
		}
		
		foreach ($fields as $key => $value) {
		// foreach (array_keys($fields) as $key) {
			// $value = $this->$key;
			
			if ($escapeVars && !is_null($value) && !get_magic_quotes_gpc()) {
				escapeVars($value);
			}
			
			$values[] = "$key = ".(!is_null($value) ? "'$value'" : "NULL");
		}
		
		$sql  = "UPDATE $table SET ";
		$sql .= implode(", ", $values);
		$sql .= " WHERE $condition";
		
		return $sql;
	}
	
	function deleteQuery($table, $condition = '1=1')
	{
		$values = array();
		
		$fields = array();
		foreach ($this->vars as $varName => $var) {
			if ($var['flagIdentity']) {
				$condition .= " AND $varName = '".$var['value']."' ";
			}
		}
		
		$sql  = "DELETE FROM $table WHERE $condition";
		
		return $sql;
	}
	
	function incluir($escapeVars = true) {
		$dbTable = constant(get_class($this).'::dbTable');
		
		if (isset($this->vars['id']) && $this->id) {
			trigger_error("Impossível incluir registro já existente em $dbTable", E_USER_WARNING);
		} else {
			$sql = $this->insertQuery($dbTable, $escapeVars);
			$result = dbQuery($sql);
			
			if (isset($this->vars['id'])) {
				$this->id = dbInsertId();
			}
			
			return true;
		}
	}

	function salvar($escapeVars = true)
	{
		$dbTable = constant(get_class($this).'::dbTable');
		
		$sql = $this->updateQuery($dbTable, $escapeVars);
		$result = dbQuery($sql);
		
		return true;
	}
	
	function excluir()
	{
		$dbTable = constant(get_class($this).'::dbTable');
		
		if (isset($this->vars['id']) && !$this->id) {
			trigger_error("Impossível excluir registro inexistente em $dbTable", E_USER_WARNING);
		} else {
			$sql = $this->deleteQuery($dbTable);
			dbQuery($sql);
			
			return true;
		}
	}
}
?>