<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$classes=[
'App\\Services\\AhmedabadScraperService'=>'Ahmedabad','App\\Services\\BangaloreScraperService'=>'Bangalore','App\\Services\\ChennaiScraperService'=>'Chennai','App\\Services\\DelhiScraperService'=>'Delhi','App\\Services\\HyderabadScraperService'=>'Hyderabad','App\\Services\\JaipurScraperService'=>'Jaipur','App\\Services\\JodhpurScraperService'=>'Jodhpur','App\\Services\\KolkataScraperService'=>'Kolkata','App\\Services\\KotaScraperService'=>'Kota','App\\Services\\MumbaiScraperService'=>'Mumbai','App\\Services\\PuneScraperService'=>'Pune'];
$call=function($class,$method,$city){$ref=new ReflectionMethod($class,$method);$ref->setAccessible(true);$res=$ref->invoke(null,$city);return is_array($res)?$res:[];};
function statField($rows,$field){$n=0;foreach($rows as $r){if(isset($r[$field])&&trim((string)$r[$field])!=='')$n++;}return $n;}
foreach($classes as $class=>$city){
$bb=$call($class,'getFallbackBloodBanks',$city);$h=$call($class,'getFallbackHospitals',$city);$d=$call($class,'getFallbackDoctors',$city);
$dupHosp=count($h)-count(array_unique(array_map(fn($x)=>strtolower(trim((string)($x['name_en']??''))),$h)));
$dupBB=count($bb)-count(array_unique(array_map(fn($x)=>strtolower(trim((string)($x['name_en']??''))),$bb)));
echo "=== {$city} ===\n";
echo 'BB email '.statField($bb,'email').'/'.count($bb).', website '.statField($bb,'website').'/'.count($bb).', source_url '.statField($bb,'source_url').'/'.count($bb).", dup_names {$dupBB}\n";
echo 'Hosp lat '.statField($h,'latitude').'/'.count($h).', lon '.statField($h,'longitude').'/'.count($h).', cashless '.statField($h,'is_cashless').'/'.count($h).", dup_names {$dupHosp}\n";
echo 'Docs reg '.statField($d,'registration_number').'/'.count($d).', phone '.statField($d,'phone').'/'.count($d).', profile_url '.statField($d,'profile_url').'/'.count($d)."\n";
}