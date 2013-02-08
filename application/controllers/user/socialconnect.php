<?php
class Socialconnect extends User_Controller
{


}
// dummy
define('YOUR_APP_ID', 'YOUR APP ID');

//uses the PHP SDK.  Download from https://github.com/facebook/facebook-php-sdk
require './../application/libraries/facebook.php';

$facebook = new Facebook(array(
    'appId'  => '137727846270788',
    'secret' => '424d91410cd3bb61e257dc4c72591a4e',
));

$userId = $facebook->getUser(); ?>


<div id="fb-root"></div>
    <?php if ($userId) {
    $userInfo = $facebook->api('/' . $userId);
    var_dump($userInfo); ?>
<?php } else {
    $loginUrl   = $facebook->getLoginUrl();
    echo $loginUrl;?>
<!--<fb:login-button></fb:login-button>-->
<?php } ?>


<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '137727846270788', // App ID
            channelUrl : '//lokal.horst/channel.html', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });
        FB.Event.subscribe('auth.login', function(response) {
            window.location.reload();
        });
    };
    // Load the SDK Asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));
</script>