<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class HomeRemedyIngredient extends Model { use HasFactory; protected $table='home_remedy_ingredients'; protected $fillable=['name_en','name_hi','slug','description_en','description_hi','traditional_uses_en','traditional_uses_hi','precautions_en','precautions_hi','image','meta_title_en','meta_title_hi','meta_description_en','meta_description_hi','is_active']; protected $casts=['is_active'=>'boolean']; public function remedies(){return $this->belongsToMany(HomeRemedy::class,'home_remedy_ingredient_map','ingredient_id','home_remedy_id')->withPivot('quantity','sort_order');} public function getNameAttribute(){return ['en'=>$this->name_en,'hi'=>$this->name_hi];} public function getTranslation(string $field,string $locale):?string{return $this->{$field.'_'.$locale}??$this->{$field.'_en'};}}
