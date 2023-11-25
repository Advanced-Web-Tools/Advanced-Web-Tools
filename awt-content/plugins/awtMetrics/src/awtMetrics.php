<?php

$metrics = new awtMetrics;

if(!defined('DASHBOARD') && !defined('JOB') && !defined('API')) $metrics->startMetrics();
