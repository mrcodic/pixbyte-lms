<template>
    <Head>
        <title>Mives - Create Question</title>
        <meta name="description" content="Your page description">
    </Head>
    <!-- container -->
    <div class="wrapper-page-light f-height">
        <!-- container header -->
        <div class="uk-container uk-container-expand rm-expand uk-margin-medium-top uk-margin-left">
            <div class="add-classroom">
                <form action="" @submit.prevent="submit" >
                    <fieldset class="uk-fieldset add-new" uk-grid>
                        <x-input id="questions" class="uk-width-1-1" type="text" name="questions"  hidden/>
                        <div class="uk-margin uk-width-1-1">
                            <input class="uk-input title" name="title" v-model="form.name" type="text" placeholder="Question title goes here....." autofocus>

                            <span v-if="errors.name" class="error-msg">
                                <strong>{{errors.name}}</strong>
                            </span>

                        </div>

<!--                        <div class="uk-margin uk-width-1-4 uk-grid-margin uk-first-column">-->
<!--                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Grade</label>-->
<!--                            <v-select class="select2" placeholder="select....." id="myselect" label="name" v-model="form.grade_id" :reduce="grades => grades.id" :options="grades" @update:modelValue="getCategory(form.grade_id)">-->
<!--                                <template #option="{ name }" class="options">-->
<!--                                    <span>-->
<!--                                        {{name}}-->
<!--                                    </span>-->
<!--                                </template>-->

<!--                            </v-select>-->
<!--                            <span v-if="errors.grade_id" class="error-msg">-->
<!--                                <strong>{{errors.grade_id}}</strong>-->
<!--                            </span>-->
<!--                        </div>-->
                        <div class="uk-margin uk-width-1-4 uk-grid-margin " >
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Category</label>
<!--                            <select  class="uk-select" v-model="form.category_id" @change="getSubCategory(form.category_id)">-->
<!--                                <option v-for="cat in category" :value="cat.id">-->
<!--                                    {{cat.name}}-->
<!--                                </option>-->
<!--                            </select>-->
                            <v-select class="select2" placeholder="select....."  id="myselect" label="name" v-model="form.category_id" :reduce="category => category.id" :options="categories" @update:modelValue="getSubCategory(form.category_id)">
                                <template #option="{ name }" class="options">
                                    <span>
                                        {{name}}
                                    </span>
                                </template>

                            </v-select>

                            <span v-if="errors.category_id" class="error-msg">
                                <strong>{{errors.category_id}}</strong>
                            </span>
                        </div>
                        <div class="uk-margin uk-width-1-3 uk-grid-margin " >
                            <label class="uk-form-label" for="absence-rooms"><span>*</span> Sub Category</label>
                            <v-select class="select2" placeholder="select....."  id="myselect" label="name" v-model="form.subcategory_id" :reduce="subcategories => subcategories.id" :options="subcategories">
                                <template #option="{ name }" class="options">
                                    <span>
                                        {{name}}
                                    </span>
                                </template>

                            </v-select>
                            <span v-if="errors.subcategory_id" class="error-msg">
                                <strong>{{errors.subcategory_id}}</strong>
                            </span>
                        </div>
                      <div style="display: flex;margin-top: 71px">
                          <a  @click="modal=true"><label class="uk-form-label" style="cursor: pointer;font-size: .9rem;margin-right: 10px;">Add New Category</label><span uk-icon="icon:plus-circle"></span></a>
