<template>
    <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
        <div class="page-header uk-margin-medium-top uk-margin-medium-bottom breadcrumb" uk-grid>
            <div class="uk-width-expand">
                <h3 style="color:#000" class="uk-margin-remove-bottom title-add">My Questions</h3>
            </div>

        </div>

        <div class="uk-overflow-auto uk-margin-medium-top x-scrollbar">


            <div class="uk-width-1-6"></div>

            <div class="uk-width-3-6">
                <div class="uk-panel uk-panel-box">
                    <div class="uk-overflow-auto">

                        <table class="uk-table uk-table-small uk-table-divider table_question">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Created At </th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="question in questions.data">
                                <td v-if="question.question_status==1" >{{question.title}} </td>
                                <td v-else v-html="question.title" class="parent_tr"> </td>
                                <td > {{question.created_at}} </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>

</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
// import Pagination from '../Components/Pagination'
import {Inertia} from "@inertiajs/inertia";
export default {
    name: "index",
    props: {
        questions: Array,
    },
    components:{
        Link,
        // Pagination
    },
    data(){
        return {
            search:'',
            pages:[1,5,10,20,50,100],
            per_page:10,
            page:1,

        };
    },
    methods:{
        // searchTerm(){
        //     this.$inertia.get('question-bank',{searchTerm:this.search},{
        //         preserveState:true
        //     })
        // },
        deleteRow(id){
            if(confirm('Are You Sure?')){
                Inertia.delete(`/question-bank/${id}`);
            }
        },
    }
}
</script>

<style scoped>
.parent{
    background:#4c4c4c !important
}
figure{
    max-width:50% !important
}

.right{
    background: #0b4e58 !important;
}
.btn{
    display: grid;
    grid-gap: 5px;
    grid-auto-flow: column;
    width: 100%;
    justify-content: end;
    margin-bottom: 8px;
    border: 14px;
}

.parent_tr figure img{
    width: 50px !important;
    height: 50px !important;
}
.button input{
    width: 150px;
    height: 22px;
    border: 0px;
    border-radius: 7px;
    padding: 5px;
}
.button{
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}
.pagnate_style{
    display: flex;
    justify-content: space-between;
}
</style>
