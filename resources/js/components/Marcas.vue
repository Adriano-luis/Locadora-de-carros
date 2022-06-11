<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Inicio do card de pesquisa -->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class=" col mb-3">
                                <input-container-component  titulo="ID"  id="inputId" id-help="idHelp" texto-ajuda="Campo opcional. Informe o Id da Marca.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class=" col mb-3">
                                <input-container-component  titulo="Nome"  id="inputNome" id-help="nomeHelp" texto-ajuda="Campo opcional. Informe o nome da Marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                    
                </card-component>
                <!-- Fim do card de pesquisa -->

                <!-- Inicio do card de listagem -->
                <card-component titilo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim do card de listagem -->
            </div>
        </div>

        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:conteudo>
                <div class="form-froup">
                    <input-container-component  titulo="Nome"  id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da Marca.">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp">
                    </input-container-component>
                </div>
                <div class="form-froup">
                    <input-container-component  titulo="Imagem"  id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem do tipo .png, .jpg ou .jpeg.">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    // export default {
        
    // }
</script>
