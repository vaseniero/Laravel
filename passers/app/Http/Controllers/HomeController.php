<?php

namespace App\Http\Controllers;

use App\Examinee;
use App\Http\Resources\ExamineeResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var Examinee
     */
    protected $examinee;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // initial examinee
        $this->examinee = new Examinee();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get Examinees.
     *
     * @return \Illuminate\Http\Response
     */
    public function getExaminees()
    {
        $examinees = $this->examinee->paginate(50);
        return response()->json($examinees);
    }

    /**
     * Get examineess for the data table.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getExamineesForDataTable(Request $request)
    {
        $examinees = $this->examinee->paginate(50);
        return ExamineeResource::collection($examinees);
    }

    /**
     * Get search examineess for the data table.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSearchExamineesForDataTable(Request $request)
    {
        if (count($request->all())) {
            $column = (is_null($request->column)) ? 'name_of_examinee' : $request->column;
            $order = (is_null($request->order)) ? 'asc' : $request->order;
            $columnOrder = $column;
            $per_page = (is_null($request->per_page)) ? 0 : $request->per_page;

            switch ($column) {
                case 'examinee':
                    $columnOrder = 'name_of_examinee';
                    break;
                case 'campus':
                    $columnOrder = 'campus_eligibility';
                    break;
                default:
                    break;
            }

            if ($per_page === 0) {
                $examinees = $this->examinee->orderBy($columnOrder, $order);
            }
            else {
                $query = $this->examinee->orderBy($columnOrder, $order);
                $examinees = $query->paginate($per_page);
            }
        }
        else {
            $examinees = $this->examinee->all();
        }
    
        return ExamineeResource::collection($examinees);
    }
}
