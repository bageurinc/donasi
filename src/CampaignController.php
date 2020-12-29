<?php
namespace Bageur\Donasi;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Bageur\Donasi\model\campaign;
use Bageur\Donasi\Model\lembaga;
use Bageur\Donasi\Model\penerima;
use Validator;
class CampaignController extends Controller
{

    public function index(Request $request)
    {
        $query = campaign::with('lembaga', 'penerima')->datatable($request);
        return $query;
    }

    public function store(Request $request)
    {
        $valid = [
            'title'          => 'required',
            'description'    => 'required',
            'target_dana'    => 'required',
            'date_start'     => 'required',
            'date_end'       => 'required',
        ];
        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $campaign                   = new campaign;
            $campaign->title            = $request->title;
            $campaign->title_seo        = Str::slug($request->title);
            $campaign->sub              = $request->sub;
            $campaign->description      = $request->description;
            $campaign->target_dana      = $request->target_dana;
            $campaign->date_start       = $request->date_start;
            $campaign->date_end         = $request->date_end;
            $campaign->lembaga_id       = $request->penerima_id;
            $campaign->penerima_id      = $request->lembaga_id;
            if($request->file != null){
                $upload                       = \Bageur::base64($request->file,'campaign');
                $campaign->gambar             = $upload['up'];
            }
            $campaign->save();
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
        return campaign::findOrFail($id);
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
            'title'          => 'required',
            'description'    => 'required',
            'target_dana'    => 'required',
            'date_start'     => 'required',
            'date_end'       => 'required',
        ];

        if($request->file('img') != null){
            $valid['img'] = 'nullable|mimes:svg,png,jpg,jpeg|max:50';
        }

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $campaign                   = campaign::find($id);
            $campaign->title            = $request->title;
            $campaign->title_seo         = Str::slug($request->title);
            $campaign->description      = $request->description;
            $campaign->target_dana      = $request->target_dana;
            $campaign->date_start       = $request->date_start;
            $campaign->date_end       = $request->date_end;
            $campaign->penerima_id       =  $request->penerima_id;
            $campaign->lembaga_id       = $request->lembaga_id;
            if($request->file != null){
                $upload                       = \Bageur::base64($request->file,'campaign');
                $campaign->gambar             = $upload['up'];
            }

            $campaign->save();
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
        $campaign = campaign::findOrFail($id);
        $campaign->delete();
        return response(['status' => true ,'text'    => 'deleted'], 200);
    }

}
