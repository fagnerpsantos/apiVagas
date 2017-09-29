<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Vaga;
use App\Empresa;
use Validator;

class EmpresaController extends Controller
{
    protected function validarEmpresa($request){
        $validator = Validator::make($request->all(),[
            "nomeFantasia" => 'required',
            "cnpj" => 'required',
            "email" => 'required',
            ]);
        return $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $qtd = $request['qtd'];
            $page = $request['page'];

            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $empresas = Empresa::paginate($qtd);
            
            $empresas = $empresas->appends(Request::capture()->except('page')); 

            return response()->json(['empresas'=>$empresas], 200);
        } catch (\Exception $e){
            return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = $this->validarEmpresa($request);
            if($validator->fails()){
                return response()->json(['message'=>'Erro', 
                    'errors' => $validator->errors()], 
                    400);
            }
            $data = $request->only(['nomeFantasia', 'cnpj', 'email']);
            if($data){
                $empresa = Empresa::create($data);
                if($empresa){
                    return response()->json(['data'=> $empresa], 201);
                }else{
                    return response()->json(['message'=>'Erro ao criar a empresa', 'data'=> $empresa], 201);
                }
            }else{
                return response()->json(['message'=>'Dados inválidos'], 400);
            }     
            }catch (\Exception $e){
                return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
            } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            if($id < 0){
                return response()->json(['message'=>'ID menor que zero, por favor, informe um ID válido'], 400);
            }
            $empresa = Empresa::find($id);
            if($empresa){
                return response()->json([$empresa], 200);
            }else{
                return response()->json(['message'=>'A empresa com id '.$id.' não existe'], 404);
            }
        }catch (\Exception $e){
            return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
        }
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
        try{
            $validator = $this->validarEmpresa($request);
            if($validator->fails()){
                return response()->json(['message'=>'Erro', 
                    'errors' => $validator->errors()], 
                    400);
            }
            $data = $request->only(['nomeFantasia', 'cnpj', 'email']);
            if($data){
                $empresa = Empresa::find($id);
                if($empresa){
                    $empresa->update($data);
                    return response()->json(['data'=> $empresa], 200);
                }else{
                    return response()->json(['message'=>'A empresa com id '.$id.' não existe'], 400);
                }
            }else{
                return response()->json(['message'=>'Dados inválidos'], 400);
            }
        }catch (\Exception $e){
            return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cont = 0;
        try{
            if(Vaga::where('empresa_id', '=', $id)->count()){                
                $vagas = Vaga::where('empresa_id', '=', $id)->get();
                foreach($vagas as $vaga){
                    if($vaga->status == 1){
                        return response()->json(['message'=>'Não é possível remover a empresa. Há vagas relacionadas'], 202);                    }                    
                }                
            }
            if($id < 0){
                return response()->json(['message'=>'ID menor que zero, por favor, informe um ID válido'], 400);
            }               
            $empresa = Empresa::find($id);
            if($empresa){
                $empresa->delete();
                return response()->json([], 204);
            }else{
                return response()->json(['message'=>'A empresa com id '.$id.' não existe'], 404);
            }         
        }catch (\Exception $e){
            return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
        }
    }

    public function vagas($id)
    {
        try{
            if($id < 0){
                return response()->json(['message'=>'ID menor que zero, por favor, informe um ID válido'], 400);
            }
            $empresa = Empresa::find($id);
            if($empresa){
                return response()->json(['vagas'=>$empresa->vagas], 200);
            }else{
                return response()->json(['message'=>'A empresa com id '.$id.' não existe'], 404);
            }
        }catch (\Exception $e){
            return response()->json(['message'=>'Ocorreu um erro no servidor'], 500);
        }
        
    }
    
}
