<!DOCTYPE html>
<html>
	<head>
		<title>Game of life</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	  	
	  	<link rel="stylesheet" href="bootstrap/bootstrap.min.css" />		
		<script src="bootstrap/jquery-3.3.1.slim.min.js"></script> 
		<script src="bootstrap/popper.min.js"></script>
		<script src="bootstrap/bootstrap.min.js"></script>		
		<script src="angularJs/angular.min.js"></script>		
		<link rel="stylesheet" href="style/style.css" />		
		<script src="myScript/game_of_life_script.js"></script>
		<script src="myScript/form_check_script.js"></script>
	</head>
	<body  id="app" ng-app="myApp" ng-controller="myCtrl" ng-init="addCanvas()" onload="getJSON()">
		<?php if(isset($_GET["file"]) && !empty($_GET["file"])) : ?>
    		<form name="get_file_form" id="get_file_form">
    			<input type="hidden" name="get_file" id="get_file" value="<?php echo htmlspecialchars( stripslashes( trim( $_GET['file']) ) ) ?>" />
    		</form>
		<?php endif; ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>Game of life</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-8" id="canvas-div"></div>
				<div class="col-lg-4" id="game-details">
					<div class="row">
						<div class="col-sm-6 col-lg-12 col-xl-12">
							<div class="game-control-box">
								<h2>Game details</h2>
								<p>Generation: <span>{{generation}}</span></p>
							</div>
						</div>
						<div class="col-sm-6 col-lg-12 col-xl-12">
							<div class="game-control-box">
								<h2>Game control</h2>
								<button type="button" class="btn btn-success" ng-click="start()">Start</button>
								<button type="button" class="btn btn-danger" ng-click="stop()">Stop</button>
								<button type="button" class="btn btn-warning" ng-click="resetMap()" >Reset</button>
								<button type="button" class="btn btn-primary" ng-click="stepNext()">Next</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-lg-12 col-xl-12">
							<div class="game-control-box">
								<h2>Speed</h2>
								<span>{{(1.01 - speed).toFixed(2)}} / sec</span>
								<input type="range" class="custom-range" id="customRange1" ng-model="speed" ng-click="changeSpeed()" min="0.01" max="1" step="0.01" />
							</div>
						</div>
						<div class="col-sm-6 col-lg-12 col-xl-12">
							<div class="game-control-box">
								<h2>Resolution</h2>
								<span>{{resolution}}px X {{resolution}}px</span>
								<input type="range" class="custom-range" id="customRange1" ng-model="resolution" ng-change="zoom()" min="1" max="50" step="1"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-sm-12">
							<div class="game-control-box last-control-box">
							<h2>Load from file</h2>
							
							<?php if(isset($_GET['error'])): ?>
							    <div class="alert alert-danger" style="display: block" role="alert">
							    <?php 
							    switch($_GET['error']){
							        case 1: echo 'File upload error!'; break;
							        case 2: echo 'The file extension is not valid!'; break;
							        case 3: echo 'The file size is too big! max(2MB)'; break;
							        case 4: echo 'File save error!'; break;
							        case 5: echo 'File format is incorrect.'; break;
							        case 6: echo 'File version is not supported. Support version is 1.05'; break;
							    }
							    ?>
							    </div>
							<?php endif; ?>
							<div class="alert alert-danger" role="alert"></div>
							<form name="load_file" id="load_file" action="php/file_uppload.php" method="post" enctype="multipart/form-data">
							  <div class="form-group">
							    <input type="file" class="form-control-file" id="lif_file" name="lif_file" />
							    <input type="hidden" name="file" value="" />
							   	<button type="button" class="btn btn-primary" onclick="check_content()">Load</button>
							  </div>
							</form>
						</div>
					</div>
				</div>			
			</div>
		</div>
		</div>	
	</body>
</html>