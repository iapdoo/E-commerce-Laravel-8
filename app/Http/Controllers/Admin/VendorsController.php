<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorsRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }


    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorsRequest $request)
    {
        try {
//            return dd($request);
            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);
            } else {
                $request->request->add(['active' => 1]);
            }
            $filepath = "";
            if ($request->has('logo')) {
                $filepath = UploadImage('vendors', $request->logo);
            }

            $vendor=Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'active' => $request->active,
                'email' => $request->email,
                'category_id' => $request->category_id,
                'address' => $request->address,
                'logo' => $filepath,
                'password' =>$request->password ,
                'latitude' =>$request->latitude ,
                'longitude' =>$request->longitude ,
            ]);
            Notification::send($vendor,new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم اضافه التاجر بنجاح ']);
        } catch (\Exception $exception) {
            return  $exception;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
    public function edit($vendor_id){

        try {
           $vendor= Vendor::selection()->find($vendor_id);
            $categories = MainCategory::where('translation_of', 0)->active()->get();
            if (!$vendor){
                return redirect()->route('admin.vendors')->with(['success' => 'هذا المتجر غير موجود ']);
            }

            return  view('admin.vendors.edit',compact('vendor','categories'));
        }catch (\Exception $exception){
            return $exception;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
    public function update(VendorsRequest $request,$vendor_id){
        try {
            $vendor= Vendor::selection()->find($vendor_id);
            $categories = MainCategory::where('translation_of', 0)->active()->get();
            if (!$vendor){
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود ']);
            }
            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);
            } else {
                $request->request->add(['active' => 1]);
            }
            DB::beginTransaction();
            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
                Vendor::where('id',$vendor_id)->update(['logo'=>$filePath]);
            }
            $data=$request->except('_token','logo','password','id');
            if ($request->has('password')) {
                $data['password']=$request->password;
            }
            Vendor::where('id',$vendor_id)->update($data);
            DB::commit();
//            return dd($data);
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح ']);
        }catch (\Exception $exception){
            DB::rollback();
            return $exception;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
    public function destroy($vendor_id)
    {
        try {
            $vendor = Vendor::find($vendor_id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود ']);
            }
            $deleteImage= Str::after($vendor->logo,'images/') ;
            $deleteImage=base_path('public/images/'.$deleteImage);
            unlink($deleteImage);
            $vendor->delete();
            return redirect()->route('admin.vendors')->with(['success' => ' تم حذف المتجر بنجاح ']);
        } catch (\Exception $exception) {
            return $exception;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
    public function changeStatus($main_vendor_id)
    {
        try {
            $vendor = Vendor::find($main_vendor_id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المنتج غير موجود ']);
            }
            $status = $vendor->active == 0 ? 1 : 0;
            $vendor->update(['active'=>$status]);
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
