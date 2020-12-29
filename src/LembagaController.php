<?php
namespace Bageur\Donasi;

use App\Http\Controllers\Controller;
use Bageur\Album\Processors\UploadProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Bageur\Donasi\model\lembaga;
use Bageur\Donasi\Processors\GlobalProcessor;
use Validator;
class LembagaController extends Controller
{

    public function index(Request $request)
    {
       $query = lembaga::datatable($request);
       return $query;
    }

    public function store(Request $request)
    {
        $valid = [
            'nama'    => 'required',
            'img'    => 'nullable|mimes:svg,png,jpg,jpeg',
            'description'    => 'required',
        ];

        if($request->file('img') != null){
            $valid['img'] = 'nullable|mimes:svg,png,jpg,jpeg';
        }

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $lembaga                   = new lembaga;
            $lembaga->nama            = $request->nama;
            $lembaga->nama_seo         = Str::slug($request->nama);
            $lembaga->description      = $request->description;
            if($request->file('gambar') != null){
                $upload                     = UploadProcessor::go($request->file('gambar'),'lembaga');
                $lembaga->gambar             = $upload;
            }

            $lembaga->save();
            return response(['status' => true ,'text'    => 'has input'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return lembaga::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = [
            'nama'    => 'required',
            'img'    => 'nullable|mimes:svg,png,jpg,jpeg',
            'description'    => 'required',
        ];

        if($request->file('img') != null){
            $valid['img'] = 'nullable|mimes:svg,png,jpg,jpeg';
        }

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $lembaga                   = lembaga::find($id);
            $lembaga->nama            = $request->nama;
            $lembaga->nama_seo         = Str::slug($request->nama);
            $lembaga->description      = $request->description;
            if($request->file('gambar') != null){
                $upload                     = UploadProcessor::go($request->file('gambar'),'lembaga');
                $lembaga->gambar             = $upload;
            }

            $lembaga->save();
            return response(['status' => true ,'text'    => 'has input'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lembaga = lembaga::findOrFail($id);
        $lembaga->delete();
        return response(['status' => true ,'text'    => 'deleted'], 200);
    }

}
