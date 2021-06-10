<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;

class SchedulesController extends Controller
{
    //全データ取得
    public function getAll()
    {
        $columns = [
            'id',
            'place',
            'start',
            'name'
        ];
        $items = Schedule::orderBy('start', 'asc')
            ->get($columns);
        return $items;
    }

    # 今月データ取得
    public function getSchedules(Request $request)
    {
        $columns = [
            'id',
            'place',
            'start',
            'name'
        ];
        $now_week = Schedule::orderBy('start', 'asc')
            ->whereYear('start', NOW())
            ->whereMonth('start', NOW())
            ->get($columns);
        return $now_week;
        // return array($now_week, $now_month);
    }

    //データ削除
    public function delete($id)
    {
        Schedule::destroy($id);
    }

    //データ登録
    public function addSchedule(Request $request)
    {
        $store = new Schedule;
        $store->start = $request->start;
        $store->place = $request->place;
        $store->name = $request->name;
        $store->save();
    }

    //データ更新
    public function update(Request $request)
    {
        // Schedule::update($request->all());
        $col = $request->column;
        $tar = Schedule::find($request->id);
        $tar->$col = $request->val;
        $tar->update();
        return 'OK!';
    }
}
