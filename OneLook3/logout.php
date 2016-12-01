<?php

include "settings/init.php";

$the_user->logout();

header("Location: $set_class->url");
