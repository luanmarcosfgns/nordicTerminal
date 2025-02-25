<template>
    <div class="loading-page">
        <div class="page">
            <span class="loader"></span>
        </div>
    </div>
</template>

<script>
export default {
    name: "loadingPage",
  mounted() {
    setTimeout(function () {
      document.getElementsByClassName('loading-page')[0].classList.add('d-none');
    }, 5000)
  }
};
</script>

<style scoped>
.loading-page {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #edf2f7;
    z-index: 99999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.page {
    width: 20px;
    height: 20px;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
}

.loader {
    color: #000000;
    font-size: 10px;
    width: 1em;
    height: 1em;
    border-radius: 50%;
    position: relative;
    text-indent: -9999em;
    animation: mulShdSpin 0.6s infinite linear;
    transform: translateZ(0);
    justify-content: center;
    align-items: center;
    display: flex;
    flex-direction: row;
}

@keyframes mulShdSpin {
    0%,
    100% {
        box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em,
        2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em,
        -2em -2em 0 0;
    }
    12.5% {
        box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em,
        0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
    }
    25% {
        box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em,
        2em 2em 0 0, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em,
        -2em -2em 0 -1em;
    }
    37.5% {
        box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0,
        2em 2em 0 0.2em, 0 3em 0 0em, -2em 2em 0 -1em,
        -3em 0em 0 -1em, -2em -2em 0 -1em;
    }
    50% {
        box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em,
        2em 2em 0 0em, 0 3em 0 0.2em, -2em 2em 0 0, -3em 0em 0 -1em,
        -2em -2em 0 -1em;
    }
    62.5% {
        box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em,
        2em 2em 0 -1em, 0 3em 0 0, -2em 2em 0 0.2em,
        -3em 0 0 0, -2em -2em 0 -1em;
    }
    75% {
        box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em,
        2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0,
        -3em 0em 0 0.2em, -2em -2em 0 0;
    }
    87.5% {
        box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em,
        2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0,
        -3em 0em 0 0, -2em -2em 0 0.2em;
    }
}
</style>
