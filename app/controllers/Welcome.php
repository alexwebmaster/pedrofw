<?php
class Welcome extends Controller
{
	function __construct(){
		parent::__construct();
	}
    
    public function index(){
    	echo 'index loaded';
    }	
}