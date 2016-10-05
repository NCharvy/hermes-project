/**
 * Created by nythe on 28/07/16.
 */

$(document).ready(function() {
    /*if("matchMedia" in window) {
        if (window.matchMedia("(min-width : 992px)").matches) {
            var $class = $('.main-classification');
            $class.each(function () {
                console.log($(this).innerHeight());
                if ($(this).innerHeight() >= 258) {
                    var $button = $('<button></button>');
                    var $contParent = $('#' + this.id).parent();
                    var $container = $('#' + $contParent.attr('id') + ' .button-develop');

                    $button.html('Suite').attr({
                        onclick: "developClass('" + this.id + "')",
                        class: "dev btn btn-default"
                    });
                    $container.append($button);
                    console.log($button.html());
                }
            });
        }
    }*/

    $('#search-engine').on('submit', function(e){
        e.preventDefault();
    });

    $('.delete-trash').on('click', function(e){
        e.preventDefault();
    });

    $('.close').on('click', function(){
        $('.modal').modal('hide');
    })
});

function showModal(id){
	var $imgSrc = $('#' + id).find('img').attr('src');
	$('.img-preview').attr('src', $imgSrc);

	console.log($imgSrc);
}

function developClass(id){
    var $c = $('#' + id);
    var $b = $c.parent().find('button');

    if($c.css('max-height') == '258px'){
        $c.animate({
            maxHeight : '100%'
        }, 500);
    }
    else{
        $c.animate({
            maxHeight : '258px'
        }, 500);
    }
}

function validateDeletion(id = null, type = null, multi = null){
    if(multi == null){
        if(confirm('Voulez-vous vraiment supprimer cet élément ?')){
            window.location = '/back/' + type + '/delete/' + id;
        }
        else{
            return false;
        }
    }
    else{
        if(confirm('Voulez-vous vraiment supprimer ces fichiers ?')){
            window.location = '/back/archive/release';
        }
        else{
            return false;
        }
    }
}

function validateArchiveFile(id){
    if(confirm('Voulez-vous vraiment archiver ce fichier ?')){
        window.location = '/back/fichier/archive/' + id;
    }
    else{
        return false;
    }
}

/** AJAX **/

