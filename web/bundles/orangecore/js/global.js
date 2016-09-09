/**
 * Created by nythe on 28/07/16.
 */
$(document).ready(function() {
    var $class = $('.main-classification');
    $class.each(function(){
        console.log($(this).innerHeight());
        if($(this).innerHeight() >= 258){
            var $button = $('<button></button>');
            var $contParent = $('#' + this.id).parent();
            var $container = $('#' + $contParent.attr('id') + ' .button-develop');

            $button.html('Suite').attr({
                onclick : "developClass('" + this.id + "')",
                class : "dev btn btn-default"
            });
            $container.append($button);
            console.log($button.html());
        }
    });

    $('#search-engine').on('submit', function(e){
        e.preventDefault();
    });
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
            overflow : 'none',
            maxHeight : '100%'
        }, 500);
        //$c.css('max-height', '100%');
    }
    else{
        $c.animate({
            overflow : 'auto',
            maxHeight : '258px'
        }, 500);
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
        success: function (response) {
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
                            var $img = $('<img />');
                            $img.attr({src : "uploads/resources/" + file.type.route + "/" + file.lien, width : 150, alt : file.nom});
                        }
                        else if(file.type.libelle == 'Document'){
                            var $pdf = $('<iframe></iframe>');
                            $pdf.attr({src : "uploads/resources/" + file.type.route + "/" + file.lien, width : 150});
                        }
                        var $typeDetail = $('<p></p>');
                        $typeDetail.attr({css: 'color : #222 !important;'}).html("Type : " + file.type.libelle);
                        var $dlLink = $('<a></a>');
                        $dlLink.attr({href : "uploads/resources/" + file.type.route + "/" + file.lien, target : "_blank"}).html("Télécharger");

                        $clearfix.append($fileCont);
                        $fileCont.append($fileDiv);
                        $fileDiv.append($nameFile);
                        if($img !== undefined){
                            $fileDiv.append($img);
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
                    $fileCont.attr({class: 'col-md-6 file-cont'});
                    var $fileDiv = $('<div></div>');
                    $fileDiv.attr({class: 'panel panel-default col-md-10 file-div'});
                    var $nameFile = $('<h3></h3>');
                    $nameFile.html(elem.nom);
                    if(elem.type.libelle == 'Image'){
                        var $img = $('<img />');
                        $img.attr({src : "uploads/resources/" + elem.type.route + "/" + elem.lien, width : 150, alt : elem.nom});
                    }
                    else if(elem.type.libelle == 'Document'){
                        var $pdf = $('<iframe></iframe>');
                        $pdf.attr({src : "uploads/resources/" + elem.type.route + "/" + elem.lien, width : 150});
                    }
                    var $typeDetail = $('<p></p>');
                    $typeDetail.attr({css: 'color : #222 !important;'}).html("Type : " + elem.type.libelle);
                    var $dlLink = $('<a></a>');
                    $dlLink.attr({href : "uploads/resources/" + elem.type.route + "/" + elem.lien, target : "_blank"}).html("Télécharger");

                    $result.append($fileCont);
                    $fileCont.append($fileDiv);
                    $fileDiv.append($nameFile);
                    if($img !== undefined){
                        $fileDiv.append($img);
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