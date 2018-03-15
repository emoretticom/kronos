<?php 
namespace emoretti\kronos;

use emoretti\kronos\KronosRepor;

use \ReflectionClass;

require_once (__DIR__ . "\KronosReport.php");

class Kronos 
{
	private $pageAlias;
	private $serverStartTime;
	private $mainStartTime;
	private $mainEndTime;
	private $objectSnapshot;
	private $checkPoints;
	private $classTimeUsed;

	protected const KRONOS_STARTED = 1;
	protected const KRONOS_ENDED = 0;


	public function __construct(string $pageAlias='', float $startTime = 0.0){
		$this->classTimeUsed = 0.0;
		$this->serverStartTime = $_SERVER['REQUEST_TIME_FLOAT'];
		$this->pageAlias = $pageAlias;
		$this->mainStartTime = ($startTime > 0.0 ) ? $startTime : microtime(true) ;
		$this->checkPoints = array();
	}

	public function setMainEnd(float $endTime = 0.0){
		foreach ($this->checkPoints as $k => $checkPoint) {
			if($checkPoint['Status'] != self::KRONOS_ENDED){
				$this->warning("WARNING!: The '" . $k ."' CheckPoint is already set and in STARTED mode!.You can't end all now!.");
				return;	
			}
		}

		$this->mainEndTime = ($endTime > 0.0 ) ? $endTime : microtime(true) ;
		$this->setObjectsAndSnapshot($this->pageAlias);
	}


	public function startCheckPoints(string $pointName, float $startTime = 0.0){
		$start = microtime(true);
		if(array_key_exists($pointName , $this->checkPoints)){
			$this->warning("WARNING!: The '" . $pointName ."' CheckPoint is already set and in STARTED mode!.You can't start it again!.");
			return;
		}

		$this->createCheckPoints($pointName, ($startTime > 0.0 ) ? $startTime : microtime(true));
		$end = microtime(true);
		$this->classTimeUsed += $this->getTimeDiff($start,$end);
	}

	public function stopCheckPoints(string $pointName, float $endTime = 0.0){
		$start = microtime(true);
		if(!array_key_exists($pointName , $this->checkPoints)){
			$this->warning("WARNING!: The '" . $pointName ."' CheckPoint doesn't exists!.");
			return;
		}

		if($this->checkPoints[$pointName]['Status'] == self::KRONOS_ENDED){
			$this->warning("WARNING!: The '" . $pointName ."' CheckPoint is already set and in ENDED mode!.You can't end it again!.");
			return;	
		}

		$this->checkPoints[$pointName]['EndTime'] = ($endTime > 0.0 ) ? $endTime : microtime(true);
		$this->checkPoints[$pointName]['Status'] = self::KRONOS_ENDED; 
		$this->setObjectsAndSnapshot($pointName);
		$end = microtime(true);
		$this->classTimeUsed += $this->getTimeDiff($start,$end);
	}

	private function setObjectsAndSnapshot($checkPointName){
			$dec_classes = get_declared_classes();
			$dec_classes = $this->get_declared_classes_from_project();

			$inc_files = get_included_files();
			$this->objectSnapshot[$checkPointName] = [
				"Declared_classes_count" => count($dec_classes),
				"Declared_classes" => $dec_classes,
				"Included_files_count" => count($inc_files),
				"Included_files" => $inc_files,
				"Memory_usage" => memory_get_usage()
			];
	}

	private function get_declared_classes_from_project(){
	    $classes = array();

	    $dc = get_declared_classes();

	    foreach ($dc as $class) {
	        $reflect = new ReflectionClass($class);
	        $filename = $reflect->getFileName();
	        if( ! $reflect->isInternal() ){
	            $filename = str_replace(array('\\'), array('/'), $filename);
	            $project_path = str_replace(array('\\'), array('/'), $_SERVER["DOCUMENT_ROOT"]);
	            $project_path = rtrim( $project_path, '/' );
	            if( stripos( $filename, $project_path ) !== false ){
	                $classes[] = $class;
	            }
	        }
	    }
	    return $classes;    
	}

	public function getReport($dateFormat = "d/m/Y H:i:s:u"){
		$report = new KronosReport($this->pageAlias, 
			[	
				"KronosTimeUsed" => $this->classTimeUsed,
				"ServerStartTime" => $this->serverStartTime,
				"MainStart" => $this->mainStartTime, 
				"MainEnd"   => $this->mainEndTime, 
				"ObjectSnapshots" => $this->objectSnapshot, 
				"CheckPoints" => $this->checkPoints
			], 
			$dateFormat
		);

		echo $report->renderReport();
	}

	public function getReportRaw($dateFormat = "d/m/Y H:i:s:u"){
		$report = new KronosReport($this->pageAlias, 
			[
				"KronosTimeUsed" => $this->classTimeUsed,
				"ServerStartTime" => $this->serverStartTime,
				"MainStart" => $this->mainStartTime, 
				"MainEnd"   => $this->mainEndTime, 
				"ObjectSnapshots" => $this->objectSnapshot, 
				"CheckPoints" => $this->checkPoints
			], 
			$dateFormat
		);

		return $report->getRawData();
	}

	private function createCheckPoints($pointName, $startTime){
		$this->checkPoints[$pointName] = [
			"Status" => self::KRONOS_STARTED,
			"StartTime" => $startTime,
			"EndTime" => 0.0
		];
	}

	private function warning($msg){
		trigger_error($msg, E_USER_WARNING);
	}

	private function getTimeDiff($start,$end){
		return round(($end-$start)*1000,3);
	}
}


?>