<?php
namespace App\Http\Controllers;

use App\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        $data = Upload::all();
        return response($data);
    }
    public function show($id){
        $data = Upload::where('id',$id)->first();
        return response ($data);
    }
    public function store (Request $request){
        DB::beginTransaction();

        try {
            $data           = new Upload();
            $date           = Carbon::now();
            $dateString     = $date->format('YmdHis');
            if(Input::hasFile('image')){
                $file = Input::file('image');

                $img = \Image::make($file->getPathName());
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path().'/uploads'."\\".$dateString.$file->getClientOriginalName(), 100);
                $file->move(public_path().'/uploads', $dateString.$file->getClientOriginalName());

                $data->name = $dateString.$file->getClientOriginalName();
                $data->link = asset('uploads').'/'.$data->name;
            }
            $data->from = $request->input('from');
            $data->save();
            if ($data instanceof Upload) {

                DB::commit();
                return response($data);
            }

        } catch (\Exception $e) {
            DB::rollback();                
            return response('Upload Bermasalah');
        }
        return false;
    }

    public function update (Request $request,$id)
    {        
        DB::beginTransaction();

        try {
            $data = Upload::findOrfail($id);
            $oldImage       = $data->name;
            $date           = Carbon::now();
            $dateString     = $date->format('YmdHis');
            if(Input::hasFile('image')){
                $file = Input::file('image');

                $img = \Image::make($file->getPathName());
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path().'/uploads'."\\".$dateString.$file->getClientOriginalName(), 100);
                $file->move(public_path().'/uploads', $dateString.$file->getClientOriginalName());

                $data->name = $dateString.$file->getClientOriginalName();
                $data->link = asset('uploads').'/'.$data->name;
            } 
            $data->from = $request->input('from');
            $data->save();

            if ($data instanceof Upload) {

                DB::commit();                      
                if(File::exists(public_path().'/uploads/'."\\".$oldImage)) {
                    File::delete(public_path().'/uploads/'."\\".$oldImage);
                }
                return response($data);
            }

        } catch (\Exception $e) {
            DB::rollback();                
            return response('Upload Bermasalah');
        }
        return false;
    }
}
