<?php
namespace Bageur\Donasi;

use App\Http\Controllers\Controller;
use App\User;
use Bageur\Auth\Model\user as ModelUser;
use Bageur\Donasi\Model\campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Bageur\Donasi\Model\donatur;
use Bageur\Donasi\Processors\GlobalProcessor;
use Validator;
class DonaturController extends Controller
{

    public function index(Request $request)
    {
       $query = donatur::with('campaign', 'user')->datatable($request);
       return $query;
    }

    public function store(Request $request)
    {
        $valid = [
        ];
        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $donatur                    = new donatur;
            $donatur->nominal           = $request->nominal;
            $donatur->pesan             = $request->pesan;
            $donatur->anonim            = $request->anonim;
            $donatur->campaign_id       = $request->campaign_id;
            $donatur->user_id           = $request->user_id;
            $donatur->save();
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
        return donatur::findOrFail($id);

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
        ];
        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $donatur                   = donatur::find($id);
            if(!empty($request->campaign_id)){
                $donatur->campaign_id      = $request->campaign_id;
            }
            elseif(!empty($request->lembaga_id)){
                $donatur->user_id       = $request->user_id;
            }
            else{
                $donatur->campaign_id   = $request->campaign_id;
                $donatur->user_id       = $request->user_id;
            }
            $donatur->nominal           = $request->nominal;
            $donatur->pesan             = $request->pesan;
            $donatur->anonim            = $request->anonim;
            $donatur->status            = $request->status;
            $donatur->save();
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
        $donatur = donatur::findOrFail($id);
        $donatur->delete();
        return response(['status' => true ,'text'    => 'deleted'], 200);
    }

}
