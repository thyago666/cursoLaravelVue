<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;


class MarcaController extends Controller
{
    
        public function __construct(Marca $marca){

                $this->marca = $marca;

        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $marcaRepository = new MarcaRepository($this->marca);
        //$marcas = Marca::all();
          if($request->has('atributos_modelos')){
              $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
              $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }
        else
        {
          $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

           if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
            }
              if($request->has('atributos')){
             $marcaRepository->selectAtributos($request->atributos);
              } 
            return response()->json($marcaRepository->getResultado(),200);
          

        //----------------------------------------------------------

        //localhost:8000/api/marca?atributos=id,nome,imagem&atributos_modelos=id,marca_id,nome,imagem&filtro=nome:=:Ford
        /*
        $marcas = array();
           //serve para filtrar as marcas, ou seja quais campos vc quer
        if($request->has('atributos_modelos')){
              $atributos_modelos = $request->atributos_modelos;
              $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        }
        else
        {
          $marcas = $this->marca->with('modelos');
        }

          if($request->has('filtro')){


            $filtros = explode(';',$request->filtro);
            foreach($filtros as $key => $condicao){
                $c = explode(':',$condicao);
            $marcas = $marcas->where($c[0],$c[1],$c[2]);

            }
            

        }

           if($request->has('atributos')){
               $atributos = $request->atributos;
               $marcas = $marcas->selectRaw($atributos)->get();

        }
        else
        {
            $marcas = $marcas->get();

        }
        
       // $marcas = $this->marca->with('modelos')->get();
       // return $marcas;

       // return 'chegamos';


         //$json = file_get_contents("dados/alunos.json");
        //$data = json_decode($json);
        */
        //return response()->json($marcas,200);
    
      
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
    public function store(Marca $marca, Request $request)
    {
       

       //dd($request->all());
       // $dados = Marca::create($request->all());
        //dd($dados);
       // return $dados;
        
        //$request->validate($this->marca->rules(),$this->marca->feedback());
        //dd($request->imagem);
       // $image = $request->file('imagem');
       // $imagem_urn = $image->store('imagens','public');

/*

        $marca->nome = $request->nome;
        $marca->imagem = $imagem_urn;
        $marca->save();
    
*/
   
       // $marca = $this->marca->create([
       //  'nome' => $request->nome,
        //'imagem' => $imagem_urn]);
/*
        $regras = [
            'nome'=>'required|unique:marcas',
            'imagem'=>'required'
        ];

        $feedback = [

            'required'=>'O campo :attribute e obrigatório',
            'nome.unique'=>'A marca ja existe'
        ];

           $request->validate($regras, $feedback);

           */

       // $request->validate($this->marca->rules(),$this->marca->feedback());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens','public');
     
        // $marca = $this->marca->create($request->all());
        $marca = $this->marca->create([
         'nome' => $request->nome,
        'imagem' => $imagem_urn]);
        
       // return $marca;
        return response()->json($marca,201);
     


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
       // 
   
        $marca = $this->marca->with('modelos')->find($id);
        //return $marca;
        
        if($marca === null){

            return response()->json(['erro'=>'recurso encontrado nao existe'],404);
        }
        return response()->json($marca,200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        


        /*
        print_r($request->all());//dados atualizados
        echo '<hr>';
        print_r($marca->getAttributes());//dados antigo
        */

        
        $marca = $this->marca->find($id);

        
        if($marca === null)
        {

            return response()->json(['erro'=>'Não atualizamos, registro não encontrado!'],404);
        }

            if($request->method() === 'PATCH'){

                $regrasDinamicas = array();

                foreach($marca->rules() as $input => $regra){

                    if(array_key_exists($input,$request->all())){

                        $regrasDinamicas[$input] = $regra;
                    }

                }
                $request->validate($regrasDinamicas,$marca->feedback());

            }else{
                $request->validate($marca->rules(),$marca->feedback());

            }

                //remove o arquivo antigo, caso tenha alterado
                if($request->file('imagem')){

                    Storage::disk('public')->delete($marca->imagem);
                }

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens','public');
     
        // $marca = $this->marca->create($request->all());
               
               $marca->fill($request->all());
               $marca->imagem = $imagem_urn;
               $marca->save();


               /*
        $marca->update([
         'nome' => $request->nome,
        'imagem' => $imagem_urn
        ]);
        */
             
        return response()->json($marca,200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$marca->delete();
        $marca = $this->marca->find($id);
         // $marca->delete();


          if($marca === null)
        {

             return response()->json(['erro'=>'Não apagamos, registro não encontrado!'],404);
        }

              //remove o arquivo antigo
                       Storage::disk('public')->delete($marca->imagem);
              

        $marca->delete();



        return response()->json(['msg'=>'A marca foi removida'],200);
        
    }
}
