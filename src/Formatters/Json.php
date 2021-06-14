<?php

namespace Project\Package\Formatters\Json;

function displayJson($arr): string
{
    return json_encode($arr, JSON_PRETTY_PRINT);
}
