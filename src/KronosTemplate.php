<?php 
namespace emoretti\kronos;


class KronosTemplate
{
    protected $_file;
    protected $_data = array();
    protected $_kronosTime;

    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    public function set($value)
    {
        $this->_data = $value;
        return $this;
    }

    public function setKronosTimeUsed($value){
        $this->_kronosTime = $value;
    }

    public function render()
    {
        extract($this->_data);
        ob_start();
        include($this->_file);
        return ob_get_clean();
    }
}


?>
