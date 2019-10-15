<?php if (!defined('SYS_ROOT')) exit;

class Request
{
	public $url;
	function __construct($routes)
	{
		$this->url = $_SERVER['REQUEST_URI'];
	}
}