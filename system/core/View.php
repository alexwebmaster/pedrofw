<?php

abstract class View
{
	var $page;
	var $view;
	var $default;
	var $folder = '';
	
	function __construct()
	{
		$this->page = Page::getInstance();
		
		$this->getView();
		$this->processView();
	}
	
	function getView($refresh = false)
	{
		if (!$this->view || $refresh) {
			$view = isset($_GET['view']) ? trim($_GET['view']) : '';
			
			if (!$this->isValid($view) && $this->isValid($this->default)) {
				$view = $this->default;
			}
			
			$this->view = $view;
		}
		
		return $this->view;
	}
	
	static function getViewName()
	{
		$viewName = isset($_GET['modulo']) ? trim($_GET['modulo']) : '';
		
		return $viewName;
	}
	
	function processView()
	{
		$view	= $this->getView();
		$method	= $this->getMethod($view);
		
		if ($this->isValid($view)) {
			$this->$method();
		}
	}
	
	function getMethod($view)
	{
		return "_$view";
	}
	
	function isValid($view)
	{
		$method = $this->getMethod($view);
		
		return method_exists($this, $method);
	}
}