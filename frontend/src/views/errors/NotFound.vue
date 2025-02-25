<template>
    <div class="container-center">
        <div class="box-center">
            <h1 class="titulo">
                404
            </h1>

            <label id class="main-text">A pagina não existe</label>
            <br>
            <a @click="voltar" class="voltar" href="#">Voltar</a>

        </div>
    </div>

</template>

<script>
export default {
    name: "NotFound",
    methods: {
        voltar() {
            history.back();
        }
    }
}
</script>

<style scoped>
.titulo {
    margin: 0px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 144px;
    font-weight: 100;
    color: #1F2937;
}

.main-text {
    margin-top: -50px;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    color: #1F2937;

}

box-center {
    text-align: center;
    padding: 60px 0px;
}

.container-center {
    width: 100%;
    height: 90vh;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center
}

.voltar {
    display: flex;
    justify-content: right;
    align-items: center;
    margin: 10px;
    color: #1F2937;

}
</style>