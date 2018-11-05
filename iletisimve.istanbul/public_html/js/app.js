var app = angular.module("myApp", []);
app.controller("myCtrl", function($scope,$http,$timeout) {
	$scope.apiBaseUrl = 'http://iletisimve.istanbul/api/hosting';
	$scope.isadmin=false;
	$scope.ismaster=false;
	$scope.lang='';
   	$scope.emails = [];
   	$scope.email={
   		'user':'',
   		'password':''	
   	};
   	$scope.quote ={
   		'name':'',
   		'email':'',
   		'message':'',
   	};
   	$scope.fwdDests = [];
   	$scope.fwdTos = [];
    	$scope.domains = [];
    	$scope.users=[];
    	$scope.unverified_domains = [];
    	$scope.toVerifiedDomain='';
    	$scope.load=false;
    	$scope.loadDomain=false;
    	$scope.errorMessage='';
    	$scope.successMessage='';
    	$scope.domainCheckLoad=false;
    	$scope.seguro_url ='';
    	$scope.selected={
    		'user':'',
    		'domain':'',
    		'email':'',
    		'fwdDest':''
    	}
    	$scope.seguro={
    		url:'',
    		message:''
    	}
    	
    	
    	// at the bottom of your controller
	var init = function () {
	   // check if there is query in url
	   // and fire search in case its value is not empty
	   $scope.getUsers();
	   $scope.getLang();
	   if($scope.selected.user!='')
	   	$scope.getDomains($scope.selected.user);
	   	
	   
		$timeout(function(){
			angular.element(document).find('.error-message').css('display','none');
			angular.element(document).find('.success-message').css('display','none');
		}, 3000);
	};
	$scope.getLang = function(lang='turkish'){
	    	$scope.errorMessage= '';
		$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/lang?lang='+lang
		}).then(function successCallback(response1) {
		
			
			// this callback will be called asynchronously
			// when the response is available
			if(response1.data!='null'){
		    		$scope.lang = response1.data;
		    	}
		    			   	
		}, function errorCallback(response) {
			
			$scope.errorMessage=response.data.message;
			angular.element(document).find('.error-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.error-message').css('display','none');
			}, 3000);
		    	// called asynchronously if an error occurs
		    	// or server returns response with an error status.
		    			   	
		});
		
	}
	$scope.postQuote = function(){
	    	$scope.errorMessage= '';
	    	
		//Restangular.allUrl('teklifal', $scope.apiBaseUrl+'/teklifal').get({'name':'','email':'','message':''});
		$http({
		  	method: 'POST',
		  	url: $scope.apiBaseUrl+'/teklifal',
		  	data:{'name':$scope.quote.name,'email':$scope.quote.email,'message':$scope.quote.message}
		}).then(function successCallback(response1) {
		
			
			// this callback will be called asynchronously
			// when the response is available
			if(response1.data!='null'){
		    		$scope.successMessage=response1.data.message;
				angular.element(document).find('.w-form-done').css('display','block');
				angular.element(document).find('.success-message').css('display','block');
				angular.element(document).find('#wf-form-Quote-Form').css('display','none');
				$timeout(function(){
					angular.element(document).find('.w-form-done').css('display','none');
					angular.element(document).find('#wf-form-Quote-Form').css('display','block');
				}, 3000);
			    	// called asynchronously if an error occurs
			    	// or server returns response with an error status.
		    	}
		    	$scope.quote ={
		   		'name':'',
		   		'email':'',
		   		'message':'',
		   	};
		   	$scope.quoteForm.$setPristine();
		}, function errorCallback(response) {
			if(response.data!=null){
				$scope.errorMessage=response.data.message;
				}
				angular.element(document).find('.w-form-fail').css('display','block');
				angular.element(document).find('#wf-form-Quote-Form').css('display','none');
				$timeout(function(){
					angular.element(document).find('.w-form-fail').css('display','none');
					angular.element(document).find('#wf-form-Quote-Form').css('display','block');
				}, 3000);
			    	// called asynchronously if an error occurs
			    	// or server returns response with an error status.
			    
			    	
		});
		
	}
	
	$scope.getUsers = function(){
	    	$scope.errorMessage= '';
		$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/users'
		}).then(function successCallback(response1) {
		
			
			// this callback will be called asynchronously
			// when the response is available
			if(response1.data!='null'){
		    		$scope.users = response1.data;
		    		for(user in $scope.users){
		    			if($scope.users[user].isadmin)
		    				$scope.isadmin=true;
		    			if($scope.users[user].ismaster)
		    				$scope.ismaster=true;
		    		}
		    		if($scope.users.length ==1){
		    			$scope.getDomains($scope.users[0]);
		    			angular.element(document).find('p.item.join.user.ng-binding').css('color','#ff5eb8');
		    			}
		    			
		    	}
		}, function errorCallback(response) {
			
			$scope.errorMessage=response.data.message;
			angular.element(document).find('.error-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.error-message').css('display','none');
			}, 3000);
		    	// called asynchronously if an error occurs
		    	// or server returns response with an error status.
		});
		
	}
	$scope.getDomains = function(user){
		$scope.loadDomain=true;
	    	$scope.domains = [];
    		$scope.unverified_domains = [];
	    	$scope.emails = [];
	    	$scope.fwdDests = [];
   		$scope.fwdTos = [];
	    	$scope.selected.fwdDest = '';
	    	$scope.selected.domain = '';
	    	$scope.selected.user = user;
	    	$scope.errorMessage= '';
		$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/domains',
		  	params: {'user':user.email},
		}).then(function successCallback(response1) {
		
			
			// this callback will be called asynchronously
			// when the response is available
			if(response1.data!='null')
		    		$scope.domains = response1.data;
		    	$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/unverified_domains',
		  	params: {'user':user.email},
			}).then(function successCallback(response2) {
				$scope.loadDomain=false;
			    	// this callback will be called asynchronously
			    	// when the response is available
			    	if(response2.data!='null')
			    		$scope.unverified_domains = response2.data;
			}, function errorCallback(response2) {
				$scope.loadDomain=false;
				
			    	// called asynchronously if an error occurs
			    	// or server returns response with an error status.
			});
		}, function errorCallback(response) {
			$scope.loadDomain=false;
			
			$scope.errorMessage=response.data.message;
			angular.element(document).find('.error-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.error-message').css('display','none');
			}, 3000);
		    	// called asynchronously if an error occurs
		    	// or server returns response with an error status.
		});
		
	}
	
	
	$scope.getEmails = function(domain){
	    	$scope.load=true;
	    	$scope.emails = [];
   		$scope.fwdDests = [];
   		$scope.fwdTos = [];
	    	$scope.selected.fwdDest = '';
	    	$scope.selected.domain = domain;
	    	
		$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/emails',
		  	params: {'domain':domain,'user':$scope.selected.user.email},
		}).then(function successCallback(response) {
		    	// this callback will be called asynchronously
		    	// when the response is available
		    	if(response.data!=null)
		    		$scope.emails = response.data;
		    	$http({
		  	method: 'GET',
		  	url: $scope.apiBaseUrl+'/fwd_dests',
		  	params: {'domain':domain,'user':$scope.selected.user.email},
			}).then(function successCallback(response2) {
	    			$scope.load=false;
			    	// this callback will be called asynchronously
			    	// when the response is available
			    	prev='';
			    	if(response2.data!=null){
			    		$scope.fwdTos = response2.data;
			    		for(fwd in response2.data ){
			    			if(prev!=response2.data[fwd].dest){
			    				$scope.fwdDests[fwd]=response2.data[fwd].dest;
			    			}
			    			prev=response2.data[fwd].dest;
			    		}
			    	}
			}, function errorCallback(response2) {
				$scope.load=false;	
			    	// called asynchronously if an error occurs
			    	// or server returns response with an error status.
			});
		}, function errorCallback(response) {
			$scope.load=false;
			$scope.errorMessage=response.data.message;
			
			angular.element(document).find('.error-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.error-message').css('display','none');
			}, 3000);
		    	// called asynchronously if an error occurs
		    	// or server returns response with an error status.
		});
		
	}
	$scope.setVerifiedDomain=function(unverified_domain){
		$scope.toVerifiedDomain=unverified_domain;
	}
	$scope.seguro = function(url,message){
		$scope.seguro.url =url;
		$scope.seguro.message =message;
		angular.element(document).find('#seguro').css('display','block');
	}
	$scope.gotoUrl = function(){
		window.location=$scope.seguro.url;
	}
	$scope.checkDomain = function(){
    		$scope.domainCheckLoad=true;
		$scope.domainCheck=false;
		if($scope.domainToSend!=null && $scope.validateDomain($scope.domainToSend)!=null){
			$http({
			  	method: 'GET',
			  	url: $scope.apiBaseUrl+'/check_domain/'+$scope.domainToSend
			}).then(function successCallback(response) {
	    			$scope.domainCheckLoad=false;	
			    	// this callback will be called asynchronously
			    	// when the response is available
			    	if(response.data!=null)
			    		if(response.data=='AVAILABLE')	
		    			 	return $scope.domainCheck='available';
		    			else(response.data=='UNAVAILABLE')
		    				return $scope.domainCheck='unavailable';
		    				
			}, function errorCallback(response) {
				
	    			$scope.domainCheckLoad=false;	
			    	// called asynchronously if an error occurs
			    	// or server returns response with an error status.
			});
			return false;
		}else{	
    			$scope.domainCheckLoad=false;
			$scope.domainCheck='error';
		}
	}
	$scope.validateDomain = function(domain) { 
	    var re = new RegExp(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/); 
	    return domain.match(re);
	} 
	$scope.submit=function(submitUrl){
		$http({
		  	method: 'POST',
		  	url: $scope.apiBaseUrl+'/'+submitUrl,
		  	data:{'domain':$scope.selected.domain,'user':$scope.selected.user.email,'email':$scope.email.user,'password':$scope.email.password }
		}).then(function successCallback(response) {
    			
		    	// this callback will be called asynchronously
		    	// when the response is available
		    	if(response.data!=null)
		    		$scope.successMessage=response.data;
			
			angular.element(document).find('.success-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.success-message').css('display','none');
			}, 3000);
			angular.element(document).find('.modal-background').css('display','none');
	    		$scope.email={
		   		'user':'',
		   		'password':''	
		   	};
			$scope.emailForm.$setPristine();
		}, function errorCallback(response) {
			$scope.errorMessage=response.data.message;
			
			angular.element(document).find('.error-message').css('display','block');
			$timeout(function(){
				angular.element(document).find('.error-message').css('display','none');
			}, 3000);
			angular.element(document).find('.modal-background').css('display','none');
    			$scope.email={
		   		'user':'',
		   		'password':''	
		   	};
			$scope.emailForm.$setPristine();
		    	// called asynchronously if an error occurs
		    	// or server returns response with an error status.
		});
	}
	// and fire it after definition
	init();
});
