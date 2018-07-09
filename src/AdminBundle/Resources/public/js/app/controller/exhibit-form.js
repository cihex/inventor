function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}

$(function () {
    $('form[name=adminbundle_exhibit]').ajaxForm({
        success: function (json) {
            var result = JSON.parse(json);
            if (result.result != 'success') {
                handleFormErrors(result.errors);
                return;
            }
            alert('Zapisano zmiany.');
            window.location.href = '/admin/exhibit/edit/' + result.id;
        }
    });
});

function handleFormErrors(errors) {
    $.each(errors, function(key, value){
        var $field = $('#adminbundle_exhibit_' + key);
        $field.after('<div id="error-for-' + key + '"><p class="label label-warning">' + value + '</p></div>');
        $field.on('change', function(e) {
            $('#error-for-' + key).remove();
        });
    })
}

$(function () {
    $('#fileupload').fileupload({
        url: Routing.generate('admin_exhibit_upload_photo', {'exhibitId': $('#exhibit-id').val()}),
        dataType: 'json',
        done: function (e, data) {
            var parsedData = JSON.parse(data.result);
            var $toClone = $('#files-file-photoId');
            var names = parsedData.names;
            $.each(parsedData.files, function (index, photoId) {
                var $newPhoto = $toClone.clone();
                $newPhoto.removeClass('hidden');
                $newPhoto.css('display', 'block');
                $newPhoto.attr('id', 'files-file-' + photoId);
                var newPhotoHtml = $newPhoto.html();
                $newPhoto.html(replaceAll(newPhotoHtml, 'photoId', photoId));
                $newPhoto.appendTo($('#files'));
                $.ajax({
                    url: Routing.generate('admin_exhibit_thumb', {'photoName': names[photoId], 'size': 200, 'container': 'gallery'}),
                    success: function(result) {
                        var $photo = $(result);
                        $newPhoto.prepend($photo);
                        updatePhotoIds();
                    }
                });
            });
            updatePhotoIds();
            setTimeout(function () {
                $('#progress .bar').css(
                    'width',
                    '0'
                );
            }, 2000);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});

function updatePhotoIds() {
    var photoIds = [];
    $('.hidden-id').each(function () {
        if ($(this).val() != 'photoId') {
            photoIds.push($(this).val());
        }
    });
    $('#adminbundle_exhibit_photosId').val(photoIds.join());
}
/**
 * @param result
 */
function saveCategoryInExhibitForm(result) {
    $('#adminbundle_exhibit_category').append('<option value="' + result.id + '">' + result.name + ' (' + result.alias + ')</option>');
    $('#adminbundle_exhibit_category').find('option').removeAttr('selected');
    $('#adminbundle_exhibit_category').find('option[value=' + result.id + ']').attr('selected', 'selected');
    $('#adminbundle_exhibit_category').change();
    $('#addEntityForm').hide();
}

/**
 * @param result
 */
function saveOwnerInExhibitForm(result) {
    $('#adminbundle_exhibit_owner').append(
        '<option value="' + result.id + '">' + result.string + '</option>'
    );
    $('#adminbundle_exhibit_owner').find('option').removeAttr('selected');
    $('#adminbundle_exhibit_owner').find('option[value=' + result.id + ']').attr('selected', 'selected');
    $('#adminbundle_exhibit_owner').change();
    $('#addEntityForm').hide();

}

/**
 * @param result
 */
function saveDonorInExhibitForm(result) {
    $('#adminbundle_exhibit_donor').append(
        '<option value="' + result.id + '">' + result.string + '</option>'
    );
    $('#adminbundle_exhibit_donor').find('option').removeAttr('selected');
    $('#adminbundle_exhibit_donor').find('option[value=' + result.id + ']').attr('selected', 'selected');
    $('#adminbundle_exhibit_donor').change();
    $('#addEntityForm').hide();
}

function deleteExhibitPhoto(photoId) {
    $.ajax({
        url: Routing.generate('admin_exhibit_delete_photo', {photoId: photoId}),
        success: function (json) {
            var response = JSON.parse(json);
            if (response.result == 'ok') {
                $('#files-file-' + photoId).remove();
            }
            updatePhotoIds();
        }
    });
    return false;
}

function setVisibilityInExposeCard(photoId) {
    var isActive = $('#visibility-button-' + photoId).hasClass('icon-eye-close');
    $.ajax({
        url: Routing.generate('admin_exhibit_photo_set_visibility', {photoId: photoId, isActive: isActive}),
        success: function (json) {
            var response = JSON.parse(json);
            if (response.result != 'ok') {
                return;
            }
            if (isActive) {
                $('#visibility-button-' + photoId).removeClass('icon-eye-close');
                $('#visibility-button-' + photoId).addClass('icon-eye-open');
            } else {
                $('#visibility-button-' + photoId).removeClass('icon-eye-open');
                $('#visibility-button-' + photoId).addClass('icon-eye-close');
            }
        }
    });
    return false;
}

