(function(C2C, $) {
  //var left_drawer = $('.snap-drawer-left'), right_drawer = $('.snap-drawer-right');

  // menu initialisation
  $('.snap-drawer-left ul').each(function() {
    var $this = $(this);
    var level = $this.parentsUntil('.snap-drawer', 'ul').length;
    $this.attr('data-level', level);
    if (level == 0) {
      $this.addClass('open');
    } else {
      $this.prev().click(function(e) {
        e.preventDefault();
        $this.addClass('open');
      });
    }
  });

  $('.snap-back').click(function() {
    $(this).closest('ul').removeClass('open');
  });

  $('.snap-drawers').height($('#holder').height());

  // activate snapjs
  C2C.snap = new Snap({
    element: document.getElementById('holder'),
    dragger: document.getElementById('page_header'),
    addBodyClasses: false, // changing body classes causes huge style recalculation, making everything laggy
    disable: 'right'
  });
  C2C.snap.on('close', function() {
    console.log('closed');
  }).on('end', function() {
    console.log('end');
    $('[data-level=1]').removeClass('open');
  });
})(window.C2C = window.C2C || {}, jQuery);
