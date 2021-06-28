$(function () {
  (function detailView () {
    $('#gem-create-mail').click(function () {
      $('#gem-create-mail').toggleClass('gemc-active');
      $('#gem-mail-message').toggleClass('gemc-show');
    });
    if ($('#gem-mail-message .f3-form-error').length) {
      $('#gem-create-mail').addClass('gemc-active');
      $('#gem-mail-message').addClass('gemc-show');
    }
    $('#gem-create-note').click(function () {
      $('#gem-create-note').toggleClass('gemc-active');
      $('#gem-note').toggleClass('gemc-show');
    });
    if ($('#gem-mail-message .f3-form-error').length) {
      $('#gem-create-note').addClass('gemc-active');
      $('#gem-note').addClass('gemc-show');
    }
  }) ();

  (function matrixView () {
    $('#gem-prev-event').click(function () {
      $('.gem-event-matrix .gemc-col-event.gemc-hidden').last().removeClass('gemc-hidden');
    });
    $('#gem-next-event').click(function () {
      $('.gem-event-matrix .gemc-col-event:not(.gemc-hidden)').first().addClass('gemc-hidden');
    });
  }) ();
});
