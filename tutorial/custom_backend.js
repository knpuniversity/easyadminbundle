$(document).ready(function () {
    var $markdownInputs = $('.js-markdown-input .form-control')

    $markdownInputs.after('<div class="markdown-preview"></div>');

    $markdownInputs.on('keyup', function (e) {
        var html = snarkdown(e.target.value);

        e.target.nextElementSibling.innerHTML = html;
    });

    $markdownInputs.trigger('keyup');
});
