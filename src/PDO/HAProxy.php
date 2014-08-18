<?php

namespace CSI\Drivers\PDO;

use \PDO as BasePDO;



class HAProxy {


	private $master   = FALSE;
	private $readonly = FALSE;
	private $currentPDO;


    private $haproxy = array();


	public function __construct(BasePDO $optionalPDO = null )	{
	    if ($optionalPDO instanceof BasePDO){
            $this->master = $optionalPDO;
        }
	    return true;
	}
	
	public function CreateMaster($dsn, $user, $password)	{
		$this->master = new BasePDO($dsn, $user, $password, array_merge(array(BasePDO::ATTR_PERSISTENT=>FALSE),$this->haproxy));
		$this->currentPDO = $this->master;
	}
	
	public function CreateReadonly($dsn, $user, $password) {
		$this->readonly = new BasePDO($dsn, $user, $password, array_merge(array(BasePDO::ATTR_PERSISTENT=>FALSE),$this->haproxy));
	}

    /**
     * @param BasePDO::Key
     * @param mixed $value
     */
    public function SetAttr($key,$value){
        $this->haproxy[$key] = $value;
    }
	
	public function __call($name, $arguments)	{
		switch($name) {
			case 'setAttribute':
				return $this->CallBoth($name, $arguments);
				break;
			case 'quote':
				return $this->CallReadonly($name, $arguments);
				break;
			case 'Execute':
				return $this->ChooseBySQLAndCall($arguments[0], $name, $arguments);
				break;
			case 'prepare':
				return $this->ChooseBySQLAndCall($arguments[0], $name, $arguments);
				break;
			default:
				return $this->CallMaster($name, $arguments);
				break;
		}
	}

    /**
     * @param $sql
     * @param $name
     * @param $arguments
     * @return BasePDO
     */
    private function ChooseBySQLAndCall($sql, $name, $arguments) {

        $sql = trim($sql);
            // select to readonly!
        if(stripos($sql, 'SELECT') === 0 || stripos($sql, 'ROW_COUNT')) {
            return $this->CallReadonly($name, $arguments);
            //everything else to master
        } else if(stripos($sql, 'INSERT') === 0 || stripos($sql, 'UPDATE') === 0
            || stripos($sql, 'LAST_INSERT_ID') || stripos($sql, 'ROW_COUNT')) {
            return $this->CallMaster($name, $arguments);
        } else {
            return $this->CallMaster($name, $arguments);
        }
    
	}

    /**
     * @param $name
     * @param $arguments
     * @return BasePDO
     */
	private function CallBoth($name, $arguments) {
		$this->CallMaster($name, $arguments);
		$this->CallReadonly($name, $arguments);
	}

    /**
     * @param $name
     * @param $arguments
     * @return BasePDO
     */
	private function CallMaster($name, $arguments)	{
        if($this->master === FASLE) throw new \PDOException("No database found",E_ERROR);
		return call_user_func_array(array($this->master,$name), $arguments);
	}

    /**
     * @param $name
     * @param $arguments
     * @return BasePDO
     */
    private function CallReadonly($name, $arguments)	{

		return call_user_func_array(array($this->readonly ? $this->readonly : $this->master,$name), $arguments);
	}	
	
}
