<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use mysql_xdevapi\Exception;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.Languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.Languages.create');
    }

    public function store(LanguageRequest $request)
    {

        try {

            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }

    }
    public function edit($id){
        $language=Language::select()->find($id);
        if (!$language){
            return redirect()->route('admin.languages')->with(['error'=>'هذه اللغه عير موجوده ']);
        }
        else
            return view('admin.Languages.edit',compact('language'));
    }
    public function update(LanguageRequest $request,$id){
        try {
            $language =Language::find($id);
            if (!$language){
                return redirect()->route('admin.languages.edit',$id)->with(['error'=>'هذه اللغه عير موجوده ']);
            }
            $language->update($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);
        }catch (Exception $ex){
            return redirect()->route('admin.languages.edit',$id)->with(['error'=>'هذه اللغه عير موجوده ']);
        }


    }
    public function destroy($id){
        try {
            $language =Language::find($id);
            if (!$language){
                return redirect()->route('admin.languages',$id)->with(['error'=>'هذه اللغه غير موجوده ']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);
        }catch (Exception $ex){
            return redirect()->route('admin.languages.edit',$id)->with(['error'=>'هذه اللغه عير موجوده ']);
        }
    }
}
