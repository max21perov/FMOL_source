<?php

// 该文件演示了php是如何与flash交互的


$n = $_POST["Name"]; //接受你插入进来的变量

// 将变量返回到flash中
print "list=123" . $n;



exit(0);


?>


