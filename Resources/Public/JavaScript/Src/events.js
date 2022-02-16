$(function () {
  (function detailView () {
    $('#gem-create-mail').click(function () {
      $('#gem-create-mail').toggleClass('gemc-active').hide();
      $('#gem-mail-message').toggleClass('gemc-show');
    });
    $('#gem-cancel-mail').click(function () {
      $('#gem-create-mail').toggleClass('gemc-active').show();
      $('#gem-mail-message').toggleClass('gemc-show');
    });
    if ($('#gem-mail-message .f3-form-error').length) {
      $('#gem-create-mail').addClass('gemc-active');
      $('#gem-mail-message').addClass('gemc-show');
    }
    $('#gem-create-note').click(function () {
      $('#gem-create-note').toggleClass('gemc-active').hide();
      $('#gem-note').toggleClass('gemc-show');
    });
    $('#gem-cancel-note').click(function () {
      $('#gem-create-note').toggleClass('gemc-active').show();
      $('#gem-note').toggleClass('gemc-show');
    });
  }) ();

  (function tableView () {
    $('#gem-prev-event').click(function () {
      $('.gem-event-table .gemc-col-event.gemc-hidden').last().removeClass('gemc-hidden');
    });
    $('#gem-next-event').click(function () {
      $('.gem-event-table .gemc-col-event:not(.gemc-hidden)').first().addClass('gemc-hidden');
    });
  }) ();
});
