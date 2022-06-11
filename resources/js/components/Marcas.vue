<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Inicio do card de pesquisa -->
                <div class="card mb-3">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body">
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
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
                <!-- Fim do card de pesquisa -->

                <!-- Inicio do card de listagem -->
                <div class="card">
                    <div class="card-header">Relação de Marcas</div>
                    <div class="card-body">
                        <table-component></table-component>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-sm float-right">Adicionar</button>
                    </div>
                </div>
                <!-- Fim do card de listagem -->
            </div>

        </div>
    </div>
</template>

<script>
    // export default {
        
    // }
</script>
