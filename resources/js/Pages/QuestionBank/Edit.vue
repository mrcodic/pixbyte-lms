<template>
    <div class="wrapper-page-light f-height">
        <!-- container header -->


        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="add-classroom">
                <form action="" @submit.prevent="submit" >
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="description"><span>*</span> Bank Name </label>

                            <input  type="text" v-model="form.name"  class="uk-input" placeholder="Enter title Here" />


                            <span v-if="errors.name" class="error-msg">
                                <strong>{{errors.name}}</strong>
                            </span>
                        </div>
                        <div class="uk-margin uk-width-1-2">
                            <label class="uk-form-label" for="absence-rooms"> Choose Dynamic</label>
                            <div class="switcher">
                                <label class="uk-switch " for="default-2">
                                    <input type="checkbox" id="default-2" :checked="form.type ? true:false" @change="form.type = !form.type ;form.questionsIds=[];form.questions=[]" >
                                    <div class="uk-switch-slider"></div>
                                </label>
                            </div>
                            <span v-if="errors.type" class="error-msg">
                                <strong>{{errors.type}}</strong>
                            </span>
                        </div>
                        <div class="uk-margin uk-width-1-3" v-if="form.type">
                            <label class="uk-form-label" for="description"><span>*</span> Number Question </label>

                            <input  type="number" v-model="form.question_num"  min="0" class="uk-input" placeholder="Enter Number Here" />
                            <span v-if="errors.question_num" class="error-msg">
                                <strong>{{errors.question_num}}</strong>
                            </span>
                        </div>

                        <div class="uk-margin uk-width-1-2" >
                            <label class="uk-form-label" for="description"><span>*</span> Questions </label>
                            <ul class="iossearchfacets"  v-for="(item,key) in grades.data" >
                                <li :key="key" :id="'default-'+key" :ref="'open-'+key" :class="item.subFilters.length >0 ?'has-children':''">
                                        <span  class="facetselector">
                                            <input v-model="item.selected" type="checkbox" @click="checkboxToggle($event ,item.id)">{{ item.name }}
                                        </span>
                                    <span v-if="item.subFilters.length >0" @click="pluss('open',key)">+</span>
                                    <ul v-for="(i,keyChild) in item.subFilters">
                                        <li :id="'child-'+i.id" :ref="'child-'+i.id"   :class="i.subsub && Object.keys(i.subsub).length >0?'has-children':''" >
                                                <span class="facetselector">
                                                    <input v-model="i.selected" @click="checkboxChildToggle($event ,item.id,i.id)" type="checkbox">{{ i.name }}
                                                </span>
                                            <span v-if="i.subsub && Object.keys(i.subsub).length >0" @click="pluss('child',i.id)">+</span>
                                            <ul v-for="e in i.subsub">
                                                <li >
                                                   <span class="facetselector">
                                                       <input v-model="e.selected"  @click="subCatIds($event,e.id)" type="checkbox">  <a v-if="!form.type" id="show-modal" @click="showModalFun(e)">{{ e.name }}</a> <span v-else>{{e.name}}</span>
                                                   </span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <span v-if="errors.subcatdIds" class="error-msg">
                                <strong>{{errors.subcatdIds}}</strong>
                            </span>

                            <!--                            </template>-->


                        </div>
                        <div class="uk-margin uk-width-1-3">
                            <label class="uk-form-label" for="description"><span>*</span> Selected Questions </label>
                            <div v-for="(item,key) in grades.data">
                                <ul>

                                    <li style="color: #cb3535;font-size: 13px;font-weight: bold;"> {{item.name}}
                                        <ul>
                                            <li v-if="item.subFilters.length >0" v-for="(i,key) in item.subFilters">
                                                <span style="color: #0000cc" v-if="i.selected"> {{i.name}}</span>
                                                <ul v-if="i.subsub">
                                                    <li v-for="(e,key) in i.subsub">
                                                        <span style="color: #0e616e" v-if="e.selected"> {{e.name}}</span>
                                                        <template v-if="form.questionsIds">
                                                      <span v-for="ques in filter" v-if="e.selected">
                                                           <span class="uk-badge" v-if="ques.subCatId==e.id">{{ques.selectedQuestions.length}} Selected</span>
                                                      </span>

                                                        </template>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                            </div>


                        </div>

                        <div class="uk-width-1">
                            <button class="uk-button uk-button-third uk-margin-small-left uk-margin-right">Save as draft <i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="uk-button uk-button-secondary">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>


                    </fieldset>
                </form>
            </div>
        </div>
        <modal :subCat="subsub_id" :subcat_ids="form.questionsIds" v-if="showModal" @close="closeModalFun">
        </modal>
    </div>
</template>

