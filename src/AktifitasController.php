<?php
namespace Bageur\Donasi;

use App\Http\Controllers\Controller;
use Bageur\Album\Processors\UploadProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Bageur\Donasi\model\aktifitas;
use Bageur\Donasi\Model\campaign;
use Bageur\Donasi\Model\lembaga;
use Bageur\Donasi\Processors\GlobalProcessor;
use Validator;
class AktifitasController extends Controller
{

    public function index(Request $request)
    {
       $query = aktifitas::with('campaign', 'lembaga')->datatable($request);
       return $query;
    }

    public function store(Request $request)
    {
        $valid = [
            'nama'    => 'required',
            'description'    => 'required',
        ];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $aktifitas                   = new aktifitas;
            $aktifitas->nama            = $request->nama;
            $aktifitas->nama_seo         = Str::slug($request->nama);
            $aktifitas->description      = $request->description;
            $aktifitas->campaign_id       = $request->campaign;
            $aktifitas->lembaga_id       = $request->lembaga;
            if($request->file('gambar') != null){
                $upload                     = UploadProcessor::go($request->file('gambar'),'aktifitas');
                $aktifitas->gambar             = $upload;
            }
            $aktifitas->save();
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
        return aktifitas::findOrFail($id);
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
            'description'    => 'required',
        ];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $aktifitas                   = aktifitas::find($id);
            if(!empty($request->lembaga_id)){
                $aktifitas->lembaga_id       = $request->lembaga_id;
            }else{
                $aktifitas->lembaga_id       = $request->lembaga;

            }

            $aktifitas->nama            = $request->nama;
            $aktifitas->nama_seo         = Str::slug($request->nama);
            $aktifitas->description      = $request->description;
            if($request->file('gambar') != null){
                $upload                     = UploadProcessor::go($request->file('gambar'),'aktifitas');
                $aktifitas->gambar             = $upload;
            }
            $aktifitas->save();

            return response(['status' => true ,'text'    => 'has updated'], 200);
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
        $aktifitas = aktifitas::findOrFail($id);
        $aktifitas->delete();
        return response(['status' => true ,'text'    => 'deleted'], 200);
    }

}
