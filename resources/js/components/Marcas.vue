<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

             <!--inicio do card de busca-->
             <card-component titulo="Busca de Marcas">

                 <template v-slot:conteudo>
                               <div class="form-row">
                <div class="col mb-3">
                
          <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Iforme o ID da marca">
       
        <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="Informe o ID">
   
        </input-container-component>
        
     </div>
            <div class="col mb-3">
        <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Iforme o nome da marca">
        <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Informe a marca">
        </input-container-component>
                   </div>
                    </div>
                 </template>

                 <template v-slot:rodape>
                       <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                 </template>

             </card-component>
        
     <!--fim do card de busca-->

      <!--inicio do card de listagem de marcas-->


      <card-component titulo="Relação de Marcas">

            <template v-slot:conteudo>
                   <table-component> </table-component>
            </template>

            <template v-slot:rodape>
                      <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                  </template>

      </card-component>

       <!--fim do card de listagem de marcas-->


        </div>
    </div>
    <!-- Button trigger modal -->


<!-- Modal -->

<modal-component id="modalMarca" titulo="Adicionar Marca">
    <template v-slot:conteudo>
<div class="form-group">
 <input-container-component titulo="Nome da Marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Iforme o nome da marca">
        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Informe a marca" v-model="nomeMarca">
 </input-container-component>
     {{nomeMarca}}
</div>

<div class="form-group">
 <input-container-component titulo="imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem PNG">
        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
 </input-container-component>
 {{arquivoImagem}}
</div>
    </template>


    <template v-slot:rodape>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
    </template>
</modal-component>
</div>


</template>

<script>

    export default{


        data(){
            return{
            nomeMarca:'',
            arquivoImagem: [],
            urlBase: 'http://localhost:8000/api/v1/marca'
       
              }

        },
        methods:{

            carregarImagem(e){

                this.arquivoImagem = e.target.files
            },

            salvar(){

                console.log(this.nomeMarca, this.arquivoImagem[0])

            
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config={
                    headers:{
                        'Content-Type':'multipart/form-data',
                        'Accept':'application/json'
                    }
                }

             axios.post(this.urlbase,formData,config)
             .then(response=>{

                 console.log(response)
             })
             .catch(errors=>{
                 console.log(errors)

             })
            
        
            }
        }
    }
</script>