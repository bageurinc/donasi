<?php

namespace Bageur\Donasi\Model;

use Illuminate\Database\Eloquent\Model;

class penerima extends Model
{
    protected $table = 'bgr_yayasan_penerima';
    protected $appends = ['avatar'];

   public function getAvatarAttribute()
    {
        return \Bageur::avatar($this->nama,$this->foto,'penerima');
    }
    public function scopeDatatable($query,$request,$page=12)
    {
        $search       = ["id", "nama", "alasan", "alamat", "kota", "provinsi", "kodepos", "no_telp"];
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

        if($request->get == 'all'){
            return $query->get();
        }else{
                return $query->paginate($page);
        }

    }
    public function campaign()
    {
        return $this->hasMany(campaign::class);
    }
}
