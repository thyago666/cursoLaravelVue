<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{


     public function __construct(Modelo $modelo){

                $this->modelo = $modelo;

        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $modeloRepository = new ModeloRepository($this->modelo);
        //$marcas = Marca::all();
          if($request->has('atributos_marca')){
              $atributos_marca = 'marca:id,'.$request->atributos_marca;
              $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        }
        else
        {
          $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }

           if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro);
            }
              if($request->has('atributos')){
             $modeloRepository->selectAtributos($request->atributos);
              } 
            return response()->json($modeloRepository->getResultado(),200);















//-----------------------------------------------------


        //localhost:8000/api/modelo?atributos=nome,marca_id&atributos_marca=nome&filtro=air_bag:=:0;nome:like:Ford%;numero_portas:=:4
        $modelos = array();


        //serve para filtrar as marcas, ou seja quais campos vc quer
        if($request->has('atributos_marca')){
              $atributos_marca = $request->atributos_marca;
              $modelos = $this->modelo->with('marca:id,'.$atributos_marca);
        }
        else
        {
          $modelos = $this->modelo->with('marca');
        }


        if($request->has('filtro')){


            $filtros = explode(';',$request->filtro);
            foreach($filtros as $key => $condicao){
                $c = explode(':',$condicao);
            $modelos = $modelos->where($c[0],$c[1],$c[2]);

            }
            

        }

        if($request->has('atributos')){
               $atributos = $request->atributos;
               $modelos = $modelos->selectRaw($atributos)->get();

        }
        else
        {
            $modelos = $modelos->get();

        }
        return response()->json($modelos,200);
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
        
        $request->validate($this->modelo->rules());

       
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos','public');
     
        
     
         $modelo = $this->modelo->create([
        'marca_id' => $request->marca_id,
        'nome' => $request->nome,
        'imagem' => $imagem_urn,
        'numero_portas' => $request->numero_portas,
        'lugares' => $request->lugares,
        'air_bag' => $request->air_bag,
        'abs' => $request->abs,

    ]);
        
       // return $marca;
        return response()->json($modelo,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $modelo = $this->modelo->with('marca')->find($id);

          if($modelo === null){
            return response()->json(['erro'=>'recurso pesquisado nao existe'],404);
        }
        return response()->json($modelo,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $modelo = $this->modelo->find($id);

        
        if($modelo === null)
        {

            return response()->json(['erro'=>'Não atualizamos, registro não encontrado!'],404);
        }

            if($request->method() === 'PATCH'){

                $regrasDinamicas = array();

                foreach($modelo->rules() as $input => $regra){

                    if(array_key_exists($input,$request->all())){

                        $regrasDinamicas[$input] = $regra;
                    }

                }
                $request->validate($regrasDinamicas);

            }else{
                $request->validate($modelo->rules());

            }

                //remove o arquivo antigo, caso tenha alterado
                if($request->file('imagem')){

                    Storage::disk('public')->delete($modelo->imagem);
                }

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos','public');

        $modelo->fill($request->all());
         $modelo->imagem = $imagem_urn;
               $modelo->save();
     
        /*
         $modelo->update([
        'marca_id' => $request->marca_id,
        'nome' => $request->nome,
        'imagem' => $imagem_urn,
        'numero_portas' => $request->numero_portas,
        'lugares' => $request->lugares,
        'air_bag' => $request->air_bag,
        'abs' => $request->abs,
        ]);
        */
             
        return response()->json($modelo,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

          if($modelo === null)
        {
             return response()->json(['erro'=>'Não apagamos, registro não encontrado!'],404);
        }
        Storage::disk('public')->delete($modelo->imagem);
         $modelo->delete();
        return response()->json(['msg'=>'O modelo foi removido'],200);
    }
}