<!--                          <a  @click="addCategory()" ></a>-->
                      </div>
                        <!-- This is a button toggling the modal -->

                        <!-- This is the modal -->
                        <div :class="{'uk-open':modal, 'uk-modal':true}" :style="modal ? 'display: block;':''" id="my-id" class="negative" uk-modal>
                            <div class="uk-modal-dialog uk-modal-body">
                                <h2 class="uk-modal-title"> Add category</h2>
                                <div class="uk-margin uk-width-1-1 uk-grid-margin">
                                    <label class="uk-form-label" for="description"> Grade </label>

                                    <v-select class="select2" placeholder="select....." id="myselect" label="name" v-model="categoryForm.grade_id" :reduce="grades => grades.id" :options="grades" @update:modelValue="getCategory(categoryForm.grade_id)">
                                        <template #option="{ name }" class="options">
                                            <span>
                                                {{name}}
                                            </span>
                                        </template>

                                    </v-select>
                                    <span v-if="errors.grade" class="error-msg">
                                        <strong>Grade is Required</strong>
                                    </span>
                                </div>
                                    <div class="uk-margin uk-width-1-1 uk-grid-margin">
                                    <label class="uk-form-label" for="description"> Category Or Sub Category Name </label>
                                    <input type="text" v-model="categoryForm.name"  class="uk-input" placeholder="Enter title Here" />
                                    <span v-if="errors.category_name" class="error-msg">
                                    <strong>category Name is Required</strong>
                                </span>

                                </div>
                                <div  class="uk-margin uk-width-1-1 uk-grid-margin">
                                    <label class="uk-form-label" for="description"> Category</label>
                                    <v-select class="select2" placeholder="select....." id="myselect" label="name" v-model="categoryForm.category_id" :reduce="category => category.id" :options="categories" style="">
                                        <template #option="{ name }" class="options">
                                    <span>
                                        {{name}}
                                    </span>
                                        </template>

                                    </v-select>

                                </div>
                                <div class="uk-modal-footer uk-text-right">
                                    <button class="uk-button uk-button-default  uk-margin-right border-radius" @click="modal=false" type="button">Cancel</button>
                                    <button class="uk-button uk-button-primary border-radius" @click="saveCategory" type="button">Save</button>
                                </div>
                            </div>

                        </div>


                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="description"><span>*</span> Question</label>
                            <div>
                            <label class="uk-form-label" for="description">Use Editor </label>
                                <div class="switcher question">
                                    <label class="uk-switch " for="default-2">
                                        <input type="checkbox" id="default-2" v-model="form.booleanValue"  @change="booleanvalue" >
                                        <div class="uk-switch-slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div ref="question-input">
                            <div v-if="form.booleanValue">
                                <ckeditor   :editor="editor.editor" :config="editorConfig" v-model="form.title"></ckeditor>
                            </div>
                            <input v-else type="text" v-model="form.title"  class="uk-input" placeholder="Enter Question Here" />
                            </div>

                            <span v-if="errors.title" class="error-msg">
                                <strong>{{errors.title}}</strong>
                            </span>
                        </div>

                        <div class="uk-margin uk-width-1-1" v-for="(option,key) in form.answers" :key="option.id">
                            <label class="uk-form-label" for="description"><span>*</span> Answer - {{option.id}} </label>
                            <div>
                                <label class="uk-form-label" for="description">Use Editor </label>
                                <div class="switcher question">
                                    <label class="uk-switch " :for="'default-1-'+option.id">
                                        <input type="checkbox" :id="'default-1-'+option.id" v-model="option.status"  @change="booleananswer($event,option.id)" >
                                        <div class="uk-switch-slider"></div>
                                    </label>
                                    <input type="checkbox" class="uk-checkbox" title="correct answer" v-model="option.correct" >
                                </div>
                            </div>
                          <div :ref="'parent-'+option.id">
                            <div v-if="option.status">
                              <ckeditor :ref="'ck-'+option.id"  :editor="editor.editor" :config="editorConfig"  v-model="option.valueCk"></ckeditor>
                            </div>

                              <input v-else :ref="'input-'+option.id"  type="text" v-model="option.valueInput"  class="uk-input" placeholder="Enter Answer Here" />
                          </div>
                            <div v-if="option.status">
                            <span v-if="errors[`answers.${key}.valueCk`]" class="error-msg">
                                <strong>{{errors[`answers.${key}.valueCk`]}}</strong>
                            </span>
                            </div>
                            <div v-else>
                            <span v-if="errors[`answers.${key}.valueInput`]" class="error-msg">
                                <strong>{{errors[`answers.${key}.valueInput`]}}</strong>
                            </span>
                            </div>

                                <div style="display: flex;justify-content: right;margin-top: 6px">
                                <a  @click="removeOption(option.id)" v-if="option.id>1"><span uk-icon="icon:trash"></span></a>
                                </div>
                            </div>
                        <div style="display: flex;justify-content: right;margin-top: 6px">
                           <a @click="addOption" ><label class="uk-form-label" style="cursor: pointer;font-size: .9rem;margin-right: 10px;">Add New Answer</label><span uk-icon="icon:plus-circle"></span></a>
                       </div>

                        <div class="uk-margin uk-width-1-1">
                            <label class="uk-form-label" for="description"> Answer Description </label>
                            <div class="uk-form-controls uk-width-3-4">
                                <label class="uk-form-label" for="cover"><span>*</span> Video URL</label>
                                <input id="url" class="uk-input" placeholder="Please Enter Url" type="text" v-model="form.answer_desc">
                            </div>
                            <span v-if="errors.answer_desc" class="error-msg">
                                <strong>{{errors.answer_desc}}</strong>
                            </span>
                        </div>

                        <div class="uk-width-1">
                            <button class="uk-button uk-button-secondary">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>


                    </fieldset>
                </form>
            </div>
        </div>
        <div v-if="loader" class="spinner loading dark-font" style=" width: 100px;height: 64px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;"  >
            <div class="circle one"></div>
            <div class="circle two"></div>
            <div class="circle three"></div>
        </div>

    </div>
