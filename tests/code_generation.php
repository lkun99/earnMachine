<?php
$all =[];
for($i=0;$i<100000;$i++){
$x = 15;
$set = array(); 
 
while(true) {
	   $rand = mt_rand(0,9);
 
	array_push($set,$rand);
	   if(count($set)==$x) 
	   break;
}
	$string = implode('',$set);
array_push($all,$string);
}
print_r(array_count_values($all));

