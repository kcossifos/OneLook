<?php

require 'vendor/autoload.php';

define('SITE_URL', 'http://localhost:8888/OneLook/OneLook/OneLook3/profile.php?u=26');

$paypal = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    'AentXU8RfB1dKrLV6RtleneHqavLwXeD9K8YRts0SWb06YuYHjruFyu9O7HNzZ5e1-U8RdRm4ZWfNnQk',
    'EOrBmfMEIRgm6qvpxfR_j6DTtUp_8EHKyX1tTg2VvuXCLbr-DvyqiJdEGQ8i5OP2PyUT8onaF2V6H-TD'
    )
  )

?>
