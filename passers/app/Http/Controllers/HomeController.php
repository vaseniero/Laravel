<?php

namespace App\Http\Controllers;

use App\Examinee;
use App\Http\Resources\ExamineeResource;
use App\Http\Resources\SchoolResource;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function school()
    {
        return view('school');
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
     * Get School.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSchoolPassers()
    {
        $school = DB::table('examinee')
                        ->select('school', DB::raw("count('name_of_examinee') as passers"))
                        ->orderBy('school')
                        ->groupBy(DB::raw("school"))
                        ->paginate(50);
        return response()->json($school);
    }

    /**
     * Get schools for the data table.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getSchoolsForDataTable(Request $request)
    {
        if (count($request->all())) {
            $column = (is_null($request->column)) ? 'school' : $request->column;
            $order = (is_null($request->order)) ? 'asc' : $request->order;
            $per_page = (is_null($request->per_page)) ? 50 : $request->per_page;

            if ($column == 'passers') {
                $school = DB::table('examinee')
                                ->select('school', DB::raw("Count('name_of_examinee') as passers"))
                                ->orderBy($column, $order)
                                ->orderBy('school', 'asc')
                                ->groupBy(DB::raw("school"))
                                ->paginate($per_page);
            }
            else {
                $school = DB::table('examinee')
                                ->select('school', DB::raw("Count('name_of_examinee') as passers"))
                                ->orderBy($column, $order)
                                ->groupBy(DB::raw("school"))
                                ->paginate($per_page);
            }
        }
        else {
            $school = DB::table('examinee')
                            ->select('school', DB::raw("Count('name_of_examinee') as passers"))
                            ->orderBy('school')
                            ->groupBy(DB::raw("school"))
                            ->paginate(50);
        }
    
        return SchoolResource::collection($school);
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
        if (count($request->all())) {
            $column = (is_null($request->column)) ? 'name_of_examinee' : $request->column;
            $order = (is_null($request->order)) ? 'asc' : $request->order;
            $per_page = (is_null($request->per_page)) ? 50 : $request->per_page;
            $columnOrder = $column;

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

            if ($column == 'examinee') {
                $examinees = $this->examinee
                                  ->orderBy($columnOrder, $order)
                                  ->paginate($per_page);
            }
            else {
                $examinees = $this->examinee
                                  ->orderBy($columnOrder, $order)
                                  ->orderBy('name_of_examinee', $order)
                                  ->paginate($per_page);
            }
        }
        else {
            $examinees = $this->examinee->orderBy('name_of_examinee')->all();
        }
    
        return ExamineeResource::collection($examinees);
    }

    /**
     * Get search examineess for the data table.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getExamineesSearchForDataTable(Request $request)
    {
        if (count($request->all())) {
            $searchColumn = (is_null($request->search_column)) ? 'name_of_examinee' : $request->search_column;
            $searchTerm = (is_null($request->search_term)) ? '' : $request->search_term . '%';
            $column = (is_null($request->column)) ? 'name_of_examinee' : $request->column;
            $order = (is_null($request->order)) ? 'asc' : $request->order;
            $per_page = (is_null($request->per_page)) ? 50 : $request->per_page;
            $columnOrder = $column;
            $columnSearch = $searchColumn;

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

            switch ($searchColumn) {
                case 'examinee':
                    $columnSearch = 'name_of_examinee';
                    break;
                case 'campus':
                    $columnSearch = 'campus_eligibility';
                    break;
                default:
                    break;
            }

            if ($column == 'examinee') {
                $examinees = $this->examinee::where($columnSearch, 'like', $searchTerm)
                                  ->orderBy($columnOrder, $order)
                                  ->paginate($per_page);
            }
            else {
                $examinees = $this->examinee::where($columnSearch, 'like', $searchTerm)
                                  ->orderBy($columnOrder, $order)
                                  ->orderBy('name_of_examinee', $order)
                                  ->paginate($per_page);
            }
        }
        else {
            $examinees = $this->examinee->orderBy('name_of_examinee')->all();
        }
    
        return ExamineeResource::collection($examinees);
    }

    public function examineeNewbie(Request $request) {
        $post = new Examinee();
        $post->name_of_examinee = $request->get('examinee');
        $post->campus_eligibility = $request->get('campus');
        $post->school = $request->get('school');
        $post->division = $request->get('division');
        $post->save();

        return response()->json($post);
    }
}
