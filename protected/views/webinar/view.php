<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 16.03.15
 * Time: 17:49
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$this->renderPartial('/site/top');
?>



<div class="wrapper">
<div class="container">
<div class="col-group">
<div class="col-9">

<div class="content">




Hello!
    <video id="localVideo" autoplay></video>
    <video id="miniVideo" autoplay></video>
    <video id="remoteVideo" autoplay></video>

<script>
    var getUserMedia;

    var getUserMedia;
    var browserUserMedia =    navigator.webkitGetUserMedia    ||    // WebKit
        navigator.mozGetUserMedia    ||    // Mozilla FireFox
        navigator.getUserMedia;            // 2013...
    if ( !browserUserMedia ) throw 'Your browser doesn\'t support WebRTC';

    getUserMedia = browserUserMedia.bind( navigator );

    getUserMedia(
        {
            audio: true,
            video: true
        },
        function( stream ) {
            var URL = window.URL || window.webkitURL

            var videoElement = document.getElementsByTagName('video')[0];
            videoElement.src = URL.createObjectURL( stream );
        },
        function( err ) {
            console.log( err );
        }
    );
</script>



</div>
</div>
<?php
$this->renderPartial('/site/bottom');
?>

