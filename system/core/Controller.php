<?php

class Controller{

	function __construct(){}

	function parse_request()
	{
		if(!empty($_SERVER['REDIRECT_URL'])){

		    $url=$_SERVER['REDIRECT_URL'];
		    $url = substr($url, 1);
		    $base_url = explode("/", base_url());
		    $url_array = explode("/", $url);
		    if(!empty($base_url[1])){
		        if($base_url[1]==$url_array[0]){

		            $info=[];
		            for($i=1; $i<count($url_array); $i++){
		                array_push($info, $url_array[$i]);
		            }

		            $this->show_controller($info);
		        }else{
		            $info=[];
		            for($i=0; $i<count($url_array); $i++){
		                array_push($info, $url_array[$i]);
		            }
		            $this->show_controller($info);
		        }
		    }else{
		        $info=[];
		            for($i=0; $i<count($url_array); $i++){
		                array_push($info, $url_array[$i]);
		            }
		            $this->show_controller($info);
		    }
		}else{

		    $url=$_SERVER['REQUEST_URI'];
		    $url = substr($url, 1);
		    $url_array = explode("/", $url);


		    if($url_array[0]==BASE_URL){
		        $info=[];
		        array_push($info, DEFAULT_CONTROLLER);		        
		        $this->show_controller($info);
		    }else{
		        $info=[];
		        for($i=0; $i<count($url_array); $i++){
		            array_push($info, $url_array[$i]);
		        }
		        $this->show_controller($info);
		    }
		}
	}

    function show_controller($url_array)
    {
        if(file_exists("./".CONTROLLERS_DIR.$url_array[0].'.php')){
            require "./".CONTROLLERS_DIR.$url_array[0].'.php';

            $new_name = $url_array[0];
            $neww = new $new_name;

            if(empty($url_array[1])){
                $method="index";
                if(method_exists($neww, $method)==true){
                    call_user_func(array($neww,$method));
                    exit();
                }else{
                    die('Error : not found.');

                }

            }else if(!empty($url_array[1])&&empty($url_array[2])){
                $method = $url_array[1];
                if(method_exists($neww, $method)==true){
                    call_user_func(array($neww,$method));
                }
                else{
                    die('Error : not found.');
                }

            }else if(!empty($url_array[2])){
                    $method = $url_array[1];
                    $params=[];
                    for ($i=2; $i < count($url_array) ; $i++) { 
                        array_push($params, $url_array[$i]);
                    }

                    if(method_exists($neww, $method)==true){

                    call_user_func_array(array($neww,$method), $params);
                    exit();
                    }

                }

        }else{
            die('Error : not found.');

        }
    }
}