<?php

global $crono;

$maxsim['debug']['timing']['sql'] += round($crono * 1000, 3);
$maxsim['debug']['sql']['count']++;