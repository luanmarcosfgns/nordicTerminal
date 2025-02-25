<template>
    <div class="modal-widget">
        <div class="row">
            <div class="col-12">
                <div  class="modal-content">
                    <slot></slot>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "modalWidgetVue",
    data() {
        return {
            visibleModal: 0
        }
    },
    methods: {
        noVisible() {
            console.log(  document.getElementsByClassName('modal-widget'));
            let modalWidgets =  document.getElementsByClassName('modal-widget');
            for (let i = 0; i<modalWidgets.length;i++){
                modalWidgets[i].classList.add('d-none')
            }
        }
        ,
    }
}
</script>

<style scoped>


/* The Modal (background) */
.modal-widget {

    position: fixed; /* Stay in place */
    z-index: 999999 !important; /* Sit on top */
    padding-top: 10px; /* Location of the box */
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    overflow-y: auto; /* Enable scroll if needed */
    overflow-x: unset !important;
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */


</style>