<?php


function addToApiExecution(string $callName, object $class) : void
{   
    global $api_executors;

    if(!method_exists($class, "Api")) die("Api executor $callName is missing method Api");

    if(!key_exists($callName, $api_executors)) $api_executors[$callName] = $class;

    return;
}