<?php
$ner = array('10001','10002','10011','10012');
$nee = $ner;
$var = 0 ;
foreach( $ner as  $value )
{
	if(strstr($value,'1000') !== false)
	{
		$var  = 1;
	}
}
//if(in_array('1000',$ner)){
//	echo "yes";
//}else{
//	echo "no";
//}
//die();
var_dump($var);

die();
	//******************php echo()****************
		$变量名="变量值";
		echo ('echo("$变量名"):&nbsp;&nbsp;&nbsp;&nbsp;结果: ');
		echo ("$变量名");
		echo "</br>";
		echo ('echo(\'$变量名\'):&nbsp;&nbsp;&nbsp;&nbsp;       结果: ');
		
		echo ('$变量名');
		echo "</br>";
		echo ('echo (\'输出$变量名\')：   结果: ');
		echo ('输出$变量名');
		echo "</br>";
		echo ('echo ("输出$变量名")：   结果: ');
		echo ("输出$变量名");
		echo "</br>";
		echo ('结论：echo()内用\'\'单引号引入的话里面的内容就是纯字符，不做任何处理，用""双引号引入的话里面的变量就会替换成变量值');
		echo "</br>";
		echo "</br>";
	//******************php支持中文变量****************
		$这是个变量 = "你看，php支持中文变量名";
		var_dump($这是个变量);
		echo "</br>";

	//*****************php支持引用赋值,&符号*****************
		$a = 25;
		$b = &$a;
		echo("引用赋值");
		var_dump($a); 		//25 
		echo "</br>";        
		var_dump($b); 		//25
		echo "</br>";		 
		unset($a);
		echo('unset($a)后：');
		echo "</br>";
		printf('var_dump($a)的结果：');		//null 		//php的引用并不是像c/c++那样是指针移动，unset()其中一个，另一个并不会消失
		var_dump($a);
		echo "</br>";
		printf('var_dump($b)的结果：%f',$b); 		//25   		//（c/c++中，释放一个变量，另一个也不会存在，因为是指针）			
		echo "</br>";
		/*print printf 和 echo 一样，''单引号表示绝对字符，""双引号内的变量会替换成变量值*/
		
	//*****************php支持八、十、十六进制整数*****************
		$Hex = 0x1A; 			//十六进制 0x开头
		$Octal = 0123;			//八进制 0开头
		$Decimal = 120;			//十进制
							/*实际上存进去的都是十进制*/				
		var_dump($Hex);		//输出结果是十进制，26
		echo "</br>";
		var_dump($Octal);		//输出结果是十进制，26
		echo "</br>";
		var_dump($Decimal);		//输出结果是十进制，26
		echo "</br>";
		echo ($Hex);

?>