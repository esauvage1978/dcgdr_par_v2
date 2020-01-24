$(document).ready(function() {

    var cadrage_file_index = $('#cadrage_filecount').val() *1 ;
    var cadrage_link_index = $('#cadrage_linkcount').val() *1 ;

    $('#cadrage_add-file').click(function(e) {

        cadrage_addItem('file');
        e.preventDefault();
        return false;
    });

    $('#cadrage_add-link').click(function(e) {
        cadrage_addItem('link');
        e.preventDefault();
        return false;
    });

    function cadrage_addItem(type) {

        if(type === 'file') {
            index = cadrage_file_index;
            cadrage_file_index++;
            name_label='Fichier n°' + (index+1);
        } else if(type === 'link') {
            index = cadrage_link_index;
            cadrage_link_index++;
            name_label='Lien n°' + (index+1);
        }

        var $container = $('#cadrage_media-add-' + type );
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, name_label)
            .replace(/__name__/g,        index)
        ;
        var $prototype = $(template);

        cadrage_addDeleteLink($prototype);
        $container.append($prototype);
    }

    function cadrage_addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger">Supprimer</a>');
        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

    $('.cadrage_media-delete').click(function (e) {
        e.preventDefault();
        var media_url = $(this).attr('data-media');
        var media_id = $(this).attr('data-msg');
        $('#cadrage_media-'+media_url).remove();
        $('#cadrage_'+media_id).remove();
    });



    $(".image-pop").on("click", function() {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});

function cadrage_showMainImage()
{
    $('#trick_images_0_comment').val(new Date());
    $('#image_0').removeClass('d-none')
}
function cadrage_showOtherFile($index)
{
    $('#cadrage_edit_cadrageFiles_' + $index + '_comment').val(new Date());
    $('#cadrageFile_' + $index).removeClass('d-none')
}

function cadrage_showOtherLink($index)
{
    $('#cadrage_edit_cadrageLinks_' + $index + '_comment').val(new Date());
    $('#cadrageLink_' + $index).removeClass('d-none')
}