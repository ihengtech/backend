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
    <div class="row" id="face-result" style="display: none;">
        <div class="col s12 m7">
            <div class="card">
                <div class="card-image">
                    <canvas id="canvas"></canvas>
                    <span class="card-title"></span>
                </div>
                <div class="card-content">
                </div>
                <div class="card-action" id="face-items">
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="wares">
    </div>
    <div class="row">
        <div class="col s12 m6">
            <div class="card blue-grey darken-1">
                <div class="card-content white-text">
                    <span class="card-title">使用说明</span>
                    <p>对准人像，点击拍照。</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/tpl" id="ware-tpl">
<div class="col s12 m7">
    <div class="card">
        <div class="card-image">
            <img />
        </div>
        <div class="card-content">
        <span class="card-title"></span>
            <p></p>
        </div>
        <div class="card-action">
            <a href="#" target="_blank">购买</a>
        </div>
    </div>
</div>

</script>
<script type="text/javascript">
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    var video = $("#video").get(0);
    var canvas = $("#canvas").get(0);
    var height = 480;
    var width = 390;
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
                $("#face-result").show();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function setMerchandise(data) {
        var $tpl = $('#ware-tpl');
        var $wares = $('#wares');
        $("#face-items").html('');
        $wares.html('');
        if (data.faceItems) {
            $.each(data.faceItems, function (index, item) {
                $('<a>', {
                    class: 'waves-effect waves-light btn',
                    html: item.key + ' ' + item.value
                }).appendTo('#face-items');
            });
        }
        if (data.merchandiseItems && data.merchandiseItems.content) {
            $.each(data.merchandiseItems.content, function (index, item) {
                console.log(item);
                $html = $($tpl.html());
                $html.find("img").attr("src", item.urlCode);
                $html.find(".card-title").html(item.wareModel);
                $html.find(".card-action a").attr("href", item.waresUrl);
                $html.find(".card-content p").html(item.wareInfo);
                $wares.append($html.prop("outerHTML"));
            });
        }
    }

    function getCsrfData() {
        return {
            key: $("meta[name='csrf-param']").attr("content"),
            value: $("meta[name='csrf-token']").attr("content")
        };
    }

</script>
<style type="text/css">

    #video {
        width: 90%;
        background: grey;
    }

    #canvas {
        width: 100%;
    }

    .video-area {
        text-align: center;
    }

    .video-area a {
        margin: 10px;
    }

    #face-items a {
        margin-right: 10px;
        margin-bottom: 15px;
    }
</style>