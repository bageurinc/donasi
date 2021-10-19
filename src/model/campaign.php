<?php

namespace Bageur\Donasi\Model;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class campaign extends Model
{
    protected $table = 'bgr_yayasan_campaign';

    protected $appends = ['avatar','text_limit','judul_limit','target_danas','dana_terkumpul','dana_terkumpul_raw'];

    public function getAvatarAttribute()
    {
        return \Bageur::avatar($this->title,$this->gambar,'campaign');
    }

    public function getTextLimitAttribute() {
        return Str::words(nl2br(strip_tags($this->description)),100);
    }

    public function getJudulLimitAttribute() {
        return Str::words(strip_tags($this->title),5,' ...');
    }

    public function getTargetDanasAttribute() {
        return idr($this->target_dana);
    }

    public function getDanaTerkumpulAttribute() {
        if ($this->dana_terkumpul == 0) {
            $data = campaign::find($this->id);
            $data->dana_terkumpul = $this->campaigndonasi()->sum('nominal');
            $data->save();
            return idr($data->dana_terkumpul);
        }
        else{
            return idr($this->dana_terkumpul);
        }
    }

    public function getDanaTerkumpulRawAttribute() {
        if ($this->dana_terkumpul == 0) {
            $data = campaign::find($this->id);
            $data->dana_terkumpul = $this->campaigndonasi()->sum('nominal');
            $data->save();
            return $data->dana_terkumpul;
        }
        else{
            return $this->dana_terkumpul;
        }
    }

    public function scopeDatatable($query,$request,$page=12)
    {
        $search       = ["id", "title", "sub", "description"];
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
    public function lembaga()
    {
        return $this->belongsTo(lembaga::class, 'lembaga_id');
    }

    public function penerima()
    {
        return $this->belongsTo(penerima::class, 'penerima_id');
    }
    public function aktifitas()
    {
        return $this->hasMany(aktifitas::class, 'campaign_id');
    }
    public function campaigndonasi()
    {
        return $this->hasMany(donatur::class, 'campaign_id')->where('status','aktif');
    }
}
