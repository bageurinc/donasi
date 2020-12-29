<?php

namespace Bageur\Donasi\Model;

use Illuminate\Database\Eloquent\Model;

class members extends Model
{
    protected $table = 'bgr_yayasan_members';
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

        if($request->get == 'all'){
            return $query->get();
        }else{
                return $query->paginate($page);
        }

    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
