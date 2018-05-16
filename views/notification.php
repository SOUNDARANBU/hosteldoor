<?php
require_once('../config.php');
\manager\page::header();
\manager\page::backgroud_img(true);
\manager\page::title('Dashboard');

echo '<button onclick="notifyMe()"> Notify </button>';
\manager\page::footer();
echo '<script>
document.addEventListener(\'DOMContentLoaded\', function () {
  if (!Notification) {
    alert(\'Desktop notifications not available in your browser. Try Chromium.\'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
});

function notifyMe() {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification(\'Notification title\', {
      icon: \'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png\',
      body: "Hey there! You\'ve been notified!",
    });

    notification.onclick = function () {
      window.open("http://stackoverflow.com/a/13328397/1269037");      
    };
  }
}
</script>';