<script>
import Modal from './ModalSelectQuestions'
import {Inertia} from "@inertiajs/inertia";
export default {
    name: "Edit",
    components:{
        Modal
    },
    props:{
        grades:Array,
        errors: Object,
        questionBank:Object

    },
    data(){
        return {

            showModal:false,
            subsub_id:'',
            form:{
                name:this.questionBank.name,
                type:this.questionBank.type==1?true:false,
                question_num:this.questionBank.question_num,
                questionsIds:[],
                subcatIds:this.questionBank.subcatIds.split(','),
                questions:[]

            },

        }

    },
    computed:{
        filter()  {
            return[...new Map(this.form.questionsIds.map(item => [item.subCatId, item])).values()];
        }
    },
    methods:{
        showModalFun(id){
            this.subsub_id=id;
            this.showModal=true;

        },
        closeModalFun(data){
            this.showModal = false
            if(data.selectedQuestions.length >0){
                this.form.questionsIds.push(data)
                this.form.questions.push(data.selectedQuestions)

            }
        },
        checkboxToggle(event,id){
            let index = this.grades.data.findIndex(x => x.id === id);
            let childs = this.grades.data[index].subFilters;
            if(event.target.checked){
                childs.map(single => {
                    single.selected = true
                    if(single.subsub){
                        single.subsub.map(s => {
                            s.selected = true
                        })
                    }
                })
            }else{
                childs.map(single => {
                    single.selected = false
                    if(single.subsub){
                        single.subsub.map(s => {
                            s.selected = false
                        })
                    }
                })
            }
        },
        checkboxChildToggle(event,id,childId){
            let index = this.grades.data.findIndex(x => x.id === id);
            let childs = this.grades.data[index].subFilters.findIndex(x => x.id === childId);
            let childss = this.grades.data[index].subFilters[childs];

            if(event.target.checked){
                childss.selected = true
                if(childss.subsub){
                    childss.subsub.map(s => {
                        s.selected = true
                    })
                }

            }else{
                childss.selected = false
                if(childss.subsub){
                    childss.subsub.map(s => {
                        s.selected = false
                    })
                }

            }
        },
        submit(){
            Inertia.put(`/question-bank/${this.questionBank.id}`, this.form,{
                    preserveScroll: true,
                }
            )
        },
        subCatIds(event,id){
            if(event.target.checked){
                this.form.subcatIds.push(id)
            }else{
                var index = this.form.subcatIds.indexOf(id);
                this.form.subcatIds.splice(index, 1);
            }

        },
        pluss(refType,key){
            console.log(this.$refs[`${refType}-${key}`][0])
            if(this.$refs[`${refType}-${key}`][0].classList.contains('open')){
                this.$refs[`${refType}-${key}`][0].classList.remove('open');
            }else{
                this.$refs[`${refType}-${key}`][0].classList.add('open');
            }

        }
    },

    mounted() {
        this.grades.data.map((item,index)=>{
            item.subFilters.map((cat,i)=>{
                cat.subsub.map((sub,key)=>{
                    let index = this.grades.data.findIndex(x => x.id === item.id);
                    let childs = this.grades.data[index].subFilters.findIndex(x => x.id === cat.id);
                    let childss = this.grades.data[index].subFilters[childs];
                     let subid=this.questionBank.subcatIds.split(',');
                     if(subid.includes(sub.id.toString())){
                         let subsub = this.grades.data[index].subFilters[childs].subsub.findIndex(x => x.id === sub.id);
                         this.grades.data[index].subFilters[childs].subsub[subsub].selected=true
                             this.$refs[`child-${cat.id}`][0].classList.add('open');
                             this.$refs[`open-${i}`][0].classList.add('open');
                     }
                })
            })
        });




    }
}
</script>

<style scoped>
.uk-switch {
    position: relative;
    display: inline-block;
    height: 34px;
    width: 60px;
}

/* Hide default HTML checkbox */
.uk-switch input {
    display:none;
}
/* Slider */
.uk-switch-slider {
    background-color: rgba(0,0,0,0.22);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    border-radius: 500px;
    bottom: 0;
    cursor: pointer;
    transition-property: background-color;
    transition-duration: .2s;
    box-shadow: inset 0 0 2px rgba(0,0,0,0.07);
}
/* Switch pointer */
.uk-switch-slider:before {
    content: '';
    background-color: #fff;
    position: absolute;
    width: 30px;
    height: 30px;
    left: 2px;
    bottom: 2px;
    border-radius: 50%;
    transition-property: transform, box-shadow;
    transition-duration: .2s;
}
/* Slider active color */
input:checked + .uk-switch-slider {
    background-color: #39f !important;
}
/* Pointer active animation */
input:checked + .uk-switch-slider:before {
    transform: translateX(26px);
}
.switcher{
    display: flex;
    justify-content: space-between;
    margin-bottom: 18px;
    align-items: baseline;
}

ul.iossearchfacets li {

    margin: 0 0 0 10px;
    font-weight: normal;
}

ul.iossearchfacets li.has-selected {

    font-weight: bold;
}

ul.iossearchfacets ul {

    display: none;
}

ul.iossearchfacets .open > ul {

    display: block;
}

/*ul.iossearchfacets li.has-children .facetselector {*/

/*    cursor: pointer;*/
/*}*/

/*ul.iossearchfacets li.has-children > .facetselector:after {*/

/*    content: "+";*/
/*    margin: 0 0 0 5px;*/
/*}*/

ul.iossearchfacets li.has-children.open > .facetselector:after {

    content: "-";
}

.removeable-facet {

    margin: 0 10px 10px 0;
    cursor: pointer;
    padding: 5px 10px;
    background-color: #444444;
    color: #FFFFFF;
    border-radius: 10px;
    display:inline-block;
}

.removeable-facet:after {

    padding: 0 0 0 5px;
    content: "x"
}

.removeable-facet:hover {

    background-color: #666666;
}


@media screen and (min-width: 600px) {

    .col-1-2 {
        width: 50%;
        padding: 0 20px 0 0;
    }
}
</style>
