<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'start_time'=>$this->timeInMinuts($this->start_time),
            'end_time'=>$this->timeInMinuts($this->end_time),
            'start_at'=>$this->timeInMinuts($this->start_at),
            'end_at'=>$this->timeInMinuts($this->end_at),
            'start_time2'=>$this->timeInMinuts($this->start_time),
            'end_time2'=>$this->timeInMinuts($this->end_time),
            'start_at2'=>$this->timeInMinuts($this->start_at),
            'end_at2'=>$this->timeInMinuts($this->end_at),
            'week_days'=>explode(',',$this->week_days),
            'late_after'=>$this->late_after

        ];
    }
    private function timeInMinuts($prop){
         return date('h:i', \strtotime($prop));
    }
}
