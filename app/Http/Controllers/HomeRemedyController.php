<?php
namespace App\Http\Controllers;
use App\Models\{HomeRemedy,HomeRemedyCategory,HomeRemedyIngredient};
use Illuminate\Http\Request;

class HomeRemedyController extends Controller
{
    public function index(Request $request) {
        $q=trim((string)$request->query('q',''));
        $remedies=HomeRemedy::with(['category','ingredients'])->published()->when($q,fn($x)=>$x->where(function($w)use($q){foreach(['title_en','title_hi','problem_name_en','problem_name_hi','short_description_en','short_description_hi','search_keywords_en','search_keywords_hi','search_aliases'] as $f)$w->orWhere($f,'like','%'.$q.'%');}))->orderByDesc('is_featured')->orderBy('sort_order')->latest()->paginate(12)->withQueryString();
        return view('nani-dadi.index',['remedies'=>$remedies,'categories'=>HomeRemedyCategory::where('is_active',true)->orderBy('sort_order')->get(),'ingredients'=>HomeRemedyIngredient::where('is_active',true)->orderBy('name_en')->limit(18)->get(),'q'=>$q]);
    }
    public function category(string $slug, Request $request) { $category=HomeRemedyCategory::where('slug',$slug)->where('is_active',true)->firstOrFail(); $q=trim((string)$request->query('q','')); $remedies=$category->remedies()->with('ingredients')->published()->when($q,fn($x)=>$x->where(fn($w)=>$w->where('title_en','like',"%$q%")->orWhere('title_hi','like',"%$q%")->orWhere('search_aliases','like',"%$q%")))->orderBy('sort_order')->latest()->paginate(12)->withQueryString(); return view('nani-dadi.category',compact('category','remedies','q')); }
    public function ingredient(string $slug) { $ingredient=HomeRemedyIngredient::where('slug',$slug)->where('is_active',true)->firstOrFail(); return view('nani-dadi.ingredient',['ingredient'=>$ingredient,'remedies'=>$ingredient->remedies()->with('category')->published()->paginate(12)]); }
    public function show(string $slug) { $remedy=HomeRemedy::with(['category','ingredients','speciality'])->published()->where('slug',$slug)->firstOrFail(); $related=HomeRemedy::with('category')->published()->where('category_id',$remedy->category_id)->whereKeyNot($remedy->id)->orderByDesc('is_featured')->limit(4)->get(); return view('nani-dadi.show',compact('remedy','related')); }
    public function apiIndex(Request $request) { $locale=$request->query('lang',app()->getLocale())==='hi'?'hi':'en'; $items=HomeRemedy::with('category')->published()->when($request->query('search'),fn($q,$v)=>$q->where(fn($w)=>$w->where('title_en','like',"%$v%")->orWhere('title_hi','like',"%$v%")->orWhere('search_aliases','like',"%$v%")))->when($request->query('category'),fn($q,$v)=>$q->whereHas('category',fn($c)=>$c->where('slug',$v)))->latest()->paginate(12); return response()->json(['success'=>true,'message'=>'Remedies fetched successfully.','data'=>$items->getCollection()->map(fn($r)=>['id'=>$r->id,'title'=>$r->getTranslation('title',$locale),'slug'=>$r->slug,'problem_name'=>$r->getTranslation('problem_name',$locale),'short_description'=>$r->getTranslation('short_description',$locale),'category'=>$r->category?->getTranslation('name',$locale),'evidence_level'=>$r->evidence_level]),'meta'=>['current_page'=>$items->currentPage(),'last_page'=>$items->lastPage(),'total'=>$items->total()]]); }
    public function apiShow(string $slug) { $r=HomeRemedy::with(['category','ingredients'])->published()->where('slug',$slug)->firstOrFail(); return response()->json(['success'=>true,'message'=>'Remedy fetched successfully.','data'=>$r]); }
    public function apiCategories() { return response()->json(['success'=>true,'message'=>'Categories fetched successfully.','data'=>HomeRemedyCategory::where('is_active',true)->orderBy('sort_order')->get()]); }
    public function apiIngredients() { return response()->json(['success'=>true,'message'=>'Ingredients fetched successfully.','data'=>HomeRemedyIngredient::where('is_active',true)->orderBy('name_en')->get()]); }
}
