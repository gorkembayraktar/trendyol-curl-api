<?php 

$requireList  = ["helper.file","helper.http","helper.response" ,
"app.account","app.api","app.messages","app.product","app.user","app.trendyol"
];
foreach($requireList as $require) require str_replace('.',"/",$require).".php";
FileHelper::folder(__DIR__.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR);