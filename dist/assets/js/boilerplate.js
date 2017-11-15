'use strict';

window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.1.1.min.js"><\/script>');

$.get('../server/header.php', function (data) {
  $('head').append(data);
});
// $.get('../server/footer.php', function(data) { $('footer').append(data); });