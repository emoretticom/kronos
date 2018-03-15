<link rel="stylesheet" type="text/css" href="vendor/emoretti/kronos/src/template/bootstrap.min.css">
<div class="container">
   <div class="alert alert-info text-center" role="alert">
      <h4 class="alert-heading">Kronos Report for <?= $this->_data['Main']['Name'] ?></h4>
      <hr>
      <div class="row">
         <div clasS="col-6">
            <p><strong>Start time:</strong>  <?=  $this->_data['Main']['StartDate'] ?> <br/><strong>&nbsp;Timestamp:</strong> <?=  $this->_data['Main']['StartTime'] ?> </p>
         </div>
         <div clasS="col-6">
            <p class="mb-0"><strong>End time:</strong> <?= $this->_data['Main']['EndDate'] ?> <br/><strong>&nbsp;Timestamp:</strong> <?=  $this->_data['Main']['EndTime'] ?> </p>
         </div>
      </div>
      <div class="row">
         <div clasS="col-12">
            <p><strong>* Server Execution Time:</strong> <?=  $this->_data['Main']['ServerExecutionTime'] ?> ms <br/></p>
            
            <p><strong>Total Execution Time:</strong> <?=  $this->_data['Main']['ExecutionTime'] ?> ms <br/></p>

            <p><strong>** Total Kronos Class Time Used : ≃</strong> <?=  $this->_kronosTime ?> ms <br/></p>
            
            <p><strong>** Total Execution Time (excluding Kronos):</strong> <?=  $this->_data['Main']['ExecutionTime'] - $this->_kronosTime ?> ms <br/></p>
            
            <p class="text-left"><small>* Using $_SERVER['REQUEST_TIME_FLOAT'] as start time</small></p>
            <p class="text-left"><small>** N.B. Kronos attempts to calculate its execution time. But these times are to be considered as APPROXIMATE. Remember that : 0.001 ms == 1 µs (In 1 μs the light runs exactly 299.792458 meters)</small></p>
         </div>
      </div>
   </div>
   <div class="alert alert-info text-center" role="alert">
      <h4 class="alert-heading">CheckPoints Statistics</h4>
      <hr>
      <div class="row">
         <div clasS="col-6">
            <p><strong>Total checkPoints execution time:</strong> <?= $this->_data['CheckPointsGeneral']['TotalExecutionTime'] ?> ms<br/></p>
         </div>
         <div clasS="col-6">
            <p><strong>Total percentage checkPoints execution time:</strong> <?= $this->_data['CheckPointsGeneral']['TotalPercentageTime'] ?>% <br/></p>
         </div>
      </div>
   </div>
   <table class="table">
      <thead class="thead-dark">
         <tr>
            <th>#</th>
            <th>Name</th>
            <th>Stat time (timestamp)</th>
            <th>End time (timestamp)</th>
            <th>Start time (ms)</th>
            <th>End time (ms)</th>
            <th>Total execution time (ms)</th>
            <th>Percentage execution time</th>
         </tr>
      </thead>
      <tbody>
         <?php 
            if(isset($this->_data['CheckPoints'])){
               $i=0;
               foreach ($this->_data['CheckPoints'] as $k => $v) {
               echo ('
               <tr>
                  <th scope="row">'.$i.'</th>
                  <td>'. $k .'</td>
                  <td>'. $v['StartTime_ts'] .'</td>
                  <td>'. $v['EndTime_ts'] .'</td>
                  <td>'. $v['StartTime_ms'] .'</td>
                  <td>'. $v['EndTime_ms'].'</td>
                  <td>'. $v['ExecutionTime'].'</td>
                  <td>'. $v['PercentageExecutionTime'] .'%</td>
               </tr>
               ');
               $i++;
               }
            }else{
               echo '<tr><td colspan="8"><p class="alert alert-warning text-center">No checkpoints to display</p></td></tr>';
            }
         ?>
      </tbody>
   </table>
</div>
<footer class="container bg-light text-center">
   <p><b>Kronos V 1.0.0 </b>  - PHP Class developed by <a href="https://www.ettoremoretti.com">Ettore Moretti</a> < info{at}ettoremoretti.com > </p>
</footer>
