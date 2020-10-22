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


  <div id="app">
    <v-app id="inspire">
      </v-row>
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
      >
        <v-text-field
          v-model="name"
          :counter="10"
          :rules="nameRules"
          label="お名前"
          required
        ></v-text-field>

        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="Eメールアドレス"
          required
        ></v-text-field>

        <v-text-field
          v-model="tel"
          :counter="11"
          :rules="telRules"
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
        label="Picker without buttons"
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
          item-text="name"
          item-value="id"
          required
        ></v-select>

        <v-checkbox
          v-model="checkbox"
          :rules="[v => !!v || 'You must agree to continue!']"
          label="Do you agree?"
          required
        ></v-checkbox>

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

      date: new Date().toISOString().substr(0, 10),
      menu: false,
      modal: false,
      menu2: false,

      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'お名前を入力してください',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'Eメールアドレスを入力してください',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      tel: '',
      telRules: [
        v => !!v || '電話番号を入力してください',
        v => (v && v.length <= 11) || 'E-mail must be valid',
      ],
      staffSelect: null,
      staffItems: [],
      timeSelect: null,
      timeItems: ['スタッフを選択してください'],
      checkbox: false,
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
  {{--  watch: {
        select:{
            handler: function ($post_select) {
                axios.get('/schedule/getData',{
                    params: {
                        date:'2020-10-22',
                        staff: $post_select
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
            },

        },
        computed: {
            eventedAction: function() {
                console.log(this.items); //②ここではスコープが切れてlength 0
                let list = this.items.slice();

                return list;
            }
        }


    },--}}
  })
</script>


</body>
<footer style="text-align:center">Send Grid</footer>

</html>
