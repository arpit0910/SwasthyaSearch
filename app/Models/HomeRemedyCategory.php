<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class HomeRemedyCategory extends Model { use HasFactory; protected $fillable=['name_en','name_hi','slug','description_en','description_hi','icon','image','meta_title_en','meta_title_hi','meta_description_en','meta_description_hi','sort_order','is_active']; protected $casts=['is_active'=>'boolean']; public function remedies(){return $this->hasMany(HomeRemedy::class,'category_id');} public function getNameAttribute(){return ['en'=>$this->name_en,'hi'=>$this->name_hi];} public function getTranslation(string $field,string $locale):?string{return $this->{$field.'_'.$locale}??$this->{$field.'_en'};}}