</template>

<script>
// import ClassicEditor from 'ckeditor5-classic-with-mathtype';
import { Inertia } from '@inertiajs/inertia'
import  vSelect  from "vue-select";
import 'vue-select/dist/vue-select.css';

export default {
    name: "Questions",
    components: {
        ClassicEditor,
        vSelect
    },
    props: {
        errors: Object,
        grades: Object,
        categories:Object
    },
    data() {
        return {
            booleanAnswer:true,
            category:[],
            categoryForm:{
              name:'',
              category_id:"",
              grade_id:''
            },
            modal:false,
            loader:false,
            subcategories:[],
            editor: {
                editor: ClassicEditor,
            },
            showInput:false,
            form:{
                 answers:[{ id: 1, valueInput: '',valueCk:"" ,correct:false ,status:false}],
                  title:"",
                  name:"",
                  type:1,
                  grade_id:"",
                  category_id:"",
                  subcategory_id:"",
                answer_desc:"",
                booleanValue:false,

            },
            editorConfig: {
                toolbar: [ 'MathType','ChemType', 'imageUpload' ],


                ckfinder: {
                    uploadUrl: `/uploadQuestionImage?_token=${document.head.querySelector('meta[name="csrf-token"]').content}`,
                    withCredentials: true,
                },

            },
        };
    },
    methods: {
        booleanvalue(e) {
          this.form.title="";

        },
        saveCategory(){
            // this.categoryForm.grade_id=this.form.grade_id
            axios
                .post(`/saveCat`,this.categoryForm)
                .then(({ data }) => {
                    if (data.cat!==null){
                        this.category.push(data.cat)
                        this.categories.push(data.cat)
                        this.$swal('success','Category create Success','success');

                    }else{
                        this.subcategories.push(data.subCat)
                        this.$swal('success','Sub Category create success','success');

                    }

                    this.modal=false
                    // this.$refs['modalClose'].style.display="none";

                })
                .catch((data) => {
                    console.log(data);
                });
        },
        booleananswer(event, key) {
            // if (event.target.checked) {
            //     this.$refs[`parent-${key}`][0].childNodes[1].style.display = "block"
            //     this.$refs[`parent-${key}`][0].childNodes[2].style.display = "none"
            // } else {
            //     this.$refs[`parent-${key}`][0].childNodes[1].style.display = "none"
            //     this.$refs[`parent-${key}`][0].childNodes[2].style.display = "block"
            // }

        },
        addOption() {
            var id = Math.max.apply(Math, this.form.answers.map(function (o) {
                return o.id;
            })) + 1;
            this.form.answers.push({id, valueInput: '',valueCk:"" ,correct: false ,status:false});


        },
        addCategory(){
            if(this.showInput){
                this.showInput=false;
           this.modal=true
            }else{
          this.showInput=true;
            }
        },

        removeOption(id) {
            if (id != 1) {
                let i = this.form.answers.map(item => item.id).indexOf(id) // find index of your object
                this.form.answers.splice(i, 1);
            }
        },
        submit() {
           let validation=this.validationAnswer()
        if(validation){
            Inertia.post('/question', this.form, {
                    preserveScroll: true,
                }
            )
            window.location.href='/question'

        }
        if(!this.errors){
            this.form.answers=[{ id: 1, valueInput: '',valueCk:"" ,correct:false ,status:false}]
            this.form.title=""
            this.form.name=""
            this.form.type=1
            this.form.grade_id=""
            this.form.category_id=""
            this.form.subcategory_id=""
            this.form.answer_desc=""
            this.form.booleanValue=false
        }



        },
        getCategory(value) {
            axios
                .get(`/gradeCategory/${value}`)
                .then(({ data }) => {

                    this.category=data.data

                })
                .catch((data) => {
                    console.log(data);
                });
        } ,
        validationAnswer(){
            let point =0;
            this.form.answers.map(item=> {
                if(item.correct){
                    point++
                }
            })
            if(point<1){
                this.$swal('warning','please check at least one answer','warning');
                return false
            }else if(point > 1) {
                this.form.type=2;
                return true
            }else{
                this.form.type=1;
                return true
            }
        },
        getSubCategory(value) {
            axios
                .get(`/gradeSubCategory/${value}`)
                .then(({ data }) => {
                    this.subcategories=data.data

                })
                .catch((data) => {
                    console.log(data);
                });
        }
    },
    mounted() {

    }
}
</script>

<style>
#myselect .vs__dropdown-toggle{
    border:none !important
}
#myselect .vs__actions{
    margin-left:-29px
}
#myselect .vs__selected-options {
    height: 50px;
    background: #fafbfb;
    border-radius: 9px;
    width: 100%;
    border: 1px solid #e4e4e4;
}


</style>
