<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="t, key in titulos" :key="key">{{t}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="m in dados" :key="m.id">
                <th scope="row">{{m.id}}</th>
                <td>{{m.nome}}</td>
                <td><img :src="'/public/storage/'+m.imagem" :alt="'Logo '+m.nome" width="35" height="35"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'dados',
            'titulos'
        ]
    }
</script>
