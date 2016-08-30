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
});

function showModal(id){
	var $imgSrc = $('#' + id).find('img').attr('src');
	$('.img-preview').attr('src', $imgSrc);

	console.log($imgSrc);
}

function developClass(id){
    var $c = $('#' + id);

    $c.animate({
        overflow : 'none',
        maxHeight : '100%'
    }, 3000);
}
