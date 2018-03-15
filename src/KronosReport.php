<?php 
namespace emoretti\kronos;

use emoretti\kronos\KronosTemplate;

require_once(__DIR__ . "/KronosTemplate.php");

class KronosReport 
{

	private $dateFormat;
	private $name;
	private $data;

	
	function __construct(string $reportName, array $reportData, string $dateFormat)
	{
		$this->name = $reportName;
		$this->data = $reportData;
		$this->dateFormat = $dateFormat;
	}

	function renderReport(){
		 $KT = new KronosTemplate(__DIR__ . "/template/KronosTemplate.php");
		 $KT->set($this->getRawData());
		 $KT->setKronosTimeUsed($this->data['KronosTimeUsed']);
		 echo $KT->render();
	}

	function getRawData(){

		//Main statistics
		$OUT = [
			"Main" => [
				"Name" => $this->name,
				"StartTime"	=> $this->data['MainStart'],
				"StartDate" => $this->udate($this->dateFormat, $this->data['MainStart']),
				"EndTime" 	=> $this->data['MainEnd'],
				"EndDate" 	=> $this->udate($this->dateFormat, $this->data['MainEnd']),
				"ServerExecutionTime" => $this->getTimeDiff($this->data['ServerStartTime'],$this->data['MainEnd']),
				"ExecutionTime" => $this->getTimeDiff($this->data['MainStart'],$this->data['MainEnd']),
				"ObjectsDeclared" => $this->data['ObjectSnapshots'][$this->name]['Declared_classes'],
				"CountObjectsDeclared" => count($this->data['ObjectSnapshots'][$this->name]['Declared_classes']),
				"FilesIncluded" => $this->data['ObjectSnapshots'][$this->name]['Included_files'],
				"CountFilesIncluded" => count( $this->data['ObjectSnapshots'][$this->name]['Included_files']),
				"MemoryUsage" =>  $this->convertSize($this->data['ObjectSnapshots'][$this->name]['Memory_usage']),
			]
		];

		//CheckPoint statistics
		$chekcPoints_tot_perc = 0.0;
		$checkPoints_tot_time = 0.0;
		foreach ($this->data['CheckPoints'] as $k => $cp) {
			$OUT['CheckPoints'][$k] = [
				"StartTime_ts"	=> $cp['StartTime'],
				"StartTime_ms" => $this->getTimeDiff($OUT['Main']['StartTime'],$cp['StartTime']),
				"EndTime_ts" 	=> $cp['EndTime'],
				"EndTime_ms" 	=> $this->getTimeDiff($OUT['Main']['StartTime'],$cp['EndTime']),
				"ExecutionTime" => $this->getTimeDiff($cp['StartTime'],$cp['EndTime']),
				"PercentageExecutionTime" => $this->getPercTimeDiff($OUT['Main']['ExecutionTime'],$this->getTimeDiff($cp['StartTime'],$cp['EndTime'])),
				"ObjectDeclared" => $this->data['ObjectSnapshots'][$k]['Declared_classes'],
				"CountObjectsDeclared" => count($this->data['ObjectSnapshots'][$k]['Declared_classes']),
				"FilesIncluded" => $this->data['ObjectSnapshots'][$k]['Included_files'],
				"CountFilesIncluded" => count( $this->data['ObjectSnapshots'][$k]['Included_files']),
				"MemoryUsage" => $this->convertSize($this->data['ObjectSnapshots'][$k]['Memory_usage'])
			];
			$checkPoints_tot_time += $OUT['CheckPoints'][$k]['ExecutionTime'];
			$chekcPoints_tot_perc += $OUT['CheckPoints'][$k]['PercentageExecutionTime'];
		}

		//General checkpoint statistics
		
		$OUT['CheckPointsGeneral'] = [
			"TotalExecutionTime" => $checkPoints_tot_time,
			"TotalPercentageTime" => $chekcPoints_tot_perc
		];
	
		return $OUT;
	}

	private function getTimeDiff($start,$end){
		return round(($end-$start)*1000,3);
	}

	private function getPercTimeDiff($tot_time,$perc_time){
		return round((($perc_time/$tot_time)*100),2);
	}

	private function udate($format = 'u', $utimestamp = null) {
        if (is_null($utimestamp))
            $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }
	
	private function convertSize($size)
	{
	    $unit=array('b','kb','mb','gb','tb','pb');
	    return @round($size/pow(1024,($i=floor(log(floatval($size),1024)))),2).' '.$unit[$i];
	}
}
?>