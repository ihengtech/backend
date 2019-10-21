<?php

/* @var $this yii\web\View */
/* @var $faceDetectForm frontend\models\FaceDetectForm */

$this->title = 'Face Detect';

?>

<div class="container-fluid">
    <div class="video-area">
        <div>
            <a class="btn waves-effect waves-light red" id="take-a-picture">
                <i class="material-icons left">add_a_photo</i><span id="take-picture">拍照</span>
            </a>
        </div>
        <div>
            <video id="video">视频流不可用。</video>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m7">
            <div class="card">
                <div class="card-image">
                    <canvas id="canvas"></canvas>
                    <span class="card-title"></span>
                </div>
                <div class="card-content">
                    <p></p>
                </div>
                <div class="card-action">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m6">
            <div class="card blue-grey darken-1">
                <div class="card-content white-text">
                    <span class="card-title">使用说明</span>
                    <p>I am a very simple card. I am good at containing small bits of information.
                        I am convenient because I require little markup to use effectively.</p>
                </div>
                <div class="card-action">
                    <a href="#">This is a link</a>
                    <a href="#">This is a link</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    var video = $("#video").get(0);
    var canvas = $("#canvas").get(0);
    var height = 480;
    var width = 390;
    height = 40;
    width = 30;
    if (navigator.getUserMedia) {
        navigator.getUserMedia({
            audio: false,
            video: {width: width, height: height}
        }, function (stream) {
            video.srcObject = stream;
            video.onloadedmetadata = function (e) {
                video.play();
            };
        }, function (error) {
            console.log("打开视频失败!");
        })
    } else {
        console.log("浏览器不支持视频");
    }
    $("#take-a-picture").on("click", function () {
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        var context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, width, height);
        var imageData = canvas.toDataURL('image/jpeg');
        uploadImage(imageData, setMerchandise);
    });

    function uploadImage(imageData, callback) {
        var csrfData = getCsrfData();
        var formData = new FormData();
        formData.append(csrfData.key, csrfData.value);
        formData.append("raw_name", imageData);
        $.ajax({
            url: '/site/face-detect',
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            success: function (response) {
                callback(response);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function setMerchandise(data) {
        console.log("data", data);
    }

    function getCsrfData() {
        return {
            key: $("meta[name='csrf-param']").attr("content"),
            value: $("meta[name='csrf-token']").attr("content")
        };
    }

</script>
<style type="text/css">
    .video-area {
        text-align: center;
    }

    .video-area a {
        margin: 10px;
    }
</style>