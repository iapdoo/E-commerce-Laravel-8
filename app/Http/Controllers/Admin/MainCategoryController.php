<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainCategoryController extends Controller
{
    public function index()
    {
        $default_language = get_default_language();
        $categories = MainCategory::where('translation_lang', $default_language)->Selection()->get();
        return view('admin.mainCategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.mainCategories.create');
    }

    public function store(MainCategoryRequest $request)
    {

        try {
            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_language();
            });

            $default_category = array_values($filter->all()) [0];


            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('maincategories', $request->photo);
            }

            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_language();
            });


            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }

                MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function edit($main_category_id)
    {
        $mainCategory = MainCategory::with('categories')->Selection()->find($main_category_id);
        if (!$mainCategory) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا المنتج غير موجود ']);
        }
        return view('admin.mainCategories.edit', compact('mainCategory'));
    }


    public function update(MainCategoryRequest $request, $main_category_id)
    {
//        return  $request;
        try {
            $main_category = MainCategory::find($main_category_id);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا المنتج غير موجود ']);
            }
            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active')) {
                $request->request->add(['active' => 0]);
            } else {
                $request->request->add(['active' => 1]);
            }

            MainCategory::where('id', $main_category_id)->update([
                'name' => $category['name'],
                'active' => $request->active,
            ]);
            if ($request->has('photo')) {
                $filepath = UploadImage('maincategories', $request->photo);
                MainCategory::where('id', $main_category_id)->update([
                    'photo' => $filepath
                ]);
            }
            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح ']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function destroy($main_category_id)
    {
        try {
            $main_category = MainCategory::find($main_category_id);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا المنتج غير موجود ']);
            }
            $vendors = $main_category->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف هذا القسم']);
            }
            $deleteImage = Str::after($main_category->photo, 'images/');
            $deleteImage = base_path('public/images/' . $deleteImage);
            unlink($deleteImage); // delete photo
            $main_category->categories()->delete();
            $main_category->delete();
            return redirect()->route('admin.maincategories')->with(['success' => ' تم حذف القسم بنجاح ']);
        } catch (\Exception $exception) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function changeStatus($main_category_id)
    {
        try {
            $main_category = MainCategory::find($main_category_id);
            if (!$main_category) {
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا المنتج غير موجود ']);
            }
            $status = $main_category->active == 0 ? 1 : 0;
            $main_category->update(['active'=>$status]);
            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $exception) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

}
