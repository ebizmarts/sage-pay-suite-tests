<?php

class SagePayConfig extends SagePayCoreConfig{

    public function __construct(){
        parent::__construct();
    }

    public function useDirectWith3D(){

        $config = new stdClass();
        $config->sagepaydirectpro = new stdClass();

        $config->sagepaydirectpro->secure3d = 3;
        $config->sagepaydirectpro->threed_layout = 'redirect';

        return $this->setConfig($config);
    }

    public function useToken($method = 'direct'){

        $config = new stdClass();
        $config->sagepaydirectpro = new stdClass();

        $config->sagepaydirectpro->token_integration = $method;

        return $this->setConfig($config);
    }

    public function useDirect(){
        $config = new stdClass();

        $config->sagepaydirectpro = new stdClass();

        $config->sagepaydirectpro->active = 1;

        return $this->setConfig($config);
    }



}

class SagePayCoreConfig{

    public function __construct(){

        $dbHost = '127.0.0.1';
        $dbUser = 'root';
        $dbPass = 'q1w2e3r4';
        $dbName = 'magento02';

        $key = 'mojzEjM8ZEEAQ';

        $this->db = new mysqli($dbHost,$dbUser,$dbPass,$dbName);

        $this->default = simplexml_load_file('tests/selenium/ConfigData.xml');
        $this->default[0]->sagepaysuite->license_key = $key;

    }

    protected function generateQuery($data){

        $query = 'UPDATE core_config_data SET value = CASE ';
        $when = '';
        $in = '';

        foreach($data as $configTab => $configFields){
            foreach($configFields as $field => $value){

                if($value == ''){
                    $value = 'NULL';
                }

                $when .= ' WHEN path = "payment/'.$configTab.'/'.$field.'" THEN "'.$this->db->escape_string($value).'"';

                $in .= '"payment/'.$configTab.'/'.$field.'",';
            }
        }

        $in = rtrim($in,',');

        $query .= $when.' END ';
        $query .= ' WHERE path IN ('.$in.')';



        return $query;
    }

    public function resetConfig(){

        $query = $this->generateQuery($this->default[0]);

        $result = $this->db->query($query);

        if($result === FALSE){
            throwException('Failed to reset Sage Pay configurtion.');
            return FALSE;
        }

        return TRUE;
    }

    public function setConfig($data){ //same organization as in the xml

        $query = $this->generateQuery($data);

        $result = $this->db->query($query);

        if($result === FALSE){
            throwException('Failed to reset Sage Pay configurtion.');
            return FALSE;
        }

        return TRUE;

    }

    public function setSingleConfig($path,$value){

        $query = 'UPDATE core_config_data SET value = "'.$this->db->escape_string($value).'" WHERE path = "'.$path.'"';

        $result = $this->db->query($query);

        if($result === FALSE){
            throwException('Failed to set Sage Pay configurtion.');
            return FALSE;
        }

        return TRUE;

    }




}
