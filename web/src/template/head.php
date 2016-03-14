<?php

function prettifyName($uuid){
    $w = explode('_', $uuid);
    return implode(' ', array_map(function ($s){return ucfirst($s);}, $w));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$CONTEXT['title']?></title>
    <link rel="icon" type="image/png" href="<?=$CONTEXT['basePath']?>src/icon/favicon.png">
    <link rel="shortcut icon" href="<?=$CONTEXT['basePath']?>src/icon/favicon.ico">
    <script src="http://use.edgefonts.net/source-code-pro.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet" type="text/css" />
    <link href="<?=$CONTEXT['basePath']?>src/css/style.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="mainWrap">

