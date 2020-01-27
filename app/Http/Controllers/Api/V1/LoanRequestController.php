<?php

namespace App\Http\Controllers\Api\V1;
use App\LoanRequest;
use App\Affiliate;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProcedureModality;
use Util;
use Carbon;
use PDF;


class LoanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Util::search_sort(new LoanRequest(), $request);
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $loan_request = new LoanRequest();
        $datos = json_encode($request->all());
        $loan_request->hash=sha1($datos);
        $loan_request->affiliate_id = $request->affiliate_id;
        $loan_request->request=$datos;
        $loan_request->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function createpdf($id_modality){
        $ultimoreg=LoanRequest::latest()->first();
        $hash=$ultimoreg->hash;
        $id_affiliate=$ultimoreg->affiliate_id;
        $affiliate = Affiliate::findOrFail($id_affiliate); 
        $name_modality=ProcedureModality::find($id_modality)->name;
        $result=new ProcedureModalityController();
        $result=$result->list_requirements_loan($id_modality)->pluck('name');
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÔN DE ESTRATEGIAS SOCIALES E INVERSIONES";
        $unit = "UNIDAD DE INVERSIÒN EN PRESTAMOS";
        $c=0;
        $year = Carbon::now()->format('Y');
        $file_name = "Presolicitud con CI:".$affiliate->identity_card." de ".$year. ".pdf";
        $datas = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'hash'  => $hash,
            'data' => $result,
            'a' => $c,
            'affiliate' => $affiliate,
            'nommodality' => $name_modality
        ];
        $options = [
            'orientation' => 'portrait',
            'page-width' => '216',
            'page-height' => '427',
            'margin-left' => '0',
            'margin-right' => '0',
            'margin-top' => '0',
            'margin-bottom' => '0',
            'encoding' => 'UTF-8',
            'user-style-sheet' => public_path('css/report-print.min.css')
          ];
          $pdf = \PDF::loadView('prerequest', $datas);
          $pdf->setOptions($options);
          return $pdf->stream($file_name);
    }  
}