(function($) {
    //'use strict';
    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
})(jQuery);

var $profile_image_exist = false;
jQuery(document).ready(function($) {
    // Hide the main image
    $img_ele = $('.um-register .um-single-image-preview');
    $img_ele.children().first().next().hide();

    // Get submitted main image on form submit
    $profile_image = $img_ele.children().first().next();
    $profile_image_src = $profile_image.prop('src');

    if (typeof $profile_image_src !== 'undefined') {
        $profile_image_exist = true;
        // Set multiple images with different qualities
        set_img_element($profile_image_src, $img_ele, $);
    }

    $(document).on('click', '.um-finish-upload', function() {
        // Hide the main image
        $img_ele = $('.um-register .um-single-image-preview');
        $img_ele.children().first().next().hide();

        // Get the cropped version
        $img = $('.cropper-hidden');
        $img_src = $img.prop('src');

        // Set multiple images with different qualities
        setTimeout(function() {
            set_img_element($img_src, $img_ele, $);
        }, 4000);
    });

    $(document).on('click', '.um-register .um-single-image-preview .um-icon-close', function(event) {
        $('.pp-img').remove();
    });

    // Select and Set the selected image 'src' value in a hidden field inside form
    $(document).on('click', '#pp-img-inner img', function(event) {
        $('#pp-img-inner img').removeClass('add-border');
        $(this).addClass('add-border');
        $src_val = $(this).prop('src');

        $ele_exist = $('.hidden_data').length;
        if ($ele_exist) {
            $('.hidden_data').val($src_val);
        }
    });

    // Check src value exist before submit form
    $(document).on('click', '#um-submit-btn', function(event) {
        $continue = true;
        $hdata = $('.hidden_data');
        $hdata.each(function(index) {
            val = $(this).val();
            if (val.indexOf('://') == -1) {
                // src value not set inside form, return error
                alert('Please select at least one image');
                $continue = false;
                return false;
            }
        });

        if ($continue == false) {
            stop_form_submission($);
        } else {
            if ($('div.um-register').length) {
                //All good submit the form
                document.forms[0].submit();
            }
        }
    });

    // Refresh Images
    $(document).on('click', '#um-refresh', function(e) {
        e.preventDefault();
        $pp_img_inner = $('#pp-img-inner');
        $html = $pp_img_inner.html();
        console.log($html);
        $('.th-img').remove();
        $pp_img_inner.prepend($html);
    });
});

function set_img_element(img_url, $img_elem, $) {
    img_url = img_url.split(/[?#]/)[0];
    img_name = img_url.split("/").pop();
    if (img_name == 'empty_file' || $.trim(img_name) == '') {
        $('.pp-img').remove();
        return;
    }

    partial_path = LOCAL_VAR.cimage + 'imgd.php?src=temp/' + img_name;

    $html = '';
    $html = $html + '<div id="pp-img" class="pp-img">';
    $html = $html + '<div id="pp-img-inner" class="pp-img-inner">';
    $html = $html + '<img id="pp-1" class="th-img" src="' + partial_path + '&width='+ LOCAL_VAR.iw +'&height='+ LOCAL_VAR.ih + '&crop-to-fit&q='+ LOCAL_VAR.iq1 +'&f=grayscale&nc&save-as=jpg">';
    $html = $html + '<img id="pp-2" class="th-img" src="' + partial_path + '&width='+ LOCAL_VAR.iw +'&height='+ LOCAL_VAR.ih + '&crop-to-fit&q='+ LOCAL_VAR.iq2 +'&f=grayscale&nc&save-as=jpg">';
    $html = $html + '<img id="pp-3" class="th-img" src="' + partial_path + '&width='+ LOCAL_VAR.iw +'&height='+ LOCAL_VAR.ih + '&crop-to-fit&q='+ LOCAL_VAR.iq3 +'&f=grayscale&nc&save-as=jpg">';
    $html = $html + '<img id="pp-4" class="th-img" src="' + partial_path + '&width='+ LOCAL_VAR.iw +'&height='+ LOCAL_VAR.ih + '&crop-to-fit&q='+ LOCAL_VAR.iq4 +'&f=grayscale&nc&save-as=jpg">';
    $html = $html + '</div>';
    $html = $html + '<input type="hidden" name="img_data" class="hidden_data"><div class="um-refresh"><a href="javascript:void(0);" id="um-refresh"> Select the clearest photo of yourself</a></div>';
    $html = $html + '</div>';

    $('.pp-img').remove();
    $img_elem.parent().prepend($html);
}

// Stop from submission
function stop_form_submission($) {
    if ($('div.um-register').length) {
        $('form').each(function() {
            $(this).submit(function(e) {
                e.preventDefault();
                $('#um-submit-btn').prop("disabled", false);
                return false;
            });
        });
    }
}