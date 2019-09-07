<?php

/* @var $this yii\web\View */
/* @var $faceDetectForm frontend\models\FaceDetectForm */

$this->title = 'Face Detect';

?>

<div class="container-fluid">
    <div class="row">
        <div class="col s12">
            <div id="face-area">
                <form action="/site/face-detect" id="face-detect-form">
                    <div class="file-field input-field">
                        <div class="btn btn-large waves-effect waves-light red">
                            <span>拍照</span>
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
                    <div class="carousel" id="merchandise-list" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var getCsrfData = function () {
            var key = $("meta[name='csrf-param']").attr("content");
            var data = {};
            data[key] = $("meta[name='csrf-token']").attr("content");
            return data;
        };
        $("#face-detect-form").on("change", function () {
            var requestData = getCsrfData();
            $.ajax({
                url: '/site/face-detect',
                data: requestData,
                type: 'POST',
                success: function (response) {
                    $("#face-detect-img").html('<img src="' + response.data.image + '" />');
                    $("#face-detect-tag").html("");
                    $.each(response.data.result, function(name, value) {
                        $("#face-detect-tag").append('<a href="#" class="waves-effect purple btn-small">' + name + " " + value + '</a>');
                    });
                    $.ajax({
                        url: '/site/merchandise-recommend',
                        data: requestData,
                        type: 'POST',
                        success: function (response) {
                            $("#merchandise-list").show();
                            $("#merchandise-list").html("");
                            $.each(response.data, function(id, item) {
                                $("#merchandise-list").append('<a class="carousel-item" href="#' + item.id  +'"><img src="' + item.image + '"></a>');
                            });
                            $('.carousel').carousel();
                            var h = $(document).height()-$(window).height();
                            $(document).scrollTop(h);
                        },
                        error: function (data) {
                            console.log("error", data);
                        },
                    });
                },
                error: function (data) {
                    console.log("error", data);
                },
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

    .carousel .carousel-item > img {
        height: 100%;
        width: auto !important;
    }

    .row {
        margin-bottom: 5px !important;
    }
</style>