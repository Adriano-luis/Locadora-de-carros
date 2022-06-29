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
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class=" col mb-3">
                                <input-container-component  titulo="Nome"  id="inputNome" id-help="nomeHelp" texto-ajuda="Campo opcional. Informe o nome da Marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                    
                </card-component>
                <!-- Fim do card de pesquisa -->

                <!-- Inicio do card de listagem -->
                <card-component titilo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaVisualizar'
                            }"
                            :atualizar="true"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaRemover'
                            }"
                            :titulos="{
                            id: {titulo: 'ID', tipo: 'texto'},
                            nome: {titulo: 'nome', tipo: 'texto'},
                            imagem: {titulo: 'imagem', tipo: 'imagem'},
                            created_at: {titulo: 'created_at', tipo: 'data'}
                        }"></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- Fim do card de listagem -->
            </div>
        </div>

        <!-- Inicio do modal adicionar marca -->
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
        <!-- Fim do modal adicionar marca -->

        <!-- Inicio do modal visualizar marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
             <template v-slot:alertas>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de Criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- fim do modal visualizar marca -->

        <!-- Inicio do modal remoção marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover Marca">
             <template v-slot:alertas>
                <alert-component tipo="success" titulo="Operação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na operação realizada" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <!-- fim do modal remoção marca -->
    </div>
</template>

<script>
    export default {
        data(){
            return {
                urlBase: 'http://localhost/public/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: {data: []},
                busca: {id: '', nome: ''}
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
                let url = this.urlBase + '?' + this.urlPaginacao+this.urlFiltro

                axios.get(url, config)
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
                this.carregarLista()
            },
            paginacao(l){
                if(l.url){
                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarLista()
                }
            },
            pesquisar(){
                let filtro = ''

                for(let chave in this.busca){
                    if(this.busca[chave]){
                        if(filtro != '')
                            filtro += ';'
                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }

                if(filtro != ''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
                }else{
                    this.urlFiltro = ''
                }
                this.carregarLista()
            },
            remover(){
                let url = this.urlBase+'/'+this.$store.state.item.id
                let formData = new FormData();
                formData.append('_method', 'delete')
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response =>{
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                    })
                    .catch(error =>{
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = error.response.data.erro
                    })
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>
