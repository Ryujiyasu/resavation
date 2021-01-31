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
    <style>
      [v-cloak]{
          display: none;
      }
    </style>
</head>
<body>


  <div id="app" v-cloak>
    <v-app id="inspire">
      <v-container>
        <v-card
          class="mx-auto my-6"
          max-width="374"
        >
        <template slot="progress">
          <v-progress-linear
            color="deep-purple"
            height="10"
            indeterminate
          ></v-progress-linear>
        </template>
        <v-img
          height="200"
          src="{{ asset('image/forest.jpg') }}"
        ></v-img>
        <v-card-title>●●接骨院</v-card-title>
        <v-card-text>
          <v-row
            align="center"
            class="mx-0"
          ></v-row>
        </v-card-text>
        <v-divider class="mx-4"></v-divider>
        <v-card-title>予約フォーム</v-card-title>
        <v-card-text>
          <v-row>
            <v-form
              ref="form"
              method="POST"
              v-model="valid"
              lazy-validation
              action = "/form"
              class="col-md"


            >
            @csrf
              <v-text-field
                v-model="name"
                :counter="20"
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
                    :rules="[v => !!v || '日付を選択してください']"
                    label="日付選択"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    required
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="date"
                  locale="jp-ja"
                  :day-format="date => new Date(date).getDate()"
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
                class="mr-4"
                @click="reset"
              >
              クリア
              </v-btn>
              <v-dialog
                v-model="dialog"
                max-width="374"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    type="submit"
                    :disabled="invalid"
                    color="primary"
                    class="mr-4"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click.prevent="submit"
                  >
                    予約する
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title class="headline">
                  </v-card-title>
                    @{{ modalTitle }}
                  <v-card-text >
                    @{{ modalText }}
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                      color="green darken-1"
                      text
                      @click="closeDialog"
                    >
                      閉じる
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
            </v-form>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>


      <v-card height="150">
        <v-footer
          absolute
          class="font-weight-medium"
        >
          <v-col
            class="text-center"
            cols="12"
          >
            2020 — <strong>Naoki Yamashita</strong>
          </v-col>
        </v-footer>
        <v-btn
          color="green darken-1"
          text
          href="/schedule/listing"
        >
          スケジュール画面へ（管理者用）
          </v-btn>
      </v-card>
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

      dialog: false,
      modalTitle:'処理中…',
      modalText:'',


      valid: true,
      invalid: false,
      name: '',
      nameRules: [
        v => !!v || 'お名前を入力してください',
        v => (v && v.length <= 20) || 'Name must be less than 10 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'Eメールアドレスを入力してください',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      tel: '',
      telRules: [
        v => !!v || '電話番号を入力してください',
        v => (v && v.length <= 11) || 'Tel must be valid',
      ],
      staffSelect: null,
      staffItems: [],
      courceSelect: null,
      courceItems: ['施術コースを選択してください'],
      timeSelect: null,
      timeItems: ['スタッフを選択してください'],
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
        },
        closeDialog(){
          vue.$data.dialog = false
          vue.$data.modalTitle = '処理中'
          vue.$data.modalText = ''
        },
        submit(){
          this.$refs.form.validate()
          var sendForm = {
            'name':this.name,
            'email':this.email,
            'tel':this.tel,
            'schedule_choice':this.timeSelect,
            'cource_choice':this.courceSelect,
          };
          if(this.$refs.form.validate()){
            axios.post('/form',sendForm)
            .then(function (res) {
              vue.$data.modalTitle = 'ご予約を承りました'
              vue.$data.modalText = res.data.name + "様、ご予約承りました"
            });
            this.$refs.form.reset()
          }else{
            vue.$data.modalTitle = '入力に誤りがあります'
          }
          console.log(sendForm)
        },
    },
  })
</script>


</body>

</html>
