<?php


function getSafeFormValue($variables, $key)
{
  return isset($variables[$key]) ? $variables[$key] : "";
}