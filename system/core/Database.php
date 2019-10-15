<?php
if (!defined('SYS_ROOT')) {
	exit;
}

if (function_exists('mysqli_set_charset') === false) {
	/**
	* Sets the client character set.
	*
	* Note: This function requires MySQL 5.0.7 or later.
	*
	* @see http://www.php.net/mysql-set-charset
	* @param string $charset A valid character set name
	* @param resource $link_identifier The MySQL connection
	* @return TRUE on success or FALSE on failure
	*/
	function mysqli_set_charset($charset, $link_identifier = null)
	{
		global $dbConnection;
		if ($link_identifier == null) {
			return mysqli_query($dbConnection,'SET NAMES "'.$charset.'"');
		} else {
			return mysqli_query($dbConnection,'SET NAMES "'.$charset.'"', $link_identifier);
		}
	}
}

$dbConnection = null;
dbConnect();

function dbConnect()
{
	global $dbConnection;
	
	if (!$dbConnection) {
		$dbConnection = mysqli_connect (DB_HOST, DB_USER, DB_PASS, DB_NAME) 
			or die ('Ocorreu uma falha ao tentar estabelecer a conexão com o banco de dados.');
		
		dbSelect(DB_NAME)
			or die('Não foi possível selecionar o banco de dados.');
			
		mysqli_set_charset($dbConnection, MYSQL_CHARSET);

		return true;
	} else {
		return false;
	}
}
	
function dbDisconnect()
{
	global $dbConnection;
	
	if ($dbConnection) {
		mysqli_close($dbConnection);
		$dbConnection = null;
		return true;
	} else {
		return false;
	}
}
	
function dbSelect($dbName)
{
	global $dbConnection;
	return mysqli_select_db($dbConnection , $dbName);
}
	
function dbQuery($sql, &$totRows = false)
{
	global $dbConnection;
	
	if (!$dbConnection) {
		dbConnect();
	}
	
	$result = mysqli_query($dbConnection,$sql)
		or die("Erro ao executar comando SQL: ".$sql.mysqli_error($dbConnection));
	
	if ($totRows !== false) {
		$totRows = dbTotRows(true);
	}
	
	return $result;
}
	
function dbAffectedRows()
{
	global $dbConnection;
	
	return mysqli_affected_rows($dbConnection);
}
	
function dbInsertId()
{
	global $dbConnection;
	return mysqli_insert_id($dbConnection);
}
	
function dbFetchArray($result, $resultType = MYSQL_NUM)
{
	return mysqli_fetch_array($result, $resultType);
}
	
function dbFetchAssoc($result)
{
	return mysqli_fetch_assoc($result);
}
	
function dbFetchObject($result)
{
	return mysqli_fetch_object($result);
}
	
function dbFetchRow($result)
{
	return mysqli_fetch_row($result);
}
	
function dbFreeResult($result)
{
	return mysqli_free_result($result);
}
	
function dbNumRows($result)
{
	return mysqli_num_rows($result);
}

function dbTotRows($refresh = false)
{
	static $totRows;
	global $dbConnection;
	
	if ($refresh) {
		$result = mysqli_query($dbConnection, "SELECT found_rows() as totRows");
		$totRows = dbFetchObject($result)->totRows;
	}
	
	return $totRows;
}

function getConnection(){
	global $dbConnection;
	return $dbConnection;
}
?>