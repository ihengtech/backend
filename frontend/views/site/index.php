<?php

/* @var $this yii\web\View */
/* @var $faceDetectForm frontend\models\FaceDetectForm */

$this->title = 'Face Detect';

?>

<div class="container-fluid">
    <div class="row">
        <div class="col s12" id="video-area">
            <a class="btn waves-effect waves-light red">
                <i id="capture" class="material-icons left">add_a_photo</i><span id="take-picture">拍照</span>
            </a>
            <video id="video" width="320" height="480" autoplay></video>
        </div>
        <div class="col s12" style="display:none;">
            <div class="select">
                <label for="videoSource">Video source: </label><select id="videoSource"></select>
            </div>
            <canvas id="canvas" width="320" height="480"></canvas>
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var getCsrfData = function () {
            var data = {
                key: $("meta[name='csrf-param']").attr("content"),
                value: $("meta[name='csrf-token']").attr("content")
            };
            return data;
        };
        var $merchandiseList = $("#merchandise-list");
        var $button = $("#take-picture");
        $("#face-detect-form").on("change", function () {
            M.toast({html: '请稍等...'});
            $button.html("正在识别中...");
            var csrfData = getCsrfData();
            var formData = new FormData(document.getElementById('face-detect-form'));
            formData.append(csrfData.key, csrfData.value);
            $.ajax({
                url: '/site/face-detect',
                data: formData,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                success: function (response) {
                    $button.html("进一步识别中...");
                    $("#face-detect-img").html('<img src="' + response.data.image + '" />');
                    $("#face-detect-tag").html("");
                    $.each(response.data.result, function (name, value) {
                        $("#face-detect-tag").append('<a href="#" class="waves-effect purple btn-small">' + name + " " + value + '</a>');
                    });
                    var requestData = {};
                    requestData[csrfData.key] = csrfData.value;
                    requestData.age = response.data.params.age;
                    requestData.sex = response.data.params.sex;
                    $.ajax({
                        url: '/site/merchandise-recommend',
                        data: requestData,
                        type: 'POST',
                        success: function (response) {
                            $merchandiseList.html("");
                            $.each(response.data, function (id, item) {
                                //$("#merchandise-list").append('<a class="carousel-item" href="#' + item.id  +'"><img src="' + item.image + '"></a>');
                                $("#merchandise-list").append('<a class="carousel-item" id="qr-code-' + item.id + '" href="' + item.url + '" ></a>');
                            });
                            $merchandiseList.find(".carousel-item").each(function () {
                                var id = $(this).attr("id");
                                var url = $(this).attr("href");
                                new QRCode(id, {
                                    text: url,
                                    width: 300,
                                    height: 300,
                                    colorDark: "#000000",
                                    colorLight: "#ffffff",
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            });
                            $('.carousel').carousel();
                            $merchandiseList.show();
                            var h = $(document).height() - $(window).height();
                            $(document).scrollTop(h);
                        },
                        error: function (data) {
                            console.log("error", data);
                            $button.html("拍照");
                        },
                        complete: function () {
                            $button.html("拍照");
                        }
                    });
                },
                error: function (data) {
                    console.log("error", data);
                    $button.html("拍照");
                },
                complete: function () {
                    $button.html("拍照");
                }
            });
            return false;
        });
    });

    $(document).ready(function () {

        //访问用户媒体设备的兼容方法
        function getUserMedia(constrains, success, error) {
            if (navigator.mediaDevices.getUserMedia) {
                //最新标准API
                navigator.mediaDevices.getUserMedia(constrains).then(success).catch(error);
            } else if (navigator.webkitGetUserMedia) {
                //webkit内核浏览器
                navigator.webkitGetUserMedia(constrains).then(success).catch(error);
            } else if (navigator.mozGetUserMedia) {
                //Firefox浏览器
                navagator.mozGetUserMedia(constrains).then(success).catch(error);
            } else if (navigator.getUserMedia) {
                //旧版API
                navigator.getUserMedia(constrains).then(success).catch(error);
            }
        }

        var video = document.getElementById("video");
        var videoSelect = document.querySelector('select#videoSource');
        var canvas = document.getElementById("canvas");
        var context = canvas.getContext("2d");


        // 获取设备摄像信息
        navigator.mediaDevices.enumerateDevices().then(gotDevices).then(getStream).catch(handleError);

        //成功的回调函数
        function success(stream) {
            console.log('success')
            window.stream = stream;
            //兼容webkit内核浏览器
            var CompatibleURL = window.URL || window.webkitURL;
            //将视频流设置为video元素的源
            video.src = CompatibleURL.createObjectURL(stream);
            //播放视频
            video.muted();
            video.play();
        }

        //异常的回调函数
        function error(error) {
            console.log("访问用户媒体设备失败：", error.name, error.message);
        }

        function getStream() {

            if (window.stream) {
                window.stream.getTracks().forEach(function (track) {
                    track.stop();
                })
            }

            if (navigator.mediaDevices.getUserMedia || navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia) {
                //调用用户媒体设备，访问摄像头
                const constraints = {
                    audio: false,
                    video: {
                        width: {ideal: 320},
                        height: {ideal: 480},
                        frameRate: {
                            ideal: 10,
                            max: 15
                        },
                        deviceId: {exact: videoSelect.value}
                    }
                };
                console.log('获取视频流');
                getUserMedia(constraints, success, error);
            } else {
                alert("你的浏览器不支持访问用户媒体设备");
            }
        }

        function gotDevices(deviceInfos) {
            console.log('deviceInfos')
            console.log('deviceInfos:', deviceInfos);
            for (let i = 0; i !== deviceInfos.length; i++) {
                let deviceInfo = deviceInfos[i];
                var option = document.createElement('option');
                // console.log(deviceInfo)
                if (deviceInfo.kind === 'videoinput') {  // audiooutput  , videoinput
                    option.value = deviceInfo.deviceId;
                    option.text = deviceInfo.label || 'camera ' + (videoSelect.length + 1);
                    videoSelect.appendChild(option);
                }
            }


        }

        function handleError(error) {
            console.log('error:', error)
        }

        videoSelect.onchange = getStream;

        //注册拍照按钮的单击事件
        document.getElementById("capture").addEventListener("click", function () {
            //绘制画面
            console.log('点击事件');
            context.drawImage(video, 0, 0, 480, 320);
        });
    });
</script>
<style type="text/css">

    #video {
        display: block;
        width: 320px;
        height: 480px;
        margin: 0 auto;
        background-color: lightgrey;
    }

    #video-area {
        text-align: center;
    }

    #video-area a {
        margin: 5px;
    }

    .file-field .btn, .file-field .btn-large, .file-field .btn-small {
        float: none !important;
    }

    .face-detect-result .card-action a {
        margin-bottom: 10px;
    }
</style>