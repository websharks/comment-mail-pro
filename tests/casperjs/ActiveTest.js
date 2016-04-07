$.test.begin('Check if plugin is active.', function () {
  $.start();
  $$.wp.thenLogin();

  if ($$.wp.isMultisite()) {
    $.thenOpen($$.www.url('/wp-admin/network/admin.php?page=' + $$$.GLOBAL_NS));
  } else {
    $.thenOpen($$.www.url('/wp-admin/admin.php?page=' + $$$.GLOBAL_NS));
  }
  $.then(function () {
    $.test.assertExists('select[name="' + $$$.GLOBAL_NS + '[save_options][enable]"] > option[value="1"][selected]');
  });
  $$.wp.thenLogout();

  $.run(function () {
    $.test.done();
  });
});
