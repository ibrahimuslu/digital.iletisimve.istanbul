<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!--  This site was created in Webflow. http://www.webflow.com -->
<!--  Last Published: Thu Dec 01 2016 12:23:53 GMT+0000 (UTC) data-wf-page="583e82de313942ad5a1d3386" data-wf-site="583e814e313942ad5a1d2ca3"  -->
<html >
<head>

  <base href="http://iletisimve.istanbul/hosting/">
  <meta charset="utf-8">
  <title>Hosting</title>
  <meta content="Domains" property="og:title">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="http://iletisimve.istanbul/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="http://iletisimve.istanbul/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="http://iletisimve.istanbul/css/iletisimve-istanbul-mix.webflow.css" rel="stylesheet" type="text/css">
  <script src="http://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
  <script type="text/javascript">
    WebFont.load({
      google: {
        families: ["Roboto Condensed:regular,700"]
      }
    });
  </script>
  <script src="http://iletisimve.istanbul/js/modernizr.js" type="text/javascript"></script>
  <link href="http://iletisimve.istanbul/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="http://iletisimve.istanbul/images/IVEistanbul.png" rel="apple-touch-icon">
</head>
<body class="body" ng-app="myApp" ng-cloak ng-controller="myCtrl">  
  <div class="container w-container " style="margin-bottom: 70px;" >
    <div class="nav w-nav" data-animation="default" data-collapse="medium" data-contain="1" data-duration="400">
      <div class="w-container">
        <a class="logo w-nav-brand" href="#">
          <div>İLETİŞİMVE.İSTANBUL</div>
        </a>
        <nav class="nav-menu w-nav-menu" role="navigation">
        	<a class="nav-link w-nav-link" href="../index.html">Anasayfa</a>
        	<a class="nav-link w-nav-link" href="../about.html">Hakkında</a>
        	<a class="nav-link w-nav-link" href="https://iletisimve.istanbul/index.php">Yönetim</a>
        	<a class="nav-link w-nav-link" href="../contact.html">İletişim</a>
        </nav>
        <div class="menu-button w-nav-button">
          <div class="w-icon-nav-menu"></div>
        </div>
      </div>
    </div>
    <div class="error-message w-form-fail w-block" <?php if($error){ ?> style="display:block" <?php } ?> >
        <p><?php $error?print($error[0]):''; ?>{{errorMessage}}</p>
    </div>
    <div class="success-message w-form-done" <?php if($info){ ?> style="display:block" <?php } ?>>
        <p><?php echo $info; ?>{{successMessage}}</p>
    </div>
    <br><br>
    <div class="join-wrapper w-clearfix">
      <div class="beta-line"></div>
      <p class="join">{{lang.language.users}}</p>
      <div ng-if="isadmin" class="add beta-line modal-link" onclick="$('#add-user').fadeIn();" ></div>
    </div>
    
    <div class="join-wrapper w-clearfix" ng-cloak ng-repeat="user in users  | filter: { email: '!ive.ist'}">
        <a href="#" ng-click="getDomains(user);" >
        	<p class="item join user" onclick="$('.user.selected').removeClass('selected');$(this).addClass('selected');">{{user.email}}</p>
        </a>
        <a href="#" ng-if="user.deletable" ng-click="seguro('remove_user?user='+user.email,$parent.lang.language.want_delete_user+' : '+user.email)">
        	<div class="beta-line close"></div>
        </a>
         <a href="#" ng-if="!user.deletable"  onclick="$('#change-pass').fadeIn();">
        	<div class="beta-line change modal-link"></div>
        </a>
    </div>
    <div class="join-wrapper w-clearfix">
      <div class="beta-line"></div>
      <p class="join">{{lang.language.domains}}</p>
      <div class="add beta-line modal-link" ng-if="selected.user!=''" onclick="$('#add-domain').fadeIn();"></div>
      
      <div class="loading" ng-show="loadDomain"></div>
    </div>
    
    <div class="join-wrapper w-clearfix" ng-cloak ng-repeat="domain in domains track by $index ">
        <a href="#" ng-click="getEmails(domain.name)" >
        	<p class="item join domain" onclick="$('.domain.selected').removeClass('selected');$(this).addClass('selected');">{{domain.name}}</p>
        </a>
        <a href="#" ng-click="seguro('remove_domain?domain='+domain.name+'&user='+selected.user.email,$parent.lang.language.want_delete_domain+' : '+domain.name)">
        	<div class="beta-line close"></div>
        </a>
    </div>
    
    <div class="join-wrapper w-clearfix " ng-cloak ng-repeat="unverified_domain in unverified_domains track by $index">
        <p class="item join unverified domain" >{{unverified_domain}}</p>
        <a href="#" ng-if="$parent.ismaster"><div class="beta-line verify" ng-click="setVerifiedDomain(unverified_domain)" onclick="$('#verify-domain').fadeIn();"></div></a>
        <a href="#" ng-click="seguro('remove_domain?domain='+unverified_domain+'&user='+$parent.selected.user.email,$parent.lang.language.want_delete_domain+' : '+unverified_domain)">
        	<div class="beta-line close"></div>
        </a>
    </div>
    
    <div class="join-wrapper w-clearfix">
      <div class="beta-line"></div>
      <p class="join">{{lang.language.emails}}</p>
      <div class="add beta-line modal-link" id="add-new-email" ng-if="selected.domain!=''" onclick="$('#add-email').fadeIn();"></div>
      <div class="fwd beta-line modal-link" id="add-new-fwd" ng-if="selected.domain!=''" onclick="$('#add-fwd').fadeIn();"></div>
      <div class="join-wrapper w-clearfix" ng-cloak  ng-repeat="email in emails">
        <a href="http://iletisimve.istanbul/email/?_user={{email.login}}" target="_blank"><p class="item join">{{email.login.split('@',1)[0]}}</p></a>
        <a href="#" ng-click="seguro('remove_email?email='+email.login,$parent.lang.language.want_delete_email+' : '+email.login)">
        	<div class="beta-line close"></div>
        </a>
      </div>
      <div class="join-wrapper w-clearfix" ng-cloak ng-repeat="fwdDest in fwdDests">
        <a href="#"   target="_blank" >
        	<div class="fwdDest beta-line modal-link" style="clear: left;"></div>
        	<p class="item join fwdDest" ng-click="selected.fwdDest=this.html()" onclick="$('.fwdDest.selected').removeClass('selected');$(this).addClass('selected');">		
        		{{fwdDest.split('@',1)[0]}}
        	</p>
        	
        	
        </a>
        
      </div>
      <div class="loading" ng-show="load"></div>
    </div>
    <div class="join-wrapper w-clearfix" ng-show="selected.fwdDest!=''">
      <div class="beta-line"></div>
      <p class="join">{{lang.language.forwarders}}</p>
      <div class="beta-line modal-link"></div>
    </div>
    <div class="join-wrapper w-clearfix" ng-cloak  ng-show="selected.fwdDest!=''" ng-repeat="fwdTo in fwdTos">
        <div class="fwdDest beta-line modal-link" style="clear: left;" ></div><p class="item join">{{fwdTo.forward}}</p>
        <a href="#" ng-click="seguro('remove_fwd?dest='+selected.fwdDest+'&to='+fwdTo.forward,'{{lang.language.want_delete_fwdto}} : '+fwdTo.forward)">
        	<div class="beta-line close"></div>
        </a>
    </div>
  </div>
  <div class="footer-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-4 w-col-small-4">
          <div class="copyright">© 2014 İletişim ve İstanbul <?php echo lang('all_rights_reserved'); ?>.&nbsp;
          	<a class="button logout w-button" href="logout">{{lang.language.logout}}</a>
          </div>
        </div>
        <div class="w-col w-col-4 w-col-small-4">
        	<a ng-click="getLang('turkish')"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAARCAYAAAA2cze9AAACz0lEQVQ4T6WU7UuTURTAf3ebKfMl51vMtMKx1BVlZgR9sDc/FYRFYRSU1T9gYREGQR96IfqQ0EfBiAxCKqKIvlSMysBVoJVtQiJraDnbNF+3x+3GfZ6lmFZSBy7ncu85v3PuufceAYhfBv8pEtCHAps+V9VUZyWlNQmwybhEmARKL0R+2iqtJB6T4XAkcqzQ3XJfrZiHNx8IWkuKbJbMxXo4IRNhF0L/xSYWGmbc1xNe7L6Vq+CWcMUeLb2s1DCTEoQw9D/K6Dsfme13knT4oGuHlrmmZDZKCMzVW2HjWrDnQWgY2t4Qu/cUpmJ/DDvU6SWn65EBH7Bv12wVK2cchMBysQ5cTiZfvab/0jW0Dj/ZW8uxVZQRf+aBqDZ/ACEIe3zk9T8x4H1s0nK3FSeqIkk6tBNq9zLmfsmXLSfIWbeMlPRUoqPjREYnSHfkY44YcCklQghdK1Hz4FMf+bQZ8ADlWu56I3O1KW6cw1LqpG/XEXICkwjzjLP+IrQ4yfU1aI2t82YffNNNAW8NeC/Fmt2RuFC10NGCKdXKd9dukidBmEDGDY6aL2o4AEf3QXMr0fO35gT48snLcrwGvIdCzc4qw9kikK8vk7J2NdGa48i7vlnOckoyyFdSLhxmoqGZXOxz4H28x0HAgHeToRWybtpo7OxOss+dJP68nWhlvfpnqhiJfTVPZgA/eSyZtywBOnAyZMC9oC1jQ8JQ8o1hstzXsVZughcetPNNxB53Y6pyYClzMHHlASZ+81oQ+PFQAgb8A2grcE1noe79M71knTqNbX81lmInov8r0dsPCZ25ShpJmPSWpF/vnJP10qWKbMA7QSuiYHZtgRADjBAlAiwCMjCTxVLENHB+eA9+1iTg5rcQzAdbKtY//ryFbI4xTh+Ey0HvLaYWOLgSGvWumOi//9pZ4hD+CHW1cPNnL1enTlcdciHZ/cVGNZ4RIPoDLzj9SkxlSGgAAAAASUVORK5CYII=" alt="http://www.iconfinder.com/iconsets/stripe-flag-set"/></a>
        	<a ng-click="getLang('english')"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAARCAYAAAA2cze9AAADp0lEQVQ4T6WUf0xVVRzAP+e9xyN4grwUVi02RkaBWfRruBHgcqG0HCAmTJMJCCg1ZaSFLXIMN7DhlDaQBHnlpIEhSFYMmU4hNrZyQqksDTadtsb74ynyxMd979127tXrInRtfbez7z33+z2f+/1x7lcAYtbif4oKaEuCTRtKmjICbSHNQgi7qqoIIZBaF+kin6X+twgB0lVqKT5VdXnuTBa01hd1y1fmDduOOA+fGbSr31Vw2Wulp38M161pg7RrazKYkh6Q/QNUftFv7B8PC2Jl0jPEWGZQ06vITUl0tda9Fy7hluxih+Jo7cM65cRUtwllSxanh8bpGxhjRvGx/9M0piwvyyQ14DzvOUp392ANMJOatIhlCVEEHujEV9qMJzSc/HWptDduDNDgGXlNSltDLqL+KL7tX2JZshi6KnDZ59PVO0remldwW+ONSIM95/nq2HkyV8QSdvMmImM33t8uYq4tRn1/LTklX3PcUaTD09Y3KMuSXyT7nReIuOvGl1dNQP8o5n2b8G5Zo9X/TpCM3K99IHh6RNOWAx34yw6hJD+P2fEJE4/ZaP/+Amf6f6WntUSHL8+qU7oPb9baNmfTALdtiVGWYPeI0ea5Wp6e28ipY9t0eNKqvcrJE23GYT1CWV89UikuXMZ9CWPBPZveg9n+qatyGDjxoQ5PeKta6exrfkjc8iL6EZg0PXdmuk36SMlKLWToZLkOj0+pUg6erXgoXBoiiDQymeDGP3xn/wmFKVUMn63Q4XEJnym9x8seCfc+GWfYzTcuIe5XZI5TKzP3cXGoUoc/G79TSVz+GkXrXufpSRe2tXswO68yXbmR6fws7bgv8jkDY7l2GVWoBLd0ErTLgW9BFFMdH3E91M7Bb35m8NQvXBmu1uFRsTuU3u4y5te0YG3p5PbSeJT6nTiFlaa2c5RvTkKNfgAX479T0zhAYc6rLPR7sH5QQ8jQMDP5mdwqLyA1fS9XR2t1+FPRpcrI+CgenEzVbcez4k2+/eECRzuG8dz18mN7PqaYGBm/Fr16ZYy3sx1YgyxkZ8XzbtpiAvtOY9taSyDhvBQdy5/j+zW4OfyJEudP0W77zOcfM3htisaWISauTxpl6OkswBK3yNh7L/1B2upD+l6FiMhQivOWkhg1j8Ade3hj3OZy/tWgzRaTLXT1+gDzwjqTKuzGiLs3FVUTCD9ILUWoQqu31HOJX6gu/4yzdNLddeT+LLcCITKLR16Z/2aUtbsNzPwNkjFdWSVgURsAAAAASUVORK5CYII=" /></a>
        </div>
        <div class="align-right w-col w-col-4 w-col-small-4">
          <a class="social-btn w-inline-block" href="http://facebook.com/iletisimve-istanbul"><img src="http://iletisimve.istanbul/images/facebook-icon.svg">
          </a>
          <a class="social-btn w-inline-block" href="http://twitter.com/iveist"><img src="http://iletisimve.istanbul/images/twitter-icon.svg">
          </a>
          <a class="social-btn w-inline-block" href="mailto:support@iletisimve.istanbul"><img src="http://iletisimve.istanbul/images/email-icon.svg">
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal USER Start -->
  <div class="modal-background" id="add-user" >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#"></a>
      <div class="sign-up-form w-form">
        <form action="add_user" method="post" data-name-a="Signup Form" name="wf-form-signup-form">
          <input autofocus="autofocus" class="input w-input" data-name="Email" maxlength="256" name="email" placeholder="{{lang.language.user_placeholder}}" required="required" type="email">
          <input class="input w-input" data-name="Password" id="Password" maxlength="256" name="password" placeholder="{{lang.language.password_placeholder}}" required="required" type="password">
          <input class="button w-button" data-wait="Please wait..." type="submit" value="{{lang.language.submit}}">
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal End -->
   <!-- Modal DOMAIN Start -->
  <div class="modal-background fade" id="add-domain"  >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#" ></a>
      <div class="sign-up-form w-form">
      
        <form action="add_unverified_domain" 
        	method="post"  
        	data-name-a="Signup Form" 
        	name="wf-form-signup-form">
          <input autofocus="autofocus" class="input w-input domain" data-name="Domain" maxlength="256" name="domain" 
          	ng-model="domainToSend" ng-class="domainCheck" ng-change="domainCheck=''"
          	placeholder="{{lang.language.domain_placeholder}}" required="required" type="text"
          	>
          <div class="beta-line verify domain" ng-click="checkDomain()" ></div>
    	  <div  class="loading domain" ng-show="domainCheckLoad" ></div>
          <input autofocus="autofocus" class="input w-input" name="user" value="{{selected.user.email}}" required="required" type="hidden">
          <input class="button w-button" data-wait="Please wait..." type="submit" value="{{lang.language.submit}}">
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal End -->
   <!-- Modal VERIFY DOMAIN Start -->
  <div class="modal-background fade" id="verify-domain"  >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#" ></a>
      <div class="sign-up-form w-form">
      
        <form action="verify_domain" 
        	method="post"  
        	data-name-a="Signup Form" 
        	name="wf-form-signup-form">
          <input class="input w-input" data-name="Domain" maxlength="256" name="domain"
          	 readonly="readonly" value="{{toVerifiedDomain}}" required="required" type="text"
          	>
    	  <select class="w-select" name="hosting" 
          	>
          	<option value="">Hosting Seçiniz</option>
          	<option value="{{user.id}}" ng-repeat="user in users | filter : 'hosting'">{{user.email.split('@')[0]}}</option>
          </select>
          <select class="w-select" name="registrar" 
          	>
          	<option value="">Kayıt Merkezi Seçiniz</option>
          	<option value="{{user.id}}" ng-repeat="user in users | filter : 'registrars'">{{user.email.split('@')[0]}}</option>
          </select>
          <input autofocus="autofocus" class="input w-input" name="user" value="{{selected.user.email}}" required="required" type="hidden">
          <input class="button w-button" data-wait="{{lang.language.please_wait}}" type="submit" value="{{lang.language.submit}}">
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal End -->
  <!-- Modal EMAIL Start -->
  <div class="modal-background" id="add-email" >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#"></a>
      <div class="sign-up-form w-form">
        <form  method="post"  data-name="emailForm" ng-submit="submit('add_email')" name="emailForm" data-direct="add_email">
          <input autofocus="autofocus" class="input w-input" data-name="Email" ng-model="email.user" maxlength="256" name="email" placeholder="{{lang.language.email_placeholder}}" required="required" type="text">
          <input class="input w-input" data-name="Domain" maxlength="256" name="domain" placeholder="Add email" value="{{selected.domain}}" type="hidden">
          <input autofocus="autofocus" class="input w-input" data-name="User" name="user" value="{{selected.user.email}}" required="required" type="hidden">
          <input class="input w-input" data-name="Password" id="Password" ng-model="email.password" maxlength="256" name="password" placeholder="{{lang.language.password_placeholder}}" required="required" type="password">
          <input class="button w-button"  data-wait="{{lang.language.please_wait}}" type="submit" value="{{lang.language.submit}}">
        </form>
         
      </div>
    </div>
  </div>
  <!-- Modal End -->
  <!-- Modal FWD Start -->
  <div class="modal-background" id="add-fwd" >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#"></a>
      <div class="sign-up-form w-form">
        <form action="add_fwd" method="post"  data-name-a="Signup Form" name="wf-form-signup-form">
          <input autofocus="autofocus" class="input w-input" data-name="Email" maxlength="256" name="email" 
          	placeholder="{{lang.language.email_placeholder}}" required="required" type="text">
          <input class="input w-input" maxlength="256" name="domain" value="{{selected.domain}}" type="hidden">
          <input autofocus="autofocus" class="input w-input" data-name="Domain" name="user" value="{{selected.user.email}}" required="required" type="hidden">
          <input class="input w-input" data-name="Fwds" id="Fwds"  name="fwds" placeholder="{{lang.language.fwd_placeholder}}" required="required" type="text">
          <input class="button w-button" data-wait="{{lang.language.please_wait}}" type="submit" value="{{lang.language.submit}}">
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal End -->
 <!-- Modal CHANGE_PASS Start -->
  <div class="modal-background" id="change-pass" >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#"></a>
      <div class="sign-up-form w-form">
        <form action="change_pass" method="post"  data-name-a="change pass Form" name="wf-form-change-pass-form">
          <p>{{selected.user.email}} {{lang.language.want_change_pass}}</p>
          <p>{{lang.language.are_you_sure}}<p>
          <input autofocus="autofocus" class="input w-input" data-name="Domain" name="user" value="{{selected.user.email}}" required="required" type="hidden">
          <input class="input w-input" data-name="Pswd" id="Pswd"  name="password" placeholder="{{lang.language.password}}" required="required" type="password">
          <input class="button w-button" data-wait="{{lang.language.please_wait}}" type="submit" value="{{lang.language.change}}">
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal End -->
  <!-- Modal SEGURO Start -->
  <div class="modal-background fade seguro" id="seguro" >
    <div class="modal-window w-clearfix">
      <a class="close-modal modal-close w-inline-block" href="#" ></a>
      <div class="sign-up-form w-form">
       <form action=""
        	method="post"  
        	data-name-a="Signup Form" 
        	name="wf-form-signup-form">
        	<p>{{seguro.message}}</p>
        	<p>{{lang.language.are_you_sure}}</p>
        	
        	<br>
          <a class="close-modal button w-button logout" >{{lang.language.no}}</a>
          <a ng-click="gotoUrl()" class="button w-button logout">{{lang.language.yes}}</a>
        </form>
        
      </div>
    </div>
  </div>
  <!-- Modal SEGURO End -->
   
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
  
  <script src="http://iletisimve.istanbul/js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
  <script type="text/javascript">

	$(document).ready(function () {
	  $('.close-modal').click(function () {
	    $('.modal-background').fadeOut();
	  });
	});
	

  </script>
  <script src="http://iletisimve.istanbul/js/app.js" type="text/javascript"></script>
</body>
</html>