function loadCustomSearch(form){
    var $result = $('#result-custom');
    var $form = $('#search-engine').serializeArray();
    var custom = [];
    var i = 0;

    if($result.length > 0){
        $result.empty();
    }
    $.ajax({
        type: "POST",
        url: '/api/load_custom_results',
        data: $form,
        async: true,
        dataType: "json",
        beforeSend: function(){
            $load = $('<img />');
            $load.attr({src : '/uploads/visuels/load.gif', id : 'load-gif'});
            $('#result-custom').append($load);
        },
        success: function (response) {
            $('#load-gif').remove();
            if(response.query.length > 0){
                custom = response.query;
            }
            custom.forEach(function(elem){
                if(elem.fichiers !== undefined){
                    var $clearfix = $('<div></div>');
                    $clearfix.attr({class: 'clearfix'});
                    var $lblType = $('<h3></h3>');
                    $lblType.html(elem.libelle);

                    $clearfix.append($lblType);                  
                    elem.fichiers.forEach(function(file){
                        i++;
                        var $fileCont = $('<div></div>');
                        $fileCont.attr({class: 'col-md-6 file-cont'});
                        var $fileDiv = $('<div></div>');
                        $fileDiv.attr({class: 'panel panel-default col-md-10 file-div'});
                        var $nameFile = $('<h3></h3>');
                        $nameFile.html(file.nom);
                        if(file.type.libelle == 'Image'){
                            var idModal = 'a-click-' + i;
                            var $aModal = $('<a id="' + idModal + '" class="click-image" data-toggle="modal" data-target="#show-image" href="" onclick="showModal(this.id);"></a>');
                            var $img = $('<img />');
                            $img.attr({src : "uploads/resources/" + file.type.route + "/" + file.lien, width : 200, alt : file.nom});
                            $aModal.append($img);
                        }
                        else if(file.type.libelle == 'Document'){
                            var $pdf = $('<iframe></iframe>');
                            $pdf.attr({src : "uploads/resources/" + file.type.route + "/" + file.lien, width : 200});
                        }
                        var $typeDetail = $('<p></p>');
                        $typeDetail.attr({css: 'color : #222 !important;'}).html("Type : " + file.type.libelle);
                        var $dlLink = $('<a></a>');
                        $dlLink.attr({href : "uploads/resources/" + file.type.route + "/" + file.lien, target : "_blank"}).html("Télécharger");

                        $clearfix.append($fileCont);
                        $fileCont.append($fileDiv);
                        $fileDiv.append($nameFile);
                        if($img !== undefined){
                            $fileDiv.append($aModal);
                        }
                        else if($pdf !== undefined){
                            $fileDiv.append($pdf);
                        }
                        $fileDiv.append($typeDetail);
                        $fileDiv.append($dlLink);
                    });

                    $result.append($clearfix);
                    console.log(elem);
                }
                else{
                    console.log(elem);

                    i++;
                    var $fileCont = $('<div></div>');
                    $fileCont.attr({class: 'col-md-6 file-cont cont-spe'});
                    var $fileDiv = $('<div></div>');
                    $fileDiv.attr({class: 'panel panel-default col-md-10 file-div'});
                    var $nameFile = $('<h3></h3>');
                    $nameFile.html(elem.nom);
                    if(elem.type.libelle == 'Image'){
                        var idModal = 'a-click-' + i;
                        var $aModal = $('<a id="' + idModal + '" class="click-image" data-toggle="modal" data-target="#show-image" href="" onclick="showModal(this.id);"></a>');
                        var $img = $('<img />');
                        $img.attr({src : "uploads/resources/" + elem.type.route + "/" + elem.lien, width : 200, alt : elem.nom});
                        $aModal.append($img);
                    }
                    else if(elem.type.libelle == 'Document'){
                        var $pdf = $('<iframe></iframe>');
                        $pdf.attr({src : "uploads/resources/" + elem.type.route + "/" + elem.lien, width : 200});
                    }
                    var $typeDetail = $('<p></p>');
                    $typeDetail.attr({style: 'color : #222 !important;'}).html("Type : " + elem.type.libelle);
                    var $dlLink = $('<a></a>');
                    $dlLink.attr({href : "uploads/resources/" + elem.type.route + "/" + elem.lien, target : "_blank"}).html("Télécharger");

                    $result.append($fileCont);
                    $fileCont.append($fileDiv);
                    $fileDiv.append($nameFile);
                    if($img !== undefined){
                        $fileDiv.append($aModal);
                    }
                    else if($pdf !== undefined){
                        $fileDiv.append($pdf);
                    }
                    $fileDiv.append($typeDetail);
                    $fileDiv.append($dlLink);
                }
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus + " " + errorThrown);
        },
    });
}

function loadFamilies(thema) {
    $.ajax({
        type: "POST",
        url: '/api/load_families',
        data: '{"idthema": ' + thema + '}',
        async: true,
        dataType: "json",
        success: function (response) {
            var data_cat = [];
            var $cat = $('#fam');
            if(response.data.length > 0){
                fam = response.data;
            }
            if($('.new').length > 0){
                $('.new').remove();
            }
            $.each(fam, function(key, value){
                var $c = $('<option class="new" value="'+ fam[key].id +'">' + fam[key].libelle + '</option>');
                $cat.append($c);
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus + " " + errorThrown);
        },
    });
}

function loadSubFamilies(fam) {
    $.ajax({
        type: "POST",
        url: '/api/load_sub_families',
        data: '{"idfam": ' + fam + '}',
        async: true,
        dataType: "json",
        success: function (response) {
            var data_subcat = [];
            var $scat = $('#sfam');
            if(response.data.length > 0){
                subfam = response.data;
            }
            if($('.nouveau').length > 0){
                $('.nouveau').remove();
            }
            $.each(subfam, function(key, value){
                var $sc = $('<option class="nouveau" value="'+ subfam[key].id +'">' + subfam[key].libelle + '</option>');
                $scat.append($sc);
            });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus + " " + errorThrown);
        },
    });
}

function loadCreatedArchive(del = null){
    if (del != null){
        del = 1;
    }
    $.ajax({
        type: "POST",
        url: '/api/load_created_archive',
        data : '{"del" : ' + del + '}',
        async: true,
        dataType: "json",
        success: function (response) {
            $cont = $('#zip');
            if ($('#dl-archive').length > 0) {
                $('#dl-archive').remove();
            }

            if (response.data.length > 0) {
                archive = response.data;

                $link = $('<a></a>');
                $link.attr({
                    href: '/uploads/zip/' + archive,
                    id: 'dl-archive',
                    target: '_blank',
                    style: 'display : block;'
                }).html('Télécharger la sauvegarde');
                $cont.append($link);

                console.log(archive);
            }
            $('.modal').modal('hide');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus + " " + errorThrown);
        },
    });
}