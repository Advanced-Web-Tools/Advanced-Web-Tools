<?php

$metrics = new awtMetrics;

if(!defined('DASHBOARD') && !defined('JOB')) $metrics->startMetrics();
