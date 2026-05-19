<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$classes=[
'App\\Services\\AhmedabadScraperService'=>'Ahmedabad',
'App\\Services\\BangaloreScraperService'=>'Bangalore',
'App\\Services\\ChennaiScraperService'=>'Chennai',
'App\\Services\\DelhiScraperService'=>'Delhi',
'App\\Services\\HyderabadScraperService'=>'Hyderabad',
'App\\Services\\JaipurScraperService'=>'Jaipur',
'App\\Services\\JodhpurScraperService'=>'Jodhpur',
'App\\Services\\KolkataScraperService'=>'Kolkata',
'App\\Services\\KotaScraperService'=>'Kota',
'App\\Services\\MumbaiScraperService'=>'Mumbai',
'App\\Services\\PuneScraperService'=>'Pune',
];
$required=[
'bloodbanks'=>['name_en','city','state','phone','emergency_phone','address_en'],
'hospitals'=>['name_en','city','state','emergency_phone','address'],
'doctors'=>['first_name','city','department_name_en','hospital_name_en']
];
$call=function($class,$method,$city){$ref=new ReflectionMethod($class,$method);$ref->setAccessible(true);$res=$ref->invoke(null,$city);return is_array($res)?$res:[];};
foreach($classes as $class=>$city){
$bb=$call($class,'getFallbackBloodBanks',$city);
$h=$call($class,'getFallbackHospitals',$city);
$d=$call($class,'getFallbackDoctors',$city);
echo "=== {$city} ===\n";
foreach(['bloodbanks'=>$bb,'hospitals'=>$h,'doctors'=>$d] as $name=>$rows){
$miss=array_fill_keys($required[$name],0);
$badCity=0;
foreach($rows as $r){
foreach($required[$name] as $f){ if(!isset($r[$f]) || trim((string)$r[$f])==='') $miss[$f]++; }
if(isset($r['city']) && strcasecmp(trim((string)$r['city']),$city)!==0){ if(!($city==='Delhi' && strcasecmp(trim((string)$r['city']),'New Delhi')===0)) $badCity++; }
}
echo strtoupper($name).': count='.count($rows).' bad_city='.$badCity;
$parts=[];
foreach($miss as $f=>$n){ if($n>0) $parts[]="{$f}:{$n}"; }
if($parts) echo ' missing{'.implode(',',$parts).'}';
echo "\n";
}
}