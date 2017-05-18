function dataURLtoMimeType(dataURL) {
    var BASE64_MARKER = ';base64,';
    var data;

    if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        data = decodeURIComponent(parts[1]);
    } else {
        var parts = dataURL.split(BASE64_MARKER);
        var contentType = parts[0].split(':')[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;

        data = new Uint8Array(rawLength);

        for (var i = 0; i < rawLength; ++i) {
            data[i] = raw.charCodeAt(i);
        }
    }

    var arr = data.subarray(0, 4);
    var header = "";
    for(var i = 0; i < arr.length; i++) {
        header += arr[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            mimeType = "image/png";
            break;
        case "47494638":
            mimeType = "image/gif";
            break;
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
            mimeType = "image/jpeg";
            break;
        default:
            mimeType = ""; // Or you can use the blob.type as fallback
            break;
    }

    return mimeType;
}

$(function () {

    'use strict';

    var console = window.console || {log: function () {
        }},
    $alert = $('.docs-alert'),
            $message = $alert.find('.message'),
            showMessage = function (message, type) {
                $message.text(message);

                if (type) {
                    $message.addClass(type);
                }

                $alert.fadeIn();

                setTimeout(function () {
                    $alert.fadeOut();
                }, 3000);
            };

    // Demo
    // -------------------------------------------------------------------------

    (function () {
        var $image = $('.img-container > img'),
                $dataX = $('#dataX'),
                $dataY = $('#dataY'),
                $dataHeight = $('#dataHeight'),
                $dataWidth = $('#dataWidth'),
                $dataRotate = $('#dataRotate'),
                options = {
                    crop: function (data) {
                        $dataX.val(Math.round(data.x));
                        $dataY.val(Math.round(data.y));
                        $dataHeight.val(Math.round(data.height));
                        $dataWidth.val(Math.round(data.width));
                        $dataRotate.val(Math.round(data.rotate));

                    }
                };

//        $image.cropper(options);


        // Methods
        $(document.body).on('click', '[data-method]', function () {
            var data = $(this).data(),
                    $target,
                    result;

            if (!$image.data('cropper')) {
                return;
            }

            if (data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                            console.log(e.message);
                        }
                    }
                }

                result = $image.cropper(data.method, data.option);

                if (data.method === 'getCroppedCanvas') {

                    $('#canvasShow').css("display", "none").html(result);
					$('#canvasShow').trigger('canvasReady');

                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        console.log(e.message);
                    }
                }

            }
        }).on('keydown', function (e) {

            if (!$image.data('cropper')) {
                return;
            }

            /*switch (e.which) {
             case 37:
             e.preventDefault();
             $image.cropper('move', -1, 0);
             break;
             
             case 38:
             e.preventDefault();
             $image.cropper('move', 0, -1);
             break;
             
             case 39:
             e.preventDefault();
             $image.cropper('move', 1, 0);
             break;
             
             case 40:
             e.preventDefault();
             $image.cropper('move', 0, 1);
             break;
             }*/

        });

        function detectIE() {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
            }

            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf('rv:');
                return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
            }

            var edge = ua.indexOf('Edge/');
            if (edge > 0) {
                // IE 12 => return version number
                return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
            }

            // other browser
            return false;
        }
//        var IEversion = detectIE();
//        if (IEversion !== false) {
//            $('#inputImage').css('display', 'block');
//        }

        // Import image
        var $inputImage = $('#inputImage'),
                URL = window.URL || window.webkitURL,
                blobURL, sizeF, file2;

        if (URL) {
            $inputImage.change(function () {

                var files = this.files,
                        file;

                if (!$image.data('cropper')) {
                    return;
                }
                file2 = files[0];

//                if (IEversion !== false) {
//                    sizeF = 0;
//                } else {
//                    sizeF = parseFloat(file2.size / 1024).toFixed(2);
//                }
                sizeF = parseFloat(file2.size / 1024).toFixed(2);
                if (sizeF > 2048)
                {
                    $('#screenoverlay').fadeOut();
                    //alert('Image should not be more than 2MB');
                    //return false;
					$(".cropcancel").trigger("click");
                    $('#cropper-example-2-modal').modal('hide');
					$("#page_ajax").html('').hide();
					$(".popup_container").css({'display': 'block'});
					$(".confirm_name .confirm_name_form").html('<p>Image should not be more than 2MB.</p><input type="button" value="OK">');
					$(".confirm_name").css({'display': 'block'});
					$('#screenoverlay').fadeOut();
					return false;
                } else {
                    if (files && files.length) {
                        file = files[0];
                        $("#filenamest").val(file.name);
                        $("#filetypest").val(file.type);
                        
                        if ((file.type.match(/image.jpg/)) || (file.type.match(/image.jpeg/)) || (file.type.match(/image.png/))) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                if (/^image\/\w+$/.test(file.type)) {
                                    var mimeType = dataURLtoMimeType(reader.result);
                                    $("#filetypest").val(mimeType);
                                    blobURL = URL.createObjectURL(file);
                                    $image.one('built.cropper', function () {
                                        URL.revokeObjectURL(blobURL); // Revoke when load complete
                                    }).cropper('reset').cropper('replace', blobURL);
                                    if (angular) {
                                      var scope = angular.element($('#cropper-example-2-modal')).scope();
                                      scope.$apply(function(){
                                        scope.showCropBtn = true;
                                      });
                                    }
                                    $inputImage.val('');
                                } else {
                                    showMessage('Please choose an image file.');
                                }
                            }
                            reader.readAsDataURL(file);
                        }
                        else
                        {
                            $(".cropcancel").trigger('click');
                            $('#cropper-example-2-modal').modal('hide');
                            $('#screenoverlay').hide();
                            $('.popup_container').fadeIn();
                            $('.image_type').css('display', 'block');
                            $('.image_type .image_type_body .image_type_form input:button').on('click', function () {
                                $('.image_type').hide();
                                $('.popup_container').hide();
                            })
                        }
                    }
                }
            });
        } else {
            $inputImage.parent().remove();
        }


        // Options
        /*$('.docs-options :checkbox').on('change', function () {
            var $this = $(this),
                    cropBoxData,
                    canvasData;

            if (!$image.data('cropper')) {
                return;
            }

            options[$this.val()] = $this.prop('checked');

            cropBoxData = $image.cropper('getCropBoxData');
            canvasData = $image.cropper('getCanvasData');
            options.built = function () {
                $image.cropper('setCropBoxData', cropBoxData);
                $image.cropper('setCanvasData', canvasData);
            };

            $image.cropper('destroy').cropper(options);
        });*/


        // Tooltips
//    $('[data-toggle="tooltip"]').tooltip();

    }());

});
