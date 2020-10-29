<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <title>Calendar</title>

</head>
<body>
@if (session('flash_message'))
    <div class="flash_message">
        {{ session('flash_message') }}
    </div>
@endif
<div id="app">
  <v-app id="inspire">
    </v-row>
    <v-form
      ref="form"
      method="POST"
      v-model="valid"
      lazy-validation
      action = "/form"
    >
    @csrf
      <v-text-field
        v-model="name"
        :counter="10"
        :rules="nameRules"
        name = "name"
        label="お名前"
        required
      ></v-text-field>

      <v-text-field
        v-model="email"
        :rules="emailRules"
        name = "email"
        label="Eメールアドレス"
        required
      ></v-text-field>

      <v-text-field
        v-model="tel"
        :counter="11"
        :rules="telRules"
        name = "tel"
        label="電話番号"
        required
      ></v-text-field>

      <v-select
        v-model="staffSelect"
        :items="staffItems"
        :rules="[v => !!v || 'スタッフを選択してください']"
        label="スタッフ"

        item-text="name"
        item-value="id"
        required
        @click = "loadStaff"
        @change = "staffDateChoiced(staffSelect,date)"
      ></v-select>
      <v-select
        v-model="courceSelect"
        :items="courceItems"
        :rules="[v => !!v || '施術コースを選択してください']"
        label="施術コース"
        name="cource_choice"
        item-text="name"
        item-value="id"
        required
        @click = "loadCource"
        @change = "staffDateChoiced(staffSelect,date)"
      ></v-select>


  </v-menu>
  <v-spacer></v-spacer>

  <v-menu
    v-model="menu2"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
      v-model="date"
      label="日付選択"
      prepend-icon="mdi-calendar"
      readonly
      v-bind="attrs"
      v-on="on"
    ></v-text-field>
  </template>
  <v-date-picker
    v-model="date"
    @input="menu2 = false"
      @change = "staffDateChoiced(staffSelect,date)"
  ></v-date-picker>
</v-menu>

      <v-select
        v-model="timeSelect"
        :items="timeItems"
        :rules="[v => !!v || '時間を選択してください']"
        label="時間"
        name = "schedule_choice"
        item-text="name"
        item-value="id"
        required
      ></v-select>

      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="validate"
      >
        Validate
      </v-btn>

      <v-btn
        color="error"
        class="mr-4"
        @click="reset"
      >
        Reset Form
      </v-btn>

      <v-btn
        color="warning"
        @click="resetValidation"
      >
        Reset Validation
      </v-btn>
      <v-btn
        type = "submit"
        :disabled="invalid"
        color="primary"

      >
        submit
      </v-btn>
    </v-form>
  </v-row>
  </v-app>


</div>


<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

const vue=new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  data: () => ({

    date: new Date('{{$prev_date}}').toISOString().substr(0, 10),
    menu: false,
    modal: false,
    menu2: false,

    valid: true,
    invalid: false,
    name: '{{$name}}',
    nameRules: [
      v => !!v || 'お名前を入力してください',
      v => (v && v.length <= 10) || 'Name must be less than 10 characters',
    ],
    email: '{{$email}}',
    emailRules: [
      v => !!v || 'Eメールアドレスを入力してください',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
    tel: '{{$tel}}',
    telRules: [
      v => !!v || '電話番号を入力してください',
      v => (v && v.length <= 11) || 'E-mail must be valid',
    ],

    staffSelect: {
        id:{{$schedule->Staff()->first()->id}},
        name:'{{$schedule->Staff()->first()->name}}'
    },
      staffItems: [
              @foreach($staffs as $staff)
          {
              id:{{$staff->id}},
              name:'{{$staff->name}}'
          },
          @endforeach
      ],
    courceSelect: {
        id:{{$schedule->Cource()->first()->id}},
        name:'{{$schedule->Cource()->first()->name}}'
    },
    courceItems: [
        @foreach($cources as $cource)
        {
            id:{{$cource->id}},
            name:'{{$cource->name}}'
        },
        @endforeach


    ],
    timeSelect: {
        id:{{$schedule->Time()->first()->id}},
        name:'{{$schedule->Time()->first()->name}}'
    },
    timeItems:[
        @foreach($return as $time)
        {
            id:{{$time["id"]}},
            name:'{{$time["name"]}}'
        },
        @endforeach
    ],
  }),

  methods: {
    validate () {
      this.$refs.form.validate()
    },
    reset () {
      this.$refs.form.reset()
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    loadStaff(){
      axios.get('/schedule/getStaff')
      .then(function (res) {
           vue.$data.staffItems=[];
           res.data.staffSelect.forEach(element => {
               vue.$data.staffItems.push({
                   id:element.id,
                   name:element.name
               })
           });
       });
    },
    loadCource(){

      axios.get('/schedule/getCource')
      .then(function (res) {

           vue.$data.courceItems=[];
           res.data.courceSelect.forEach(element => {
               vue.$data.courceItems.push({
                   id:element.id,
                   name:element.name
               })
           });
       });
    },
    staffDateChoiced($staff,$date){
      axios.get('/schedule/getData',{
          params: {
              date: $date,
              staff: $staff,
          }
      })
         .then(function (res) {
              vue.$data.timeItems=[];
              if(res.data.schedules.length != 0){
                console.log(res.data.schedules.length);
                res.data.schedules.forEach(element => {
                    vue.$data.timeItems.push({
                        id:element.id,
                        name:element.name
                    })
                });
              }else if(res.data.schedules.length == 0){
                console.log(res.data.schedules.length);
                  vue.$data.timeItems.push({
                      id:'null',
                      name:'スタッフを選択してください'
                  });
              };
          });
    }
  },
})
</script>

</body>
</html>
