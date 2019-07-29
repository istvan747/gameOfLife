var app = angular.module('myApp', []);

var cords = new Array();;

app.controller('myCtrl', function($scope) {
	
	$scope.generation = 0;
	$scope.speed = 0.99;
	$scope.resolution = 1;
	let canvas = undefined;
	let ctx = undefined;
	let canvasWidth = 0;
	let canvasHeight = 0;
	let rows = 0;
	let cols = 0;
	let resetMap = undefined;
	$scope.map = undefined;
	let nextMap = undefined;
	let maxRows = 0;
	let maxCols = 0;
	let startRow = 0;
	let startCol = 0;
	let myVar = undefined;
	let run = false;
	
	$scope.start = function(){
		if(!run){
			myVar = setInterval(function(){
				$scope.calculeteNextGeneration( $scope.map );
				$scope.drawMap();
			},((1.01 - $scope.speed) * 1000));
			run = true;
		}
	}
	
	$scope.stop = function(){
		clearInterval(myVar);
		run = false;
	}
	
	$scope.stepNext = function(){
		if(!run){
			$scope.calculeteNextGeneration( $scope.map );
			$scope.drawMap();
		}
	}
	
	$scope.resetMap = function(){
		$scope.stop();
		$scope.map = resetMap;
		$scope.drawMap();
		$scope.generation = 0;
	}
	
	$scope.changeSpeed = function(){
		if(run){
			$scope.stop();
			$scope.start();
		}
	}
	
	$scope.addCanvas = function(){
		let canvasDiv = document.getElementById("canvas-div");
		canvasWidth = canvasDiv.getBoundingClientRect().width;
		canvasHeight = canvasWidth * 0.618;
		canvas = document.createElement('canvas');
		canvas.setAttribute("width", canvasWidth);
		canvas.setAttribute("height", canvasHeight);
		canvas.setAttribute("id", "canvas");
		canvasDiv.appendChild(canvas);
		ctx = canvas.getContext("2d");
		rows = Math.floor( canvasHeight / $scope.resolution );
		cols = Math.floor( canvasWidth / $scope.resolution );
		$scope.map = $scope.get2DArray(rows, cols);
		nextMap = $scope.get2DArray(rows, cols);
		ctx.fillStyle = 'rgba(80,80,150,1)';
		$scope.getRandomMap();
		resetMap = $scope.map;
		$scope.zoom();
		$scope.drawMap();
	}
	
	$scope.resizeCanvas = function(){
		let canvasDiv = document.getElementById("canvas-div");
		canvasWidth = canvasDiv.getBoundingClientRect().width;
		canvasHeight = canvasWidth * 0.618;
		canvas.setAttribute("width", canvasWidth);
		canvas.setAttribute("height", canvasHeight);
		canvas.setAttribute("id", "canvas");
		ctx.fillStyle = 'rgba(80,80,150,1)';
		$scope.zoom();
	}
	
	$scope.getRandomMap = function(){
		for(let i = 0; i < $scope.map.length; i++){
			for(let j = 0; j < $scope.map[i].length; j++){
				$scope.map[i][j] = $scope.getRandomInteger(0, 1);
			}			
		}
	}
	
	$scope.getMap = function(){
		let maxRow = $scope.map.length;
		let maxCol = $scope.map[0].length;
		let origoX = Math.floor( maxCol / 2 );
		let origoY = Math.floor( maxRow / 2 );
		for(let i = 0; i < maxRow; i++){
			for(let j = 0; j < maxCol; j++){
				$scope.map[i][j] = 0;
			}
		}
		for(let i = 0; i < cords.length; i++){
			let y = cords[i][0] + origoX;
			let x = cords[i][1] + origoY;
			if( x > 0 && y > 0 && x < maxRow && y < maxCol )
				$scope.map[x][y] = 1;
		}
	}
	
	$scope.drawMap = function(){
		ctx.clearRect(0,0,canvasWidth,canvasHeight);
		for(let i = 0; i < maxRows; i++){			
			for(let j = 0; j < maxCols; j++){
				if($scope.map[i + startRow][j + startCol] == 1)
					ctx.fillRect(j * $scope.resolution, i * $scope.resolution, $scope.resolution, $scope.resolution );
			}
		}
	}
	
	$scope.calculeteNextGeneration = function( prevMap ){
		nextMap = $scope.get2DArray(rows, cols);
		for(let i = 0; i < rows; i++){
			for(let j = 0; j < cols; j++){
				let neighbors = $scope.countNeighbors(prevMap, i, j);
				if(prevMap[i][j] == 0 && neighbors == 3){
					nextMap[i][j] = 1;
				}else if(prevMap[i][j] == 1 && (neighbors < 2 || neighbors > 3)){
					nextMap[i][j] = 0;
				}else{
					nextMap[i][j] = prevMap[i][j];
				}
			}
		}
		$scope.map = nextMap;
		$scope.generation++;
		if(run)
			$scope.$apply();
	}
	
	
	$scope.countNeighbors = function(array, x, y){
		let sum = 0;
		for(let i = -1; i < 2; i++){
			for(let j = -1; j < 2; j++){
				if( (x + i) >= 0 && (y + j) >= 0 && (x + i) < rows && (y + j) < cols ){
					sum += array[x + i][y + j];
				}
			}
		}
		sum -= array[x][y];
		return sum;
	}
	
	$scope.zoom = function(){
		maxRows = Math.floor(canvasHeight / $scope.resolution);
		maxCols = Math.floor(canvasWidth / $scope.resolution);
		startRow = Math.floor(($scope.map.length - maxRows) / 2);
		startCol = Math.floor(($scope.map[0].length - maxCols) / 2);
		$scope.drawMap();
	}
	
	$scope.getRandomInteger = function(min, max){
		return Math.floor(Math.random() * (max - min + 1) ) + min;
	}
	
	$scope.get2DArray = function( rows, cols ){
		let array = new Array(rows);
		for(let i = 0; i < array.length; i++)
			array[i] = new Array(cols);
		return array;
	}

});

window.onresize = function(){
	angular.element(document.getElementById('app')).scope().resizeCanvas();
};

function getJSON(){
	let load_file = document.getElementById("get_file");
	if(load_file !== null){
		let xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				cords = JSON.parse(this.responseText);
				angular.element(document.getElementById('app')).scope().getMap();
				angular.element(document.getElementById('app')).scope().drawMap();
			}
		};
		xmlhttp.open("GET", "lif_to_json.php?file=" + load_file.value, true);
		xmlhttp.send();
	}
}


