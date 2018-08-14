<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
	
	<!-- Shown when the token is invalid or does not exist --> 
	<div ng-show="tokenExists === false">
		<a href="/">You appear to have reached this page in error. Please click here to the main page.</a>
	</div>
	
	<!-- Shown when the token is valid and exists -->
	<div ng-show="tokenExists === true">
		Your token exists {{token}}
	</div>

</div>
