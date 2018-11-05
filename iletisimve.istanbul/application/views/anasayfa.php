<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!--  This site was created in Webflow. http://www.webflow.com -->
<!--  Last Published: Thu Dec 01 2016 06:29:02 GMT+0000 (UTC) data-wf-page="583e814f313942ad5a1d2ca6" data-wf-site="583e814e313942ad5a1d2ca3"  -->
<html >
<head>
  <base href="http://iletisimve.istanbul/hosting/">

  <meta charset="utf-8">
  <title>iletisimve.istanbul</title>
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
<body class="body">
  <div class="container w-container" style="margin-bottom: 45px;">
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
    <div class="error-message w-form-fail" <?php if($error){ ?> style="display:block" <?php } ?> >
        <p><?php echo $error[0]; ?></p>
    </div>
    <div class="success-message w-form-done" <?php if($info){ ?> style="display:block" <?php } ?>>
        <p><?php echo $info; ?></p>
    </div>
    <br><br>
    <div class="sign-up-form w-form" style="margin: -10px auto;">
      <form action="enter" data-name="Signup Form" data-redirect="domains" method="post" name="wf-form-signup-form" redirect="domains">
        <input autofocus="autofocus" class="input w-input" data-name="Email" maxlength="256" name="email" placeholder="<?php echo lang('user_placeholder'); ?>" required="required" type="email">
        <input class="input w-input" data-name="Password" id="Password" maxlength="256" name="password" placeholder="<?php echo lang('password_placeholder'); ?>" required="required" type="password">
        <input class="button w-button" data-wait="Please wait..." type="submit" value="<?php echo lang('enter'); ?>">
        <a href="#" style="color:#00d6b4" onclick="window.location='forgot_password?user='+$('input[name=email]').val()" >
        	<p><?php echo lang('forgot_password'); ?></p>
        </a>
      </form>
    </div>
  </div>
  <div class="footer-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-4 w-col-small-4">
          <div class="copyright">© 2014 İletişim ve İstanbul <?php echo lang('all_rights_reserved'); ?>.&nbsp;</div>
        </div>
        <div class="w-col w-col-4 w-col-small-4">
        	<a href="tolang/turkish"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAARCAYAAAA2cze9AAACz0lEQVQ4T6WU7UuTURTAf3ebKfMl51vMtMKx1BVlZgR9sDc/FYRFYRSU1T9gYREGQR96IfqQ0EfBiAxCKqKIvlSMysBVoJVtQiJraDnbNF+3x+3GfZ6lmFZSBy7ncu85v3PuufceAYhfBv8pEtCHAps+V9VUZyWlNQmwybhEmARKL0R+2iqtJB6T4XAkcqzQ3XJfrZiHNx8IWkuKbJbMxXo4IRNhF0L/xSYWGmbc1xNe7L6Vq+CWcMUeLb2s1DCTEoQw9D/K6Dsfme13knT4oGuHlrmmZDZKCMzVW2HjWrDnQWgY2t4Qu/cUpmJ/DDvU6SWn65EBH7Bv12wVK2cchMBysQ5cTiZfvab/0jW0Dj/ZW8uxVZQRf+aBqDZ/ACEIe3zk9T8x4H1s0nK3FSeqIkk6tBNq9zLmfsmXLSfIWbeMlPRUoqPjREYnSHfkY44YcCklQghdK1Hz4FMf+bQZ8ADlWu56I3O1KW6cw1LqpG/XEXICkwjzjLP+IrQ4yfU1aI2t82YffNNNAW8NeC/Fmt2RuFC10NGCKdXKd9dukidBmEDGDY6aL2o4AEf3QXMr0fO35gT48snLcrwGvIdCzc4qw9kikK8vk7J2NdGa48i7vlnOckoyyFdSLhxmoqGZXOxz4H28x0HAgHeToRWybtpo7OxOss+dJP68nWhlvfpnqhiJfTVPZgA/eSyZtywBOnAyZMC9oC1jQ8JQ8o1hstzXsVZughcetPNNxB53Y6pyYClzMHHlASZ+81oQ+PFQAgb8A2grcE1noe79M71knTqNbX81lmInov8r0dsPCZ25ShpJmPSWpF/vnJP10qWKbMA7QSuiYHZtgRADjBAlAiwCMjCTxVLENHB+eA9+1iTg5rcQzAdbKtY//ryFbI4xTh+Ey0HvLaYWOLgSGvWumOi//9pZ4hD+CHW1cPNnL1enTlcdciHZ/cVGNZ4RIPoDLzj9SkxlSGgAAAAASUVORK5CYII=" alt="http://www.iconfinder.com/iconsets/stripe-flag-set"/></a>
        	<a href="tolang/english"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAARCAYAAAA2cze9AAADp0lEQVQ4T6WUf0xVVRzAP+e9xyN4grwUVi02RkaBWfRruBHgcqG0HCAmTJMJCCg1ZaSFLXIMN7DhlDaQBHnlpIEhSFYMmU4hNrZyQqksDTadtsb74ynyxMd979127tXrInRtfbez7z33+z2f+/1x7lcAYtbif4oKaEuCTRtKmjICbSHNQgi7qqoIIZBaF+kin6X+twgB0lVqKT5VdXnuTBa01hd1y1fmDduOOA+fGbSr31Vw2Wulp38M161pg7RrazKYkh6Q/QNUftFv7B8PC2Jl0jPEWGZQ06vITUl0tda9Fy7hluxih+Jo7cM65cRUtwllSxanh8bpGxhjRvGx/9M0piwvyyQ14DzvOUp392ANMJOatIhlCVEEHujEV9qMJzSc/HWptDduDNDgGXlNSltDLqL+KL7tX2JZshi6KnDZ59PVO0remldwW+ONSIM95/nq2HkyV8QSdvMmImM33t8uYq4tRn1/LTklX3PcUaTD09Y3KMuSXyT7nReIuOvGl1dNQP8o5n2b8G5Zo9X/TpCM3K99IHh6RNOWAx34yw6hJD+P2fEJE4/ZaP/+Amf6f6WntUSHL8+qU7oPb9baNmfTALdtiVGWYPeI0ea5Wp6e28ipY9t0eNKqvcrJE23GYT1CWV89UikuXMZ9CWPBPZveg9n+qatyGDjxoQ5PeKta6exrfkjc8iL6EZg0PXdmuk36SMlKLWToZLkOj0+pUg6erXgoXBoiiDQymeDGP3xn/wmFKVUMn63Q4XEJnym9x8seCfc+GWfYzTcuIe5XZI5TKzP3cXGoUoc/G79TSVz+GkXrXufpSRe2tXswO68yXbmR6fws7bgv8jkDY7l2GVWoBLd0ErTLgW9BFFMdH3E91M7Bb35m8NQvXBmu1uFRsTuU3u4y5te0YG3p5PbSeJT6nTiFlaa2c5RvTkKNfgAX479T0zhAYc6rLPR7sH5QQ8jQMDP5mdwqLyA1fS9XR2t1+FPRpcrI+CgenEzVbcez4k2+/eECRzuG8dz18mN7PqaYGBm/Fr16ZYy3sx1YgyxkZ8XzbtpiAvtOY9taSyDhvBQdy5/j+zW4OfyJEudP0W77zOcfM3htisaWISauTxpl6OkswBK3yNh7L/1B2upD+l6FiMhQivOWkhg1j8Ade3hj3OZy/tWgzRaTLXT1+gDzwjqTKuzGiLs3FVUTCD9ILUWoQqu31HOJX6gu/4yzdNLddeT+LLcCITKLR16Z/2aUtbsNzPwNkjFdWSVgURsAAAAASUVORK5CYII=" /></a>
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

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>

  <script src="http://iletisimve.istanbul/js/webflow.js" type="text/javascript"></script>
  <script src="http://iletisimve.istanbul/js/app.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
  <script>
  
  	$(document).ready(function () {
	  setTimeout(function(){
	  	$('.success-message').fadeOut();
	  	$('.error-message').fadeOut();
	  }, 1500);
	});
  </script>
</body>
</html>