<?php

/* @var $this yii\web\View */
/* @var $faceDetectForm frontend\models\FaceDetectForm */

$this->title = 'Face Detect';

?>

<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <div id="face-area">
                <form action="/site/face-detect" id="face-detect-form" name="face-detect-form">
                    <div class="file-field input-field">
                        <div class="btn btn-large waves-effect waves-light red">
                            <i class="material-icons left">add_a_photo</i><span id="take-picture">拍照</span>
                            <input type="file" name="filename"  accept="image/*" capture="camera">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m7">
            <div class="card face-detect" id="face-detect">
                <div class="card-image" id="face-detect-img">
                    <img src="/images/avatar.jpg">
                </div>
                <div class="card-content" id="face-detect-tag">
                    <p>拍个照吧</p>
                </div>
                <div class="card-action">
                    <div class="carousel-deprecated" id="merchandise-list" style="display: none;"></div>
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
                processData: false,
                contentType: false,
                success: function (response) {
                    $("#face-detect-img").html('<img src="' + response.data.image + '" />');
                    $("#face-detect-tag").html("");
                    $.each(response.data.result, function(name, value) {
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
                            $.each(response.data, function(id, item) {
                                //$("#merchandise-list").append('<a class="carousel-item" href="#' + item.id  +'"><img src="' + item.image + '"></a>');
                                $("#merchandise-list").append('<a class="carousel-item" id="qr-code-' + item.id  + '" href="' + item.url  + '" ></a>');
                            });
                            $merchandiseList.find(".carousel-item").each(function() {
                                var id = $(this).attr("id");
                                var url = $(this).attr("href");
                                new QRCode(id, {
                                    text: url,
                                    width: 300,
                                    height: 300,
                                    colorDark : "#000000",
                                    colorLight : "#ffffff",
                                    correctLevel : QRCode.CorrectLevel.H
                                });
                            });
                            $('.carousel').carousel();
                            $merchandiseList.show();
                            var h = $(document).height()-$(window).height();
                            $(document).scrollTop(h);
                        },
                        error: function (data) {
                            console.log("error", data);
                            $button.html("拍照");
                        },
                        complete: function() {
                            $button.html("拍照");
                        }
                    });
                },
                error: function (data) {
                    console.log("error", data);
                    $button.html("拍照");
                },
                complete: function() {
                    $button.html("进一步识别中...");
                }
            });
            return false;
        });

    });
</script>
<style type="text/css">
    #face-area {
        padding: 40% 0;
        text-align: center;
    }

    .file-field .btn, .file-field .btn-large, .file-field .btn-small {
        float: none !important;
    }

    .face-detect-result .card-action a {
        margin-bottom: 10px;
    }

    .carousel .carousel-item {
        width: 100%;
        height: 100%;
    }

    .card .card-content a {
        margin-bottom: 5px;
        margin-right: 5px;
    }

    /*
    .carousel .carousel-item > img {
        height: 100%;
        width: auto !important;
    }
     */

    .row {
        margin-bottom: 5px !important;
    }
</style>