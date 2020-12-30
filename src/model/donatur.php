<?php

namespace Bageur\Donasi\Model;

use App\User;
use Bageur\Auth\Model\user as ModelUser;
use Illuminate\Database\Eloquent\Model;

class donatur extends Model
{
    protected $table = 'bgr_yayasan_campaign_donasi';

    protected $appends = ['idr','user'];

    public function getIdrAttribute() {
        return idr($this->nominal);
    }
    public function getUserAttribute()
    {
        $data = json_decode($this->donatur);
        $parse = [];
        if($this->anonim != 1){
        // $parse['name'] = $data->donatur->name;
            $parse['nama'] = $data->nama;
        }else{
            $parse['nama'] = 'Hamba Allah';
        }
        $parse['tanggal'] = $data->tanggal;
        $parse['pesan'] = $data->pesan;
        return $parse;
        // return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeDatatable($query,$request,$page=12)
    {
        $search       = ["id"];
        $searchqry    = '';

        $searchqry = "(";
        foreach ($search as $key => $value) {
            if($key == 0){
                $searchqry .= "lower($value) like '%".strtolower($request->search)."%'";
            }else{
                $searchqry .= "OR lower($value) like '%".strtolower($request->search)."%'";
            }
        }

        $searchqry .= ")";
        if(@$request->sort_by){
            if(@$request->sort_by != null){
            	$explode = explode('.', $request->sort_by);
                 $query->orderBy($explode[0],$explode[1]);
            }else{
                  $query->orderBy('created_at','desc');
            }

             $query->whereRaw($searchqry);
        }else{
             $query->whereRaw($searchqry);
        }

        if($request->campaign_id){
          $query->where('campaign_id',$request->campaign_id);
        }
        if($request->get == 'all'){
            return $query->get();
        }else{
                return $query->paginate($page);
        }

    }
    public function campaign()
    {
        return $this->belongsTo(campaign::class, 'campaign_id');
    }
}
