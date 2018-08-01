<?php
require_once('config.php');
echo __DIR__;
\manager\email::send_email();
