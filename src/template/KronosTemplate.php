
<link rel="stylesheet" type="text/css" href="src/template/bootstrap.min.css">
<div class="container-fluid">
   <div class="alert alert-info text-center" role="alert">
      <h1 class="alert-heading">Kronos Report for <?= $this->_data['Main']['Name'] ?></h1>
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

            <p><strong>Memory usage:</strong> <?=  $this->_data['Main']['MemoryUsage'] ?> <br/></p>
            
            <p><strong>Total Execution Time:</strong> <?=  $this->_data['Main']['ExecutionTime'] ?> ms <br/></p>

            <p><strong>** Total Kronos Class Time Used : ≃</strong> <?=  $this->_kronosTime ?> ms <br/></p>
            
            <p><strong>** Total Execution Time (excluding Kronos):</strong> <?=  $this->_data['Main']['ExecutionTime'] - $this->_kronosTime ?> ms <br/></p>
               
            <hr>

            <h2>Objects declared / Files included</h2>

            <p>
               <a class="btn btn-primary" data-toggle="collapse" href="#objectDeclared" role="button" aria-expanded="false" aria-controls="objectDeclared">Objects declared
                  <span class="badge badge-pill badge-light"><?= $this->_data['Main']['CountObjectsDeclared'] ?></span>
               </a>
               <a class="btn btn-primary" data-toggle="collapse" href="#fileIncluded" role="button" aria-expanded="false" aria-controls="fileIncluded">Files included
                  <span class="badge badge-pill badge-light"><?= $this->_data['Main']['CountFilesIncluded'] ?></span>
               </a>
            </p>
            
            <div class="collapse multi-collapse" id="objectDeclared">
             <div class="card card-body">
                  <h5>Objects declared</h5>
                   <ul class="list-group">
                     <?php 

                        foreach ($this->_data['Main']['ObjectsDeclared'] as $obj) {
                           echo '<li class="list-group-item text-left">'. $obj .'</li>';
                        }

                     ?>
                  </ul>
             </div>
            </div>
             <hr>
             <div class="collapse multi-collapse" id="fileIncluded">
                <div class="card card-body">
                    <h5>File included</h5>
                   <ul class="list-group">
                     <?php 

                        foreach ($this->_data['Main']['FilesIncluded'] as $obj) {
                           echo '<li class="list-group-item text-left">'. $obj .'</li>';
                        }

                     ?>
                  </ul>
                </div>
             </div>

            <p class="text-left"><small>* Using $_SERVER['REQUEST_TIME_FLOAT'] as start time</small></p>
            <p class="text-left"><small>** N.B. Kronos attempts to calculate its execution time. But these times are to be considered as APPROXIMATE. Remember that : 0.001 ms == 1 µs (In 1 μs the light runs exactly 299.792458 meters)</small></p>
         </div>
      </div>
   </div>
   <div class="alert alert-info text-center" role="alert">
      <h2 class="alert-heading">CheckPoints Statistics</h2>
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
            <th>Memory usage</th>
            <th>Objects declared</th>
            <th>File included</th>
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
                  <td>'. $v['MemoryUsage'] .'</td>
                  <td>
                     <a class="btn btn-primary" data-toggle="collapse" href="#objectDeclared_'.$k.'" role="button" aria-expanded="false" aria-controls="objectDeclared_'.$k.'">Objects declared
                        <span class="badge badge-pill badge-light">'. $v['CountObjectsDeclared'] .'</span>
                     </a>
                  </td>
                  <td>
                     <a class="btn btn-primary" data-toggle="collapse" href="#fileIncluded_'.$k.'" role="button" aria-expanded="false" aria-controls="fileIncluded_'.$k.'">File Icluded
                        <span class="badge badge-pill badge-light">'. $v['CountFilesIncluded'] .'</span>
                     </a>
                  </td>
               </tr>
               <tr class="collapse multi-collapse" id="objectDeclared_'.$k.'">
                  <td colspan="11">
                      <div class="card card-body bg-light text-dark">
                           <h5>Objects declared</h5>
                            <ul class="list-group">');

                        foreach ($v['ObjectDeclared'] as $obj) {
                                 echo '<li class="list-group-item text-left">'. $obj .'</li>';
                        }
                     echo ('
                           </ul>
                      </div>
                  </td>
               </tr>
                <tr  class="collapse multi-collapse" id="fileIncluded_'.$k.'">
                  <td colspan="11">
                      <div class="card card-body bg-light text-dark">
                           <h5>File included</h5>
                            <ul class="list-group">');

                        foreach ($v['FilesIncluded'] as $obj) {
                                 echo '<li class="list-group-item text-left">'. $obj .'</li>';
                        }
                     echo ('
                           </ul>
                      </div>
                  </td>
               </tr>
               ');
               $i++;
               }
            }else{
               echo '<tr><td colspan="11"><p class="alert alert-warning text-center">No checkpoints to display</p></td></tr>';
            }
         ?>
      </tbody>
   </table>
</div>
<footer class="container bg-light text-center">
   <p><b>Kronos V 1.0.0 </b>  - PHP Class developed by <a href="https://www.ettoremoretti.com">Ettore Moretti</a> < info{at}ettoremoretti.com > </p>
</footer>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
