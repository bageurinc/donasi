<?php

namespace Bageur\Donasi\Model;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class aktifitas extends Model
{
    protected $table = 'bgr_yayasan_campaign_aktifitas';
    protected $appends = ['img','text_limit','judul_limit'];

    public function getImgAttribute()
    {
          return url('storage/aktifitas/'.$this->gambar);
    }

    public function getTextLimitAttribute() {
        return Str::words(nl2br(strip_tags($this->description)),25);
   }

   public function getJudulLimitAttribute() {
        return Str::words(strip_tags($this->title),5,' ...');
   }
    public function scopeDatatable($query,$request,$page=12)
    {
        $search       = ["id", "nama", "description"];
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

    public function lembaga()
    {
        return $this->belongsTo(lembaga::class, 'lembaga_id');
    }
}
