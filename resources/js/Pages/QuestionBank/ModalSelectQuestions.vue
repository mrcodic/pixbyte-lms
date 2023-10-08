<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
             <input class="uk-input" type="text" v-model="search" @input="filterSearch" placeholder="Search Here.........">
                    <div class="modal-header">
                      <h3> {{subCat.name}} </h3>
                    </div>

                    <div class="modal-body question_bank">
                        <ul class="iossearchfacets"  v-for="(item,key) in filterSearch" >
                            <li >
                                        <span  class="facetselector">
                                            <input :value="{id:item.id,text:item.title,status:item.question_status}" v-model="form.question_id" type="checkbox">
                                            <span v-if="item.question_status==1">{{ item.title }}</span>
                                            <span v-else v-html="item.title"></span>
                                        </span>
                            </li>
                        </ul>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            <p>Select Question </p>
                            <button class="uk-button uk-button-secondary" @click="emitFun">
                                Save
                            </button>


                            <button class="uk-button uk-button-primary" @click="emitFun">
                                Close
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>

</template>

<script>
export default {
    name: "ModalSelectQuestions",
    props:{
        subCat:Array,
        subcat_ids:Array
    },
    data(){
        return{
            search:"",
        form:{
            question_id:[]
        }
        }
    },
    mounted() {
    this.subcat_ids.map(item=>{
       if(item.subCatId==this.subCat.id){
           this.form.question_id=item.selectedQuestions
       }
    });
        },
    computed:{
        filterSearch(){
                return this.subCat.questions.filter((item) =>{
                    return item.title
                        .toLowerCase()
                        .includes(this.search.toLowerCase());

                });
        }
    },
    methods:{
        emitFun(){
            let object={
                id:this.form.question_id,
                text:this.form.text
            }
            this.$emit('close',{"subCatId":this.subCat.id,"selectedQuestions":this.form.question_id})
        },

    }

}
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 300px;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}
.question_bank{
    max-height: 250px;
    overflow-y: scroll;
    white-space: nowrap;
}
/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
    opacity: 0;
}

.modal-leave-active {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
.facetselector{
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}
.facetselector span img{
    width: 90px !important;
    height: 45px !important;
}
</style>
