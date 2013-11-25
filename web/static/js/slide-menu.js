(function(C2C, $) {
  var left_drawer = $('.snap-drawer-left'), right_drawer = $('.snap-drawer-right');
  C2C.snap = new Snap({
    element: document.getElementById('holder'),
    addBodyClasses: false, // changing body classes causes huge style recalculation, making everything laggy
    disable: 'right'
  });
  C2C.snap.on('expandLeft', function() {
    console.log('left');
    right_drawer.hide();
  }).on('expandRight', function() {
    console.log('right');
    left_drawer.hide();
  });
})(window.C2C = window.C2C || {}, jQuery);
