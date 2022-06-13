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
                        <table-component :dados="marcas" :titulos="['id', 'nome', 'imagem']"></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim do card de listagem -->
            </div>
        </div>

        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo=success :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo=danger :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a Marca" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-froup">
                    <input-container-component  titulo="Nome"  id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da Marca.">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="form-froup">
                    <input-container-component  titulo="Imagem"  id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem do tipo .png, .jpg ou .jpeg.">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" @change="carregarImagem($event)">
                    </input-container-component>
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
    export default {
        data(){
            return {
                urlBase: 'http://localhost/public/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: [],
            }
        },
        computed: {
            token(){
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                })
                token = token.split('=')[1]

                return 'Bearer '+ token
            }
        },
        methods: {
            carregarLista(){
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.get(this.urlBase, config)
                    .then(response => {
                        this.marcas = response.data
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                //axios.post(URL, CONTEUDO, CONFIGURAÇÕES DA REQUISIÇÃO)
                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: '+response.data.id
                        }
                    })
                    .catch(error => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: error.response.data.message,
                            dados: error.response.data.errors
                        } 
                    })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>
