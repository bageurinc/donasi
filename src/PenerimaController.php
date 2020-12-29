<?php
namespace Bageur\Donasi;

use App\Http\Controllers\Controller;
use Bageur\Donasi\Model\penerima;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
class PenerimaController extends Controller
{

    public function index(Request $request)
    {
       $query = penerima::datatable($request);
       return $query;
    }

    public function store(Request $request)
    {
        $valid = [
            'nama'    => 'required',
            'alasan'    => 'required',
            'alamat'    => 'required',
        ];
        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $penerima                    = new penerima;
            $penerima->nama              = $request->nama;
            $penerima->alasan            = $request->alasan;
            $penerima->alamat            = $request->alamat;
            $penerima->kota              = $request->kota;
            $penerima->provinsi          = $request->provinsi;
            $penerima->kodepos           = $request->kodepos;
            $penerima->no_telp           = $request->no_telp;
            if($request->file != null){
                $upload                       = \Bageur::base64($request->file,'penerima');
                $penerima->foto             = $upload['up'];
            }
            $penerima->save();
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
        return penerima::findOrFail($id);

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
            'alasan'    => 'required',
            'alamat'    => 'required',
        ];
        if($request->file('foto') != null){
            $valid['foto'] = 'mimes:jpg,jpeg,png|max:2000';
        }
        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $penerima                    = penerima::findOrFail($id);
            $penerima->nama              = $request->nama;
            $penerima->alasan            = $request->alasan;
            $penerima->alamat            = $request->alamat;
            $penerima->kota              = $request->kota;
            $penerima->provinsi          = $request->provinsi;
            $penerima->kodepos           = $request->kodepos;
            $penerima->no_telp           = $request->no_telp;
            if($request->file('foto') != null){
                $upload                      = UploadProcessor::go($request->file('foto'),'penerima');
                $penerima->foto              = $upload;
            }
            $penerima->save();
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
        $penerima = penerima::findOrFail($id);
        $penerima->delete();
        return response(['status' => true ,'text'    => 'deleted'], 200);
    }

}
