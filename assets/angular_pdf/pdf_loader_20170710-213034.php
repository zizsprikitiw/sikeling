<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <link rel="stylesheet" href="style.css">
</head>

<script src="js/lib/pdf.js"></script>
<script src="js/lib/angular.min.js"></script>
<script src="js/app.js"></script>
<script src="js/directives/angular-pdf.js"></script>
<!--script src="js/controllers/docCtrl.js"></script-->

<script type="text/javascript">				
			
	app.controller('DocCtrl', function($scope) {
						
		  $scope.pdfName = '';
		  $scope.pdfUrl = 'http://proyek2.pustekbang.go.id/pustekbang_file_archive/log/22/Laporan_Triwulan_II_2017_-_Natanael_M.pdf';
		  $scope.scroll = 0;
		  $scope.loading = 'loading';
		
		  $scope.getNavStyle = function(scroll) {
			if(scroll > 100) return 'pdf-controls fixed';
			else return 'pdf-controls';
		  }
		
		  $scope.onError = function(error) {
			console.log(error);
		  }
		
		  $scope.onLoad = function() {
			$scope.loading = '';
		  }
		
		  $scope.onProgress = function(progress) {
			console.log(progress);
		  }
	
	});
</script>

<body >

<div ng-app="App">
	<div class="wrapper" ng-controller="DocCtrl">	  
	  <ng-pdf template-url="partials/viewer.html" canvasid="pdf" scale="page-fit" page=1></ng-pdf>
	</div>
</div>	


</body>
</html>
