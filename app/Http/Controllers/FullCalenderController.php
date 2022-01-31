<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class FullCalenderController extends Controller
{



	public function index(Request $request)
	{
		if ($request->ajax()) {
			//	$data = Event::select("id","title","start","end","idEmpleado","tipoEvento")->where('idEmpleado',$user)->get()->toArray();

			$id =  Auth::user()->id;
			$idJefe =  Auth::user()->idJefeEquipo;
			$vacaciones = 'vacas';
			if ($id == $idJefe) {

				$data = Event::select("id", "title", "start", "end", "idEmpleado", "idJefeEquipo", "tipoEvento", "color")->where('tipoEvento', '=', $vacaciones)
					->where('idJefeEquipo', '=', $idJefe)
					->orwhere('idEmpleado', '=', $id)
					->get()->toArray();
			} else {
				$data = Event::select("id", "title", "start", "end", "idEmpleado", "idJefeEquipo", "tipoEvento", "color")->where('idEmpleado', '=', $id)
					->get()->toArray();
			}

			return response()->json($data);
		}
		return view('full-calender');
	}

	public function action(Request $request)
	{
		if ($request->ajax()) {
			if ($request->type == 'add') {
				$event = Event::create([
					'title'		=>	$request->title,
					'start'		=>	$request->start,
					'end'		=>	$request->end,
					'idEmpleado'		=>	$request->idEmpleado,
					'idJefeEquipo'		=>	$request->idJefeEquipo,
					'tipoEvento'		=>	$request->tipoEvento,
					'color'		=>	$request->color
				]);

				return response()->json($event);
			}

			if ($request->type == 'update') {
				$event = Event::find($request->id)->update([
					'title'		=>	$request->title,
					'start'		=>	$request->start,
					'end'		=>	$request->end
				]);

				return response()->json($event);
			}

			if ($request->type == 'aprobar') {

				$id =  Auth::user()->id;
				$idJefe =  Auth::user()->idJefeEquipo;
				$idEvento = Event::find($request->id);
				$vacaciones = Event::find($request->tipoEvento);
				$idJefe =  Auth::user()->idJefeEquipo;

			
				if ($id == $idJefe) {

					$event = Event::find($request->id)->update([
						'color' => $request->color

					]);
				}

				return response()->json($event);
			}
			if ($request->type == 'delete') {
				$event = Event::find($request->id)->update([
					'color' => $request->color
				]);

				$event = Event::find($request->id)->delete();
				
			
		}
		}
	}
}
